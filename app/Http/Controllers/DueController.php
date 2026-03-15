<?php

namespace App\Http\Controllers;

use App\Helpers\NotificationHelper;
use App\Models\Donor;
use App\Models\Transaction;
use App\Models\Month;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class DueController extends Controller implements HasMiddleware
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
     * Display due payments for all donors.
     */
    public function index(Request $request)
    {
        // Get all active donors
        $donors = Donor::where('status', 'active')
            ->orderBy('name')
            ->get();
        
        // Get current month and year
        $currentMonth = now()->month;
        $currentYear = now()->year;
        $currentMonthName = now()->format('F');
        
        $dueData = [];
        $totalOverallDue = 0;
        $totalDonorsWithDue = 0;
        
        foreach ($donors as $donor) {
            // Calculate due from donor's creation date to current date
            $donorDueData = $this->calculateDonorDueFromCreation($donor);
            
            if ($donorDueData['total_due'] > 0) {
                $dueData[] = [
                    'donor' => $donor,
                    'due_months' => $donorDueData['due_months'],
                    'total_due' => $donorDueData['total_due'],
                    'due_count' => $donorDueData['due_count'],
                    'oldest_due' => $donorDueData['oldest_due'],
                    'max_days_overdue' => $donorDueData['max_days_overdue']
                ];
                
                $totalOverallDue += $donorDueData['total_due'];
                $totalDonorsWithDue++;
            }
        }
        
        // Sort donors by oldest due date (most urgent first)
        usort($dueData, function($a, $b) {
            if (!$a['oldest_due']) return 1;
            if (!$b['oldest_due']) return -1;
            return $a['oldest_due']->timestamp - $b['oldest_due']->timestamp;
        });
        
        // Summary statistics
        $summary = [
            'total_donors' => $donors->count(),
            'donors_with_due' => $totalDonorsWithDue,
            'total_due_amount' => $totalOverallDue,
            'average_due_per_donor' => $totalDonorsWithDue > 0 ? $totalOverallDue / $totalDonorsWithDue : 0,
            'current_month' => $currentMonthName,
            'current_year' => $currentYear
        ];
        
        return view('due.index', compact('dueData', 'summary'));
    }
    
    /**
     * Calculate due amount for a donor from their creation date to current date
     */
    private function calculateDonorDueFromCreation($donor)
    {
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
        
        // Sort due months by date (oldest first)
        usort($dueMonths, function($a, $b) {
            return $a['due_date']->timestamp - $b['due_date']->timestamp;
        });
        
        return [
            'due_months' => $dueMonths,
            'total_due' => $totalDue,
            'due_count' => count($dueMonths),
            'oldest_due' => !empty($dueMonths) ? $dueMonths[0]['due_date'] : null,
            'max_days_overdue' => !empty($dueMonths) ? max(array_column($dueMonths, 'days_overdue')) : 0
        ];
    }
    
    /**
     * Get all months between two dates
     */
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
    
    /**
     * Helper function to get month number from name
     */
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
     * Export due report
     */
    public function export(Request $request)
    {
        // Implement export logic (PDF/Excel) here
        return back()->with('info', 'Export feature coming soon!');
    }

    /**
     * Send reminder to a single donor
     */
    public function sendReminder(Request $request)
    {
        $request->validate([
            'donor_id' => 'required|exists:donors,id'
        ]);

        $donor = Donor::find($request->donor_id);
        
        if (!$donor->phone) {
            return response()->json([
                'success' => false,
                'message' => 'Donor has no phone number'
            ]);
        }

        // Calculate due amount for this donor from creation date
        $dueData = $this->calculateDonorDueFromCreation($donor);
        
        if ($dueData['total_due'] <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'This donor has no due payments'
            ]);
        }

        // Send SMS using NotificationHelper
        $result = NotificationHelper::sendDueReminder(
            $donor, 
            $dueData['total_due'], 
            $dueData['due_count']
        );

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'message' => "Due reminder sent to {$donor->name} successfully"
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send SMS: ' . $result['message']
            ]);
        }
    }

    /**
     * Send bulk due reminders to all donors with due payments
     */
    public function sendBulkReminder(Request $request)
    {
        $donors = Donor::where('status', 'active')->get();
        $smsService = new \App\Services\SMSService();
        
        $successCount = 0;
        $failCount = 0;
        $results = [];

        foreach ($donors as $donor) {
            if (!$donor->phone) continue;
            
            $dueData = $this->calculateDonorDueFromCreation($donor);
            
            if ($dueData['total_due'] <= 0) continue;
            
            $message = "জনাব {$donor->name}, মসজিদ ফান্ডে আপনার {$dueData['due_count']} মাসের বকেয়া ৳" . number_format($dueData['total_due']) . " টাকা রয়েছে। অনুগ্রহ করে দ্রুত পরিশোধ করুন। জাযাকাল্লাহু খাইরান।";
            
            $result = $smsService->sendSMS($donor->phone, $message);
            
            if ($result['success']) {
                $successCount++;
                $results[] = [
                    'donor' => $donor->name,
                    'phone' => $donor->phone,
                    'status' => 'success'
                ];
            } else {
                $failCount++;
                $results[] = [
                    'donor' => $donor->name,
                    'phone' => $donor->phone,
                    'status' => 'failed',
                    'error' => $result['message']
                ];
            }
            
            // Small delay to avoid rate limiting
            usleep(500000); // 0.5 seconds
        }

        return redirect()->route('due.index')->with('info', 
            "Bulk reminders sent: {$successCount} successful, {$failCount} failed."
        );
    }
}