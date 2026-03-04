@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Overview')

@section('content')
<div class="container-fluid px-2 px-sm-3">
    <!-- Stats Cards - Modern and Compressed -->
    <div class="row g-2 g-sm-3">

        <!-- Donations Card -->
        <div class="col-6 col-md-4 col-lg-3">
            <div class="stat-card p-3" style="background: linear-gradient(145deg, #f59e0b, #d97706); border-radius: 18px; box-shadow: 0 12px 20px -10px rgba(245,158,11,0.3);">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <div style="width: 36px; height: 36px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
                        <i class="fas fa-hand-holding-heart text-white" style="font-size: 1rem;"></i>
                    </div>
                    <span class="text-white opacity-75" style="font-size: 0.7rem; font-weight: 500; letter-spacing: 0.3px;">DONATIONS</span>
                </div>
                
                <div class="mt-2">
                    <h2 class="text-white mb-1" style="font-size: 1.8rem; font-weight: 700; line-height: 1;">{{ $donationsCount ?? 0 }}</h2>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-white opacity-75" style="font-size: 0.7rem;">
                            <i class="fas fa-check-circle me-1" style="font-size: 0.5rem;"></i>
                            ৳{{ number_format($totalDonationAmount ?? 0) }}
                        </span>
                        <a href="{{ route('donations.index') }}" class="text-white text-decoration-none" style="font-size: 0.7rem; font-weight: 500;">
                            View <i class="fas fa-arrow-right ms-1" style="font-size: 0.6rem;"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>


        <!-- Transactions Card -->
        <div class="col-6 col-md-4 col-lg-3">
            <div class="stat-card p-3" style="background: linear-gradient(145deg, #10b981, #059669); border-radius: 18px; box-shadow: 0 12px 20px -10px rgba(16,185,129,0.3);">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <div style="width: 36px; height: 36px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
                        <i class="fas fa-exchange-alt text-white" style="font-size: 1rem;"></i>
                    </div>
                    <span class="text-white opacity-75" style="font-size: 0.7rem; font-weight: 500; letter-spacing: 0.3px;">TRANSACTIONS</span>
                </div>
                
                <div class="mt-2">
                    <h2 class="text-white mb-1" style="font-size: 1.8rem; font-weight: 700; line-height: 1;">{{ $transactionsCount ?? 0 }}</h2>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-white opacity-75" style="font-size: 0.7rem;">
                            <i class="fas fa-check-circle me-1" style="font-size: 0.5rem;"></i>
                            ৳{{ number_format($totalTransactionAmount ?? 0) }}
                        </span>
                        <a href="{{ route('transactions.index') }}" class="text-white text-decoration-none" style="font-size: 0.7rem; font-weight: 500;">
                            View <i class="fas fa-arrow-right ms-1" style="font-size: 0.6rem;"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Donors Card -->
        <div class="col-6 col-md-4 col-lg-3">
            <div class="stat-card p-3" style="background: linear-gradient(145deg, #667eea, #5a67d8); border-radius: 18px; box-shadow: 0 12px 20px -10px rgba(102,126,234,0.3);">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <div style="width: 36px; height: 36px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
                        <i class="fas fa-users text-white" style="font-size: 1rem;"></i>
                    </div>
                    <span class="text-white opacity-75" style="font-size: 0.7rem; font-weight: 500; letter-spacing: 0.3px;">DONORS</span>
                </div>
                
                <div class="mt-2">
                    <h2 class="text-white mb-1" style="font-size: 1.8rem; font-weight: 700; line-height: 1;">{{ $donorsCount ?? 0 }}</h2>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-white opacity-75" style="font-size: 0.7rem;">
                            <i class="fas fa-arrow-up me-1" style="font-size: 0.5rem;"></i>
                            {{ $activeDonors ?? 0 }} active
                        </span>
                        <a href="{{ route('donors.index') }}" class="text-white text-decoration-none" style="font-size: 0.7rem; font-weight: 500;">
                            View <i class="fas fa-arrow-right ms-1" style="font-size: 0.6rem;"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Months Card -->
        <div class="col-6 col-md-4 col-lg-3">
            <div class="stat-card p-3" style="background: linear-gradient(145deg, #06b6d4, #0891b2); border-radius: 18px; box-shadow: 0 12px 20px -10px rgba(6,182,212,0.3);">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <div style="width: 36px; height: 36px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
                        <i class="fas fa-calendar-alt text-white" style="font-size: 1rem;"></i>
                    </div>
                    <span class="text-white opacity-75" style="font-size: 0.7rem; font-weight: 500; letter-spacing: 0.3px;">MONTHS</span>
                </div>
                
                <div class="mt-2">
                    <h2 class="text-white mb-1" style="font-size: 1.8rem; font-weight: 700; line-height: 1;">{{ $monthsCount ?? 0 }}</h2>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-white opacity-75" style="font-size: 0.7rem;">
                            <i class="fas fa-calendar-check me-1" style="font-size: 0.5rem;"></i>
                            {{ $activeMonths ?? 0 }} active
                        </span>
                        <a href="{{ route('months.index') }}" class="text-white text-decoration-none" style="font-size: 0.7rem; font-weight: 500;">
                            View <i class="fas fa-arrow-right ms-1" style="font-size: 0.6rem;"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        
        
        
        
        <!-- Total Collection Card -->
        <div class="col-6 col-md-4 col-lg-3">
            <div class="stat-card p-3" style="background: linear-gradient(145deg, #8b5cf6, #7c3aed); border-radius: 18px; box-shadow: 0 12px 20px -10px rgba(139,92,246,0.3);">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <div style="width: 36px; height: 36px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
                        <i class="fas fa-coins text-white" style="font-size: 1rem;"></i>
                    </div>
                    <span class="text-white opacity-75" style="font-size: 0.7rem; font-weight: 500; letter-spacing: 0.3px;">COLLECTED</span>
                </div>
                
                <div class="mt-2">
                    <h2 class="text-white mb-1" style="font-size: 1.8rem; font-weight: 700; line-height: 1;">৳{{ number_format($totalCollected ?? 0) }}</h2>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-white opacity-75" style="font-size: 0.7rem;">
                            <i class="fas fa-calendar me-1" style="font-size: 0.5rem;"></i>
                            This month: ৳{{ number_format($monthlyCollected ?? 0) }}
                        </span>
                        <span class="text-white opacity-75" style="font-size: 0.7rem;">
                            {{ $collectionRate ?? 0 }}% rate
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Pending Amount Card -->
        <div class="col-6 col-md-4 col-lg-3">
            <div class="stat-card p-3" style="background: linear-gradient(145deg, #ef4444, #dc2626); border-radius: 18px; box-shadow: 0 12px 20px -10px rgba(239,68,68,0.3);">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <div style="width: 36px; height: 36px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
                        <i class="fas fa-clock text-white" style="font-size: 1rem;"></i>
                    </div>
                    <span class="text-white opacity-75" style="font-size: 0.7rem; font-weight: 500; letter-spacing: 0.3px;">PENDING</span>
                </div>
                
                <div class="mt-2">
                    <h2 class="text-white mb-1" style="font-size: 1.8rem; font-weight: 700; line-height: 1;">৳{{ number_format($pendingAmount ?? 0) }}</h2>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-white opacity-75" style="font-size: 0.7rem;">
                            <i class="fas fa-hourglass-half me-1" style="font-size: 0.5rem;"></i>
                            {{ $pendingCount ?? 0 }} pending
                        </span>
                        <span class="text-white opacity-75" style="font-size: 0.7rem;">
                            {{ $overdueCount ?? 0 }} overdue
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- This Month Card -->
        <div class="col-6 col-md-4 col-lg-3">
            <div class="stat-card p-3" style="background: linear-gradient(145deg, #3b82f6, #2563eb); border-radius: 18px; box-shadow: 0 12px 20px -10px rgba(59,130,246,0.3);">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <div style="width: 36px; height: 36px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
                        <i class="fas fa-calendar-check text-white" style="font-size: 1rem;"></i>
                    </div>
                    <span class="text-white opacity-75" style="font-size: 0.7rem; font-weight: 500; letter-spacing: 0.3px;">THIS MONTH</span>
                </div>
                
                <div class="mt-2">
                    <h2 class="text-white mb-1" style="font-size: 1.8rem; font-weight: 700; line-height: 1;">৳{{ number_format($thisMonthTotal ?? 0) }}</h2>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-white opacity-75" style="font-size: 0.7rem;">
                            <i class="fas fa-check-circle me-1" style="font-size: 0.5rem;"></i>
                            {{ $thisMonthPaid ?? 0 }} paid
                        </span>
                        <span class="text-white opacity-75" style="font-size: 0.7rem;">
                            {{ $thisMonthUnpaid ?? 0 }} unpaid
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Quick Actions Card -->
        <div class="col-6 col-md-4 col-lg-3">
            <div class="stat-card p-3" style="background: linear-gradient(145deg, #14b8a6, #0d9488); border-radius: 18px; box-shadow: 0 12px 20px -10px rgba(20,184,166,0.3);">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <div style="width: 36px; height: 36px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
                        <i class="fas fa-bolt text-white" style="font-size: 1rem;"></i>
                    </div>
                    <span class="text-white opacity-75" style="font-size: 0.7rem; font-weight: 500; letter-spacing: 0.3px;">QUICK ACTIONS</span>
                </div>
                
                <div class="mt-2">
                    <div class="d-flex flex-column gap-1">
                        <a href="{{ route('donations.create') }}" class="text-white text-decoration-none small" style="font-size: 0.75rem; display: flex; align-items: center;">
                            <i class="fas fa-plus-circle me-2" style="font-size: 0.6rem;"></i> Add Donation
                        </a>
                        <a href="{{ route('transactions.create') }}" class="text-white text-decoration-none small" style="font-size: 0.75rem; display: flex; align-items: center;">
                            <i class="fas fa-plus-circle me-2" style="font-size: 0.6rem;"></i> New Transaction
                        </a>
                        <a href="{{ route('donors.create') }}" class="text-white text-decoration-none small" style="font-size: 0.75rem; display: flex; align-items: center;">
                            <i class="fas fa-plus-circle me-2" style="font-size: 0.6rem;"></i> Add Donor
                        </a>
                        <a href="{{ route('months.create') }}" class="text-white text-decoration-none small" style="font-size: 0.75rem; display: flex; align-items: center;">
                            <i class="fas fa-plus-circle me-2" style="font-size: 0.6rem;"></i> Add Month
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity Section -->
    @if(isset($recentTransactions) && $recentTransactions->count() > 0)
    <div class="row g-2 g-sm-3 mt-4">
        <div class="col-12">
            <div class="content-card" style="border-radius: 20px; overflow: hidden; background: white;">
                <div class="px-3 py-2" style="background: linear-gradient(145deg, #fafcff, #ffffff); border-bottom: 1px solid rgba(226, 232, 240, 0.6);">
                    <h6 class="mb-0" style="font-family: 'Space Grotesk', sans-serif; font-weight: 600; color: #0b1e33; font-size: 0.9rem;">
                        <i class="fas fa-history me-2" style="color: #2563eb;"></i>
                        Recent Transactions
                    </h6>
                </div>
                <div class="p-2 p-sm-3">
                    <!-- Mobile view - cards -->
                    <div class="d-block d-md-none">
                        @foreach($recentTransactions as $transaction)
                        <div class="recent-item mb-2 p-2" style="background: #f8fafc; border-radius: 16px;">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span style="font-weight: 600; font-size: 0.8rem;">{{ $transaction->donor->name }}</span>
                                    <div class="d-flex gap-2 mt-1">
                                        <span class="badge" style="background: #eef2ff; color: #4338ca; font-size: 0.6rem; padding: 3px 8px;">{{ $transaction->month->name }}</span>
                                        <span class="badge" style="background: {{ $transaction->paid_status == 'paid' ? '#dcfce7' : '#fee2e2' }}; color: {{ $transaction->paid_status == 'paid' ? '#166534' : '#991b1b' }}; font-size: 0.6rem; padding: 3px 8px;">
                                            ৳{{ number_format($transaction->amount) }}
                                        </span>
                                    </div>
                                </div>
                                <small class="text-muted" style="font-size: 0.6rem;">{{ $transaction->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <!-- Desktop view - table -->
                    <div class="d-none d-md-block">
                        <table class="table table-sm" style="margin-bottom: 0;">
                            <tbody>
                                @foreach($recentTransactions as $transaction)
                                <tr>
                                    <td style="border: none; padding: 8px;">{{ $transaction->donor->name }}</td>
                                    <td style="border: none; padding: 8px;">{{ $transaction->month->name }} {{ $transaction->month->year }}</td>
                                    <td style="border: none; padding: 8px;">৳{{ number_format($transaction->amount) }}</td>
                                    <td style="border: none; padding: 8px;">
                                        <span class="badge" style="background: {{ $transaction->paid_status == 'paid' ? '#dcfce7' : '#fee2e2' }}; color: {{ $transaction->paid_status == 'paid' ? '#166534' : '#991b1b' }}; padding: 4px 10px;">
                                            {{ ucfirst($transaction->paid_status) }}
                                        </span>
                                    </td>
                                    <td style="border: none; padding: 8px;">
                                        <small class="text-muted">{{ $transaction->created_at->diffForHumans() }}</small>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<style>
/* Stat card hover effect */
.stat-card {
    transition: all 0.2s ease;
    height: 100%;
    min-height: 120px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 20px 30px -10px rgba(0,0,0,0.2) !important;
}

/* Recent activity item hover */
.recent-item {
    transition: background 0.2s;
}

.recent-item:hover {
    background: #f1f5f9 !important;
}

/* Mobile optimizations */
@media (max-width: 768px) {
    .stat-card {
        min-height: 110px;
        padding: 12px !important;
    }
    
    .stat-card h2 {
        font-size: 1.4rem !important;
    }
    
    .stat-card .opacity-75 {
        font-size: 0.6rem !important;
    }
    
    .content-card {
        border-radius: 16px !important;
    }
    
    /* Better touch targets */
    .stat-card a {
        min-height: 44px;
        display: inline-flex;
        align-items: center;
    }
}

/* Tablet adjustments */
@media (min-width: 769px) and (max-width: 1024px) {
    .stat-card h2 {
        font-size: 1.6rem !important;
    }
}

/* Small height screens */
@media (max-height: 700px) {
    .stat-card {
        min-height: 100px;
    }
}

/* Badge styling */
.badge {
    font-weight: 500;
    border-radius: 20px;
}
</style>
@endsection