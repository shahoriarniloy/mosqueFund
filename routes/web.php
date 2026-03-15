<?php
// routes/web.php

use App\Http\Controllers\AnalyticsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ContributorController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\DonorController;
use App\Http\Controllers\MonthController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\DueController;

// Public routes (no authentication required)
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

  Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [RegisterController::class, 'register']);
// Protected routes (require authentication) - ALL ROUTES INSIDE THIS GROUP
Route::middleware(['auth'])->group(function () {
    
    // ==================== REGISTER ROUTES (NOW PROTECTED) ====================
    // Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    // Route::post('register', [RegisterController::class, 'register']);
    
    // ==================== DASHBOARD ====================
    // Dashboard - redirect root to dashboard
    Route::get('/', function () {
        return redirect()->route('dashboard');
    });
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // ==================== DONOR MANAGEMENT ====================
    Route::resource('donors', DonorController::class);
    Route::patch('/donors/{donor}/toggle-status', [DonorController::class, 'toggleStatus'])->name('donors.toggleStatus');

    // ==================== CONTRIBUTOR MANAGEMENT ====================
    Route::prefix('contributors')->name('contributors.')->group(function () {
        Route::get('/', [ContributorController::class, 'index'])->name('index');
        Route::get('/statistics', [ContributorController::class, 'statistics'])->name('statistics');
        Route::get('/export', [ContributorController::class, 'export'])->name('export');
        
        // Create routes
        Route::get('/create', [ContributorController::class, 'create'])->name('create');
        Route::post('/', [ContributorController::class, 'store'])->name('store');
        
        // Routes with phone parameter
        Route::get('/{phone}', [ContributorController::class, 'show'])->name('show');
        Route::get('/{phone}/edit', [ContributorController::class, 'edit'])->name('edit');
        Route::put('/{phone}', [ContributorController::class, 'update'])->name('update');
        Route::delete('/{phone}', [ContributorController::class, 'destroy'])->name('destroy');
        Route::post('/merge-duplicates', [ContributorController::class, 'mergeDuplicates'])->name('merge-duplicates');
    });
    
    // ==================== MONTH MANAGEMENT ====================
    Route::resource('months', MonthController::class);
    
    // ==================== TRANSACTION MANAGEMENT ====================
    Route::get('/transactions/get-paid-months', [TransactionController::class, 'getPaidMonths'])->name('transactions.getPaidMonths');
    Route::get('/get-donor-amount', [TransactionController::class, 'getDonorAmount'])->name('transactions.getDonorAmount');
    Route::patch('/transactions/{transaction}/mark-as-paid', [TransactionController::class, 'markAsPaid'])->name('transactions.markAsPaid');
    Route::resource('transactions', TransactionController::class);
    
    // ==================== DONATION MANAGEMENT ====================
    Route::resource('donations', DonationController::class);
    Route::patch('/donations/{donation}/mark-as-paid', [DonationController::class, 'markAsPaid'])->name('donations.markAsPaid');
    Route::get('/get-donor-details', [DonationController::class, 'getDonorDetails'])->name('donations.getDonorDetails');
    Route::get('/donations/export/report', [DonationController::class, 'export'])->name('donations.export');
    Route::post('/donations/check-donor', [DonationController::class, 'checkDonor'])->name('donations.checkDonor');

    // ==================== ANALYTICS ROUTES ====================
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics.index');
    Route::get('/analytics/monthly-comparison', [AnalyticsController::class, 'monthlyComparison'])->name('analytics.monthly-comparison');
    Route::get('/analytics/get-monthly-data', [AnalyticsController::class, 'getMonthlyComparison'])->name('analytics.get-monthly-data');
    Route::get('/analytics/export', [AnalyticsController::class, 'export'])->name('analytics.export');

    // ==================== DUE ROUTES ====================
    Route::get('/due', [DueController::class, 'index'])->name('due.index');
    Route::get('/due/export', [DueController::class, 'export'])->name('due.export');
    Route::post('/due/send-reminder', [DueController::class, 'sendReminder'])->name('due.send-reminder');
    Route::post('/due/send-bulk-reminder', [DueController::class, 'sendBulkReminder'])->name('due.send-bulk-reminder');

    // ==================== LOGS ROUTES ====================
    // Donation logs
    Route::get('/donations/{donation}/logs', [DonationController::class, 'logs'])->name('donations.logs');
    Route::get('/donation-logs', [DonationController::class, 'allLogs'])->name('donation-logs.index');
    Route::get('/donation-logs/{donationLog}', [DonationController::class, 'showLog'])->name('donation-logs.show');
    
    // Transaction logs
    Route::get('/transactions/{transaction}/logs', [TransactionController::class, 'logs'])->name('transactions.logs');
    Route::get('/transaction-logs', [TransactionController::class, 'allLogs'])->name('transaction-logs.index');
    Route::get('/transaction-logs/{transactionLog}', [TransactionController::class, 'showLog'])->name('transaction-logs.show');
    Route::get('/transaction-logs/export', [TransactionController::class, 'exportLogs'])->name('transaction-logs.export');


    // Registration Logs Routes
Route::get('/registration-logs', [App\Http\Controllers\RegistrationLogController::class, 'index'])->name('registration-logs.index');
Route::get('/registration-logs/{id}', [App\Http\Controllers\RegistrationLogController::class, 'show'])->name('registration-logs.show');
Route::get('/registration-logs/export', [App\Http\Controllers\RegistrationLogController::class, 'export'])->name('registration-logs.export');
});

// ==================== FALLBACK ROUTE ====================
Route::fallback(function () {
    return redirect()->route('login');
});