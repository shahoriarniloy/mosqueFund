<?php
// database/migrations/YYYY_MM_DD_HHMMSS_add_phone_to_users_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->unique()->after('name');
            $table->string('password')->change();
            $table->dropColumn('email');
            $table->dropColumn('email_verified_at');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('phone');
            $table->string('email')->unique()->after('name');
            $table->timestamp('email_verified_at')->nullable();
        });
    }
};