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
        'name',
        'phone',
        'amount',
        'paid_status',
        'payment_method',
        'notes',
        'user_id'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relationships
    public function donor()
    {
        return $this->belongsTo(Donor::class);
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
}