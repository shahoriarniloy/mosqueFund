<?php
// app/Models/Month.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Month extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'status',
        'year'
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}