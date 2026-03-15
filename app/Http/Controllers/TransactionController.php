<?php

namespace App\Http\Controllers;

use App\Helpers\NotificationHelper;
use App\Models\Contributor;
use App\Models\Donation;
use App\Models\DonationLog;
use App\Models\Transaction;
use App\Models\TransactionLog;
use App\Models\Donor;
use App\Models\Month;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [new Middleware('auth')];
    }

    /**
     * Get months ordered by calendar order
     */
    private function getOrderedMonths()
    {
        return Month::orderBy('year', 'desc')
            ->orderByRaw("FIELD(name, 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December')")
            ->get();
    }

    /**
     * Build transaction snapshot for logs
     */
    private function buildTransactionSnapshot(Transaction $transaction): array
    {
        $transaction->loadMissing(['donor', 'month', 'user']);

        return [
            'id' => $transaction->id,
            'donor_id' => $transaction->donor_id,
            'month_id' => $transaction->month_id,
            'amount' => $transaction->amount,
            'payment_method' => $transaction->payment_method,
            'paid_status' => $transaction->paid_status,
            'user_id' => $transaction->user_id,
            'created_at' => optional($transaction->created_at)?->toDateTimeString(),
            'updated_at' => optional($transaction->updated_at)?->toDateTimeString(),

            'donor_name' => $transaction->donor?->name,
            'donor_phone' => $transaction->donor?->phone,
            'donor_address' => $transaction->donor?->address,
            'donor_monthly_amount' => $transaction->donor?->monthly_amount,

            'month_name' => $transaction->month?->name,
            'month_year' => $transaction->month?->year,

            'recorded_by' => $transaction->user?->name,
        ];
    }

    /**
     * Display a listing of transactions.
     */
    public function index(Request $request)
    {
        $query = Transaction::with(['donor', 'month', 'user']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('donor', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('donor_id')) {
            $query->where('donor_id', $request->donor_id);
        }

        if ($request->filled('month_id')) {
            $query->where('month_id', $request->month_id);
        }

        if ($request->filled('status')) {
            $query->where('paid_status', $request->status);
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $totalAmount = (clone $query)->sum('amount');
        $paidCount = (clone $query)->where('paid_status', 'paid')->count();
        $unpaidCount = (clone $query)->where('paid_status', 'unpaid')->count();

        $transactions = $query->latest()->paginate(15);

        $donors = Donor::orderBy('name')->get();
        $months = $this->getOrderedMonths();

        if ($request->ajax() || $request->has('ajax')) {
            $mobileCards = view('transactions.partials.mobile-cards', compact('transactions'))->render();
            $desktopTable = view('transactions.partials.desktop-table', compact('transactions'))->render();
            $stats = view('transactions.partials.stats', compact('totalAmount', 'paidCount', 'unpaidCount'))->render();
            $pagination = $transactions->withQueryString()->links()->render();

            return response()->json([
                'mobileCards' => $mobileCards,
                'desktopTable' => $desktopTable,
                'stats' => $stats,
                'pagination' => $pagination,
                'total' => $transactions->total(),
            ]);
        }

        return view('transactions.index', compact('transactions', 'donors', 'months', 'totalAmount', 'paidCount', 'unpaidCount'));
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

        $paidMonths = [];
        if (old('donor_id')) {
            $paidMonths = Transaction::where('donor_id', old('donor_id'))
                ->where('paid_status', 'paid')
                ->pluck('month_id')
                ->toArray();
        }

        return view('transactions.create', compact('donors', 'months', 'paidMonths'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'donor_id' => 'required|exists:donors,id',
            'month_id' => 'required|exists:months,id',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,bkash,nagad',
        ]);

        $exists = Transaction::where('donor_id', $validated['donor_id'])
            ->where('month_id', $validated['month_id'])
            ->exists();

        if ($exists) {
            return back()
                ->withErrors(['month_id' => 'Transaction for this donor and month already exists.'])
                ->withInput();
        }

        $donor = Donor::find($validated['donor_id']);
        $monthlyAmount = $donor->monthly_amount;
        $excessAmount = 0;
        $originalAmount = $validated['amount'];

        if ($validated['amount'] > $monthlyAmount) {
            $excessAmount = $validated['amount'] - $monthlyAmount;
            $validated['amount'] = $monthlyAmount;
        }

        $validated['paid_status'] = 'paid';
        $validated['user_id'] = Auth::id();

        $transaction = Transaction::create($validated);

        $donor->total_donation += $validated['amount'];
        $donor->donation_count += 1;
        $donor->save();

        if ($excessAmount > 0) {
            try {
                $month = Month::find($validated['month_id']);

                $donation = Donation::create([
                    'donor_id' => $donor->id,
                    'contributor_id' => null,
                    'amount' => $excessAmount,
                    'paid_status' => 'paid',
                    'payment_method' => $validated['payment_method'],
                    'notes' => 'Excess amount from monthly transaction for ' . $month->name . ' ' . $month->year,
                    'user_id' => Auth::id(),
                ]);

                $donor->total_donation += $excessAmount;
                $donor->donation_count += 1;
                $donor->save();

                try {
                    $contributor = Contributor::where('phone', $donor->phone)->first();

                    if ($contributor) {
                        $contributor->amount += $excessAmount;
                        $contributor->donation_count += 1;
                        $contributor->last_donation_at = now();
                        $contributor->save();

                        $donation->contributor_id = $contributor->id;
                        $donation->save();
                    } else {
                        $contributor = Contributor::create([
                            'name' => $donor->name,
                            'phone' => $donor->phone,
                            'amount' => $excessAmount,
                            'donation_count' => 1,
                            'last_donation_at' => now(),
                        ]);

                        $donation->contributor_id = $contributor->id;
                        $donation->save();
                    }
                } catch (\Exception $e) {
                    \Log::error('Failed to update contributor stats', [
                        'error' => $e->getMessage(),
                        'donation_id' => $donation->id,
                        'donor_id' => $donor->id,
                    ]);
                }

                if (class_exists('App\Models\DonationLog')) {
                    DonationLog::create([
                        'donation_id' => $donation->id,
                        'user_id' => Auth::id(),
                        'field_name' => null,
                        'old_value' => null,
                        'new_value' => null,
                        'donation_snapshot' => $donation->toArray(),
                        'action' => 'created',
                        'ip_address' => $request->ip(),
                        'user_agent' => $request->userAgent(),
                    ]);
                }

                \Log::info('Excess amount converted to donation', [
                    'transaction_id' => $transaction->id,
                    'donation_id' => $donation->id,
                    'donation_amount' => $excessAmount,
                    'donor_id' => $donor->id,
                    'contributor_id' => $donation->contributor_id,
                    'donor_total_donation' => $donor->total_donation,
                    'donor_donation_count' => $donor->donation_count,
                    'original_amount' => $originalAmount,
                    'transaction_amount' => $validated['amount'],
                ]);
            } catch (\Exception $e) {
                \Log::error('Failed to create donation from excess amount', [
                    'error' => $e->getMessage(),
                    'transaction_id' => $transaction->id,
                    'excess_amount' => $excessAmount,
                    'donor_id' => $donor->id,
                    'trace' => $e->getTraceAsString(),
                ]);

                session()->flash('warning', 'Transaction created but excess amount could not be recorded as donation. Please check logs.');
            }
        }

        if (class_exists('App\Models\TransactionLog')) {
            TransactionLog::create([
                'transaction_id' => $transaction->id,
                'user_id' => Auth::id(),
                'field_name' => null,
                'old_value' => null,
                'new_value' => null,
                'transaction_snapshot' => $this->buildTransactionSnapshot($transaction),
                'action' => 'created',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        }

        try {
            if (class_exists('App\Helpers\NotificationHelper')) {
                NotificationHelper::sendTransactionSMS($transaction);
            }
        } catch (\Exception $e) {
            \Log::error('SMS notification failed', [
                'transaction_id' => $transaction->id,
                'error' => $e->getMessage(),
            ]);
        }

        $message = 'Transaction created successfully.';
        if ($excessAmount > 0) {
            $message .= ' Excess amount ৳' . number_format($excessAmount, 2) . ' has been recorded as donation.';
            $message .= ' Donor total: ৳' . number_format($donor->total_donation) . ' (' . $donor->donation_count . ' donations)';
        }

        return redirect()->route('transactions.index')->with('success', $message);
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
        $months = $this->getOrderedMonths()->where('status', 'active');

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
            'paid_status' => 'required|in:paid,unpaid',
        ]);

        $exists = Transaction::where('donor_id', $validated['donor_id'])
            ->where('month_id', $validated['month_id'])
            ->where('id', '!=', $transaction->id)
            ->exists();

        if ($exists) {
            return back()
                ->withErrors(['month_id' => 'Transaction for this donor and month already exists.'])
                ->withInput();
        }

        $originalData = $transaction->toArray();

        $changes = [];
        $fieldsToCheck = ['donor_id', 'month_id', 'amount', 'payment_method', 'paid_status'];

        foreach ($fieldsToCheck as $field) {
            $oldValue = $originalData[$field] ?? null;
            $newValue = $validated[$field] ?? null;

            if ($oldValue != $newValue) {
                $changes[] = [
                    'field_name' => $field,
                    'old_value' => $oldValue,
                    'new_value' => $newValue,
                ];
            }
        }

        $validated['user_id'] = Auth::id();
        $transaction->update($validated);
        $transaction->refresh();

        foreach ($changes as $change) {
            TransactionLog::create([
                'transaction_id' => $transaction->id,
                'user_id' => Auth::id(),
                'field_name' => $change['field_name'],
                'old_value' => $change['old_value'],
                'new_value' => $change['new_value'],
                'transaction_snapshot' => $this->buildTransactionSnapshot($transaction),
                'action' => 'updated',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        }

        if (empty($changes)) {
            TransactionLog::create([
                'transaction_id' => $transaction->id,
                'user_id' => Auth::id(),
                'field_name' => null,
                'old_value' => null,
                'new_value' => null,
                'transaction_snapshot' => $this->buildTransactionSnapshot($transaction),
                'action' => 'updated',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        }

        if (!empty($changes)) {
            try {
                $hasImportantChange = collect($changes)
                    ->pluck('field_name')
                    ->intersect(['amount', 'paid_status', 'month_id'])
                    ->isNotEmpty();

                if ($hasImportantChange) {
                    NotificationHelper::sendTransactionSMS($transaction);
                }
            } catch (\Exception $e) {
                \Log::error('Update SMS notification failed', [
                    'transaction_id' => $transaction->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return redirect()->route('transactions.index')->with('success', 'Transaction updated successfully.');
    }

    /**
     * Remove the specified transaction from storage.
     */
    public function destroy(Transaction $transaction)
{
    try {
        DB::beginTransaction();

        $transaction->load(['donor', 'month', 'user']);

        \Log::info('=== STARTING TRANSACTION DELETION ===', [
            'transaction_id' => $transaction->id,
            'user_id' => Auth::id(),
            'ip' => request()->ip(),
        ]);

        $transactionId = $transaction->id;

        $transactionData = [
            'id' => $transaction->id,
            'donor_id' => $transaction->donor_id,
            'month_id' => $transaction->month_id,
            'amount' => $transaction->amount,
            'payment_method' => $transaction->payment_method,
            'paid_status' => $transaction->paid_status,
            'user_id' => $transaction->user_id,
            'created_at' => optional($transaction->created_at)?->toDateTimeString(),
            'updated_at' => optional($transaction->updated_at)?->toDateTimeString(),

            'donor_name' => $transaction->donor?->name,
            'donor_phone' => $transaction->donor?->phone,
            'donor_address' => $transaction->donor?->address,
            'donor_monthly_amount' => $transaction->donor?->monthly_amount,

            'month_name' => $transaction->month?->name,
            'month_year' => $transaction->month?->year,

            'recorded_by' => $transaction->user?->name,
        ];

        TransactionLog::create([
            'transaction_id' => $transactionId,
            'user_id' => Auth::id(),
            'field_name' => null,
            'old_value' => null,
            'new_value' => null,
            'transaction_snapshot' => $transactionData,
            'action' => 'deleted',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        $transaction->delete();

        DB::commit();

        \Log::info('Transaction deleted successfully', [
            'transaction_id' => $transactionId,
        ]);

        return redirect()->route('transactions.index')->with('success', 'Transaction deleted successfully.');
    } catch (\Exception $e) {
        DB::rollBack();

        \Log::error('=== TRANSACTION DELETION FAILED ===', [
            'transaction_id' => $transaction->id ?? null,
            'error_message' => $e->getMessage(),
            'error_file' => $e->getFile(),
            'error_line' => $e->getLine(),
            'trace' => $e->getTraceAsString(),
        ]);

        return redirect()->route('transactions.index')->with('error', 'Failed to delete transaction: ' . $e->getMessage());
    }
}

    /**
     * Mark transaction as paid
     */
    public function markAsPaid(Transaction $transaction)
    {
        $oldStatus = $transaction->paid_status;

        $transaction->update([
            'paid_status' => 'paid',
            'user_id' => Auth::id(),
        ]);

        $transaction->refresh();

        TransactionLog::create([
            'transaction_id' => $transaction->id,
            'user_id' => Auth::id(),
            'field_name' => 'paid_status',
            'old_value' => $oldStatus,
            'new_value' => 'paid',
            'transaction_snapshot' => $this->buildTransactionSnapshot($transaction),
            'action' => 'updated',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
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
                'amount' => $donor->monthly_amount,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Donor not found',
        ]);
    }

    /**
     * Display transaction logs for a specific transaction
     */
    public function logs(Transaction $transaction)
    {
        $logs = TransactionLog::where('transaction_id', $transaction->id)
            ->with(['user', 'transaction', 'transaction.donor', 'transaction.month'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('transactions.logs', compact('transaction', 'logs'));
    }

    /**
     * Display all transaction logs across all transactions
     */
    public function allLogs(Request $request)
    {
        $query = TransactionLog::with(['transaction', 'transaction.donor', 'transaction.month', 'user'])
            ->orderBy('created_at', 'desc');

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

        if ($request->filled('donor_id')) {
            $query->where(function ($q) use ($request) {
                $q->whereHas('transaction', function ($subQ) use ($request) {
                    $subQ->where('donor_id', $request->donor_id);
                })->orWhere('transaction_snapshot->donor_id', $request->donor_id);
            });
        }

        $logs = $query->paginate(30);

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
        $query = TransactionLog::with(['transaction', 'transaction.donor', 'transaction.month', 'user']);

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

        $filename = 'transaction-logs-' . now()->format('Y-m-d-His') . '.csv';
        $handle = fopen('php://temp', 'w');

        fputcsv($handle, [
            'Log ID',
            'Date',
            'Time',
            'Transaction ID',
            'Donor',
            'Month',
            'Year',
            'User',
            'Action',
            'Field',
            'Old Value',
            'New Value',
            'IP Address'
        ]);

        foreach ($logs as $log) {
            $snapshot = $log->transaction_snapshot ?? [];

            $transactionId = $log->transaction_id ?? ($snapshot['id'] ?? 'Deleted');
            $donorName = $log->transaction?->donor?->name ?? ($snapshot['donor_name'] ?? 'Deleted Donor');
            $monthName = $log->transaction?->month?->name ?? ($snapshot['month_name'] ?? 'Unknown');
            $monthYear = $log->transaction?->month?->year ?? ($snapshot['month_year'] ?? 'Unknown');
            $userName = $log->user?->name ?? ($snapshot['recorded_by'] ?? 'Unknown');

            fputcsv($handle, [
                $log->id,
                $log->created_at->format('Y-m-d'),
                $log->created_at->format('H:i:s'),
                $transactionId,
                $donorName,
                $monthName,
                $monthYear,
                $userName,
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

    /**
     * Get paid months for a donor (AJAX)
     */
    public function getPaidMonths(Request $request)
    {
        $request->validate([
            'donor_id' => 'required|exists:donors,id',
        ]);

        $paidMonths = Transaction::where('donor_id', $request->donor_id)
            ->where('paid_status', 'paid')
            ->pluck('month_id')
            ->toArray();

        return response()->json([
            'success' => true,
            'paid_months' => $paidMonths,
        ]);
    }
}