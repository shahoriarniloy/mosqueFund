<?php
namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\Transaction;
use App\Models\Month;
use App\Models\Donor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class AnalyticsController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth'),
        ];
    }

   
    public function index(Request $request)
    {
        $selectedMonth = $request->get('month', now()->month);
        $selectedYear = $request->get('year', now()->year);
        
        $monthNames = [
            1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
            5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
            9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
        ];
        $selectedMonthName = $monthNames[$selectedMonth];
        
        $availableYears = collect(range(2020, now()->year))->reverse();
        
        $months = Month::orderBy('year', 'desc')
            ->orderByRaw("FIELD(name, 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December')")
            ->get();
        
        $selectedMonthId = Month::where('name', $selectedMonthName)
            ->where('year', $selectedYear)
            ->value('id');
        
        // ===== DONATIONS DATA =====
       
        $donations = Donation::with(['donor', 'user'])
            ->whereYear('created_at', $selectedYear)
            ->whereMonth('created_at', $selectedMonth)
            ->orderBy('created_at', 'desc')
            ->get();
        
        $donationsSummary = [
            'total' => $donations->count(),
            'paid' => $donations->where('paid_status', 'paid')->count(),
            'unpaid' => $donations->where('paid_status', 'unpaid')->count(),
            'amount' => $donations->sum('amount'),
            'paid_amount' => $donations->where('paid_status', 'paid')->sum('amount'),
            'unpaid_amount' => $donations->where('paid_status', 'unpaid')->sum('amount'),
            'cash' => $donations->where('payment_method', 'cash')->sum('amount'),
            'bkash' => $donations->where('payment_method', 'bkash')->sum('amount'),
            'nagad' => $donations->where('payment_method', 'nagad')->sum('amount'),
        ];
        
        // ===== TRANSACTIONS DATA =====
        $transactions = Transaction::with(['donor', 'month', 'user'])
            ->where('month_id', $selectedMonthId)
            ->orderBy('created_at', 'desc')
            ->get();
        
        $transactionsSummary = [
            'total' => $transactions->count(),
            'paid' => $transactions->where('paid_status', 'paid')->count(),
            'unpaid' => $transactions->where('paid_status', 'unpaid')->count(),
            'amount' => $transactions->sum('amount'),
            'paid_amount' => $transactions->where('paid_status', 'paid')->sum('amount'),
            'unpaid_amount' => $transactions->where('paid_status', 'unpaid')->sum('amount'),
            'cash' => $transactions->where('payment_method', 'cash')->sum('amount'),
            'bkash' => $transactions->where('payment_method', 'bkash')->sum('amount'),
            'nagad' => $transactions->where('payment_method', 'nagad')->sum('amount'),
        ];
        
        // ===== COMBINED TOTALS =====
        $totalCollection = [
            'total_amount' => $donationsSummary['amount'] + $transactionsSummary['amount'],
            'paid_amount' => $donationsSummary['paid_amount'] + $transactionsSummary['paid_amount'],
            'unpaid_amount' => $donationsSummary['unpaid_amount'] + $transactionsSummary['unpaid_amount'],
            'total_count' => $donationsSummary['total'] + $transactionsSummary['total'],
            'paid_count' => $donationsSummary['paid'] + $transactionsSummary['paid'],
            'unpaid_count' => $donationsSummary['unpaid'] + $transactionsSummary['unpaid'],
            'cash' => $donationsSummary['cash'] + $transactionsSummary['cash'],
            'bkash' => $donationsSummary['bkash'] + $transactionsSummary['bkash'],
            'nagad' => $donationsSummary['nagad'] + $transactionsSummary['nagad'],
        ];
        
        // ===== PAYMENT METHODS BREAKDOWN =====
        $paymentBreakdown = [
            'cash' => $totalCollection['cash'],
            'bkash' => $totalCollection['bkash'],
            'nagad' => $totalCollection['nagad'],
        ];
        
        // ===== TOP DONORS THIS MONTH =====
        $topDonors = Transaction::where('month_id', $selectedMonthId)
            ->where('paid_status', 'paid')
            ->with('donor')
            ->select('donor_id', DB::raw('SUM(amount) as total'))
            ->groupBy('donor_id')
            ->orderBy('total', 'desc')
            ->take(5)
            ->get();
        
        // ===== DAILY BREAKDOWN =====
        $dailyDonations = Donation::whereYear('created_at', $selectedYear)
            ->whereMonth('created_at', $selectedMonth)
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(amount) as total'), DB::raw('COUNT(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        $dailyTransactions = Transaction::where('month_id', $selectedMonthId)
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(amount) as total'), DB::raw('COUNT(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        // Merge daily data
        $dailyData = [];
        foreach ($dailyDonations as $donation) {
            $dailyData[$donation->date] = [
                'date' => $donation->date,
                'donations' => $donation->total,
                'donations_count' => $donation->count,
                'transactions' => 0,
                'transactions_count' => 0,
                'total' => $donation->total,
            ];
        }
        
        foreach ($dailyTransactions as $transaction) {
            if (isset($dailyData[$transaction->date])) {
                $dailyData[$transaction->date]['transactions'] = $transaction->total;
                $dailyData[$transaction->date]['transactions_count'] = $transaction->count;
                $dailyData[$transaction->date]['total'] += $transaction->total;
            } else {
                $dailyData[$transaction->date] = [
                    'date' => $transaction->date,
                    'donations' => 0,
                    'donations_count' => 0,
                    'transactions' => $transaction->total,
                    'transactions_count' => $transaction->count,
                    'total' => $transaction->total,
                ];
            }
        }
        
        ksort($dailyData);
        
        return view('analytics.index', compact(
            'selectedMonth',
            'selectedYear',
            'selectedMonthName',
            'availableYears',
            'months',
            'monthNames',
            'donations',
            'donationsSummary',
            'transactions',
            'transactionsSummary',
            'totalCollection',
            'paymentBreakdown',
            'topDonors',
            'dailyData'
        ));
    }

    public function monthlyComparison(Request $request)
    {
        $selectedYear = $request->get('year', now()->year);
        $availableYears = collect(range(2020, now()->year))->reverse();
        
        return view('analytics.monthlycomparison', compact('selectedYear', 'availableYears'));
    }
    
    /**
     * Get monthly comparison data for charts
     */
    public function getMonthlyComparison(Request $request)
    {
        $year = $request->get('year', now()->year);
        
        $monthlyData = [];
        $monthNames = [
            1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
            5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
            9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
        ];
        
        for ($i = 1; $i <= 12; $i++) {
            // Get month_id for this month and year
            $monthId = Month::where('name', $monthNames[$i])
                ->where('year', $year)
                ->value('id');
            
            // Donations still use created_at (unless you add month_id to donations table)
            $donationsAmount = Donation::whereYear('created_at', $year)
                ->whereMonth('created_at', $i)
                ->sum('amount');
            
            // Transactions use month_id
            $transactionsAmount = 0;
            if ($monthId) {
                $transactionsAmount = Transaction::where('month_id', $monthId)
                    ->sum('amount');
            }
                
            $monthlyData[] = [
                'month' => $monthNames[$i],
                'donations' => $donationsAmount,
                'transactions' => $transactionsAmount,
                'total' => $donationsAmount + $transactionsAmount,
            ];
        }
        
        return response()->json($monthlyData);
    }
    
    /**
     * Export monthly report
     */
    public function export(Request $request)
    {
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);
        
        // Implement export logic (PDF/Excel) here
        return back()->with('info', 'Export feature coming soon!');
    }
}