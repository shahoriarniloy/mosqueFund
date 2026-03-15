<?php
// app/Services/RegistrationLogService.php

namespace App\Services;

use App\Models\RegistrationLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stevebauman\Location\Facades\Location;

class RegistrationLogService
{
    /**
     * Log successful registration
     */
    public function logSuccess(Request $request, User $user)
    {
        $location = $this->getLocation($request->ip());
        
        return RegistrationLog::create([
            'user_id' => $user->id,
            'created_by' => Auth::id(),
            'name' => $user->name,
            'phone' => $user->phone,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'browser' => $this->detectBrowser($request->userAgent()),
            'platform' => $this->detectPlatform($request->userAgent()),
            'device' => $this->detectDevice($request->userAgent()),
            'location' => $location,
            'is_successful' => true,
            'error_message' => null,
        ]);
    }

    /**
     * Get registration statistics
     */
    public function getStats()
    {
        return [
            'total' => RegistrationLog::count(),
            'successful' => RegistrationLog::count(), // All are successful now
            'failed' => 0,
            'today' => RegistrationLog::whereDate('created_at', today())->count(),
            'this_month' => RegistrationLog::whereMonth('created_at', now()->month)
                                ->whereYear('created_at', now()->year)->count(),
            'success_rate' => 100,
        ];
    }

    /**
     * Detect browser from user agent
     */
    private function detectBrowser($userAgent)
    {
        $browsers = [
            'Chrome' => 'Chrome',
            'Firefox' => 'Firefox',
            'Safari' => 'Safari',
            'Edge' => 'Edge',
            'Opera' => 'Opera',
            'MSIE' => 'Internet Explorer',
            'Trident' => 'Internet Explorer'
        ];

        foreach ($browsers as $key => $value) {
            if (stripos($userAgent, $key) !== false) {
                return $value;
            }
        }

        return 'Unknown';
    }

    /**
     * Detect platform from user agent
     */
    private function detectPlatform($userAgent)
    {
        $platforms = [
            'Windows' => 'Windows',
            'Macintosh' => 'Mac',
            'Mac OS' => 'Mac',
            'iOS' => 'iOS',
            'Android' => 'Android',
            'Linux' => 'Linux'
        ];

        foreach ($platforms as $key => $value) {
            if (stripos($userAgent, $key) !== false) {
                return $value;
            }
        }

        return 'Unknown';
    }

    /**
     * Detect device type from user agent
     */
    private function detectDevice($userAgent)
    {
        if (preg_match('/(tablet|ipad|playbook)|(android(?!.*mobile))/i', $userAgent)) {
            return 'Tablet';
        }
        
        if (preg_match('/(mobile|iphone|ipod|android|blackberry|opera mini|iemobile)/i', $userAgent)) {
            return 'Mobile';
        }
        
        return 'Desktop';
    }

    /**
     * Get location from IP
     */
    private function getLocation($ip)
    {
        if ($ip == '127.0.0.1' || $ip == '::1' || str_starts_with($ip, '192.168.')) {
            return 'Local';
        }

        try {
            if (class_exists('Stevebauman\Location\Facades\Location')) {
                $position = Location::get($ip);
                if ($position) {
                    return $position->cityName . ', ' . $position->countryName;
                }
            }
        } catch (\Exception $e) {
            \Log::warning('Location detection failed: ' . $e->getMessage());
        }

        return 'Unknown';
    }
}