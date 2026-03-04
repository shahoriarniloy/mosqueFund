<?php

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
        Schema::create('donation_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('donation_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Track changes
            $table->string('field_name')->nullable(); // Which field was changed
            $table->text('old_value')->nullable();    // Old value
            $table->text('new_value')->nullable();    // New value
            
            // Store snapshot of complete donation data
            $table->json('donation_snapshot')->nullable();
            
            // Action type
            $table->enum('action', ['created', 'updated', 'deleted', 'restored'])->default('updated');
            
            // IP Address and User Agent for tracking
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            
            $table->timestamps();
            
            // Indexes for faster queries
            $table->index(['donation_id', 'created_at']);
            $table->index(['user_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donation_logs');
    }
};