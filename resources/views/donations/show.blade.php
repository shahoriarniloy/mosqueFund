@extends('layouts.app')

@section('title', 'Donation Details')
@section('page-title', 'Donation Details')
@section('page-subtitle', 'View donation information')

@section('quick-actions')
    <a href="{{ route('donations.index') }}" class="quick-action">
        <i class="fas fa-arrow-left"></i> Back
    </a>
    <a href="{{ route('donations.edit', $donation) }}" class="quick-action">
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
                
                <!-- Status Header - Ultra compact -->
                <div class="px-3 py-2" style="background: {{ $donation->paid_status == 'paid' ? 'linear-gradient(135deg, #10b981, #059669)' : 'linear-gradient(135deg, #ef4444, #dc2626)' }}; height: 65px;">
                    <div class="d-flex justify-content-between align-items-center h-100">
                        <div>
                            <h6 class="mb-0 text-white fw-semibold" style="font-size: 0.9rem;">Donation #{{ $donation->id }}</h6>
                            <span class="text-white-50 small" style="font-size: 0.6rem;">{{ $donation->created_at->format('M d, Y') }}</span>
                        </div>
                        <span class="badge bg-white rounded-pill px-3 py-2 fw-semibold shadow-sm" style="color: {{ $donation->paid_status == 'paid' ? '#059669' : '#dc2626' }}; min-width: 85px; font-size: 0.65rem;">
                            <i class="fas fa-{{ $donation->paid_status == 'paid' ? 'check-circle' : 'hourglass' }} me-1"></i>
                            {{ strtoupper(substr($donation->paid_status, 0, 4)) }}
                        </span>
                    </div>
                </div>

                <!-- Body - Tighter padding -->
                <div class="p-3" style="background: rgba(255, 255, 255, 0.5);">
                    
                    <!-- Donor Info - Single line compact -->
                    <div class="glass-card p-2 mb-3 d-flex align-items-center" style="background: rgba(255, 255, 255, 0.7); border-radius: 14px; height: 60px;">
                        <div class="d-flex align-items-center gap-2 w-100">
                            <div class="rounded-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; background: linear-gradient(135deg, #667eea, #764ba2);">
                                <span class="text-white fw-semibold" style="font-size: 0.9rem;">{{ substr($donation->name, 0, 1) }}</span>
                            </div>
                            <div class="flex-grow-1" style="min-width: 0;">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="fw-semibold text-truncate" style="font-size: 0.85rem; max-width: 120px;">{{ $donation->name }}</span>
                                    <span class="badge rounded-pill px-2" style="font-size: 0.55rem; background: {{ $donation->donor_id ? 'rgba(37, 99, 235, 0.1)' : 'rgba(100, 116, 139, 0.1)' }}; color: {{ $donation->donor_id ? '#2563eb' : '#475569' }};">
                                        <i class="fas fa-{{ $donation->donor_id ? 'user-check' : 'user-plus' }} me-1"></i>
                                        {{ $donation->donor_id ? 'Ext' : 'New' }}
                                    </span>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    @if($donation->phone || ($donation->donor_id && $donation->donor))
                                        <small class="d-flex align-items-center text-secondary" style="font-size: 0.6rem;">
                                            <i class="fas fa-phone-alt me-1" style="color: #2563eb; font-size: 0.5rem;"></i>
                                            <span class="text-truncate" style="max-width: 90px;">
                                                {{ $donation->donor_id && $donation->donor ? $donation->donor->phone : $donation->phone }}
                                            </span>
                                        </small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Stats Grid - 2x2 more compressed -->
                    <div class="row g-1 mb-3">
                        <div class="col-6">
                            <div class="glass-card d-flex align-items-center p-2" style="background: rgba(255, 255, 255, 0.5); border-radius: 12px; height: 55px;">
                                <div class="d-flex align-items-center gap-2 w-100">
                                    <div class="rounded-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background: rgba(37, 99, 235, 0.1);">
                                        <i class="fas fa-coins" style="color: #2563eb; font-size: 0.8rem;"></i>
                                    </div>
                                    <div>
                                        <small class="text-secondary d-block" style="font-size: 0.5rem;">Amount</small>
                                        <span class="fw-bold" style="color: #2563eb; font-size: 0.9rem;">৳{{ number_format($donation->amount) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="glass-card d-flex align-items-center p-2" style="background: rgba(255, 255, 255, 0.5); border-radius: 12px; height: 55px;">
                                <div class="d-flex align-items-center gap-2 w-100">
                                    <div class="rounded-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background: rgba(226, 19, 110, 0.1);">
                                        <i class="fas fa-{{ $donation->payment_method == 'bkash' ? 'mobile-alt' : ($donation->payment_method == 'nagad' ? 'mobile' : 'money-bill') }}" 
                                           style="color: {{ $donation->payment_method == 'bkash' ? '#e2136e' : ($donation->payment_method == 'nagad' ? '#f5841f' : '#10b981') }}; font-size: 0.8rem;"></i>
                                    </div>
                                    <div>
                                        <small class="text-secondary d-block" style="font-size: 0.5rem;">Method</small>
                                        <span class="fw-semibold" style="font-size: 0.75rem;">{{ ucfirst($donation->payment_method) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="glass-card d-flex align-items-center p-2" style="background: rgba(255, 255, 255, 0.5); border-radius: 12px; height: 55px;">
                                <div class="d-flex align-items-center gap-2 w-100">
                                    <div class="rounded-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background: {{ $donation->paid_status == 'paid' ? 'rgba(16, 185, 129, 0.1)' : 'rgba(239, 68, 68, 0.1)' }};">
                                        <i class="fas fa-{{ $donation->paid_status == 'paid' ? 'check-circle' : 'clock' }}" style="color: {{ $donation->paid_status == 'paid' ? '#10b981' : '#ef4444' }}; font-size: 0.8rem;"></i>
                                    </div>
                                    <div>
                                        <small class="text-secondary d-block" style="font-size: 0.5rem;">Status</small>
                                        <span class="fw-semibold" style="font-size: 0.7rem; color: {{ $donation->paid_status == 'paid' ? '#10b981' : '#ef4444' }};">{{ ucfirst($donation->paid_status) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="glass-card d-flex align-items-center p-2" style="background: rgba(255, 255, 255, 0.5); border-radius: 12px; height: 55px;">
                                <div class="d-flex align-items-center gap-2 w-100">
                                    <div class="rounded-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background: rgba(100, 116, 139, 0.1);">
                                        <i class="fas fa-user" style="color: #64748b; font-size: 0.8rem;"></i>
                                    </div>
                                    <div>
                                        <small class="text-secondary d-block" style="font-size: 0.5rem;">By</small>
                                        <span class="fw-semibold text-secondary text-truncate" style="max-width: 70px; font-size: 0.7rem;">{{ $donation->user->name }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Notes - Compact -->
                    @if($donation->notes)
                    <div class="mb-3">
                        <small class="text-secondary d-block mb-1" style="font-size: 0.6rem;"><i class="fas fa-sticky-note me-1" style="color: #2563eb;"></i>Notes</small>
                        <div class="glass-card p-2" style="background: rgba(255, 255, 255, 0.5); border-radius: 12px; height: 50px; overflow-y: auto;">
                            <p class="mb-0 text-secondary" style="font-size: 0.7rem;">{{ $donation->notes }}</p>
                        </div>
                    </div>
                    @endif

                    <!-- Timeline - Ultra compact -->
                    <div>
                        <small class="text-secondary d-block mb-1" style="font-size: 0.6rem;"><i class="fas fa-history me-1" style="color: #2563eb;"></i>Timeline</small>
                        <div class="glass-card p-2" style="background: rgba(255, 255, 255, 0.5); border-radius: 12px; height: {{ $donation->paid_status == 'paid' ? '90px' : '55px' }}; overflow-y: auto;">
                            <div class="d-flex gap-2 mb-2">
                                <div class="rounded-2 d-flex align-items-center justify-content-center" style="width: 24px; height: 24px; background: rgba(37, 99, 235, 0.1);">
                                    <i class="fas fa-plus-circle" style="color: #2563eb; font-size: 0.6rem;"></i>
                                </div>
                                <div>
                                    <span class="fw-semibold d-block" style="font-size: 0.65rem;">Created</span>
                                    <small class="text-secondary" style="font-size: 0.55rem;">{{ $donation->created_at->format('d M, h:i A') }}</small>
                                </div>
                            </div>
                            
                            @if($donation->paid_status == 'paid')
                            <div class="d-flex gap-2">
                                <div class="rounded-2 d-flex align-items-center justify-content-center" style="width: 24px; height: 24px; background: rgba(16, 185, 129, 0.1);">
                                    <i class="fas fa-check-circle" style="color: #10b981; font-size: 0.6rem;"></i>
                                </div>
                                <div>
                                    <span class="fw-semibold d-block" style="font-size: 0.65rem;">Paid</span>
                                    <small class="text-secondary" style="font-size: 0.55rem;">{{ $donation->updated_at->format('d M, h:i A') }}</small>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Footer - Ultra compact -->
                <div class="px-3 py-2 d-flex justify-content-between align-items-center" style="background: rgba(248, 250, 252, 0.9); border-top: 1px solid rgba(255, 255, 255, 0.8); height: 45px;">
                    <small class="text-secondary" style="font-size: 0.55rem;">{{ $donation->created_at->diffForHumans() }}</small>
                    <div class="d-flex gap-1">
                        <a href="{{ route('donations.index') }}" class="btn btn-sm rounded-pill px-2 py-1" style="font-size: 0.6rem; background: white; border: 1px solid #e2e8f0;">
                            <i class="fas fa-arrow-left me-1"></i>Back
                        </a>
                        <a href="{{ route('donations.edit', $donation) }}" class="btn btn-sm rounded-pill px-2 py-1" style="font-size: 0.6rem; background: #2563eb; color: white; border: none;">
                            <i class="fas fa-edit me-1"></i>Edit
                        </a>
                    </div>
                </div>
            </div>

            <!-- Mobile Actions - More compact -->
            @if($donation->paid_status == 'unpaid')
            <div class="d-flex d-md-none gap-1 mt-2">
                <form action="{{ route('donations.markAsPaid', $donation) }}" method="POST" class="flex-fill">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn w-100 rounded-3 py-2" style="font-size: 0.7rem; background: #10b981; color: white;" onclick="return confirm('Mark as paid?')">
                        <i class="fas fa-check-circle me-1"></i>Paid
                    </button>
                </form>
                <form action="{{ route('donations.destroy', $donation) }}" method="POST" class="flex-fill">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn w-100 rounded-3 py-2" style="font-size: 0.7rem; background: #ef4444; color: white;" onclick="return confirm('Delete?')">
                        <i class="fas fa-trash me-1"></i>Delete
                    </button>
                </form>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
/* Glass effect */
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

/* Mobile optimizations */
@media (max-width: 768px) {
    .container-fluid {
        padding-left: 0 !important;
        padding-right: 0 !important;
    }
    .card {
        border-radius: 0 !important;
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