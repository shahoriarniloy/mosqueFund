<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('donor_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('contributor_id')->nullable()->constrained()->nullOnDelete();
            $table->integer('amount');
            $table->enum('paid_status', ['paid', 'unpaid'])->default('unpaid');
            $table->enum('payment_method', ['cash', 'bkash', 'nagad'])->default('cash');
            $table->text('notes')->nullable(); 
            $table->foreignId('user_id')->constrained();
            $table->timestamps();
            $table->softDeletes(); 
            
            // Optional: Add indexes for better query performance
            $table->index('donor_id');
            $table->index('contributor_id');
            $table->index('paid_status');
            $table->index('created_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('donations');
    }
};