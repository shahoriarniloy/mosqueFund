@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Overview')

@section('content')
<div class="container-fluid px-2 px-sm-3">
    <!-- Stats Cards - Ultra Compact Grid -->
    <div class="row g-1 g-sm-2">
        <!-- Donations Card -->
        <div class="col-6 col-md-4 col-lg-3">
            <div class="stat-card p-2" style="background: linear-gradient(145deg, #f59e0b, #d97706); border-radius: 14px; box-shadow: 0 8px 16px -6px rgba(245,158,11,0.3);">
                <div class="d-flex align-items-center gap-1 mb-1">
                    <div style="width: 28px; height: 28px; background: rgba(255,255,255,0.2); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-hand-holding-heart text-white" style="font-size: 0.8rem;"></i>
                    </div>
                    <span class="text-white opacity-75" style="font-size: 0.6rem;">DONATIONS</span>
                </div>
                <div class="d-flex justify-content-between align-items-end">
                    <div>
                        <h3 class="text-white mb-0" style="font-size: 1.3rem; font-weight: 700; line-height: 1.2;">{{ $donationsCount ?? 0 }}</h3>
                        <span class="text-white opacity-75" style="font-size: 0.55rem;">৳{{ number_format($totalDonationAmount ?? 0) }}</span>
                    </div>
                    <a href="{{ route('donations.index') }}" class="text-white text-decoration-none" style="font-size: 0.6rem;">
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Transactions Card -->
        <div class="col-6 col-md-4 col-lg-3">
            <div class="stat-card p-2" style="background: linear-gradient(145deg, #10b981, #059669); border-radius: 14px; box-shadow: 0 8px 16px -6px rgba(16,185,129,0.3);">
                <div class="d-flex align-items-center gap-1 mb-1">
                    <div style="width: 28px; height: 28px; background: rgba(255,255,255,0.2); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-exchange-alt text-white" style="font-size: 0.8rem;"></i>
                    </div>
                    <span class="text-white opacity-75" style="font-size: 0.6rem;">TRANSACTIONS</span>
                </div>
                <div class="d-flex justify-content-between align-items-end">
                    <div>
                        <h3 class="text-white mb-0" style="font-size: 1.3rem; font-weight: 700;">{{ $transactionsCount ?? 0 }}</h3>
                        <span class="text-white opacity-75" style="font-size: 0.55rem;">৳{{ number_format($totalTransactionAmount ?? 0) }}</span>
                    </div>
                    <a href="{{ route('transactions.index') }}" class="text-white text-decoration-none" style="font-size: 0.6rem;">
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Donors Card -->
        <div class="col-6 col-md-4 col-lg-3">
            <div class="stat-card p-2" style="background: linear-gradient(145deg, #667eea, #5a67d8); border-radius: 14px; box-shadow: 0 8px 16px -6px rgba(102,126,234,0.3);">
                <div class="d-flex align-items-center gap-1 mb-1">
                    <div style="width: 28px; height: 28px; background: rgba(255,255,255,0.2); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-users text-white" style="font-size: 0.8rem;"></i>
                    </div>
                    <span class="text-white opacity-75" style="font-size: 0.6rem;">DONORS</span>
                </div>
                <div class="d-flex justify-content-between align-items-end">
                    <div>
                        <h3 class="text-white mb-0" style="font-size: 1.3rem; font-weight: 700;">{{ $donorsCount ?? 0 }}</h3>
                        <span class="text-white opacity-75" style="font-size: 0.55rem;">{{ $activeDonors ?? 0 }} active</span>
                    </div>
                    <a href="{{ route('donors.index') }}" class="text-white text-decoration-none" style="font-size: 0.6rem;">
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Months Card -->
        <div class="col-6 col-md-4 col-lg-3">
            <div class="stat-card p-2" style="background: linear-gradient(145deg, #06b6d4, #0891b2); border-radius: 14px; box-shadow: 0 8px 16px -6px rgba(6,182,212,0.3);">
                <div class="d-flex align-items-center gap-1 mb-1">
                    <div style="width: 28px; height: 28px; background: rgba(255,255,255,0.2); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-calendar-alt text-white" style="font-size: 0.8rem;"></i>
                    </div>
                    <span class="text-white opacity-75" style="font-size: 0.6rem;">MONTHS</span>
                </div>
                <div class="d-flex justify-content-between align-items-end">
                    <div>
                        <h3 class="text-white mb-0" style="font-size: 1.3rem; font-weight: 700;">{{ $monthsCount ?? 0 }}</h3>
                        <span class="text-white opacity-75" style="font-size: 0.55rem;">{{ $activeMonths ?? 0 }} active</span>
                    </div>
                    <a href="{{ route('months.index') }}" class="text-white text-decoration-none" style="font-size: 0.6rem;">
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Total Collection Card -->
        <div class="col-6 col-md-4 col-lg-3">
            <div class="stat-card p-2" style="background: linear-gradient(145deg, #8b5cf6, #7c3aed); border-radius: 14px; box-shadow: 0 8px 16px -6px rgba(139,92,246,0.3);">
                <div class="d-flex align-items-center gap-1 mb-1">
                    <div style="width: 28px; height: 28px; background: rgba(255,255,255,0.2); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-coins text-white" style="font-size: 0.8rem;"></i>
                    </div>
                    <span class="text-white opacity-75" style="font-size: 0.6rem;">COLLECTED</span>
                </div>
                <div class="d-flex justify-content-between align-items-end">
                    <div>
                        <h3 class="text-white mb-0" style="font-size: 1.1rem; font-weight: 700;">৳{{ number_format($totalCollected ?? 0) }}</h3>
                        <span class="text-white opacity-75" style="font-size: 0.5rem;">This month: ৳{{ number_format($monthlyCollected ?? 0) }}</span>
                    </div>
                    <span class="text-white opacity-75" style="font-size: 0.5rem;">{{ $collectionRate ?? 0 }}%</span>
                </div>
            </div>
        </div>

        <!-- Pending Amount Card -->
        <div class="col-6 col-md-4 col-lg-3">
            <div class="stat-card p-2" style="background: linear-gradient(145deg, #ef4444, #dc2626); border-radius: 14px; box-shadow: 0 8px 16px -6px rgba(239,68,68,0.3);">
                <div class="d-flex align-items-center gap-1 mb-1">
                    <div style="width: 28px; height: 28px; background: rgba(255,255,255,0.2); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-clock text-white" style="font-size: 0.8rem;"></i>
                    </div>
                    <span class="text-white opacity-75" style="font-size: 0.6rem;">PENDING</span>
                </div>
                <div class="d-flex justify-content-between align-items-end">
                    <div>
                        <h3 class="text-white mb-0" style="font-size: 1.1rem; font-weight: 700;">৳{{ number_format($pendingAmount ?? 0) }}</h3>
                        <span class="text-white opacity-75" style="font-size: 0.5rem;">{{ $pendingCount ?? 0 }} pending</span>
                    </div>
                    <span class="text-white opacity-75" style="font-size: 0.5rem;">{{ $overdueCount ?? 0 }} overdue</span>
                </div>
            </div>
        </div>

        <!-- This Month Card -->
        <div class="col-6 col-md-4 col-lg-3">
            <div class="stat-card p-2" style="background: linear-gradient(145deg, #3b82f6, #2563eb); border-radius: 14px; box-shadow: 0 8px 16px -6px rgba(59,130,246,0.3);">
                <div class="d-flex align-items-center gap-1 mb-1">
                    <div style="width: 28px; height: 28px; background: rgba(255,255,255,0.2); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-calendar-check text-white" style="font-size: 0.8rem;"></i>
                    </div>
                    <span class="text-white opacity-75" style="font-size: 0.6rem;">THIS MONTH</span>
                </div>
                <div class="d-flex justify-content-between align-items-end">
                    <div>
                        <h3 class="text-white mb-0" style="font-size: 1.1rem; font-weight: 700;">৳{{ number_format($thisMonthTotal ?? 0) }}</h3>
                        <span class="text-white opacity-75" style="font-size: 0.5rem;">{{ $thisMonthPaid ?? 0 }} paid</span>
                    </div>
                    <span class="text-white opacity-75" style="font-size: 0.5rem;">{{ $thisMonthUnpaid ?? 0 }} unpaid</span>
                </div>
            </div>
        </div>

        <!-- Quick Actions Card -->
        <div class="col-6 col-md-4 col-lg-3">
            <div class="stat-card p-2" style="background: linear-gradient(145deg, #14b8a6, #0d9488); border-radius: 14px; box-shadow: 0 8px 16px -6px rgba(20,184,166,0.3);">
                <div class="d-flex align-items-center gap-1 mb-1">
                    <div style="width: 28px; height: 28px; background: rgba(255,255,255,0.2); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-bolt text-white" style="font-size: 0.8rem;"></i>
                    </div>
                    <span class="text-white opacity-75" style="font-size: 0.6rem;">QUICK</span>
                </div>
                <div class="d-flex flex-wrap gap-1 mt-1">
                    <a href="{{ route('donations.create') }}" class="btn btn-sm p-0" style="width: 24px; height: 24px; background: rgba(255,255,255,0.2); border-radius: 6px; display: flex; align-items: center; justify-content: center;" title="Add Donation">
                        <i class="fas fa-hand-holding-heart text-white" style="font-size: 0.7rem;"></i>
                    </a>
                    <a href="{{ route('transactions.create') }}" class="btn btn-sm p-0" style="width: 24px; height: 24px; background: rgba(255,255,255,0.2); border-radius: 6px; display: flex; align-items: center; justify-content: center;" title="New Transaction">
                        <i class="fas fa-exchange-alt text-white" style="font-size: 0.7rem;"></i>
                    </a>
                    <a href="{{ route('donors.create') }}" class="btn btn-sm p-0" style="width: 24px; height: 24px; background: rgba(255,255,255,0.2); border-radius: 6px; display: flex; align-items: center; justify-content: center;" title="Add Donor">
                        <i class="fas fa-user-plus text-white" style="font-size: 0.7rem;"></i>
                    </a>
                    <a href="{{ route('months.create') }}" class="btn btn-sm p-0" style="width: 24px; height: 24px; background: rgba(255,255,255,0.2); border-radius: 6px; display: flex; align-items: center; justify-content: center;" title="Add Month">
                        <i class="fas fa-calendar-plus text-white" style="font-size: 0.7rem;"></i>
                    </a>
                    <!-- New Admin Registration Button -->
                    <a href="{{ route('register') }}" class="btn btn-sm p-0" style="width: 24px; height: 24px; background: rgba(255,255,255,0.2); border-radius: 6px; display: flex; align-items: center; justify-content: center;" title="Register New Admin">
                        <i class="fas fa-user-shield text-white" style="font-size: 0.7rem;"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity Section -->
    @if(isset($recentTransactions) && $recentTransactions->count() > 0)
    <div class="row g-1 g-sm-2 mt-2">
        <div class="col-12">
            <div class="compact-card" style="background: white; border-radius: 12px; border: 1px solid #edf2f7; overflow: hidden;">
                <div class="d-flex justify-content-between align-items-center px-2 py-1" style="background: linear-gradient(145deg, #f8fafc, #f1f5f9); border-bottom: 1px solid #e2e8f0;">
                    <div class="d-flex align-items-center gap-1">
                        <i class="fas fa-history" style="color: #2563eb; font-size: 0.7rem;"></i>
                        <span style="font-weight: 600; font-size: 0.65rem; color: #1e293b;">RECENT</span>
                    </div>
                    <span class="badge" style="background: #e2e8f0; color: #475569; font-size: 0.55rem; padding: 2px 8px;">{{ $recentTransactions->count() }}</span>
                </div>

                <!-- Mobile View - Cards -->
                <div class="d-block d-md-none p-1" style="max-height: 250px; overflow-y: auto;">
                    @foreach($recentTransactions as $transaction)
                    <div class="d-flex justify-content-between align-items-center py-1 px-1 {{ !$loop->last ? 'mb-1' : '' }}" style="border-bottom: {{ !$loop->last ? '1px dashed #edf2f7' : 'none' }};">
                        <div style="min-width: 0; flex: 1;">
                            <span style="font-weight: 500; font-size: 0.65rem; color: #1e293b; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 90px;">{{ $transaction->donor->name }}</span>
                            <div class="d-flex align-items-center gap-1">
                                <span style="font-size: 0.5rem; color: #64748b;">{{ substr($transaction->month->name, 0, 3) }}</span>
                                @if($transaction->paid_status == 'paid')
                                    <i class="fas fa-check-circle" style="color: #10b981; font-size: 0.5rem;"></i>
                                @else
                                    <i class="fas fa-times-circle" style="color: #ef4444; font-size: 0.5rem;"></i>
                                @endif
                            </div>
                        </div>
                        <div class="text-end">
                            <span style="font-weight: 600; font-size: 0.65rem; color: #2563eb;">৳{{ number_format($transaction->amount) }}</span>
                            <div><small style="font-size: 0.45rem; color: #94a3b8;">{{ $transaction->created_at->diffForHumans(null, true) }}</small></div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Desktop View - Compact Table -->
                <div class="d-none d-md-block" style="max-height: 250px; overflow-y: auto;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <tbody>
                            @foreach($recentTransactions as $transaction)
                            <tr style="border-bottom: 1px solid #edf2f7;">
                                <td style="padding: 6px 10px; width: 30%;"><span style="font-size: 0.7rem;">{{ $transaction->donor->name }}</span></td>
                                <td style="padding: 6px 10px; width: 15%;"><span style="font-size: 0.6rem; color: #64748b;">{{ substr($transaction->month->name, 0, 3) }}'{{ substr($transaction->month->year, 2) }}</span></td>
                                <td style="padding: 6px 10px; width: 15%;"><span style="font-weight: 600; font-size: 0.7rem; color: #2563eb;">৳{{ number_format($transaction->amount) }}</span></td>
                                <td style="padding: 6px 10px; width: 15%;">
                                    @if($transaction->paid_status == 'paid')
                                        <span style="color: #10b981; font-size: 0.6rem;">Paid</span>
                                    @else
                                        <span style="color: #ef4444; font-size: 0.6rem;">Unpaid</span>
                                    @endif
                                </td>
                                <td style="padding: 6px 10px; width: 25%;"><small style="font-size: 0.55rem; color: #94a3b8;">{{ $transaction->created_at->diffForHumans(null, true) }}</small></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="text-center py-1" style="border-top: 1px solid #edf2f7; background: #fafbfc;">
                    <a href="{{ route('transactions.index') }}" style="font-size: 0.6rem; color: #2563eb; text-decoration: none;">
                        View all <i class="fas fa-arrow-right ms-1" style="font-size: 0.5rem;"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<style>
.stat-card {
    transition: all 0.2s ease;
    height: 100%;
    min-height: 75px;
}
.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 20px -8px rgba(0,0,0,0.15) !important;
}
.compact-card {
    transition: all 0.2s ease;
}
.compact-card:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px -2px rgba(0,0,0,0.05);
}
[style*="overflow-y: auto"]::-webkit-scrollbar {
    width: 3px;
}
[style*="overflow-y: auto"]::-webkit-scrollbar-track {
    background: #f1f5f9;
}
[style*="overflow-y: auto"]::-webkit-scrollbar-thumb {
    background: #cbd5e0;
    border-radius: 3px;
}
[style*="overflow-y: auto"]::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}
@media (max-width: 768px) {
    .stat-card { min-height: 70px; }
    .stat-card h3 { font-size: 1.1rem !important; }
}
</style>
@endsection