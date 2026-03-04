<?php
// database/migrations/YYYY_MM_DD_HHMMSS_create_months_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('months', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // January, February, etc.
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->year('year');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('months');
    }
};