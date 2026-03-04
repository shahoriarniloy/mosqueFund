<?php

namespace App\Http\Controllers;

use App\Helpers\NotificationHelper;
use App\Models\Transaction;
use App\Models\TransactionLog;
use App\Models\Donor;
use App\Models\Month;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class TransactionController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            new Middleware('auth'),
        ];
    }

    /**
     * Display a listing of transactions.
     */
    public function index(Request $request)
    {
        $query = Transaction::with(['donor', 'month', 'user']);
        
        // Filter by month
        if ($request->filled('month_id')) {
            $query->where('month_id', $request->month_id);
        }
        
        // Filter by donor
        if ($request->filled('donor_id')) {
            $query->where('donor_id', $request->donor_id);
        }
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('paid_status', $request->status);
        }

        // Filter by payment method
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        // Filter by date range
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }
        
        $transactions = $query->latest()->paginate(15);
        
        // Get filter data
        $donors = Donor::orderBy('name')->get();
        $months = Month::orderBy('year', 'desc')
                      ->orderByRaw("FIELD(name, 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December')")
                      ->get();
        
        // Calculate totals
        $totalAmount = $query->sum('amount');
        $paidCount = $query->where('paid_status', 'paid')->count();
        $unpaidCount = $query->where('paid_status', 'unpaid')->count();

        return view('transactions.index', compact(
            'transactions', 
            'donors', 
            'months',
            'totalAmount',
            'paidCount',
            'unpaidCount'
        ));
    }

    /**
     * Show the form for creating a new transaction.
     */
    public function create()
    {
        $donors = Donor::where('status', 'active')->orderBy('name')->get();
        $months = Month::where('status', 'active')
                      ->orderBy('year', 'desc')
                      ->orderByRaw("FIELD(name, 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December')")
                      ->get();
        
        return view('transactions.create', compact('donors', 'months'));
    }

    /**
     * Store a newly created transaction in storage.
     */
    public function store(Request $request)
{
    $validated = $request->validate([
        'donor_id' => 'required|exists:donors,id',
        'month_id' => 'required|exists:months,id',
        'amount' => 'required|numeric|min:0',
        'payment_method' => 'required|in:bkash,nagad,cash',
        'paid_status' => 'required|in:paid,unpaid'
    ]);

    // Check if transaction already exists for this donor and month
    $exists = Transaction::where('donor_id', $validated['donor_id'])
        ->where('month_id', $validated['month_id'])
        ->exists();
        
    if ($exists) {
        return back()->withErrors(['month_id' => 'Transaction for this donor and month already exists.'])
            ->withInput();
    }

    $validated['user_id'] = Auth::id();
    
    $transaction = Transaction::create($validated);

    // Log the creation (if you have transaction logs)
    if (class_exists('App\Models\TransactionLog')) {
        TransactionLog::create([
            'transaction_id' => $transaction->id,
            'user_id' => Auth::id(),
            'field_name' => null,
            'old_value' => null,
            'new_value' => null,
            'transaction_snapshot' => $transaction->toArray(),
            'action' => 'created',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);
    }

    // Send SMS notification to donor
    try {
        NotificationHelper::sendTransactionSMS($transaction);
    } catch (\Exception $e) {
        // Log error but don't stop the transaction process
        \Log::error('SMS notification failed', [
            'transaction_id' => $transaction->id,
            'error' => $e->getMessage()
        ]);
    }

    return redirect()->route('transactions.index')
        ->with('success', 'Transaction created successfully.');
}

    /**
     * Display the specified transaction.
     */
    public function show(Transaction $transaction)
    {
        $transaction->load(['donor', 'month', 'user']);
        return view('transactions.show', compact('transaction'));
    }

    /**
     * Show the form for editing the specified transaction.
     */
    public function edit(Transaction $transaction)
    {
        $donors = Donor::where('status', 'active')->orderBy('name')->get();
        $months = Month::where('status', 'active')
                      ->orderBy('year', 'desc')
                      ->orderByRaw("FIELD(name, 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December')")
                      ->get();
        
        return view('transactions.edit', compact('transaction', 'donors', 'months'));
    }

    /**
     * Update the specified transaction in storage.
     */
    public function update(Request $request, Transaction $transaction)
    {
        $validated = $request->validate([
            'donor_id' => 'required|exists:donors,id',
            'month_id' => 'required|exists:months,id',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,bkash,nagad',
            'paid_status' => 'required|in:paid,unpaid'
        ]);

        // Check if another transaction exists for this donor and month (excluding current)
        $exists = Transaction::where('donor_id', $validated['donor_id'])
            ->where('month_id', $validated['month_id'])
            ->where('id', '!=', $transaction->id)
            ->exists();
            
        if ($exists) {
            return back()->withErrors(['month_id' => 'Transaction for this donor and month already exists.'])
                ->withInput();
        }

        // Get the original transaction data before update
        $originalData = $transaction->toArray();
        
        // Track which fields are being changed
        $changes = [];
        $fieldsToCheck = ['donor_id', 'month_id', 'amount', 'payment_method', 'paid_status'];
        
        foreach ($fieldsToCheck as $field) {
            $oldValue = $originalData[$field] ?? null;
            $newValue = $validated[$field] ?? null;
            
            // Check if the value has changed
            if ($oldValue != $newValue) {
                $changes[] = [
                    'field_name' => $field,
                    'old_value' => $oldValue,
                    'new_value' => $newValue
                ];
            }
        }

        $validated['user_id'] = Auth::id();
        $transaction->update($validated);

        // Log each changed field
        foreach ($changes as $change) {
            TransactionLog::create([
                'transaction_id' => $transaction->id,
                'user_id' => Auth::id(),
                'field_name' => $change['field_name'],
                'old_value' => $change['old_value'],
                'new_value' => $change['new_value'],
                'transaction_snapshot' => $transaction->toArray(),
                'action' => 'updated',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);
        }

        // If no specific fields were tracked but there was an update, log a general entry
        if (empty($changes)) {
            TransactionLog::create([
                'transaction_id' => $transaction->id,
                'user_id' => Auth::id(),
                'field_name' => null,
                'old_value' => null,
                'new_value' => null,
                'transaction_snapshot' => $transaction->toArray(),
                'action' => 'updated',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);
        }

        return redirect()->route('transactions.index')
            ->with('success', 'Transaction updated successfully.');
    }

    /**
     * Remove the specified transaction from storage.
     */
    public function destroy(Transaction $transaction)
    {
        // Log the deletion before actually deleting
        TransactionLog::create([
            'transaction_id' => $transaction->id,
            'user_id' => Auth::id(),
            'field_name' => null,
            'old_value' => null,
            'new_value' => null,
            'transaction_snapshot' => $transaction->toArray(),
            'action' => 'deleted',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);
        
        $transaction->delete();
        
        return redirect()->route('transactions.index')
            ->with('success', 'Transaction deleted successfully.');
    }

    /**
     * Mark transaction as paid
     */
    public function markAsPaid(Transaction $transaction)
    {
        $oldStatus = $transaction->paid_status;
        
        $transaction->update([
            'paid_status' => 'paid',
            'user_id' => Auth::id()
        ]);

        // Log the status change
        TransactionLog::create([
            'transaction_id' => $transaction->id,
            'user_id' => Auth::id(),
            'field_name' => 'paid_status',
            'old_value' => $oldStatus,
            'new_value' => 'paid',
            'transaction_snapshot' => $transaction->toArray(),
            'action' => 'updated',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);

        return back()->with('success', 'Transaction marked as paid successfully.');
    }

    /**
     * Get donor's monthly amount (AJAX)
     */
    public function getDonorAmount(Request $request)
    {
        $donor = Donor::find($request->donor_id);
        
        if ($donor) {
            return response()->json([
                'success' => true,
                'amount' => $donor->monthly_amount
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Donor not found'
        ]);
    }

    /**
     * Display transaction logs for a specific transaction
     */
    public function logs(Transaction $transaction)
    {
        $logs = TransactionLog::where('transaction_id', $transaction->id)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('transactions.logs', compact('transaction', 'logs'));
    }

    /**
     * Display all transaction logs across all transactions
     */
    public function allLogs(Request $request)
    {
        $query = TransactionLog::with(['transaction', 'transaction.donor', 'user'])
            ->orderBy('created_at', 'desc');
        
        // Filter by action
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }
        
        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        
        // Filter by date range
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }
        
        // Filter by donor
        if ($request->filled('donor_id')) {
            $query->whereHas('transaction', function($q) use ($request) {
                $q->where('donor_id', $request->donor_id);
            });
        }
        
        $logs = $query->paginate(30);
        
        // Get data for filters
        $users = User::orderBy('name')->get();
        $donors = Donor::orderBy('name')->get();
        $actions = ['created', 'updated', 'deleted'];
        
        return view('transaction-logs.index', compact('logs', 'users', 'donors', 'actions'));
    }

    /**
     * Show a single log entry details
     */
    public function showLog(TransactionLog $transactionLog)
    {
        $transactionLog->load(['transaction', 'transaction.donor', 'transaction.month', 'user']);
        
        return view('transaction-logs.show', compact('transactionLog'));
    }

    /**
     * Export transaction logs
     */
    public function exportLogs(Request $request)
    {
        $query = TransactionLog::with(['transaction', 'transaction.donor', 'user']);
        
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }
        
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }
        
        $logs = $query->orderBy('created_at', 'desc')->get();
        
        // Generate CSV
        $filename = 'transaction-logs-' . now()->format('Y-m-d-His') . '.csv';
        $handle = fopen('php://temp', 'w');
        
        // Add headers
        fputcsv($handle, [
            'Log ID',
            'Date',
            'Time',
            'Transaction ID',
            'Donor',
            'Month',
            'User',
            'Action',
            'Field',
            'Old Value',
            'New Value',
            'IP Address'
        ]);
        
        // Add data
        foreach ($logs as $log) {
            fputcsv($handle, [
                $log->id,
                $log->created_at->format('Y-m-d'),
                $log->created_at->format('H:i:s'),
                $log->transaction_id,
                $log->transaction->donor->name ?? 'Deleted',
                $log->transaction->month->name ?? 'Unknown',
                $log->user->name ?? 'Unknown',
                $log->action,
                $log->field_name,
                $log->old_value,
                $log->new_value,
                $log->ip_address
            ]);
        }
        
        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);
        
        return response($content)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
}