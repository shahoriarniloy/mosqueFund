@forelse($donations as $donation)
    <div class="mobile-card mb-3">
        <div class="d-flex justify-content-between align-items-start mb-2">
            <div class="d-flex align-items-center">
                @if($donation->donor)
                    <div class="donor-avatar me-2">
                        {{ substr($donation->donor->name, 0, 1) }}
                    </div>
                    <div>
                        <h6 class="mb-0">{{ $donation->donor->name }}</h6>
                        <small class="text-muted">{{ $donation->donor->phone }}</small>
                    </div>
                @elseif($donation->contributor)
                    <div class="contributor-avatar me-2">
                        {{ substr($donation->contributor->name, 0, 1) }}
                    </div>
                    <div>
                        <h6 class="mb-0">{{ $donation->contributor->name }}</h6>
                        <small class="text-muted">{{ $donation->contributor->phone }}</small>
                    </div>
                @else
                    <div class="unknown-avatar me-2">
                        <i class="fas fa-user"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 text-muted">Unknown</h6>
                    </div>
                @endif
            </div>
            <span class="badge {{ $donation->paid_status == 'paid' ? 'bg-success' : 'bg-warning' }}">
                {{ ucfirst($donation->paid_status) }}
            </span>
        </div>
        
        <div class="row g-2 mb-2">
            <div class="col-6">
                <small class="text-muted d-block">Type</small>
                @if($donation->donor)
                    <span class="badge bg-primary bg-opacity-10 text-primary">
                        <i class="fas fa-calendar-check me-1"></i> Monthly
                    </span>
                @elseif($donation->contributor)
                    <span class="badge bg-info bg-opacity-10 text-info">
                        <i class="fas fa-random me-1"></i> Random
                    </span>
                @endif
            </div>
            <div class="col-6">
                <small class="text-muted d-block">Amount</small>
                <strong class="text-success">৳{{ number_format($donation->amount, 2) }}</strong>
            </div>
        </div>
        
        <div class="row g-2 mb-2">
            <div class="col-6">
                <small class="text-muted d-block">Payment Method</small>
                <span>{{ ucfirst($donation->payment_method) }}</span>
            </div>
            <div class="col-6">
                <small class="text-muted d-block">Date</small>
                <span>{{ $donation->created_at->format('M d, Y') }}</span>
            </div>
        </div>
        
        <div class="d-flex justify-content-end gap-2 mt-2 pt-2 border-top">
            <a href="{{ route('donations.show', $donation) }}" class="btn btn-sm btn-light">
                <i class="fas fa-eye"></i> View
            </a>
            <a href="{{ route('donations.edit', $donation) }}" class="btn btn-sm btn-light">
                <i class="fas fa-edit"></i> Edit
            </a>
        </div>
    </div>
@empty
    <div class="text-center py-5">
        <i class="fas fa-hand-holding-heart fa-3x text-muted mb-3"></i>
        <h5>No Donations Found</h5>
        <p class="text-muted">Get started by recording your first donation.</p>
        <a href="{{ route('donations.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Add Donation
        </a>
    </div>
@endforelse

<style>
    .mobile-card {
        background: white;
        border-radius: 16px;
        padding: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 8px rgba(0,0,0,0.02);
    }
    
    .donor-avatar, .contributor-avatar, .unknown-avatar {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
    }
    
    .donor-avatar {
        background: rgba(37, 99, 235, 0.1);
        color: #2563eb;
    }
    
    .contributor-avatar {
        background: rgba(6, 182, 212, 0.1);
        color: #0891b2;
    }
    
    .unknown-avatar {
        background: #f1f5f9;
        color: #64748b;
    }
</style>