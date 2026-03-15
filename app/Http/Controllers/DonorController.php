<?php
namespace App\Http\Controllers;

use App\Models\Donor;
use App\Models\Month;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class DonorController extends Controller implements HasMiddleware
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
     * Display a listing of donors.
     */
    public function index(Request $request)
    {
        $query = Donor::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $donors = $query->paginate(10);
        
        // Get statistics
        $totalDonors = Donor::count();
        $activeDonors = Donor::where('status', 'active')->count();
        $inactiveDonors = Donor::where('status', 'inactive')->count();
        $totalMonthlyCommitment = Donor::where('status', 'active')->sum('monthly_amount');

        return view('donors.index', compact(
            'donors', 
            'totalDonors', 
            'activeDonors', 
            'inactiveDonors',
            'totalMonthlyCommitment'
        ));
    }

    /**
     * Show the form for creating a new donor.
     */
    public function create()
    {
        return view('donors.create');
    }

    /**
     * Store a newly created donor in storage.
     */
    public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'phone' => 'required|string|unique:donors,phone',
        'address' => 'nullable|string',
        'monthly_amount' => 'required|numeric|min:0',
        'status' => 'required|in:active,inactive'
    ]);

    // Add the created_by field with the current authenticated user's ID
    $validated['created_by'] = auth()->id();

    Donor::create($validated);

    return redirect()->route('donors.index')
        ->with('success', 'Donor created successfully.');
}


private function getMonthsInRange($startDate, $endDate)
    {
        $months = collect();
        
        $start = \Carbon\Carbon::parse($startDate)->startOfMonth();
        $end = \Carbon\Carbon::parse($endDate)->startOfMonth();
        
        while ($start <= $end) {
            $month = Month::where('year', $start->year)
                ->where('name', $start->format('F'))
                ->first();
            
            if ($month) {
                $months->push($month);
            }
            
            $start->addMonth();
        }
        
        return $months;
    }

    private function getMonthNumber($monthName)
    {
        $months = [
            'January' => 1, 'February' => 2, 'March' => 3, 'April' => 4,
            'May' => 5, 'June' => 6, 'July' => 7, 'August' => 8,
            'September' => 9, 'October' => 10, 'November' => 11, 'December' => 12
        ];
        
        return $months[$monthName] ?? 1;
    }

    /**
     * Display the specified donor.
     */
    public function show(Donor $donor)
    {
        // Get donor's transaction history
        $transactions = $donor->transactions()
            ->with(['month', 'user'])
            ->latest()
            ->paginate(10);

        // Get payment statistics
        $totalPaid = $donor->transactions()
            ->where('paid_status', 'paid')
            ->sum('amount');
        
        $totalDue = 0; // Simple calculation


        // Due Payment
        $currentDate = now();
        $creationDate = \Carbon\Carbon::parse($donor->created_at);
        
        // Get all months from creation date to current date
        $months = $this->getMonthsInRange($creationDate, $currentDate);
        
        // Get paid months for this donor
        $paidMonthIds = Transaction::where('donor_id', $donor->id)
            ->where('paid_status', 'paid')
            ->pluck('month_id')
            ->toArray();
        
        // Get unpaid transactions
        $unpaidTransactions = Transaction::where('donor_id', $donor->id)
            ->where('paid_status', 'unpaid')
            ->with('month')
            ->get();
        
        $unpaidMonthIds = $unpaidTransactions->pluck('month_id')->toArray();
        
        $dueMonths = [];
        $totalDue = 0;
        
        foreach ($months as $month) {
            // Check if this month is paid
            if (in_array($month->id, $paidMonthIds)) {
                continue;
            }
            
            // Check if this month is already recorded as unpaid in transactions
            if (in_array($month->id, $unpaidMonthIds)) {
                // Find the transaction for this month
                $transaction = $unpaidTransactions->firstWhere('month_id', $month->id);
                $dueDate = \Carbon\Carbon::create($month->year, $this->getMonthNumber($month->name), 1);
                $daysOverdue = $dueDate->isPast() ? $dueDate->diffInDays(now()) : 0;
                
                $dueMonths[] = [
                    'month' => $month,
                    'amount' => $transaction->amount,
                    'due_date' => $dueDate,
                    'days_overdue' => $daysOverdue,
                    'transaction_id' => $transaction->id
                ];
                
                $totalDue += $transaction->amount;
            } else {
                // This month is unpaid and no transaction record exists
                $dueDate = \Carbon\Carbon::create($month->year, $this->getMonthNumber($month->name), 1);
                $daysOverdue = $dueDate->isPast() ? $dueDate->diffInDays(now()) : 0;
                
                $dueMonths[] = [
                    'month' => $month,
                    'amount' => $donor->monthly_amount,
                    'due_date' => $dueDate,
                    'days_overdue' => $daysOverdue,
                    'transaction_id' => null
                ];
                
                $totalDue += $donor->monthly_amount;
            }
        }
        



        // End due
        
        // Calculate payment rate
        $totalExpected = $donor->monthly_amount * 12;
        $paymentRate = $totalExpected > 0 ? ($totalPaid / $totalExpected) * 100 : 0;
        
        $lastPayment = $donor->transactions()
            ->where('paid_status', 'paid')
            ->latest()
            ->first();

        return view('donors.show', compact(
            'donor', 
            'transactions', 
            'totalPaid', 
            'totalDue', 
            'lastPayment',
            'paymentRate'  // Added this variable
        ));
    }

    /**
     * Show the form for editing the specified donor.
     */
    public function edit(Donor $donor)
    {
        return view('donors.edit', compact('donor'));
    }

    /**
     * Update the specified donor in storage.
     */
    public function update(Request $request, Donor $donor)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|unique:donors,phone,' . $donor->id,
            'address' => 'nullable|string',
            'monthly_amount' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive'
        ]);

        $donor->update($validated);

        return redirect()->route('donors.index')
            ->with('success', 'Donor updated successfully.');
    }

    /**
     * Remove the specified donor from storage.
     */
    public function destroy(Donor $donor)
    {
        // Check if donor has transactions
        if ($donor->transactions()->count() > 0) {
            return back()->with('error', 'Cannot delete donor with existing transactions.');
        }

        $donor->delete();
        
        return redirect()->route('donors.index')
            ->with('success', 'Donor deleted successfully.');
    }

    /**
     * Toggle donor status
     */
    public function toggleStatus(Donor $donor)
    {
        $donor->status = $donor->status === 'active' ? 'inactive' : 'active';
        $donor->save();

        return back()->with('success', 'Donor status updated successfully.');
    }
}