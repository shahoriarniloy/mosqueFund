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
        'status'
    ];

    protected $casts = [
        'monthly_amount' => 'decimal:2'
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}