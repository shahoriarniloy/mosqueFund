<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SMSService
{
    protected $apiKey;
    protected $senderId;
    protected $baseUrl;
    protected $balanceUrl;

    public function __construct()
    {
        // Load from config instead of hardcoding
        $this->apiKey = config('services.sms.api_key');
        $this->senderId = config('services.sms.sender_id');
        $this->baseUrl = config('services.sms.api_url');
        $this->balanceUrl = config('services.sms.balance_url');
        
        // Validate configuration
        if (empty($this->apiKey)) {
            Log::error('SMS API Key is not configured');
        }
        
        if (empty($this->senderId)) {
            Log::error('SMS Sender ID is not configured');
        }
    }

    /**
     * Send a single SMS
     */
    public function sendSMS($phoneNumber, $message)
    {
        // Format phone number (ensure it starts with 88)
        $formattedNumber = $this->formatPhoneNumber($phoneNumber);
        
        if (!$formattedNumber) {
            Log::error('Invalid phone number format', ['phone' => $phoneNumber]);
            return [
                'success' => false,
                'message' => 'Invalid phone number format',
                'code' => 1001
            ];
        }

        // Check if API key is configured
        if (empty($this->apiKey)) {
            Log::error('SMS API Key not configured');
            return [
                'success' => false,
                'message' => 'SMS service not configured',
                'code' => 1003
            ];
        }

        try {
            $response = Http::get($this->baseUrl, [
                'api_key' => $this->apiKey,
                'type' => 'text',
                'number' => $formattedNumber,
                'senderid' => $this->senderId,
                'message' => $message
            ]);

            $result = $response->json();
            
            // Log the response for debugging
            Log::info('SMS Response', ['response' => $result]);

            // Check if SMS was successful (code 202)
            if (isset($result['response_code']) && $result['response_code'] == 202) {
                return [
                    'success' => true,
                    'message' => 'SMS sent successfully',
                    'code' => 202
                ];
            }

            // Get error message
            $errorMessage = $this->getErrorMessage($result['response_code'] ?? 'unknown');
            
            // Log error if not successful
            Log::error('SMS sending failed', [
                'code' => $result['response_code'] ?? 'unknown',
                'message' => $errorMessage,
                'phone' => $formattedNumber
            ]);

            return [
                'success' => false,
                'message' => $errorMessage,
                'code' => $result['response_code'] ?? 'unknown'
            ];

        } catch (\Exception $e) {
            Log::error('SMS Exception', [
                'error' => $e->getMessage(),
                'phone' => $formattedNumber
            ]);
            
            return [
                'success' => false,
                'message' => 'SMS service error: ' . $e->getMessage(),
                'code' => 1005
            ];
        }
    }

    /**
     * Format phone number to Bangladesh format (88XXXXXXXXXX)
     */
    private function formatPhoneNumber($phone)
    {
        if (empty($phone)) {
            return null;
        }
        
        // Remove all non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        // Check if it's a valid Bangladesh number
        if (strlen($phone) == 11 && substr($phone, 0, 2) == '01') {
            return '88' . $phone;
        } elseif (strlen($phone) == 13 && substr($phone, 0, 3) == '880') {
            return $phone;
        } elseif (strlen($phone) == 14 && substr($phone, 0, 4) == '8801') {
            return $phone;
        } elseif (strlen($phone) == 10 && substr($phone, 0, 1) == '1') {
            return '88' . $phone;
        }
        
        return null; // Invalid format
    }

    /**
     * Check SMS balance
     */
    public function checkBalance()
    {
        if (empty($this->apiKey)) {
            Log::error('SMS API Key not configured');
            return false;
        }

        try {
            $response = Http::get($this->balanceUrl, [
                'api_key' => $this->apiKey
            ]);

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Balance check failed', ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Get error message from response code
     */
    public function getErrorMessage($code)
    {
        $messages = [
            '202' => 'SMS Submitted Successfully',
            '1001' => 'Invalid Number',
            '1002' => 'Sender ID not correct/disabled',
            '1003' => 'Please Required all fields/Contact Administrator',
            '1005' => 'Internal Error',
            '1006' => 'Balance Validity Not Available',
            '1007' => 'Balance Insufficient',
            '1011' => 'User Id not found',
            '1012' => 'Masking SMS must be sent in Bengali',
            '1013' => 'Sender Id Gateway not found',
            '1014' => 'Sender Type Name not found',
            '1015' => 'Sender Id Gateway not found',
            '1016' => 'Sender Type Price not found',
            '1017' => 'Sender Type Price not found',
            '1018' => 'Account is disabled',
            '1019' => 'Price is disabled',
            '1020' => 'Parent account not found',
            '1021' => 'Parent price not found',
            '1031' => 'Account Not Verified',
            '1032' => 'IP not whitelisted'
        ];

        return $messages[$code] ?? 'Unknown error (Code: ' . $code . ')';
    }
    
    /**
     * Get current configuration status (for debugging)
     */
    public function getConfigStatus()
    {
        return [
            'api_key_configured' => !empty($this->apiKey),
            'sender_id_configured' => !empty($this->senderId),
            'api_url' => $this->baseUrl,
            'balance_url' => $this->balanceUrl,
        ];
    }
}