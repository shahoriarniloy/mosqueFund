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
    $query = Donation::with(['donor', 'contributor', 'user']);

    // Search by donor name, contributor name, or phone
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->whereHas('donor', function ($donorQuery) use ($search) {
                $donorQuery->where('name', 'like', "%{$search}%")
                          ->orWhere('phone', 'like', "%{$search}%");
            })
            ->orWhereHas('contributor', function ($contributorQuery) use ($search) {
                $contributorQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('phone', 'like', "%{$search}%");
            });
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

    // Filter by donor type
    if ($request->filled('donor_type')) {
        if ($request->donor_type === 'monthly') {
            $query->whereNotNull('donor_id');
        } elseif ($request->donor_type === 'random') {
            $query->whereNotNull('contributor_id');
        }
    }

    // Filter by date range
    if ($request->filled('from_date')) {
        $query->whereDate('created_at', '>=', $request->from_date);
    }
    if ($request->filled('to_date')) {
        $query->whereDate('created_at', '<=', $request->to_date);
    }

    $donations = $query->latest()->paginate(15)->withQueryString();

    // Calculate statistics
    $totalAmount = Donation::sum('amount');
    $paidCount = Donation::where('paid_status', 'paid')->count();
    $unpaidCount = Donation::where('paid_status', 'unpaid')->count();
    $thisMonthAmount = Donation::whereMonth('created_at', now()->month)
        ->whereYear('created_at', now()->year)
        ->sum('amount');
    
    // Additional stats
    $monthlyDonorCount = Donation::whereNotNull('donor_id')->count();
    $randomDonorCount = Donation::whereNotNull('contributor_id')->count();

    // If it's an AJAX request, return JSON
    if ($request->ajax() || $request->has('ajax')) {
        $mobileCards = view('donations.partials.mobile-cards', compact('donations'))->render();
        $desktopTable = view('donations.partials.desktop-table', compact('donations'))->render();
        $stats = view('donations.partials.stats', compact(
            'totalAmount', 
            'paidCount', 
            'unpaidCount', 
            'thisMonthAmount',
            'monthlyDonorCount',
            'randomDonorCount'
        ))->render();
        $pagination = $donations->withQueryString()->links()->render();

        return response()->json([
            'mobileCards' => $mobileCards,
            'desktopTable' => $desktopTable,
            'stats' => $stats,
            'pagination' => $pagination,
            'total' => $donations->total(),
        ]);
    }

    return view('donations.index', compact(
        'donations', 
        'totalAmount', 
        'paidCount', 
        'unpaidCount', 
        'thisMonthAmount',
        'monthlyDonorCount',
        'randomDonorCount'
    ));
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
            'paid_status' => 'nullable|in:paid,unpaid',
            'payment_method' => 'required|in:cash,bkash,nagad',
            'notes' => 'nullable|string|max:500',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['paid_status'] = 'paid';

        // Initialize foreign keys as null
        $donorId = null;
        $contributorId = null;

        // Check if donor_id is provided directly
        if (!empty($validated['donor_id'])) {
            $donor = Donor::find($validated['donor_id']);
            if ($donor) {
                $donorId = $donor->id;
            }
        }
        // Check if phone number is provided (for contributor lookup)
        elseif (!empty($validated['phone'])) {
            // First check in donors table
            $donor = Donor::where('phone', $validated['phone'])->first();

            if ($donor) {
                // Existing donor - link to donor
                $donorId = $donor->id;
            } else {
                // Check in contributors table
                $contributor = Contributor::findByPhone($validated['phone']);

                if ($contributor) {
                    // Existing contributor
                    $contributorId = $contributor->id;

                    // Update contributor stats
                    $contributor->amount += $validated['amount'];
                    $contributor->donation_count += 1;
                    $contributor->last_donation_at = now();
                    $contributor->save();
                } else {
                    // Create new contributor
                    $contributor = Contributor::create([
                        'name' => $validated['name'] ?? 'Anonymous',
                        'phone' => $validated['phone'],
                        'amount' => $validated['amount'],
                        'donation_count' => 1,
                        'last_donation_at' => now(),
                    ]);
                    $contributorId = $contributor->id;
                }
            }
        }

        // Create the donation with only the fields that exist in the table
        $donation = Donation::create([
            'donor_id' => $donorId,
            'contributor_id' => $contributorId,
            'amount' => $validated['amount'],
            'paid_status' => $validated['paid_status'],
            'payment_method' => $validated['payment_method'],
            'notes' => $validated['notes'] ?? null,
            'user_id' => $validated['user_id'],
        ]);

        // If this donation is linked to a donor, update donor's stats
        if ($donation->donor_id) {
            $donor = Donor::find($donation->donor_id);
            if ($donor) {
                $donor->donation_count += 1;
                $donor->total_donation += $donation->amount;
                $donor->save();

                \Log::info('Donor stats updated', [
                    'donor_id' => $donor->id,
                    'donation_count' => $donor->donation_count,
                    'total_donation' => $donor->total_donation,
                ]);
            }
        }

        // Send SMS notification if phone number exists
        if (!empty($validated['phone'])) {
            try {
                // You might need to modify this helper to work with donation
                if (class_exists('App\Helpers\NotificationHelper')) {
                    $smsResult = NotificationHelper::sendDonationSMS($donation);

                    if ($smsResult['success']) {
                        \Log::info('Donation SMS sent successfully', [
                            'donation_id' => $donation->id,
                            'phone' => $validated['phone'],
                        ]);
                    } else {
                        \Log::warning('Donation SMS failed', [
                            'donation_id' => $donation->id,
                            'phone' => $validated['phone'],
                            'error' => $smsResult['message'],
                        ]);
                    }
                }
            } catch (\Exception $e) {
                \Log::error('Donation SMS exception', [
                    'donation_id' => $donation->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return redirect()->route('donations.index')->with('success', 'Donation recorded successfully.');
    }

   
    public function show(Donation $donation)
    {
        $donation->load(['donor', 'user']);
        return view('donations.show', compact('donation'));
    }

   
    public function edit(Donation $donation)
    {
        $donors = Donor::where('status', 'active')->orderBy('name')->get();
        return view('donations.edit', compact('donation', 'donors'));
    }

    
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

        $originalData = $donation->toArray();

        $changes = [];
        $fieldsToCheck = ['donor_id', 'name', 'phone', 'amount', 'paid_status', 'payment_method', 'notes'];

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

        if ($request->filled('donor_id')) {
            $donor = Donor::find($request->donor_id);
            $validated['name'] = $donor->name;
            $validated['phone'] = $donor->phone;
        }

        $donation->update($validated);

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

    
    public function destroy(Donation $donation)
    {
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

   
    public function markAsPaid(Donation $donation)
    {
        $donation->update([
            'paid_status' => 'paid',
            'user_id' => Auth::id(),
        ]);

        return back()->with('success', 'Donation marked as paid.');
    }

   
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

    
    public function export(Request $request)
    {
        return back()->with('info', 'Export feature coming soon!');
    }

   
    public function checkDonor(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|min:8',
        ]);

        $searchPhone = $request->phone;
        $cleanSearch = preg_replace('/[^0-9]/', '', $searchPhone);

        $donors = Donor::all();
        foreach ($donors as $donor) {
            $cleanDonor = preg_replace('/[^0-9]/', '', $donor->phone);

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

        $contributors = Contributor::all();
        foreach ($contributors as $contributor) {
            $cleanContributor = preg_replace('/[^0-9]/', '', $contributor->phone);

            if ($cleanContributor === $cleanSearch) {
                return response()->json([
                    'success' => true,
                    'type' => 'contributor',
                    'donor' => [
                        'id' => $contributor->id,
                        'name' => $contributor->name,
                        'phone' => $contributor->phone,
                        'total_amount' => $contributor->amount,
                        'donation_count' => $contributor->donation_count,
                        'source' => 'contributor',
                    ],
                ]);
            }

            if (strlen($cleanContributor) >= 11 && strlen($cleanSearch) >= 11) {
                if (substr($cleanContributor, -11) === substr($cleanSearch, -11)) {
                    return response()->json([
                        'success' => true,
                        'type' => 'contributor',
                        'donor' => [
                            'id' => $contributor->id,
                            'name' => $contributor->name,
                            'phone' => $contributor->phone,
                            'total_amount' => $contributor->amount,
                            'donation_count' => $contributor->donation_count,
                            'source' => 'contributor',
                        ],
                    ]);
                }
            }

            if (strlen($cleanContributor) >= 10 && strlen($cleanSearch) >= 10) {
                if (substr($cleanContributor, -10) === substr($cleanSearch, -10)) {
                    return response()->json([
                        'success' => true,
                        'type' => 'contributor',
                        'donor' => [
                            'id' => $contributor->id,
                            'name' => $contributor->name,
                            'phone' => $contributor->phone,
                            'total_amount' => $contributor->amount,
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

  
    public function logs(Donation $donation)
    {
        $logs = DonationLog::where('donation_id', $donation->id)->with('user')->orderBy('created_at', 'desc')->paginate(20);

        return view('donations.logs', compact('donation', 'logs'));
    }

    
    public function allLogs(Request $request)
    {
        $query = DonationLog::with(['donation', 'user'])->orderBy('created_at', 'desc');

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

        if ($request->filled('donation_id')) {
            $query->where('donation_id', $request->donation_id);
        }

        if ($request->filled('field')) {
            $query->where('field_name', $request->field);
        }

        $logs = $query->paginate(30);

        $users = User::orderBy('name')->get();
        $actions = ['created', 'updated', 'deleted', 'restored'];
        $fields = ['donor_id', 'name', 'phone', 'amount', 'paid_status', 'payment_method', 'notes'];

        return view('donation-logs.index', compact('logs', 'users', 'actions', 'fields'));
    }

   
    public function showLog(DonationLog $donationLog)
    {
        $donationLog->load(['donation', 'user', 'donation.donor']);

        return view('donation-logs.show', compact('donationLog'));
    }
}
