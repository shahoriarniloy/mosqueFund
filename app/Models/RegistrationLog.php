<?php
// app/Models/RegistrationLog.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistrationLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'created_by', // Add this field
        'name',
        'phone',
        'ip_address',
        'user_agent',
        'browser',
        'platform',
        'device',
        'location',
        'is_successful',
        'error_message'
    ];

    protected $casts = [
        'is_successful' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Get the user that was registered (the new user).
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the admin/staff who created this registration.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the name of the creator (admin/staff).
     */
    public function getCreatorNameAttribute()
    {
        return $this->creator ? $this->creator->name : 'System/Auto';
    }

    /**
     * Get the name of the registered user.
     */
    public function getRegisteredUserNameAttribute()
    {
        return $this->user ? $this->user->name : $this->name;
    }

    /**
     * Scope for successful registrations
     */
    public function scopeSuccessful($query)
    {
        return $query->where('is_successful', true);
    }

    /**
     * Scope for failed registrations
     */
    public function scopeFailed($query)
    {
        return $query->where('is_successful', false);
    }

    /**
     * Scope for today's registrations
     */
    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    /**
     * Scope for this month's registrations
     */
    public function scopeThisMonth($query)
    {
        return $query->whereMonth('created_at', now()->month)
                     ->whereYear('created_at', now()->year);
    }

    /**
     * Scope for registrations created by a specific admin
     */
    public function scopeCreatedBy($query, $userId)
    {
        return $query->where('created_by', $userId);
    }

    /**
     * Get browser family
     */
    public function getBrowserFamilyAttribute()
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
            if (stripos($this->user_agent, $key) !== false) {
                return $value;
            }
        }

        return 'Unknown';
    }

    /**
     * Get platform family
     */
    public function getPlatformFamilyAttribute()
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
            if (stripos($this->user_agent, $key) !== false) {
                return $value;
            }
        }

        return 'Unknown';
    }
}