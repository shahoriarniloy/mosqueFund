@extends('layouts.app')

@section('title', 'Analytics')
@section('page-title', 'Analytics')
@section('page-subtitle', 'Monthly financial overview')

@section('quick-actions')
    {{-- <a href="{{ route('analytics.export') }}?month={{ $selectedMonth }}&year={{ $selectedYear }}" class="quick-action">
        <i class="fas fa-file-export"></i> Export Report
    </a> --}}
    <a href="#" class="quick-action" onclick="window.print()">
        <i class="fas fa-print"></i> Print
    </a>
@endsection

@section('content')
<div class="container-fluid px-2 px-sm-3">
    <!-- Month/Year Filter -->
    <div class="row g-2 g-sm-3 mb-3">
        <div class="col-12">
            <div class="filter-card" style="background: white; border-radius: 16px; padding: 16px; box-shadow: 0 4px 12px rgba(0,0,0,0.02);">
                <form action="{{ route('analytics.index') }}" method="GET" class="row g-2 align-items-end">
                    <div class="col-12 col-md-4">
                        <label class="form-label small text-muted mb-1">Month</label>
                        <select name="month" class="form-select form-select-sm" style="border-radius: 12px; padding: 8px 12px;">
                            @foreach($monthNames as $num => $name)
                                <option value="{{ $num }}" {{ $selectedMonth == $num ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label small text-muted mb-1">Year</label>
                        <select name="year" class="form-select form-select-sm" style="border-radius: 12px; padding: 8px 12px;">
                            @foreach($availableYears as $year)
                                <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-4">
                        <button type="submit" class="btn w-100" style="background: linear-gradient(145deg, #2563eb, #1d4ed8); color: white; border: none; border-radius: 12px; padding: 8px 16px; font-weight: 500;">
                            <i class="fas fa-search me-2"></i> View Analytics
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row g-2 g-sm-3 mb-3">
        <!-- Total Collection Card -->
        <div class="col-6 col-md-4">
            <div class="summary-card p-3" style="background: linear-gradient(145deg, #667eea, #5a67d8); border-radius: 16px; box-shadow: 0 8px 16px -8px rgba(102,126,234,0.4);">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <div style="width: 32px; height: 32px; background: rgba(255,255,255,0.2); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-coins text-white" style="font-size: 0.9rem;"></i>
                    </div>
                    <span class="text-white opacity-75" style="font-size: 0.65rem; font-weight: 500;">TOTAL COLLECTION</span>
                </div>
                <h4 class="text-white mb-0" style="font-size: 1.4rem; font-weight: 700;">৳{{ number_format($totalCollection['total_amount']) }}</h4>
                <small class="text-white opacity-75">{{ $selectedMonthName }} {{ $selectedYear }}</small>
            </div>
        </div>
        
        <!-- Paid Collection Card -->
        <div class="col-6 col-md-4">
            <div class="summary-card p-3" style="background: linear-gradient(145deg, #10b981, #059669); border-radius: 16px; box-shadow: 0 8px 16px -8px rgba(16,185,129,0.4);">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <div style="width: 32px; height: 32px; background: rgba(255,255,255,0.2); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-check-circle text-white" style="font-size: 0.9rem;"></i>
                    </div>
                    <span class="text-white opacity-75" style="font-size: 0.65rem; font-weight: 500;">PAID</span>
                </div>
                <h4 class="text-white mb-0" style="font-size: 1.4rem; font-weight: 700;">৳{{ number_format($totalCollection['paid_amount']) }}</h4>
                <small class="text-white opacity-75">{{ $totalCollection['paid_count'] }} transactions</small>
            </div>
        </div>
        
        <!-- Unpaid Collection Card -->
        <div class="col-6 col-md-4">
            <div class="summary-card p-3" style="background: linear-gradient(145deg, #ef4444, #dc2626); border-radius: 16px; box-shadow: 0 8px 16px -8px rgba(239,68,68,0.4);">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <div style="width: 32px; height: 32px; background: rgba(255,255,255,0.2); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-clock text-white" style="font-size: 0.9rem;"></i>
                    </div>
                    <span class="text-white opacity-75" style="font-size: 0.65rem; font-weight: 500;">UNPAID</span>
                </div>
                <h4 class="text-white mb-0" style="font-size: 1.4rem; font-weight: 700;">৳{{ number_format($totalCollection['unpaid_amount']) }}</h4>
                <small class="text-white opacity-75">{{ $totalCollection['unpaid_count'] }} pending</small>
            </div>
        </div>
    </div>

    <!-- Payment Method Breakdown -->
    <div class="row g-2 g-sm-3 mb-3">
        <div class="col-12">
            <div class="content-card" style="background: white; border-radius: 16px; overflow: hidden;">
                <div class="px-3 py-2" style="background: linear-gradient(145deg, #fafcff, #ffffff); border-bottom: 1px solid rgba(226, 232, 240, 0.6);">
                    <h6 class="mb-0" style="font-weight: 600; color: #0b1e33; font-size: 0.9rem;">
                        <i class="fas fa-credit-card me-2" style="color: #2563eb;"></i>
                        Payment Method Breakdown
                    </h6>
                </div>
                <div class="p-3">
                    <div class="row g-2">
                        <div class="col-4">
                            <div class="method-card p-2 text-center" style="background: #f8fafc; border-radius: 12px;">
                                <i class="fas fa-money-bill mb-1" style="color: #10b981; font-size: 1.2rem;"></i>
                                <div style="font-weight: 600; font-size: 1rem;">৳{{ number_format($paymentBreakdown['cash']) }}</div>
                                <small class="text-muted">Cash</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="method-card p-2 text-center" style="background: #f8fafc; border-radius: 12px;">
                                <i class="fas fa-mobile-alt mb-1" style="color: #8b5cf6; font-size: 1.2rem;"></i>
                                <div style="font-weight: 600; font-size: 1rem;">৳{{ number_format($paymentBreakdown['bkash']) }}</div>
                                <small class="text-muted">bKash</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="method-card p-2 text-center" style="background: #f8fafc; border-radius: 12px;">
                                <i class="fas fa-mobile mb-1" style="color: #f59e0b; font-size: 1.2rem;"></i>
                                <div style="font-weight: 600; font-size: 1rem;">৳{{ number_format($paymentBreakdown['nagad']) }}</div>
                                <small class="text-muted">Nagad</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Two Tables Side by Side -->
    <div class="row g-2 g-sm-3">
        <!-- Donations Table -->
        <div class="col-12 col-md-6">
            <div class="content-card" style="background: white; border-radius: 16px; overflow: hidden; height: fit-content;">
                <div class="px-3 py-2" style="background: linear-gradient(145deg, #fafcff, #ffffff); border-bottom: 1px solid rgba(226, 232, 240, 0.6);">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0" style="font-weight: 600; color: #0b1e33; font-size: 0.9rem;">
                            <i class="fas fa-hand-holding-heart me-2" style="color: #f59e0b;"></i>
                            Donations - {{ $selectedMonthName }} {{ $selectedYear }}
                        </h6>
                        <span class="badge" style="background: #f59e0b; color: white; padding: 4px 10px; border-radius: 20px; font-size: 0.65rem;">
                            ৳{{ number_format($donationsSummary['amount']) }}
                        </span>
                    </div>
                </div>
                
                <!-- Donations Summary Stats -->
                <div class="px-3 py-2" style="background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                    <div class="row g-1 text-center">
                        <div class="col-4">
                            <div style="font-size: 0.65rem; color: #64748b;">Total</div>
                            <div style="font-weight: 600; font-size: 0.9rem;">{{ $donationsSummary['total'] }}</div>
                        </div>
                        <div class="col-4">
                            <div style="font-size: 0.65rem; color: #10b981;">Paid</div>
                            <div style="font-weight: 600; font-size: 0.9rem; color: #10b981;">{{ $donationsSummary['paid'] }}</div>
                        </div>
                        <div class="col-4">
                            <div style="font-size: 0.65rem; color: #ef4444;">Unpaid</div>
                            <div style="font-weight: 600; font-size: 0.9rem; color: #ef4444;">{{ $donationsSummary['unpaid'] }}</div>
                        </div>
                    </div>
                </div>
                
                <!-- Mobile Donations Cards -->
                <div class="d-block d-md-none p-2">
                    @forelse($donations as $donation)
                    <div class="donation-item p-2 mb-2" style="background: #f8fafc; border-radius: 12px;">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span style="font-weight: 600; font-size: 0.8rem;">{{ $donation->name }}</span>
                            <span class="badge" style="background: {{ $donation->paid_status == 'paid' ? '#dcfce7' : '#fee2e2' }}; color: {{ $donation->paid_status == 'paid' ? '#166534' : '#991b1b' }}; font-size: 0.6rem;">
                                ৳{{ number_format($donation->amount) }}
                            </span>
                        </div>
                        <div class="d-flex justify-content-between small">
                            <span style="font-size: 0.65rem; color: #64748b;">
                                <i class="fas fa-phone-alt me-1" style="font-size: 0.5rem;"></i>{{ $donation->phone ?? 'N/A' }}
                            </span>
                            <span class="badge" style="background: #eef2ff; color: #4338ca; font-size: 0.6rem;">
                                {{ ucfirst($donation->payment_method) }}
                            </span>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-3">
                        <small class="text-muted">No donations this month</small>
                    </div>
                    @endforelse
                </div>
                
                <!-- Desktop Donations Table -->
                <div class="d-none d-md-block">
                    <div class="table-responsive">
                        <table class="table table-sm" style="margin-bottom: 0;">
                            <thead>
                                <tr style="background: #f8fafc;">
                                    <th style="padding: 8px; font-size: 0.65rem;">Donor</th>
                                    <th style="padding: 8px; font-size: 0.65rem;">Phone</th>
                                    <th style="padding: 8px; font-size: 0.65rem;">Amount</th>
                                    <th style="padding: 8px; font-size: 0.65rem;">Method</th>
                                    <th style="padding: 8px; font-size: 0.65rem;">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($donations as $donation)
                                <tr>
                                    <td style="padding: 6px 8px; font-size: 0.7rem;">{{ $donation->name }}</td>
                                    <td style="padding: 6px 8px; font-size: 0.7rem;">{{ $donation->phone ?? '—' }}</td>
                                    <td style="padding: 6px 8px; font-weight: 600; color: #f59e0b;">৳{{ number_format($donation->amount) }}</td>
                                    <td style="padding: 6px 8px;">
                                        <span class="badge" style="background: #eef2ff; color: #4338ca; font-size: 0.6rem;">
                                            {{ ucfirst($donation->payment_method) }}
                                        </span>
                                    </td>
                                    <td style="padding: 6px 8px;">
                                        @if($donation->paid_status == 'paid')
                                            <span class="badge" style="background: #dcfce7; color: #166534; font-size: 0.6rem;">Paid</span>
                                        @else
                                            <span class="badge" style="background: #fee2e2; color: #991b1b; font-size: 0.6rem;">Unpaid</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-3">
                                        <small class="text-muted">No donations this month</small>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Transactions Table -->
        <div class="col-12 col-md-6">
            <div class="content-card" style="background: white; border-radius: 16px; overflow: hidden; height: fit-content;">
                <div class="px-3 py-2" style="background: linear-gradient(145deg, #fafcff, #ffffff); border-bottom: 1px solid rgba(226, 232, 240, 0.6);">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0" style="font-weight: 600; color: #0b1e33; font-size: 0.9rem;">
                            <i class="fas fa-exchange-alt me-2" style="color: #2563eb;"></i>
                            Transactions - {{ $selectedMonthName }} {{ $selectedYear }}
                        </h6>
                        <span class="badge" style="background: #2563eb; color: white; padding: 4px 10px; border-radius: 20px; font-size: 0.65rem;">
                            ৳{{ number_format($transactionsSummary['amount']) }}
                        </span>
                    </div>
                </div>
                
                <!-- Transactions Summary Stats -->
                <div class="px-3 py-2" style="background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                    <div class="row g-1 text-center">
                        <div class="col-4">
                            <div style="font-size: 0.65rem; color: #64748b;">Total</div>
                            <div style="font-weight: 600; font-size: 0.9rem;">{{ $transactionsSummary['total'] }}</div>
                        </div>
                        <div class="col-4">
                            <div style="font-size: 0.65rem; color: #10b981;">Paid</div>
                            <div style="font-weight: 600; font-size: 0.9rem; color: #10b981;">{{ $transactionsSummary['paid'] }}</div>
                        </div>
                        <div class="col-4">
                            <div style="font-size: 0.65rem; color: #ef4444;">Unpaid</div>
                            <div style="font-weight: 600; font-size: 0.9rem; color: #ef4444;">{{ $transactionsSummary['unpaid'] }}</div>
                        </div>
                    </div>
                </div>
                
                <!-- Mobile Transactions Cards -->
                <div class="d-block d-md-none p-2">
                    @forelse($transactions as $transaction)
                    <div class="transaction-item p-2 mb-2" style="background: #f8fafc; border-radius: 12px;">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span style="font-weight: 600; font-size: 0.8rem;">{{ $transaction->donor->name }}</span>
                            <span class="badge" style="background: {{ $transaction->paid_status == 'paid' ? '#dcfce7' : '#fee2e2' }}; color: {{ $transaction->paid_status == 'paid' ? '#166534' : '#991b1b' }}; font-size: 0.6rem;">
                                ৳{{ number_format($transaction->amount) }}
                            </span>
                        </div>
                        <div class="d-flex justify-content-between small">
                            <span style="font-size: 0.65rem; color: #64748b;">
                                <i class="far fa-calendar me-1" style="font-size: 0.5rem;"></i>{{ $transaction->month->name }}
                            </span>
                            <span class="badge" style="background: #eef2ff; color: #4338ca; font-size: 0.6rem;">
                                {{ ucfirst($transaction->payment_method) }}
                            </span>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-3">
                        <small class="text-muted">No transactions this month</small>
                    </div>
                    @endforelse
                </div>
                
                <!-- Desktop Transactions Table -->
                <div class="d-none d-md-block">
                    <div class="table-responsive">
                        <table class="table table-sm" style="margin-bottom: 0;">
                            <thead>
                                <tr style="background: #f8fafc;">
                                    <th style="padding: 8px; font-size: 0.65rem;">Donor</th>
                                    <th style="padding: 8px; font-size: 0.65rem;">Month</th>
                                    <th style="padding: 8px; font-size: 0.65rem;">Amount</th>
                                    <th style="padding: 8px; font-size: 0.65rem;">Method</th>
                                    <th style="padding: 8px; font-size: 0.65rem;">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transactions as $transaction)
                                <tr>
                                    <td style="padding: 6px 8px; font-size: 0.7rem;">{{ $transaction->donor->name }}</td>
                                    <td style="padding: 6px 8px; font-size: 0.7rem;">{{ $transaction->month->name }}</td>
                                    <td style="padding: 6px 8px; font-weight: 600; color: #2563eb;">৳{{ number_format($transaction->amount) }}</td>
                                    <td style="padding: 6px 8px;">
                                        <span class="badge" style="background: #eef2ff; color: #4338ca; font-size: 0.6rem;">
                                            {{ ucfirst($transaction->payment_method) }}
                                        </span>
                                    </td>
                                    <td style="padding: 6px 8px;">
                                        @if($transaction->paid_status == 'paid')
                                            <span class="badge" style="background: #dcfce7; color: #166534; font-size: 0.6rem;">Paid</span>
                                        @else
                                            <span class="badge" style="background: #fee2e2; color: #991b1b; font-size: 0.6rem;">Unpaid</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-3">
                                        <small class="text-muted">No transactions this month</small>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Daily Breakdown (Optional - for larger screens) -->
    @if(count($dailyData) > 0)
<div class="row g-2 g-sm-3 mt-3">
    <div class="col-12">
        <div class="content-card" style="background: white; border-radius: 24px; overflow: hidden; box-shadow: 0 15px 30px -12px rgba(0,0,0,0.1); border: 1px solid rgba(226, 232, 240, 0.6);">
            <!-- Card Header with Gradient -->
            <div class="px-4 py-3" style="background: linear-gradient(145deg, #f8fafc, #f1f5f9); border-bottom: 1px solid rgba(139, 92, 246, 0.2);">
                <div class="d-flex align-items-center justify-content-between">
                    <h6 class="mb-0" style="font-weight: 600; color: #0b1e33; font-size: 0.95rem;">
                        <i class="fas fa-calendar-day me-2" style="color: #8b5cf6;"></i>
                        Daily Breakdown
                    </h6>
                    <span class="badge rounded-pill px-3 py-1" style="background: linear-gradient(145deg, #8b5cf6, #7c3aed); color: white; font-size: 0.65rem; height: 22px; min-width: 70px; display: flex; align-items: center; justify-content: center;">
                        {{ count($dailyData) }} days
                    </span>
                </div>
            </div>
            
            <div class="p-3">
                <!-- Desktop Table View with Fixed Columns -->
                <div class="d-none d-md-block">
                    <div class="table-responsive">
                        <table class="table align-middle" style="border-collapse: separate; border-spacing: 0 4px; width: 100%; table-layout: fixed;">
                            <colgroup>
                                <col style="width: 25%;">
                                <col style="width: 25%;">
                                <col style="width: 25%;">
                                <col style="width: 25%;">
                            </colgroup>
                            <thead>
                                <tr>
                                    <th style="padding: 8px 12px; font-size: 0.65rem; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.3px; border: none; background: transparent; height: 36px;">Date</th>
                                    <th style="padding: 8px 12px; font-size: 0.65rem; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.3px; border: none; background: transparent; height: 36px;">Donations</th>
                                    <th style="padding: 8px 12px; font-size: 0.65rem; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.3px; border: none; background: transparent; height: 36px;">Transactions</th>
                                    <th style="padding: 8px 12px; font-size: 0.65rem; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.3px; border: none; background: transparent; height: 36px;">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dailyData as $day)
                                <tr style="background: white; border-radius: 16px; box-shadow: 0 2px 8px -2px rgba(0,0,0,0.02); transition: all 0.2s; border: 1px solid #edf2f7; height: 70px;" 
                                    onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 16px -4px rgba(139,92,246,0.1)'" 
                                    onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px -2px rgba(0,0,0,0.02)'">
                                    <td style="padding: 8px 12px; border: none; border-radius: 16px 0 0 16px; height: 70px;">
                                        <div class="d-flex align-items-center" style="height: 100%;">
                                            <div style="width: 36px; height: 36px; background: linear-gradient(145deg, #eef2ff, #e0e7ff); border-radius: 10px; display: flex; align-items: center; justify-content: center; margin-right: 10px; flex-shrink: 0;">
                                                <i class="fas fa-calendar-alt" style="color: #8b5cf6; font-size: 0.8rem;"></i>
                                            </div>
                                            <span style="font-weight: 600; font-size: 0.8rem; color: #1e293b; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ \Carbon\Carbon::parse($day['date'])->format('d M, Y') }}</span>
                                        </div>
                                    </td>
                                    <td style="padding: 8px 12px; border: none; height: 70px;">
                                        <div class="d-flex align-items-center" style="height: 100%;">
                                            <div style="width: 28px; height: 28px; background: rgba(245, 158, 11, 0.1); border-radius: 8px; display: flex; align-items: center; justify-content: center; margin-right: 8px; flex-shrink: 0;">
                                                <i class="fas fa-hand-holding-heart" style="color: #f59e0b; font-size: 0.7rem;"></i>
                                            </div>
                                            <div style="overflow: hidden;">
                                                <span style="font-weight: 600; font-size: 0.8rem; color: #f59e0b; display: block; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">৳{{ number_format($day['donations']) }}</span>
                                                <span style="font-size: 0.6rem; color: #94a3b8; display: block;">({{ $day['donations_count'] }} items)</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td style="padding: 8px 12px; border: none; height: 70px;">
                                        <div class="d-flex align-items-center" style="height: 100%;">
                                            <div style="width: 28px; height: 28px; background: rgba(37, 99, 235, 0.1); border-radius: 8px; display: flex; align-items: center; justify-content: center; margin-right: 8px; flex-shrink: 0;">
                                                <i class="fas fa-exchange-alt" style="color: #2563eb; font-size: 0.7rem;"></i>
                                            </div>
                                            <div style="overflow: hidden;">
                                                <span style="font-weight: 600; font-size: 0.8rem; color: #2563eb; display: block; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">৳{{ number_format($day['transactions']) }}</span>
                                                <span style="font-size: 0.6rem; color: #94a3b8; display: block;">({{ $day['transactions_count'] }} items)</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td style="padding: 8px 12px; border: none; border-radius: 0 16px 16px 0; height: 70px;">
                                        <div class="d-flex align-items-center" style="height: 100%;">
                                            <div style="width: 32px; height: 32px; background: linear-gradient(145deg, #10b981, #059669); border-radius: 8px; display: flex; align-items: center; justify-content: center; margin-right: 8px; flex-shrink: 0;">
                                                <i class="fas fa-coins" style="color: white; font-size: 0.8rem;"></i>
                                            </div>
                                            <span style="font-weight: 700; font-size: 0.9rem; color: #10b981; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">৳{{ number_format($day['total']) }}</span>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Mobile Card View with Fixed Heights -->
                <div class="d-block d-md-none">
                    @foreach($dailyData as $day)
                    <div class="mobile-day-card mb-2 p-2" style="background: white; border-radius: 16px; box-shadow: 0 2px 8px -2px rgba(0,0,0,0.03); border: 1px solid #edf2f7; height: 140px; display: flex; flex-direction: column;">
                        <!-- Date Header - Fixed height -->
                        <div class="d-flex align-items-center gap-2 pb-1" style="border-bottom: 1px dashed #e2e8f0; height: 40px;">
                            <div style="width: 32px; height: 32px; background: linear-gradient(145deg, #8b5cf6, #7c3aed); border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <i class="fas fa-calendar-day" style="color: white; font-size: 0.8rem;"></i>
                            </div>
                            <span style="font-weight: 600; font-size: 0.85rem; color: #1e293b; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ \Carbon\Carbon::parse($day['date'])->format('d M, Y') }}</span>
                        </div>
                        
                        <!-- Stats Grid - Fixed height -->
                        <div class="row g-1 mt-1" style="height: 85px;">
                            <div class="col-4" style="height: 100%;">
                                <div style="background: rgba(245, 158, 11, 0.05); border-radius: 12px; padding: 6px 2px; text-align: center; height: 100%; display: flex; flex-direction: column; justify-content: center;">
                                    <i class="fas fa-hand-holding-heart" style="color: #f59e0b; font-size: 0.9rem;"></i>
                                    <div style="font-weight: 600; font-size: 0.75rem; color: #f59e0b; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">৳{{ number_format($day['donations']) }}</div>
                                    <small style="font-size: 0.5rem; color: #94a3b8; display: block; white-space: nowrap;">{{ $day['donations_count'] }} items</small>
                                </div>
                            </div>
                            <div class="col-4" style="height: 100%;">
                                <div style="background: rgba(37, 99, 235, 0.05); border-radius: 12px; padding: 6px 2px; text-align: center; height: 100%; display: flex; flex-direction: column; justify-content: center;">
                                    <i class="fas fa-exchange-alt" style="color: #2563eb; font-size: 0.9rem;"></i>
                                    <div style="font-weight: 600; font-size: 0.75rem; color: #2563eb; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">৳{{ number_format($day['transactions']) }}</div>
                                    <small style="font-size: 0.5rem; color: #94a3b8; display: block; white-space: nowrap;">{{ $day['transactions_count'] }} items</small>
                                </div>
                            </div>
                            <div class="col-4" style="height: 100%;">
                                <div style="background: rgba(16, 185, 129, 0.05); border-radius: 12px; padding: 6px 2px; text-align: center; height: 100%; display: flex; flex-direction: column; justify-content: center;">
                                    <i class="fas fa-coins" style="color: #10b981; font-size: 0.9rem;"></i>
                                    <div style="font-weight: 700; font-size: 0.8rem; color: #10b981; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">৳{{ number_format($day['total']) }}</div>
                                    <small style="font-size: 0.5rem; color: #94a3b8; display: block; white-space: nowrap;">total</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Fixed column widths for desktop */
table {
    table-layout: fixed;
    width: 100%;
}

/* Fixed row heights */
tr {
    height: 70px !important;
    transition: all 0.2s ease;
}

td, th {
    height: 70px !important;
    vertical-align: middle;
}

/* Fixed height for mobile cards */
.mobile-day-card {
    height: 140px !important;
    transition: all 0.2s ease;
}

.mobile-day-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 16px -4px rgba(139,92,246,0.1) !important;
}

/* Icon containers fixed size */
.icon-container {
    width: 36px;
    height: 36px;
    flex-shrink: 0;
}

.small-icon-container {
    width: 28px;
    height: 28px;
    flex-shrink: 0;
}

.medium-icon-container {
    width: 32px;
    height: 32px;
    flex-shrink: 0;
}

/* Text truncation */
.truncate-text {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .content-card {
        border-radius: 20px !important;
    }
    
    .col-4 > div {
        min-height: 85px;
    }
}
</style>
@endif
</div>

<style>
.summary-card {
    transition: all 0.2s ease;
    height: 100%;
}

.summary-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 24px -8px rgba(0,0,0,0.2) !important;
}

.method-card {
    transition: background 0.2s;
}

.method-card:hover {
    background: #f1f5f9 !important;
}

@media (max-width: 768px) {
    .summary-card h4 {
        font-size: 1.2rem !important;
    }
    
    .table {
        font-size: 0.7rem;
    }
    
    .table td, .table th {
        padding: 4px !important;
    }
}
</style>
@endsection