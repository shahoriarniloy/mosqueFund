@extends('layouts.app')

@section('title', 'Donor Details')
@section('page-title', 'Donor Details')
@section('page-subtitle', $donor->name)

@section('quick-actions')
    <a href="{{ route('donors.index') }}" class="quick-action">
        <i class="fas fa-arrow-left"></i> Back
    </a>
    <a href="{{ route('donors.edit', $donor) }}" class="quick-action">
        <i class="fas fa-edit"></i> Edit
    </a>
    <a href="#" class="quick-action" onclick="window.print()">
        <i class="fas fa-print"></i> Print
    </a>
@endsection

@section('content')
<div class="container-fluid px-2 px-sm-3">
    <div class="row g-2 g-sm-3">
        <div class="col-12 col-lg-8 mx-auto">
            <!-- Main Card -->
            <div class="card border-0" style="border-radius: 20px; background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px); box-shadow: 0 15px 35px rgba(0, 0, 0, 0.05); overflow: hidden;">
                
                <!-- Status Header - Color based on donor status -->
                <div class="px-3 py-2" style="background: {{ $donor->status == 'active' ? 'linear-gradient(135deg, #10b981, #059669)' : 'linear-gradient(135deg, #ef4444, #dc2626)' }}; height: 65px;">
                    <div class="d-flex justify-content-between align-items-center h-100">
                        <div>
                            <h6 class="mb-0 text-white fw-semibold" style="font-size: 0.9rem;">Donor #{{ $donor->id }}</h6>
                            <span class="text-white-50 small" style="font-size: 0.6rem;">{{ $donor->created_at->format('M d, Y') }}</span>
                        </div>
                        <span class="badge bg-white rounded-pill px-3 py-2 fw-semibold shadow-sm" style="color: {{ $donor->status == 'active' ? '#059669' : '#dc2626' }}; min-width: 85px; font-size: 0.65rem;">
                            <i class="fas fa-{{ $donor->status == 'active' ? 'check-circle' : 'hourglass' }} me-1"></i>
                            {{ strtoupper(substr($donor->status, 0, 4)) }}
                        </span>
                    </div>
                </div>

                <!-- Body -->
                <div class="p-3" style="background: rgba(255, 255, 255, 0.5);">
                    
                    <!-- Donor Info - Compact -->
                    <div class="glass-card p-2 mb-3 d-flex align-items-center" style="background: rgba(255, 255, 255, 0.7); border-radius: 14px; height: 60px;">
                        <div class="d-flex align-items-center gap-2 w-100">
                            <div class="rounded-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; background: linear-gradient(135deg, #667eea, #764ba2);">
                                <span class="text-white fw-semibold" style="font-size: 0.9rem;">{{ substr($donor->name, 0, 1) }}</span>
                            </div>
                            <div class="flex-grow-1" style="min-width: 0;">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="fw-semibold text-truncate" style="font-size: 0.85rem; max-width: 120px;">{{ $donor->name }}</span>
                                    <span class="badge rounded-pill px-2" style="font-size: 0.55rem; background: {{ $donor->status == 'active' ? 'rgba(16, 185, 129, 0.1)' : 'rgba(239, 68, 68, 0.1)' }}; color: {{ $donor->status == 'active' ? '#10b981' : '#ef4444' }};">
                                        <i class="fas fa-{{ $donor->status == 'active' ? 'check-circle' : 'clock' }} me-1"></i>
                                        {{ ucfirst($donor->status) }}
                                    </span>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    @if($donor->phone)
                                        <a href="tel:{{ $donor->phone }}" class="d-flex align-items-center text-secondary" style="font-size: 0.6rem; text-decoration: none;">
                                            <i class="fas fa-phone-alt me-1" style="color: #2563eb; font-size: 0.5rem;"></i>
                                            <span class="text-truncate" style="max-width: 90px;">{{ $donor->phone }}</span>
                                        </a>
                                    @endif
                                    @if($donor->address)
                                        <small class="d-flex align-items-center text-secondary" style="font-size: 0.6rem;">
                                            <i class="fas fa-map-marker-alt me-1" style="color: #64748b; font-size: 0.5rem;"></i>
                                            <span class="text-truncate" style="max-width: 100px;">{{ Str::limit($donor->address, 15) }}</span>
                                        </small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Stats Grid - 2x2 compact -->
                    <div class="row g-1 mb-3">
                        <div class="col-6">
                            <div class="glass-card d-flex align-items-center p-2" style="background: rgba(255, 255, 255, 0.5); border-radius: 12px; height: 55px;">
                                <div class="d-flex align-items-center gap-2 w-100">
                                    <div class="rounded-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background: rgba(37, 99, 235, 0.1);">
                                        <i class="fas fa-coins" style="color: #2563eb; font-size: 0.8rem;"></i>
                                    </div>
                                    <div>
                                        <small class="text-secondary d-block" style="font-size: 0.5rem;">Monthly</small>
                                        <span class="fw-bold" style="color: #2563eb; font-size: 0.9rem;">৳{{ number_format($donor->monthly_amount) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="glass-card d-flex align-items-center p-2" style="background: rgba(255, 255, 255, 0.5); border-radius: 12px; height: 55px;">
                                <div class="d-flex align-items-center gap-2 w-100">
                                    <div class="rounded-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background: rgba(16, 185, 129, 0.1);">
                                        <i class="fas fa-check-circle" style="color: #10b981; font-size: 0.8rem;"></i>
                                    </div>
                                    <div>
                                        <small class="text-secondary d-block" style="font-size: 0.5rem;">Total Paid</small>
                                        <span class="fw-bold" style="color: #10b981; font-size: 0.9rem;">৳{{ number_format($totalPaid) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="glass-card d-flex align-items-center p-2" style="background: rgba(255, 255, 255, 0.5); border-radius: 12px; height: 55px;">
                                <div class="d-flex align-items-center gap-2 w-100">
                                    <div class="rounded-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background: rgba(166, 128, 131, 0.1);">
                                        <i class="fas fa-clock" style="color: #ce4a4a; font-size: 0.8rem;"></i>
                                    </div>
                                    <div>
                                        <small class="text-secondary d-block" style="font-size: 0.5rem;">Total Due</small>
                                        <span class="fw-bold" style="color: #ce4a4a; font-size: 0.9rem;">৳{{ number_format($totalDue) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="glass-card d-flex align-items-center p-2" style="background: rgba(255, 255, 255, 0.5); border-radius: 12px; height: 55px;">
                                <div class="d-flex align-items-center gap-2 w-100">
                                    <div class="rounded-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background: rgba(139, 92, 246, 0.1);">
                                        <i class="fas fa-chart-pie" style="color: #8b5cf6; font-size: 0.8rem;"></i>
                                    </div>
                                    <div>
                                        <small class="text-secondary d-block" style="font-size: 0.5rem;">Payment Rate</small>
                                        <span class="fw-bold" style="color: #8b5cf6; font-size: 0.9rem;">{{ number_format($paymentRate, 1) }}%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Last Payment Info - Compact -->
                    @if($lastPayment)
                    <div class="mb-3">
                        <small class="text-secondary d-block mb-1" style="font-size: 0.6rem;"><i class="fas fa-history me-1" style="color: #2563eb;"></i>Last Payment</small>
                        <div class="glass-card p-2" style="background: rgba(255, 255, 255, 0.5); border-radius: 12px;">
                            <div class="row g-1">
                                <div class="col-3">
                                    <small class="text-secondary d-block" style="font-size: 0.5rem;">Amount</small>
                                    <span class="fw-semibold" style="font-size: 0.75rem;">৳{{ number_format($lastPayment->amount) }}</span>
                                </div>
                                <div class="col-3">
                                    <small class="text-secondary d-block" style="font-size: 0.5rem;">Month</small>
                                    <span class="fw-semibold" style="font-size: 0.7rem;">{{ $lastPayment->month->name }}'{{ substr($lastPayment->month->year, 2) }}</span>
                                </div>
                                <div class="col-3">
                                    <small class="text-secondary d-block" style="font-size: 0.5rem;">Method</small>
                                    <span class="badge rounded-pill" style="background: rgba(37, 99, 235, 0.1); color: #2563eb; font-size: 0.55rem; padding: 2px 6px;">
                                        {{ ucfirst($lastPayment->payment_method) }}
                                    </span>
                                </div>
                                <div class="col-3">
                                    <small class="text-secondary d-block" style="font-size: 0.5rem;">Date</small>
                                    <span style="font-size: 0.65rem;">{{ $lastPayment->created_at->format('d M') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Transaction History Section -->
                    <div>
                        <small class="text-secondary d-block mb-1" style="font-size: 0.6rem;"><i class="fas fa-exchange-alt me-1" style="color: #2563eb;"></i>Recent Transactions</small>
                        <div class="glass-card p-2" style="background: rgba(255, 255, 255, 0.5); border-radius: 12px; max-height: 200px; overflow-y: auto;">
                            @forelse($transactions as $transaction)
                            <div class="d-flex justify-content-between align-items-center py-1 {{ !$loop->last ? 'mb-1 border-bottom' : '' }}" style="border-bottom-color: #edf2f7 !important;">
                                <div>
                                    <span class="fw-semibold" style="font-size: 0.7rem;">{{ $transaction->month->name }} {{ $transaction->month->year }}</span>
                                    <div class="d-flex gap-2">
                                        <span class="badge rounded-pill" style="background: rgba(37, 99, 235, 0.1); color: #2563eb; font-size: 0.55rem;">{{ ucfirst($transaction->payment_method) }}</span>
                                        <span class="badge rounded-pill" style="background: {{ $transaction->paid_status == 'paid' ? 'rgba(16, 185, 129, 0.1)' : 'rgba(239, 68, 68, 0.1)' }}; color: {{ $transaction->paid_status == 'paid' ? '#10b981' : '#ef4444' }}; font-size: 0.55rem;">
                                            <i class="fas fa-{{ $transaction->paid_status == 'paid' ? 'check-circle' : 'clock' }} me-1"></i>
                                            {{ substr($transaction->paid_status, 0, 1) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <span class="fw-bold" style="color: #2563eb; font-size: 0.8rem;">৳{{ number_format($transaction->amount) }}</span>
                                    <small class="text-secondary d-block" style="font-size: 0.5rem;">{{ $transaction->created_at->format('d M') }}</small>
                                </div>
                            </div>
                            @empty
                            <div class="text-center py-2">
                                <small class="text-secondary">No transactions found</small>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Footer with Quick Actions -->
                <div class="px-3 py-2" style="background: rgba(248, 250, 252, 0.9); border-top: 1px solid rgba(255, 255, 255, 0.8);">
                    <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center">
                        <div class="d-flex gap-1">
                            <a href="{{ route('transactions.create', ['donor_id' => $donor->id]) }}" class="btn btn-sm rounded-pill px-2 py-1" style="font-size: 0.65rem; background: #10b981; color: white;">
                                <i class="fas fa-plus me-1"></i>Add
                            </a>
                            <a href="{{ route('transactions.index', ['donor_id' => $donor->id]) }}" class="btn btn-sm rounded-pill px-2 py-1" style="font-size: 0.65rem; background: #3b82f6; color: white;">
                                <i class="fas fa-list me-1"></i>View
                            </a>
                        </div>
                        <form action="{{ route('donors.toggleStatus', $donor) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-sm rounded-pill px-2 py-1" style="font-size: 0.65rem; background: {{ $donor->status == 'active' ? '#f59e0b' : '#10b981' }}; color: white;">
                                <i class="fas fa-{{ $donor->status == 'active' ? 'ban' : 'check' }} me-1"></i>
                                {{ $donor->status == 'active' ? 'Deactivate' : 'Activate' }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Mobile Danger Zone (if needed) -->
            <div class="d-block d-md-none mt-2">
                <form action="{{ route('donors.destroy', $donor) }}" method="POST" onsubmit="return confirm('Delete this donor?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn w-100 rounded-3 py-2" style="font-size: 0.7rem; background: #ef4444; color: white;">
                        <i class="fas fa-trash me-1"></i>Delete Donor
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
/* Glass card effect */
.glass-card {
    transition: all 0.2s ease;
    border: 1px solid rgba(255, 255, 255, 0.8);
    backdrop-filter: blur(4px);
}

.glass-card:hover {
    transform: translateY(-1px);
    background: rgba(255, 255, 255, 0.9) !important;
}

/* Custom scrollbar */
[style*="overflow-y: auto"]::-webkit-scrollbar {
    width: 3px;
}
[style*="overflow-y: auto"]::-webkit-scrollbar-track {
    background: rgba(241, 245, 249, 0.5);
}
[style*="overflow-y: auto"]::-webkit-scrollbar-thumb {
    background: rgba(148, 163, 184, 0.5);
    border-radius: 3px;
}

/* Phone link hover */
a[href^="tel"] {
    transition: all 0.2s ease;
}
a[href^="tel"]:hover {
    color: #2563eb !important;
    transform: translateX(2px);
}

/* Mobile optimizations */
@media (max-width: 768px) {
    .container-fluid {
        padding-left: 0 !important;
        padding-right: 0 !important;
    }
    .card {
        border-radius: 0 !important;
    }
    a[href^="tel"] {
        padding: 4px 0;
        display: inline-block;
    }
}

/* Print styles */
@media print {
    .quick-action, .btn, .mobile-bottom-nav, .fab {
        display: none !important;
    }
}
</style>
@endsection