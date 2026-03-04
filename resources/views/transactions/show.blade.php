@extends('layouts.app')

@section('title', 'Transaction Details')
@section('page-title', 'Transaction Details')

@section('page-actions')
    <div class="d-flex gap-2">
        <a href="{{ route('transactions.index') }}" class="btn" style="background: #f1f5f9; color: #475569; border: none; border-radius: 30px; padding: 8px 18px; font-weight: 500; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 8px;">
            <i class="fas fa-arrow-left"></i> Back
        </a>
        <a href="{{ route('transactions.edit', $transaction) }}" class="btn" style="background: linear-gradient(145deg, #2563eb, #1d4ed8); color: white; border: none; border-radius: 30px; padding: 8px 18px; font-weight: 500; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 8px 16px -6px rgba(37,99,235,0.3);">
            <i class="fas fa-edit"></i> Edit
        </a>
    </div>
@endsection

@section('content')
<div class="container-fluid px-2 px-sm-3">
    <div class="row justify-content-center g-3">
        <div class="">
            <!-- Status Banner - Modern Alert -->
            <div class="mb-4 p-4" style="background: {{ $transaction->paid_status == 'paid' ? 'linear-gradient(145deg, #10b981, #059669)' : 'linear-gradient(145deg, #ef4444, #dc2626)' }}; border-radius: 24px; box-shadow: 0 20px 30px -10px {{ $transaction->paid_status == 'paid' ? 'rgba(16,185,129,0.3)' : 'rgba(239,68,68,0.3)' }};">
                <div class="d-flex align-items-center gap-4">
                    <div style="width: 64px; height: 64px; background: rgba(255,255,255,0.2); border-radius: 20px; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(8px);">
                        @if($transaction->paid_status == 'paid')
                            <i class="fas fa-check-circle fa-2x text-white"></i>
                        @else
                            <i class="fas fa-times-circle fa-2x text-white"></i>
                        @endif
                    </div>
                    <div class="text-white">
                        <h4 class="mb-1" style="font-weight: 600; font-family: 'Space Grotesk', sans-serif;">
                            @if($transaction->paid_status == 'paid')
                                Payment Received
                            @else
                                Payment Pending
                            @endif
                        </h4>
                        <p class="mb-0 opacity-90" style="font-size: 0.95rem;">
                            @if($transaction->paid_status == 'paid')
                                This transaction has been marked as paid on {{ $transaction->updated_at->format('d M Y') }}
                            @else
                                This transaction is currently unpaid
                            @endif
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Transaction Details Card -->
            <div class="content-card mb-4" style="border-radius: 24px; overflow: hidden; background: white; box-shadow: 0 20px 40px -12px rgba(0,20,40,0.12); border: 1px solid rgba(226, 232, 240, 0.6);">
                <div class="px-4 py-3" style="background: linear-gradient(145deg, #fafcff, #ffffff); border-bottom: 1px solid rgba(226, 232, 240, 0.6);">
                    <h5 class="mb-0" style="font-family: 'Space Grotesk', sans-serif; font-weight: 600; color: #0b1e33; font-size: 1rem;">
                        <i class="fas fa-exchange-alt me-2" style="color: #2563eb;"></i>
                        Transaction Information
                    </h5>
                </div>
                
                <div class="p-4">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="d-flex flex-column gap-3">
                                <!-- Transaction ID -->
                                <div>
                                    <small class="text-muted d-block mb-1" style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.4px;">Transaction ID</small>
                                    <div class="d-flex align-items-center">
                                        <div style="width: 32px; height: 32px; background: #eef2ff; border-radius: 10px; display: flex; align-items: center; justify-content: center; margin-right: 10px;">
                                            <i class="fas fa-hashtag" style="color: #2563eb; font-size: 0.9rem;"></i>
                                        </div>
                                        <span style="font-weight: 600; font-size: 1.1rem;">#{{ $transaction->id }}</span>
                                    </div>
                                </div>
                                
                                <!-- Donor -->
                                <div>
                                    <small class="text-muted d-block mb-1" style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.4px;">Donor</small>
                                    <div class="d-flex align-items-center">
                                        <div style="width: 32px; height: 32px; background: #eef2ff; border-radius: 10px; display: flex; align-items: center; justify-content: center; margin-right: 10px;">
                                            <i class="fas fa-user" style="color: #2563eb; font-size: 0.9rem;"></i>
                                        </div>
                                        <a href="{{ route('donors.show', $transaction->donor) }}" class="text-decoration-none" style="color: #2563eb; font-weight: 500;">
                                            {{ $transaction->donor->name }}
                                        </a>
                                    </div>
                                </div>
                                
                                <!-- Month -->
                                <div>
                                    <small class="text-muted d-block mb-1" style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.4px;">Month</small>
                                    <div class="d-flex align-items-center">
                                        <div style="width: 32px; height: 32px; background: #eef2ff; border-radius: 10px; display: flex; align-items: center; justify-content: center; margin-right: 10px;">
                                            <i class="fas fa-calendar-alt" style="color: #2563eb; font-size: 0.9rem;"></i>
                                        </div>
                                        <span>{{ $transaction->month->name }} {{ $transaction->month->year }}</span>
                                    </div>
                                </div>
                                
                                <!-- Amount -->
                                <div>
                                    <small class="text-muted d-block mb-1" style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.4px;">Amount</small>
                                    <div class="d-flex align-items-center">
                                        <div style="width: 32px; height: 32px; background: #eef2ff; border-radius: 10px; display: flex; align-items: center; justify-content: center; margin-right: 10px;">
                                            <i class="fas fa-coins" style="color: #2563eb; font-size: 0.9rem;"></i>
                                        </div>
                                        <span style="font-weight: 700; font-size: 1.3rem; color: #2563eb;">৳{{ number_format($transaction->amount, 2) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="d-flex flex-column gap-3">
                                <!-- Payment Method -->
                                <div>
                                    <small class="text-muted d-block mb-1" style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.4px;">Payment Method</small>
                                    <div class="d-flex align-items-center">
                                        <div style="width: 32px; height: 32px; background: #eef2ff; border-radius: 10px; display: flex; align-items: center; justify-content: center; margin-right: 10px;">
                                            @if($transaction->payment_method == 'bkash')
                                                <i class="fas fa-mobile-alt" style="color: #2563eb; font-size: 0.9rem;"></i>
                                            @elseif($transaction->payment_method == 'nagad')
                                                <i class="fas fa-mobile" style="color: #2563eb; font-size: 0.9rem;"></i>
                                            @else
                                                <i class="fas fa-money-bill" style="color: #2563eb; font-size: 0.9rem;"></i>
                                            @endif
                                        </div>
                                        <span class="badge" style="background: #eef2ff; color: #4338ca; padding: 6px 12px; border-radius: 30px; font-weight: 500;">
                                            {{ ucfirst($transaction->payment_method) }}
                                        </span>
                                    </div>
                                </div>
                                
                                <!-- Status -->
                                <div>
                                    <small class="text-muted d-block mb-1" style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.4px;">Status</small>
                                    <div class="d-flex align-items-center">
                                        <div style="width: 32px; height: 32px; background: #eef2ff; border-radius: 10px; display: flex; align-items: center; justify-content: center; margin-right: 10px;">
                                            @if($transaction->paid_status == 'paid')
                                                <i class="fas fa-check-circle" style="color: #10b981; font-size: 0.9rem;"></i>
                                            @else
                                                <i class="fas fa-clock" style="color: #ef4444; font-size: 0.9rem;"></i>
                                            @endif
                                        </div>
                                        @if($transaction->paid_status == 'paid')
                                            <span class="badge" style="background: #dcfce7; color: #166534; padding: 6px 12px; border-radius: 30px; font-weight: 500;">
                                                <i class="fas fa-check-circle me-1"></i> Paid
                                            </span>
                                        @else
                                            <span class="badge" style="background: #fee2e2; color: #991b1b; padding: 6px 12px; border-radius: 30px; font-weight: 500;">
                                                <i class="fas fa-clock me-1"></i> Unpaid
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                
                                <!-- Recorded By -->
                                <div>
                                    <small class="text-muted d-block mb-1" style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.4px;">Recorded By</small>
                                    <div class="d-flex align-items-center">
                                        <div style="width: 32px; height: 32px; background: #eef2ff; border-radius: 10px; display: flex; align-items: center; justify-content: center; margin-right: 10px;">
                                            <i class="fas fa-user-tie" style="color: #2563eb; font-size: 0.9rem;"></i>
                                        </div>
                                        <span>{{ $transaction->user->name }}</span>
                                    </div>
                                </div>
                                
                                <!-- Date -->
                                <div>
                                    <small class="text-muted d-block mb-1" style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.4px;">Transaction Date</small>
                                    <div class="d-flex align-items-center">
                                        <div style="width: 32px; height: 32px; background: #eef2ff; border-radius: 10px; display: flex; align-items: center; justify-content: center; margin-right: 10px;">
                                            <i class="fas fa-calendar-check" style="color: #2563eb; font-size: 0.9rem;"></i>
                                        </div>
                                        <span>{{ $transaction->created_at->format('d F Y, h:i A') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Donor Information Card -->
            <div class="content-card mb-4" style="border-radius: 24px; overflow: hidden; background: white; box-shadow: 0 20px 40px -12px rgba(0,20,40,0.12); border: 1px solid rgba(226, 232, 240, 0.6);">
                <div class="px-4 py-3" style="background: linear-gradient(145deg, #fafcff, #ffffff); border-bottom: 1px solid rgba(226, 232, 240, 0.6);">
                    <h5 class="mb-0" style="font-family: 'Space Grotesk', sans-serif; font-weight: 600; color: #0b1e33; font-size: 1rem;">
                        <i class="fas fa-user me-2" style="color: #2563eb;"></i>
                        Donor Information
                    </h5>
                </div>
                
                <div class="p-4">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-3">
                                <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #667eea, #764ba2); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-right: 12px;">
                                    <i class="fas fa-user text-white"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block" style="font-size: 0.7rem;">Name</small>
                                    <span style="font-weight: 500;">{{ $transaction->donor->name }}</span>
                                </div>
                            </div>
                            
                            <div class="d-flex align-items-center mb-3">
                                <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #11998e, #38ef7d); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-right: 12px;">
                                    <i class="fas fa-phone text-white"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block" style="font-size: 0.7rem;">Phone</small>
                                    <span style="font-weight: 500;">{{ $transaction->donor->phone ?: 'Not provided' }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-3">
                                <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #f093fb, #f5576c); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-right: 12px;">
                                    <i class="fas fa-map-marker-alt text-white"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block" style="font-size: 0.7rem;">Address</small>
                                    <span style="font-weight: 500;">{{ $transaction->donor->address ?: 'Not provided' }}</span>
                                </div>
                            </div>
                            
                            <div class="d-flex align-items-center">
                                <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #4facfe, #00f2fe); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-right: 12px;">
                                    <i class="fas fa-hand-holding-heart text-white"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block" style="font-size: 0.7rem;">Monthly Commitment</small>
                                    <span style="font-weight: 700; color: #2563eb;">৳{{ number_format($transaction->donor->monthly_amount, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="content-card" style="border-radius: 24px; overflow: hidden; background: white; border: 1px solid rgba(226, 232, 240, 0.6);">
                <div class="p-4">
                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3">
                        <div class="w-100 w-sm-auto">
                            @if($transaction->paid_status == 'unpaid')
                                <form action="{{ route('transactions.markAsPaid', $transaction) }}" method="POST" class="d-inline w-100 w-sm-auto">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn w-100" style="background: linear-gradient(145deg, #10b981, #059669); color: white; border: none; border-radius: 40px; padding: 10px 24px; font-weight: 500; font-size: 0.9rem; display: inline-flex; align-items: center; justify-content: center; gap: 8px; box-shadow: 0 8px 16px -6px rgba(16,185,129,0.3);" onclick="return confirm('Mark this transaction as paid?')">
                                        <i class="fas fa-check-circle"></i> Mark as Paid
                                    </button>
                                </form>
                            @endif
                        </div>
                        <div class="w-100 w-sm-auto">
                            <form action="{{ route('transactions.destroy', $transaction) }}" method="POST" class="d-inline w-100 w-sm-auto">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn w-100" style="background: #fee2e2; color: #dc2626; border: none; border-radius: 40px; padding: 10px 24px; font-weight: 500; font-size: 0.9rem; display: inline-flex; align-items: center; justify-content: center; gap: 8px;" onclick="return confirm('Are you sure you want to delete this transaction?')">
                                    <i class="fas fa-trash"></i> Delete Transaction
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Mobile optimizations */
@media (max-width: 768px) {
    .content-card {
        border-radius: 20px !important;
    }
    
    .p-4 {
        padding: 16px !important;
    }
    
    .btn {
        min-height: 44px;
    }
    
    /* Better spacing for mobile */
    .d-flex.align-items-center {
        flex-wrap: wrap;
    }
    
    /* Status banner adjustments */
    .mb-4.p-4 {
        padding: 20px !important;
    }
    
    .gap-4 {
        gap: 12px !important;
    }
    
    .fa-2x {
        font-size: 1.3rem !important;
    }
}

/* Hover effects */
.btn:hover {
    transform: translateY(-2px);
    transition: all 0.2s;
}

.btn:active {
    transform: translateY(0);
}
</style>
@endsection