<?php
namespace App\Http\Controllers;

use App\Models\Donor;
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

        $donors = $query->latest()->paginate(10);
        
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

        Donor::create($validated);

        return redirect()->route('donors.index')
            ->with('success', 'Donor created successfully.');
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
        
        $totalDue = $donor->monthly_amount * 12 - $totalPaid; // Simple calculation
        
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