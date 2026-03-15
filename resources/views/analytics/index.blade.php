@extends('layouts.app')

@section('title', 'Analytics')
@section('page-title', 'Analytics')
@section('page-subtitle', 'Monthly financial overview')

@section('quick-actions')
    <a href="#" class="quick-action" onclick="window.print()">
        <i class="fas fa-print"></i> Print
    </a>
@endsection

@section('content')
<div class="container-fluid px-2 px-sm-3">
    <!-- Filter Section - Moderate -->
    <div class="row g-2 mb-3">
        <div class="col-12">
            <div class="bg-white p-3 rounded-3 border" style="border-color: #eef2f8 !important;">
                <form action="{{ route('analytics.index') }}" method="GET" class="row g-2 align-items-end">
                    <div class="col-12 col-md-5 col-lg-4">
                        <label class="form-label small text-secondary mb-1">Month</label>
                        <select name="month" class="form-select form-select-sm" style="border-radius: 10px; padding: 8px 12px;">
                            @foreach($monthNames as $num => $name)
                                <option value="{{ $num }}" {{ $selectedMonth == $num ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-5 col-lg-4">
                        <label class="form-label small text-secondary mb-1">Year</label>
                        <select name="year" class="form-select form-select-sm" style="border-radius: 10px; padding: 8px 12px;">
                            @foreach($availableYears as $year)
                                <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>{{ $year }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-2">
                        <button type="submit" class="btn btn-sm w-100" style="background: linear-gradient(145deg, #2563eb, #1d4ed8); color: white; border: none; border-radius: 10px; padding: 8px 12px;">
                            <i class="fas fa-search me-1"></i> View
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Summary Cards - Moderate Size -->
    <div class="row g-2 mb-3">
        <div class="col-md-4">
            <div class="p-3 rounded-3 text-white" style="background: linear-gradient(145deg, #667eea, #5a67d8);">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <div style="width: 36px; height: 36px; background: rgba(255,255,255,0.2); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-coins" style="font-size: 1rem;"></i>
                    </div>
                    <span class="small opacity-75">TOTAL COLLECTION</span>
                </div>
                <h4 class="mb-0 fw-bold" style="font-size: 1.5rem;">৳{{ number_format($totalCollection['total_amount']) }}</h4>
                <small class="opacity-75">{{ $selectedMonthName }} {{ $selectedYear }}</small>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="p-3 rounded-3 text-white" style="background: linear-gradient(145deg, #10b981, #059669);">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <div style="width: 36px; height: 36px; background: rgba(255,255,255,0.2); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-check-circle" style="font-size: 1rem;"></i>
                    </div>
                    <span class="small opacity-75">PAID</span>
                </div>
                <h4 class="mb-0 fw-bold" style="font-size: 1.5rem;">৳{{ number_format($totalCollection['paid_amount']) }}</h4>
                <small class="opacity-75">{{ $totalCollection['paid_count'] }} transactions</small>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="p-3 rounded-3 text-white" style="background: linear-gradient(145deg, #ef4444, #dc2626);">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <div style="width: 36px; height: 36px; background: rgba(255,255,255,0.2); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-clock" style="font-size: 1rem;"></i>
                    </div>
                    <span class="small opacity-75">UNPAID</span>
                </div>
                <h4 class="mb-0 fw-bold" style="font-size: 1.5rem;">৳{{ number_format($totalCollection['unpaid_amount']) }}</h4>
                <small class="opacity-75">{{ $totalCollection['unpaid_count'] }} pending</small>
            </div>
        </div>
    </div>

    <!-- Payment Method Breakdown -->
    <div class="row g-2 mb-3">
        <div class="col-12">
            <div class="bg-white p-3 rounded-3 border" style="border-color: #edf2f7 !important;">
                <h6 class="mb-3 fw-semibold" style="font-size: 0.9rem;">
                    <i class="fas fa-credit-card me-2" style="color: #2563eb;"></i>
                    Payment Methods
                </h6>
                <div class="row g-2">
                    <div class="col-4">
                        <div class="p-2 text-center bg-light rounded-2">
                            <i class="fas fa-money-bill mb-1" style="color: #10b981; font-size: 1.1rem;"></i>
                            <div class="fw-semibold" style="font-size: 0.9rem;">৳{{ number_format($paymentBreakdown['cash']) }}</div>
                            <small class="text-secondary" style="font-size: 0.7rem;">Cash</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-2 text-center bg-light rounded-2">
                            <i class="fas fa-mobile-alt mb-1" style="color: #8b5cf6; font-size: 1.1rem;"></i>
                            <div class="fw-semibold" style="font-size: 0.9rem;">৳{{ number_format($paymentBreakdown['bkash']) }}</div>
                            <small class="text-secondary" style="font-size: 0.7rem;">bKash</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-2 text-center bg-light rounded-2">
                            <i class="fas fa-mobile mb-1" style="color: #f59e0b; font-size: 1.1rem;"></i>
                            <div class="fw-semibold" style="font-size: 0.9rem;">৳{{ number_format($paymentBreakdown['nagad']) }}</div>
                            <small class="text-secondary" style="font-size: 0.7rem;">Nagad</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Two Tables Side by Side -->
    <div class="row g-2">
        <!-- Donations Table -->
        <div class="col-12 col-lg-6">
            <div class="bg-white rounded-3 border" style="border-color: #edf2f7 !important; overflow: hidden;">
                <div class="px-3 py-2 border-bottom d-flex justify-content-between align-items-center bg-light bg-opacity-50">
                    <h6 class="mb-0 fw-semibold" style="font-size: 0.85rem;">
                        <i class="fas fa-hand-holding-heart me-2" style="color: #f59e0b;"></i>
                        Donations
                    </h6>
                    <span class="badge bg-warning bg-opacity-10 text-warning px-2 py-1 rounded-pill" style="font-size: 0.7rem;">
                        ৳{{ number_format($donationsSummary['amount']) }}
                    </span>
                </div>
                
                <div class="px-3 py-2 border-bottom d-flex justify-content-around bg-white">
                    <div class="text-center">
                        <span class="d-block text-secondary" style="font-size: 0.65rem;">Total</span>
                        <span class="fw-semibold" style="font-size: 0.8rem;">{{ $donationsSummary['total'] }}</span>
                    </div>
                    <div class="text-center">
                        <span class="d-block text-success" style="font-size: 0.65rem;">Paid</span>
                        <span class="fw-semibold text-success" style="font-size: 0.8rem;">{{ $donationsSummary['paid'] }}</span>
                    </div>
                    <div class="text-center">
                        <span class="d-block text-danger" style="font-size: 0.65rem;">Unpaid</span>
                        <span class="fw-semibold text-danger" style="font-size: 0.8rem;">{{ $donationsSummary['unpaid'] }}</span>
                    </div>
                </div>
                
                <div style="max-height: 280px; overflow-y: auto;">
                    @forelse($donations as $donation)
                    <div class="px-3 py-2 border-bottom d-flex justify-content-between align-items-center">
                        <div style="min-width: 0; flex: 1;">
                            <div class="text-truncate fw-medium" style="font-size: 0.8rem; max-width: 160px;">{{ $donation->name }}</div>
                            @if($donation->phone)
                                <small class="text-secondary" style="font-size: 0.6rem;"><i class="fas fa-phone-alt me-1"></i>{{ $donation->phone }}</small>
                            @endif
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-info bg-opacity-10 text-info px-2 py-1" style="font-size: 0.6rem;">{{ substr($donation->payment_method, 0, 3) }}</span>
                            <span class="fw-semibold" style="font-size: 0.8rem; color: #f59e0b;">৳{{ number_format($donation->amount) }}</span>
                            @if($donation->paid_status == 'paid')
                                <span class="badge bg-success bg-opacity-10 text-success px-2 py-1" style="font-size: 0.6rem;">Paid</span>
                            @else
                                <span class="badge bg-danger bg-opacity-10 text-danger px-2 py-1" style="font-size: 0.6rem;">Unpaid</span>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-3"><small class="text-secondary">No donations</small></div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Transactions Table -->
        <div class="col-12 col-lg-6">
            <div class="bg-white rounded-3 border" style="border-color: #edf2f7 !important; overflow: hidden;">
                <div class="px-3 py-2 border-bottom d-flex justify-content-between align-items-center bg-light bg-opacity-50">
                    <h6 class="mb-0 fw-semibold" style="font-size: 0.85rem;">
                        <i class="fas fa-exchange-alt me-2" style="color: #2563eb;"></i>
                        Transactions
                    </h6>
                    <span class="badge bg-primary bg-opacity-10 text-primary px-2 py-1 rounded-pill" style="font-size: 0.7rem;">
                        ৳{{ number_format($transactionsSummary['amount']) }}
                    </span>
                </div>
                
                <div class="px-3 py-2 border-bottom d-flex justify-content-around bg-white">
                    <div class="text-center">
                        <span class="d-block text-secondary" style="font-size: 0.65rem;">Total</span>
                        <span class="fw-semibold" style="font-size: 0.8rem;">{{ $transactionsSummary['total'] }}</span>
                    </div>
                    <div class="text-center">
                        <span class="d-block text-success" style="font-size: 0.65rem;">Paid</span>
                        <span class="fw-semibold text-success" style="font-size: 0.8rem;">{{ $transactionsSummary['paid'] }}</span>
                    </div>
                    <div class="text-center">
                        <span class="d-block text-danger" style="font-size: 0.65rem;">Unpaid</span>
                        <span class="fw-semibold text-danger" style="font-size: 0.8rem;">{{ $transactionsSummary['unpaid'] }}</span>
                    </div>
                </div>
                
                <div style="max-height: 280px; overflow-y: auto;">
                    @forelse($transactions as $transaction)
                    <div class="px-3 py-2 border-bottom d-flex justify-content-between align-items-center">
                        <div style="min-width: 0; flex: 1;">
                            <div class="text-truncate fw-medium" style="font-size: 0.8rem; max-width: 140px;">{{ $transaction->donor->name }}</div>
                            <small class="text-secondary" style="font-size: 0.6rem;">{{ $transaction->month->name }} {{ $transaction->month->year }}</small>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-info bg-opacity-10 text-info px-2 py-1" style="font-size: 0.6rem;">{{ substr($transaction->payment_method, 0, 3) }}</span>
                            <span class="fw-semibold" style="font-size: 0.8rem; color: #2563eb;">৳{{ number_format($transaction->amount) }}</span>
                            @if($transaction->paid_status == 'paid')
                                <span class="badge bg-success bg-opacity-10 text-success px-2 py-1" style="font-size: 0.6rem;">Paid</span>
                            @else
                                <span class="badge bg-danger bg-opacity-10 text-danger px-2 py-1" style="font-size: 0.6rem;">Unpaid</span>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-3"><small class="text-secondary">No transactions</small></div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Daily Breakdown -->
    @if(count($dailyData) > 0)
    <div class="row g-2 mt-3">
        <div class="col-12">
            <div class="bg-white rounded-3 border" style="border-color: #edf2f7 !important; overflow: hidden;">
                <div class="px-3 py-2 border-bottom d-flex justify-content-between align-items-center bg-light bg-opacity-50">
                    <h6 class="mb-0 fw-semibold" style="font-size: 0.85rem;">
                        <i class="fas fa-calendar-day me-2" style="color: #8b5cf6;"></i>
                        Daily Breakdown
                    </h6>
                    <span class="badge px-2 py-1 rounded-pill text-white" style="background: #8b5cf6; font-size: 0.65rem;">{{ count($dailyData) }} days</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="px-3 py-2" style="font-size: 0.7rem;">Date</th>
                                <th class="py-2" style="font-size: 0.7rem;">Donations</th>
                                <th class="py-2" style="font-size: 0.7rem;">Transactions</th>
                                <th class="px-3 py-2" style="font-size: 0.7rem;">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dailyData as $day)
                            <tr>
                                <td class="px-3 py-1" style="font-size: 0.75rem;">{{ \Carbon\Carbon::parse($day['date'])->format('d M') }}</td>
                                <td class="py-1" style="font-size: 0.75rem;">
                                    <span style="color: #f59e0b;">৳{{ number_format($day['donations']) }}</span>
                                    <small class="text-secondary ms-1">({{ $day['donations_count'] }})</small>
                                </td>
                                <td class="py-1" style="font-size: 0.75rem;">
                                    <span style="color: #2563eb;">৳{{ number_format($day['transactions']) }}</span>
                                    <small class="text-secondary ms-1">({{ $day['transactions_count'] }})</small>
                                </td>
                                <td class="px-3 py-1 fw-semibold" style="font-size: 0.75rem; color: #10b981;">৳{{ number_format($day['total']) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<style>
.bg-opacity-10 { --bs-bg-opacity: 0.1; }
[style*="overflow-y: auto"]::-webkit-scrollbar { width: 4px; }
[style*="overflow-y: auto"]::-webkit-scrollbar-track { background: #f1f5f9; }
[style*="overflow-y: auto"]::-webkit-scrollbar-thumb { background: #cbd5e0; border-radius: 4px; }
.border { border-width: 1px !important; }
</style>
@endsection