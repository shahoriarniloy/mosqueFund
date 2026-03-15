<?php
// database/migrations/YYYY_MM_DD_HHMMSS_create_donors_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('donors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone')->unique();
            $table->text('address')->nullable();
            $table->integer('monthly_amount')->default(0);
            $table->enum('status', ['active', 'inactive'])->default('active');
            
            // Foreign key to users table
            $table->foreignId('created_by')
                  ->constrained('users');  // <-- Add semicolon here!
                  
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('donors');
    }
};