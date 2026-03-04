<?php

namespace App\Helpers;

use App\Models\Transaction;
use App\Models\Donation;
use App\Services\SMSService;
use Illuminate\Support\Facades\Log;

class NotificationHelper
{
    /**
     * Send transaction creation SMS to donor
     */
    public static function sendTransactionSMS(Transaction $transaction)
    {
        $smsService = new SMSService();
        
        // Get donor phone number
        $donor = $transaction->donor;
        
        if (!$donor || !$donor->phone) {
            Log::warning('No phone number found for donor', [
                'transaction_id' => $transaction->id,
                'donor_id' => $donor->id ?? null
            ]);
            return [
                'success' => false,
                'message' => 'No phone number found for donor'
            ];
        }

        // Prepare SMS message
        $monthName = $transaction->month->name ?? 'Unknown';
        $year = $transaction->month->year ?? date('Y');
        $amount = number_format($transaction->amount, 2);
        
        // Message format
$message = "মসজিদ ফান্ডে {$monthName} {$year} মাসের জন্য আপনার ৳{$amount} টাকার দান সফলভাবে রেকর্ড করা হয়েছে। আপনার অবদানের জন্য জাযাকাল্লাহু খাইরান।";
        // Send SMS
        $result = $smsService->sendSMS($donor->phone, $message);
        
        if ($result['success']) {
            Log::info('Transaction SMS sent successfully', [
                'transaction_id' => $transaction->id,
                'donor_id' => $donor->id,
                'phone' => $donor->phone
            ]);
        } else {
            Log::error('Failed to send transaction SMS', [
                'transaction_id' => $transaction->id,
                'donor_id' => $donor->id,
                'phone' => $donor->phone,
                'error' => $result['message']
            ]);
        }
        
        return $result;
    }

    /**
     * Send donation SMS to donor/contributor
     */
    public static function sendDonationSMS(Donation $donation)
    {
        $smsService = new SMSService();
        
        // Get phone number from donation
        $phone = $donation->phone;
        
        if (!$phone) {
            Log::warning('No phone number found for donation', [
                'donation_id' => $donation->id
            ]);
            return [
                'success' => false,
                'message' => 'No phone number found for this donation'
            ];
        }

        // Prepare SMS message
        $amount = number_format($donation->amount, 2);
        $donorName = $donation->name ?? 'Donor';
        $date = $donation->created_at->format('d M Y');
        
        // Check if donor is existing donor
        $donorType = $donation->donor_id ? 'registered donor' : 'contributor';
        
       
        // Bengali version (optional)
        $message = "প্রিয় {$donorName}, মসজিদ ফান্ডে {$date} তারিখে ৳{$amount} টাকার দানের জন্য আপনাকে ধন্যবাদ। আপনার সমর্থন অত্যন্ত প্রশংসনীয়। জাযাকাল্লাহু খাইরান।";
        
        // Send SMS
        $result = $smsService->sendSMS($phone, $message);
        
        if ($result['success']) {
            Log::info('Donation SMS sent successfully', [
                'donation_id' => $donation->id,
                'phone' => $phone,
                'donor_type' => $donorType
            ]);
        } else {
            Log::error('Failed to send donation SMS', [
                'donation_id' => $donation->id,
                'phone' => $phone,
                'error' => $result['message']
            ]);
        }
        
        return $result;
    }

    

    /**
     * Send OTP message
     */
    public static function sendOTP($phone, $otp, $brandName = 'MosqueFund')
    {
        $smsService = new SMSService();
        
        // Format: Your {Brand/Company Name} OTP is XXXX
        $message = "Your {$brandName} OTP is {$otp}";
        
        return $smsService->sendSMS($phone, $message);
    }

    /**
     * Send due reminder SMS
     */
    
    public static function sendDueReminder($donor, $dueAmount, $dueMonths)
{
    $smsService = new \App\Services\SMSService();
    
    if (!$donor->phone) {
        return [
            'success' => false,
            'message' => 'No phone number found'
        ];
    }

    // Bangla message
    $message = "জনাব {$donor->name}, মসজিদ ফান্ডে আপনার {$dueMonths} মাসের বকেয়া ৳" . number_format($dueAmount) . " টাকা রয়েছে। অনুগ্রহ করে দ্রুত পরিশোধ করুন। জাযাকাল্লাহু খাইরান।";
    
    // English alternative
    // $message = "Dear {$donor->name}, you have {$dueMonths} month(s) pending payment of ৳" . number_format($dueAmount) . " at MosqueFund. Please clear your dues. Jazakallah Khair.";
    
    return $smsService->sendSMS($donor->phone, $message);
}
}