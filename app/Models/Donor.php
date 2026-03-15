<?php
// app/Models/Donor.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'address',
        'monthly_amount',
        'status',
        'created_by'  // Added created_by to fillable
    ];

    protected $casts = [
        'monthly_amount' => 'int'
    ];

    /**
     * Get the transactions for this donor.
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Get the user who created this donor record.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}