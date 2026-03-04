<?php
// app/Models/Transaction.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'donor_id',
        'month_id',
        'amount',
        'paid_status',
        'user_id',
        'payment_method'
    ];

    protected $casts = [
        'amount' => 'decimal:2'
    ];

    public function donor()
    {
        return $this->belongsTo(Donor::class);
    }

    public function month()
    {
        return $this->belongsTo(Month::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}