<?php
// database/migrations/xxxx_xx_xx_xxxxxx_add_created_by_to_registration_logs_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('registration_logs', function (Blueprint $table) {
            $table->foreignId('created_by')
                  ->nullable()
                  ->constrained('users')
                  ->after('user_id')
                  ->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::table('registration_logs', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropColumn('created_by');
        });
    }
};