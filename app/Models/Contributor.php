<?php
// app/Models/Contributor.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contributor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'amount',
        'donation_count',
        'last_donation_at'
    ];

    protected $casts = [
        'total_amount' => 'integer',
        'donation_count' => 'integer',
        'last_donation_at' => 'datetime'
    ];

    /**
     * Update contributor stats after a donation
     */
    public function updateStats($donationAmount)
    {
        $this->amount += $donationAmount;
        $this->donation_count += 1;
        $this->last_donation_at = now();
        $this->save();
        
        return $this;
    }

    /**
     * Find contributor by phone with flexible matching
     * This is a static method, not a scope
     */
    public static function findByPhone($phone)
    {
        if (empty($phone)) {
            return null;
        }
        
        // Clean phone number for matching
        $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
        
        // Get all contributors
        $contributors = self::all();
        
        foreach ($contributors as $contributor) {
            $cleanContributor = preg_replace('/[^0-9]/', '', $contributor->phone);
            
            // Exact match after cleaning
            if ($cleanContributor === $cleanPhone) {
                return $contributor;
            }
            
            // Match last 11 digits (for numbers with +88, 01, etc.)
            if (strlen($cleanContributor) >= 11 && strlen($cleanPhone) >= 11) {
                if (substr($cleanContributor, -11) === substr($cleanPhone, -11)) {
                    return $contributor;
                }
            }
            
            // Match last 10 digits (without leading 0/1)
            if (strlen($cleanContributor) >= 10 && strlen($cleanPhone) >= 10) {
                if (substr($cleanContributor, -10) === substr($cleanPhone, -10)) {
                    return $contributor;
                }
            }
        }
        
        return null;
    }
    
    /**
     * Alternative: Use a scope that returns a query builder
     * This is useful if you want to chain other conditions
     */
    public function scopeWherePhoneMatches($query, $phone)
    {
        if (empty($phone)) {
            return $query;
        }
        
        $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
        
        // This is more complex with raw SQL, so we'll keep the static method for now
        return $query;
    }
}