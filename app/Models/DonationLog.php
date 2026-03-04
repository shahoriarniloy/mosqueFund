<?php
// app/Models/DonationLog.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonationLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'donation_id',
        'user_id',
        'field_name',
        'old_value',
        'new_value',
        'donation_snapshot',
        'action',
        'ip_address',
        'user_agent'
    ];

    protected $casts = [
        'donation_snapshot' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Get the donation that owns the log.
     */
    public function donation()
    {
        return $this->belongsTo(Donation::class);
    }

    /**
     * Get the user that made the change.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get formatted old value
     */
    public function getFormattedOldValueAttribute()
    {
        return $this->formatValue($this->old_value);
    }

    /**
     * Get formatted new value
     */
    public function getFormattedNewValueAttribute()
    {
        return $this->formatValue($this->new_value);
    }

    /**
     * Format value based on field type
     */
    private function formatValue($value)
    {
        if ($this->field_name === 'amount' && $value) {
            return '৳' . number_format($value, 2);
        }
        
        if ($this->field_name === 'paid_status') {
            return ucfirst($value);
        }
        
        if ($this->field_name === 'payment_method') {
            return ucfirst($value);
        }
        
        return $value ?? '<em>Empty</em>';
    }

    /**
     * Scope for filtering by date range
     */
    public function scopeDateBetween($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Scope for filtering by action
     */
    public function scopeWithAction($query, $action)
    {
        return $query->where('action', $action);
    }
}