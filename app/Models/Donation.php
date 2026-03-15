<?php
// app/Models/Donation.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Donation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'donor_id',
        'contributor_id',
        'amount',
        'paid_status',
        'payment_method',
        'notes',
        'user_id'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    // Relationships
    public function donor()
    {
        return $this->belongsTo(Donor::class);
    }

    public function contributor()
    {
        return $this->belongsTo(Contributor::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopePaid($query)
    {
        return $query->where('paid_status', 'paid');
    }

    public function scopeUnpaid($query)
    {
        return $query->where('paid_status', 'unpaid');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('created_at', now()->month)
                     ->whereYear('created_at', now()->year);
    }

    public function scopeFromMonthlyDonors($query)
    {
        return $query->whereNotNull('donor_id');
    }

    public function scopeFromRandomDonors($query)
    {
        return $query->whereNotNull('contributor_id');
    }

    // Accessors
    public function getFormattedAmountAttribute()
    {
        return '৳' . number_format($this->amount, 2);
    }

    public function getStatusBadgeAttribute()
    {
        return $this->paid_status == 'paid' 
            ? '<span class="badge bg-success bg-opacity-10 text-success">Paid</span>'
            : '<span class="badge bg-danger bg-opacity-10 text-danger">Unpaid</span>';
    }

    public function getDonorNameAttribute()
    {
        if ($this->donor) {
            return $this->donor->name;
        }
        if ($this->contributor) {
            return $this->contributor->name;
        }
        return 'Unknown';
    }

    public function getDonorPhoneAttribute()
    {
        if ($this->donor) {
            return $this->donor->phone;
        }
        if ($this->contributor) {
            return $this->contributor->phone;
        }
        return null;
    }

    public function getDonorTypeAttribute()
    {
        if ($this->donor) {
            return 'monthly';
        }
        if ($this->contributor) {
            return 'random';
        }
        return 'unknown';
    }

    public function getDonorTypeBadgeAttribute()
    {
        if ($this->donor) {
            return '<span class="badge bg-primary bg-opacity-10 text-primary">
                        <i class="fas fa-calendar-check me-1"></i> Monthly
                    </span>';
        }
        if ($this->contributor) {
            return '<span class="badge bg-info bg-opacity-10 text-info">
                        <i class="fas fa-random me-1"></i> Random
                    </span>';
        }
        return '<span class="badge bg-secondary bg-opacity-10 text-secondary">Unknown</span>';
    }

    public function getPaymentMethodBadgeAttribute()
    {
        $icons = [
            'cash' => 'fas fa-money-bill-wave',
            'bkash' => 'fas fa-mobile-alt',
            'nagad' => 'fas fa-mobile-alt'
        ];
        
        $icon = $icons[$this->payment_method] ?? 'fas fa-credit-card';
        
        return '<span class="badge bg-light text-dark">
                    <i class="' . $icon . ' me-1"></i> ' . ucfirst($this->payment_method) . '
                </span>';
    }

    // Boot method to handle contributor stats updates
    protected static function booted()
    {
        static::created(function ($donation) {
            if ($donation->contributor_id) {
                $contributor = Contributor::find($donation->contributor_id);
                if ($contributor) {
                    $contributor->updateStats($donation->amount);
                }
            }
        });

        static::updated(function ($donation) {
            if ($donation->contributor_id) {
                $contributor = Contributor::find($donation->contributor_id);
                if ($contributor) {
                    // Recalculate total amount for this contributor
                    $totalAmount = Donation::where('contributor_id', $contributor->id)
                        ->sum('amount');
                    $donationCount = Donation::where('contributor_id', $contributor->id)
                        ->count();
                    $lastDonation = Donation::where('contributor_id', $contributor->id)
                        ->latest()
                        ->first();

                    $contributor->amount = $totalAmount;
                    $contributor->donation_count = $donationCount;
                    $contributor->last_donation_at = $lastDonation?->created_at;
                    $contributor->save();
                }
            }
        });

        static::deleted(function ($donation) {
            if ($donation->contributor_id) {
                $contributor = Contributor::find($donation->contributor_id);
                if ($contributor) {
                    // Recalculate total amount for this contributor
                    $totalAmount = Donation::where('contributor_id', $contributor->id)
                        ->sum('amount');
                    $donationCount = Donation::where('contributor_id', $contributor->id)
                        ->count();
                    $lastDonation = Donation::where('contributor_id', $contributor->id)
                        ->latest()
                        ->first();

                    $contributor->amount = $totalAmount;
                    $contributor->donation_count = $donationCount;
                    $contributor->last_donation_at = $lastDonation?->created_at;
                    $contributor->save();
                }
            }
        });
    }
}