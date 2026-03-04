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
        
        // Get all months up to current month
        $allMonths = Month::where(function($query) use ($currentYear, $currentMonth) {
                // Months from previous years
                $query->where('year', '<', $currentYear)
                    // OR months from current year up to current month
                    ->orWhere(function($q) use ($currentYear, $currentMonth) {
                        $q->where('year', $currentYear)
                          ->whereRaw("FIELD(name, 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December') <= ?", [$currentMonth]);
                    });
            })
            ->orderBy('year', 'desc')
            ->orderByRaw("FIELD(name, 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December')")
            ->get();
        
        $dueData = [];
        $totalOverallDue = 0;
        $totalDonorsWithDue = 0;
        
        foreach ($donors as $donor) {
            // Get paid months for this donor
            $paidMonthIds = Transaction::where('donor_id', $donor->id)
                ->where('paid_status', 'paid')
                ->pluck('month_id')
                ->toArray();
            
            // Calculate due months and amount
            $donorDueMonths = [];
            $donorDueAmount = 0;
            
            foreach ($allMonths as $month) {
                if (!in_array($month->id, $paidMonthIds)) {
                    $dueDate = \Carbon\Carbon::create($month->year, $this->getMonthNumber($month->name), 1);
                    $daysOverdue = $dueDate->isPast() ? $dueDate->diffInDays(now()) : 0;
                    
                    $donorDueMonths[] = [
                        'month' => $month,
                        'amount' => $donor->monthly_amount,
                        'due_date' => $dueDate,
                        'days_overdue' => $daysOverdue
                    ];
                    
                    $donorDueAmount += $donor->monthly_amount;
                }
            }
            
            // Get unpaid transactions from database (only for months up to current)
            $unpaidTransactions = Transaction::where('donor_id', $donor->id)
                ->where('paid_status', 'unpaid')
                ->whereHas('month', function($query) use ($currentYear, $currentMonth) {
                    $query->where(function($q) use ($currentYear, $currentMonth) {
                        $q->where('year', '<', $currentYear)
                          ->orWhere(function($sub) use ($currentYear, $currentMonth) {
                              $sub->where('year', $currentYear)
                                  ->whereRaw("FIELD(name, 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December') <= ?", [$currentMonth]);
                          });
                    });
                })
                ->with('month')
                ->get();
            
            foreach ($unpaidTransactions as $transaction) {
                // Check if this month is already counted
                $monthExists = false;
                foreach ($donorDueMonths as $dueMonth) {
                    if ($dueMonth['month']->id == $transaction->month_id) {
                        $monthExists = true;
                        break;
                    }
                }
                
                if (!$monthExists) {
                    $dueDate = \Carbon\Carbon::create($transaction->month->year, $this->getMonthNumber($transaction->month->name), 1);
                    $daysOverdue = $dueDate->isPast() ? $dueDate->diffInDays(now()) : 0;
                    
                    $donorDueMonths[] = [
                        'month' => $transaction->month,
                        'amount' => $transaction->amount,
                        'due_date' => $dueDate,
                        'days_overdue' => $daysOverdue
                    ];
                    
                    $donorDueAmount += $transaction->amount;
                }
            }
            
            // Sort due months by date (oldest first for overdue priority)
            usort($donorDueMonths, function($a, $b) {
                return $a['due_date']->timestamp - $b['due_date']->timestamp;
            });
            
            if ($donorDueAmount > 0) {
                $dueData[] = [
                    'donor' => $donor,
                    'due_months' => $donorDueMonths,
                    'total_due' => $donorDueAmount,
                    'due_count' => count($donorDueMonths),
                    'oldest_due' => $donorDueMonths[0]['due_date'] ?? null,
                    'max_days_overdue' => max(array_column($donorDueMonths, 'days_overdue'))
                ];
                
                $totalOverallDue += $donorDueAmount;
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

        // Calculate due amount for this donor
        $dueAmount = $this->calculateDonorDue($donor);
        
        if ($dueAmount['total'] <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'This donor has no due payments'
            ]);
        }

        // Send SMS using NotificationHelper
        $result = NotificationHelper::sendDueReminder(
            $donor, 
            $dueAmount['total'], 
            $dueAmount['months']
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
            
            $dueAmount = $this->calculateDonorDue($donor);
            
            if ($dueAmount['total'] <= 0) continue;
            
            $message = "জনাব {$donor->name}, মসজিদ ফান্ডে আপনার {$dueAmount['months']} মাসের বকেয়া ৳" . number_format($dueAmount['total']) . " টাকা রয়েছে। অনুগ্রহ করে দ্রুত পরিশোধ করুন। জাযাকাল্লাহু খাইরান।";
            
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

    /**
     * Calculate due amount for a specific donor
     */
    private function calculateDonorDue($donor)
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;
        
        // Get all months up to current month
        $allMonths = Month::where(function($query) use ($currentYear, $currentMonth) {
                $query->where('year', '<', $currentYear)
                    ->orWhere(function($q) use ($currentYear, $currentMonth) {
                        $q->where('year', $currentYear)
                          ->whereRaw("FIELD(name, 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December') <= ?", [$currentMonth]);
                    });
            })
            ->get();
        
        // Get paid months
        $paidMonthIds = Transaction::where('donor_id', $donor->id)
            ->where('paid_status', 'paid')
            ->pluck('month_id')
            ->toArray();
        
        $dueMonths = 0;
        $dueAmount = 0;
        
        foreach ($allMonths as $month) {
            if (!in_array($month->id, $paidMonthIds)) {
                $dueMonths++;
                $dueAmount += $donor->monthly_amount;
            }
        }
        
        return [
            'months' => $dueMonths,
            'total' => $dueAmount
        ];
    }
}