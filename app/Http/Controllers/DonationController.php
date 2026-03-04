<?php
namespace App\Http\Controllers;

use App\Helpers\NotificationHelper;
use App\Models\Contributor;
use App\Models\Donation;
use App\Models\DonationLog;
use App\Models\Donor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class DonationController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [new Middleware('auth')];
    }

    /**
     * Display a listing of donations.
     */
    public function index(Request $request)
    {
        $query = Donation::with(['donor', 'user']);

        // Search by name or phone
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")->orWhere('phone', 'like', "%{$search}%");
            });
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

        // Filter by donor type
        if ($request->filled('donor_type')) {
            if ($request->donor_type == 'existing') {
                $query->whereNotNull('donor_id');
            } elseif ($request->donor_type == 'new') {
                $query->whereNull('donor_id');
            }
        }

        $donations = $query->latest()->paginate(15);

        // Calculate statistics
        $totalAmount = Donation::sum('amount');
        $paidCount = Donation::where('paid_status', 'paid')->count();
        $unpaidCount = Donation::where('paid_status', 'unpaid')->count();
        $thisMonthAmount = Donation::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->sum('amount');

        return view('donations.index', compact('donations', 'totalAmount', 'paidCount', 'unpaidCount', 'thisMonthAmount'));
    }

    /**
     * Show the form for creating a new donation.
     */
    public function create()
    {
        $donors = Donor::where('status', 'active')->orderBy('name')->get();
        return view('donations.create', compact('donors'));
    }

  
     public function store(Request $request)
    {
        $validated = $request->validate([
            'donor_id' => 'nullable|exists:donors,id',
            'name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'amount' => 'required|numeric|min:1',
            'paid_status' => 'required|in:paid,unpaid',
            'payment_method' => 'required|in:cash,bkash,nagad',
            'notes' => 'nullable|string|max:500'
        ]);

        $validated['user_id'] = Auth::id();
        
        // Set default name if not provided
        if (empty($validated['name'])) {
            $validated['name'] = 'Anonymous';
        }
        
        // Check if phone number is provided
        if (!empty($validated['phone'])) {
            // First check in donors table
            $donor = Donor::where('phone', $validated['phone'])->first();
            
            if ($donor) {
                // Existing donor - link to donor
                $validated['donor_id'] = $donor->id;
                $validated['name'] = $donor->name; // Override with donor name
            } else {
                // Check in contributors table
                $contributor = Contributor::findByPhone($validated['phone']);
                
                if ($contributor) {
                    // Update existing contributor
                    $contributor->updateStats($validated['amount']);
                } else {
                    // Create new contributor
                    Contributor::create([
                        'name' => $validated['name'] ?? null,
                        'phone' => $validated['phone'],
                        'total_amount' => $validated['amount'],
                        'donation_count' => 1,
                        'last_donation_at' => now()
                    ]);
                }
            }
        }
        
        $donation = Donation::create($validated);

        // Send SMS notification if phone number exists
        if (!empty($validated['phone'])) {
            try {
                $smsResult = NotificationHelper::sendDonationSMS($donation);
                
                if ($smsResult['success']) {
                    \Log::info('Donation SMS sent successfully', [
                        'donation_id' => $donation->id,
                        'phone' => $validated['phone']
                    ]);
                } else {
                    \Log::warning('Donation SMS failed', [
                        'donation_id' => $donation->id,
                        'phone' => $validated['phone'],
                        'error' => $smsResult['message']
                    ]);
                }
            } catch (\Exception $e) {
                \Log::error('Donation SMS exception', [
                    'donation_id' => $donation->id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        return redirect()->route('donations.index')
            ->with('success', 'Donation recorded successfully.');
    }

    /**
     * Display the specified donation.
     */
    public function show(Donation $donation)
    {
        $donation->load(['donor', 'user']);
        return view('donations.show', compact('donation'));
    }

    /**
     * Show the form for editing the specified donation.
     */
    public function edit(Donation $donation)
    {
        $donors = Donor::where('status', 'active')->orderBy('name')->get();
        return view('donations.edit', compact('donation', 'donors'));
    }

    /**
     * Update the specified donation in storage.
     */
    public function update(Request $request, Donation $donation)
    {
        $validated = $request->validate([
            'donor_id' => 'nullable|exists:donors,id',
            'name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'amount' => 'required|numeric|min:1',
            'paid_status' => 'required|in:paid,unpaid',
            'payment_method' => 'required|in:cash,bkash,nagad',
            'notes' => 'nullable|string|max:500',
        ]);

        // Get the original donation data before update
        $originalData = $donation->toArray();

        // Track which fields are being changed
        $changes = [];
        $fieldsToCheck = ['donor_id', 'name', 'phone', 'amount', 'paid_status', 'payment_method', 'notes'];

        foreach ($fieldsToCheck as $field) {
            $oldValue = $originalData[$field] ?? null;
            $newValue = $validated[$field] ?? null;

            // Check if the value has changed
            if ($oldValue != $newValue) {
                $changes[] = [
                    'field_name' => $field,
                    'old_value' => $oldValue,
                    'new_value' => $newValue,
                ];
            }
        }

        // If donor is selected, update name and phone from donor
        if ($request->filled('donor_id')) {
            $donor = Donor::find($request->donor_id);
            $validated['name'] = $donor->name;
            $validated['phone'] = $donor->phone;
        }

        // Update the donation
        $donation->update($validated);

        // Log each changed field
        foreach ($changes as $change) {
            DonationLog::create([
                'donation_id' => $donation->id,
                'user_id' => Auth::id(),
                'field_name' => $change['field_name'],
                'old_value' => $change['old_value'],
                'new_value' => $change['new_value'],
                'donation_snapshot' => $donation->toArray(),
                'action' => 'updated',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        }

        // If no specific fields were tracked but there was an update, log a general entry
        if (empty($changes)) {
            DonationLog::create([
                'donation_id' => $donation->id,
                'user_id' => Auth::id(),
                'field_name' => null,
                'old_value' => null,
                'new_value' => null,
                'donation_snapshot' => $donation->toArray(),
                'action' => 'updated',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        }

        return redirect()->route('donations.index')->with('success', 'Donation updated successfully.');
    }
   
    /**
     * Remove the specified donation from storage.
     */
    public function destroy(Donation $donation)
    {
        // Log the deletion before actually deleting
        DonationLog::create([
            'donation_id' => $donation->id,
            'user_id' => Auth::id(),
            'field_name' => null,
            'old_value' => null,
            'new_value' => null,
            'donation_snapshot' => $donation->toArray(),
            'action' => 'deleted',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        $donation->delete();

        return redirect()->route('donations.index')->with('success', 'Donation deleted successfully.');
    }

    /**
     * Mark donation as paid
     */
    public function markAsPaid(Donation $donation)
    {
        $donation->update([
            'paid_status' => 'paid',
            'user_id' => Auth::id(),
        ]);

        return back()->with('success', 'Donation marked as paid.');
    }

    /**
     * Get donor details for AJAX request
     */
    public function getDonorDetails(Request $request)
    {
        $donor = Donor::find($request->donor_id);

        if ($donor) {
            return response()->json([
                'success' => true,
                'name' => $donor->name,
                'phone' => $donor->phone,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Donor not found',
        ]);
    }

    /**
     * Export donations report
     */
    public function export(Request $request)
    {
        // You can implement Excel/PDF export here
        return back()->with('info', 'Export feature coming soon!');
    }

    /**
     * Check if donor exists by phone number
     */
    /**
     * Check if donor exists by phone number (in both donors and contributors)
     */
    public function checkDonor(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|min:8',
        ]);

        $searchPhone = $request->phone;
        $cleanSearch = preg_replace('/[^0-9]/', '', $searchPhone);

        // Check in donors table first
        $donors = Donor::all();
        foreach ($donors as $donor) {
            $cleanDonor = preg_replace('/[^0-9]/', '', $donor->phone);

            // Exact match after cleaning
            if ($cleanDonor === $cleanSearch) {
                return response()->json([
                    'success' => true,
                    'type' => 'donor',
                    'donor' => [
                        'id' => $donor->id,
                        'name' => $donor->name,
                        'phone' => $donor->phone,
                        'status' => $donor->status,
                        'source' => 'donor',
                    ],
                ]);
            }

            // Match last 11 digits
            if (strlen($cleanDonor) >= 11 && strlen($cleanSearch) >= 11) {
                if (substr($cleanDonor, -11) === substr($cleanSearch, -11)) {
                    return response()->json([
                        'success' => true,
                        'type' => 'donor',
                        'donor' => [
                            'id' => $donor->id,
                            'name' => $donor->name,
                            'phone' => $donor->phone,
                            'status' => $donor->status,
                            'source' => 'donor',
                        ],
                    ]);
                }
            }

            // Match last 10 digits
            if (strlen($cleanDonor) >= 10 && strlen($cleanSearch) >= 10) {
                if (substr($cleanDonor, -10) === substr($cleanSearch, -10)) {
                    return response()->json([
                        'success' => true,
                        'type' => 'donor',
                        'donor' => [
                            'id' => $donor->id,
                            'name' => $donor->name,
                            'phone' => $donor->phone,
                            'status' => $donor->status,
                            'source' => 'donor',
                        ],
                    ]);
                }
            }
        }

        // Check in contributors table
        $contributors = Contributor::all();
        foreach ($contributors as $contributor) {
            $cleanContributor = preg_replace('/[^0-9]/', '', $contributor->phone);

            // Exact match after cleaning
            if ($cleanContributor === $cleanSearch) {
                return response()->json([
                    'success' => true,
                    'type' => 'contributor',
                    'donor' => [
                        'id' => $contributor->id,
                        'name' => $contributor->name,
                        'phone' => $contributor->phone,
                        'total_amount' => $contributor->total_amount,
                        'donation_count' => $contributor->donation_count,
                        'source' => 'contributor',
                    ],
                ]);
            }

            // Match last 11 digits
            if (strlen($cleanContributor) >= 11 && strlen($cleanSearch) >= 11) {
                if (substr($cleanContributor, -11) === substr($cleanSearch, -11)) {
                    return response()->json([
                        'success' => true,
                        'type' => 'contributor',
                        'donor' => [
                            'id' => $contributor->id,
                            'name' => $contributor->name,
                            'phone' => $contributor->phone,
                            'total_amount' => $contributor->total_amount,
                            'donation_count' => $contributor->donation_count,
                            'source' => 'contributor',
                        ],
                    ]);
                }
            }

            // Match last 10 digits
            if (strlen($cleanContributor) >= 10 && strlen($cleanSearch) >= 10) {
                if (substr($cleanContributor, -10) === substr($cleanSearch, -10)) {
                    return response()->json([
                        'success' => true,
                        'type' => 'contributor',
                        'donor' => [
                            'id' => $contributor->id,
                            'name' => $contributor->name,
                            'phone' => $contributor->phone,
                            'total_amount' => $contributor->total_amount,
                            'donation_count' => $contributor->donation_count,
                            'source' => 'contributor',
                        ],
                    ]);
                }
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'No donor or contributor found with this phone number',
        ]);
    }

    /**
     * Display donation edit history
     */
    public function logs(Donation $donation)
    {
        $logs = DonationLog::where('donation_id', $donation->id)->with('user')->orderBy('created_at', 'desc')->paginate(20);

        return view('donations.logs', compact('donation', 'logs'));
    }

    /**
 * Display all donation logs across all donations
 */
public function allLogs(Request $request)
{
    $query = DonationLog::with(['donation', 'user'])
        ->orderBy('created_at', 'desc');
    
    // Filter by action (created, updated, deleted)
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
    
    // Filter by donation ID
    if ($request->filled('donation_id')) {
        $query->where('donation_id', $request->donation_id);
    }
    
    // Filter by field name
    if ($request->filled('field')) {
        $query->where('field_name', $request->field);
    }
    
    $logs = $query->paginate(30);
    
    // Get data for filters
    $users = User::orderBy('name')->get();
    $actions = ['created', 'updated', 'deleted', 'restored'];
    $fields = ['donor_id', 'name', 'phone', 'amount', 'paid_status', 'payment_method', 'notes'];
    
    return view('donation-logs.index', compact('logs', 'users', 'actions', 'fields'));
}

/**
 * Show a single log entry details
 */
public function showLog(DonationLog $donationLog)
{
    $donationLog->load(['donation', 'user', 'donation.donor']);
    
    return view('donation-logs.show', compact('donationLog'));
}
}
