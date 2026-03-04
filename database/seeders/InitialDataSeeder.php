<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Month;
use Illuminate\Support\Facades\Hash;

class InitialDataSeeder extends Seeder
{
    public function run()
    {
        // Create admin user
        User::create([
            'name' => 'Admin',
            'phone' => '01700000000',
            'password' => Hash::make('password')
        ]);

        // Create months for current year
        $months = [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ];

        foreach ($months as $month) {
            Month::create([
                'name' => $month,
                'year' => now()->year,
                'status' => 'active'
            ]);
        }
    }
}