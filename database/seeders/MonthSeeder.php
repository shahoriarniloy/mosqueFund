<?php

namespace Database\Seeders;

use App\Models\Month;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MonthSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $months = [
            ['name' => 'January', 'status' => 'active', 'year' => date('Y')],
            ['name' => 'February', 'status' => 'active', 'year' => date('Y')],
            ['name' => 'March', 'status' => 'active', 'year' => date('Y')],
            ['name' => 'April', 'status' => 'active', 'year' => date('Y')],
            ['name' => 'May', 'status' => 'active', 'year' => date('Y')],
            ['name' => 'June', 'status' => 'active', 'year' => date('Y')],
            ['name' => 'July', 'status' => 'active', 'year' => date('Y')],
            ['name' => 'August', 'status' => 'active', 'year' => date('Y')],
            ['name' => 'September', 'status' => 'active', 'year' => date('Y')],
            ['name' => 'October', 'status' => 'active', 'year' => date('Y')],
            ['name' => 'November', 'status' => 'active', 'year' => date('Y')],
            ['name' => 'December', 'status' => 'active', 'year' => date('Y')],
        ];

        foreach ($months as $month) {
            Month::create($month);
        }

        $this->command->info('12 months have been seeded successfully!');
    }
}