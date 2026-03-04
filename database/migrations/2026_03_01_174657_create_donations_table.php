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
            $table->string('name'); // Donor name (either from donor table or manual entry)
            $table->string('phone')->nullable(); // Phone number
            $table->decimal('amount', 10, 2);
            $table->enum('paid_status', ['paid', 'unpaid'])->default('unpaid');
            $table->enum('payment_method', ['cash', 'bkash', 'nagad'])->default('cash');
            $table->text('notes')->nullable(); // Additional notes
            $table->foreignId('user_id')->constrained();
            $table->timestamps();
            $table->softDeletes(); // For soft delete functionality
        });
    }

    public function down()
    {
        Schema::dropIfExists('donations');
    }
};