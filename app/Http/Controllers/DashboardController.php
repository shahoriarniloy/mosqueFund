<?php
namespace App\Http\Controllers;

use App\Models\Donor;
use App\Models\Donation;
use App\Models\Transaction;
use App\Models\Month;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class DashboardController extends Controller implements HasMiddleware
{
   
    public static function middleware(): array
    {
        return [
            new Middleware('auth'),
        ];
    }

    /**
     * Display the dashboard.
     */
    public function index(Request $request)
    {
        // Donors Statistics
        $donorsCount = Donor::count();
        $activeDonors = Donor::where('status', 'active')->count();
        
        // Months Statistics
        $monthsCount = Month::count();
        $activeMonths = Month::where('status', 'active')->count();
        
        // Transactions Statistics
        $transactionsCount = Transaction::count();
        $totalTransactionAmount = Transaction::sum('amount');
        
        // Donations Statistics
        $donationsCount = Donation::count();
        $totalDonationAmount = Donation::sum('amount');
        
        // Collection Statistics
        $totalCollected = Transaction::where('paid_status', 'paid')->sum('amount') + 
                          Donation::where('paid_status', 'paid')->sum('amount');
        
        $pendingAmount = Transaction::where('paid_status', 'unpaid')->sum('amount') + 
                         Donation::where('paid_status', 'unpaid')->sum('amount');
        
        $pendingCount = Transaction::where('paid_status', 'unpaid')->count() + 
                        Donation::where('paid_status', 'unpaid')->count();
        
        $overdueCount = Transaction::where('paid_status', 'unpaid')
                          ->where('created_at', '<', now()->subDays(30))
                          ->count() +
                        Donation::where('paid_status', 'unpaid')
                          ->where('created_at', '<', now()->subDays(30))
                          ->count();
        
        // This Month Statistics
        $thisMonthTotal = Transaction::whereMonth('created_at', now()->month)
                            ->whereYear('created_at', now()->year)
                            ->sum('amount') + 
                          Donation::whereMonth('created_at', now()->month)
                            ->whereYear('created_at', now()->year)
                            ->sum('amount');
        
        $thisMonthPaid = Transaction::whereMonth('created_at', now()->month)
                           ->whereYear('created_at', now()->year)
                           ->where('paid_status', 'paid')
                           ->count() +
                         Donation::whereMonth('created_at', now()->month)
                           ->whereYear('created_at', now()->year)
                           ->where('paid_status', 'paid')
                           ->count();
        
        $thisMonthUnpaid = Transaction::whereMonth('created_at', now()->month)
                             ->whereYear('created_at', now()->year)
                             ->where('paid_status', 'unpaid')
                             ->count() +
                           Donation::whereMonth('created_at', now()->month)
                             ->whereYear('created_at', now()->year)
                             ->where('paid_status', 'unpaid')
                             ->count();
        
        // Monthly Collection (for chart)
        $currentYear = now()->year;
        $monthlyCollected = Transaction::whereMonth('created_at', now()->month)
                              ->whereYear('created_at', now()->year)
                              ->where('paid_status', 'paid')
                              ->sum('amount') +
                            Donation::whereMonth('created_at', now()->month)
                              ->whereYear('created_at', now()->year)
                              ->where('paid_status', 'paid')
                              ->sum('amount');
        
        $totalExpected = $activeDonors * 12 * (Donor::avg('monthly_amount') ?? 0);
        $collectionRate = $totalExpected > 0 ? round(($totalCollected / $totalExpected) * 100, 1) : 0;
        
        $recentTransactions = Transaction::with(['donor', 'month', 'user'])
                               ->latest()
                               ->take(5)
                               ->get();
        
        $totalDonors = $donorsCount;
        $totalTransactions = $transactionsCount;
        $currentMonthCollection = $monthlyCollected;
        $currentMonthTransactions = $thisMonthPaid + $thisMonthUnpaid;
        
        $monthlyCollection = Transaction::join('months', 'transactions.month_id', '=', 'months.id')
            ->where('months.year', $currentYear)
            ->where('paid_status', 'paid')
            ->select(
                'months.name as month',
                DB::raw('SUM(transactions.amount) as total')
            )
            ->groupBy('months.name', 'months.id')
            ->orderBy('months.id')
            ->get();
        
        $paymentMethods = Transaction::where('paid_status', 'paid')
            ->select('payment_method', DB::raw('COUNT(*) as count'), DB::raw('SUM(amount) as total'))
            ->groupBy('payment_method')
            ->get();
        
        $topDonors = Transaction::where('paid_status', 'paid')
            ->with('donor')
            ->select('donor_id', DB::raw('SUM(amount) as total_paid'))
            ->groupBy('donor_id')
            ->orderBy('total_paid', 'desc')
            ->take(5)
            ->get();
        
        $currentMonth = now()->format('F');
        $currentMonthId = Month::where('name', $currentMonth)
            ->where('year', $currentYear)
            ->value('id');
        
        $dueDonors = Donor::where('status', 'active')
            ->whereDoesntHave('transactions', function($query) use ($currentMonthId) {
                $query->where('month_id', $currentMonthId)
                      ->where('paid_status', 'paid');
            })
            ->withCount(['transactions' => function($query) {
                $query->where('paid_status', 'paid');
            }])
            ->take(5)
            ->get();
        
        $lastYear = $currentYear - 1;
        
        $yearlyComparison = [
            'current_year' => Transaction::whereYear('created_at', $currentYear)
                ->where('paid_status', 'paid')
                ->sum('amount'),
            'last_year' => Transaction::whereYear('created_at', $lastYear)
                ->where('paid_status', 'paid')
                ->sum('amount')
        ];
        
        return view('dashboard', compact(
            'donorsCount',
            'activeDonors',
            'totalDonors',
            
            'monthsCount',
            'activeMonths',
            
            'transactionsCount',
            'totalTransactionAmount',
            'totalTransactions',
            
            'donationsCount',
            'totalDonationAmount',
            
            'totalCollected',
            'pendingAmount',
            'pendingCount',
            'overdueCount',
            'collectionRate',
            
            'thisMonthTotal',
            'thisMonthPaid',
            'thisMonthUnpaid',
            'monthlyCollected',
            'currentMonthCollection',
            'currentMonthTransactions',
            
            'monthlyCollection',
            'paymentMethods',
            'topDonors',
            'recentTransactions',
            'dueDonors',
            'yearlyComparison',
            
            'currentMonth',
            'currentYear'
        ));
    }

   
    public function getChartData(Request $request)
    {
        $year = $request->get('year', now()->year);
        
        $data = Transaction::join('months', 'transactions.month_id', '=', 'months.id')
            ->where('months.year', $year)
            ->where('paid_status', 'paid')
            ->select(
                'months.name as month',
                DB::raw('SUM(transactions.amount) as total')
            )
            ->groupBy('months.name', 'months.id')
            ->orderBy('months.id')
            ->get();
        
        return response()->json($data);
    }

   
    public function exportReport(Request $request)
    {
        
        return back()->with('info', 'Export feature coming soon!');
    }
}