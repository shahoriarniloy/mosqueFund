<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('donor_id')->constrained()->onDelete('cascade');
            $table->foreignId('month_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->enum('paid_status', ['paid', 'unpaid'])->default('unpaid');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('payment_method', ['bkash', 'nagad', 'cash'])->default('cash');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};