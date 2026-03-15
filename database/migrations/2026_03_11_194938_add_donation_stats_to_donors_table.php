<?php
// database/migrations/xxxx_xx_xx_xxxxxx_add_donation_stats_to_donors_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('donors', function (Blueprint $table) {
            $table->integer('donation_count')->default(0)->after('monthly_amount');
            $table->integer('total_donation')->default(0)->after('donation_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('donors', function (Blueprint $table) {
            $table->dropColumn(['donation_count', 'total_donation']);
        });
    }
};