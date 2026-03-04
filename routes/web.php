<?php
// routes/web.php

use App\Http\Controllers\AnalyticsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\DonorController;
use App\Http\Controllers\MonthController;
use App\Http\Controllers\TransactionController;

// Public routes (no authentication required)
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);

// Protected routes (require authentication)
Route::middleware(['auth'])->group(function () {
    // Dashboard - redirect root to dashboard
    Route::get('/', function () {
        return redirect()->route('dashboard');
    });
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Donor Management
    Route::resource('donors', DonorController::class);
    Route::patch('/donors/{donor}/toggle-status', [DonorController::class, 'toggleStatus'])->name('donors.toggleStatus');
    
    // Month Management
    Route::resource('months', MonthController::class);
    
    // Transaction Management
    Route::resource('transactions', TransactionController::class);
    Route::patch('/transactions/{transaction}/mark-as-paid', [TransactionController::class, 'markAsPaid'])->name('transactions.markAsPaid');
    Route::get('/get-donor-amount', [TransactionController::class, 'getDonorAmount'])->name('transactions.getDonorAmount');

    // Donation Management
Route::resource('donations', DonationController::class);
Route::patch('/donations/{donation}/mark-as-paid', [DonationController::class, 'markAsPaid'])->name('donations.markAsPaid');
Route::get('/get-donor-details', [DonationController::class, 'getDonorDetails'])->name('donations.getDonorDetails');
Route::get('/donations/export/report', [DonationController::class, 'export'])->name('donations.export');
});


Route::resource('donations', DonationController::class);

// Analytics routes
Route::get('/analytics', [App\Http\Controllers\AnalyticsController::class, 'index'])->name('analytics.index');
Route::get('/analytics/monthly-comparison', [App\Http\Controllers\AnalyticsController::class, 'monthlyComparison'])->name('analytics.monthly-comparison');
Route::get('/analytics/get-monthly-data', [App\Http\Controllers\AnalyticsController::class, 'getMonthlyComparison'])->name('analytics.get-monthly-data');
Route::get('/analytics/export', [App\Http\Controllers\AnalyticsController::class, 'export'])->name('analytics.export');

// Due routes
Route::get('/due', [App\Http\Controllers\DueController::class, 'index'])->name('due.index');
Route::get('/due/export', [App\Http\Controllers\DueController::class, 'export'])->name('due.export');

Route::post('/donations/check-donor', [App\Http\Controllers\DonationController::class, 'checkDonor'])->name('donations.checkDonor');


// Donation logs route
Route::get('/donations/{donation}/logs', [App\Http\Controllers\DonationController::class, 'logs'])->name('donations.logs');


// Donation logs routes
Route::get('/donation-logs', [App\Http\Controllers\DonationController::class, 'allLogs'])->name('donation-logs.index');
Route::get('/donation-logs/{donationLog}', [App\Http\Controllers\DonationController::class, 'showLog'])->name('donation-logs.show');


// Transaction logs routes
Route::get('/transactions/{transaction}/logs', [TransactionController::class, 'logs'])->name('transactions.logs');
Route::get('/transaction-logs', [TransactionController::class, 'allLogs'])->name('transaction-logs.index');
Route::get('/transaction-logs/{transactionLog}', [TransactionController::class, 'showLog'])->name('transaction-logs.show');
Route::get('/transaction-logs/export', [TransactionController::class, 'exportLogs'])->name('transaction-logs.export');

// Due SMS reminder routes
Route::post('/due/send-reminder', [App\Http\Controllers\DueController::class, 'sendReminder'])->name('due.send-reminder');
Route::post('/due/send-bulk-reminder', [App\Http\Controllers\DueController::class, 'sendBulkReminder'])->name('due.send-bulk-reminder');
// Fallback route for any undefined routes
Route::fallback(function () {
    return redirect()->route('login');
});