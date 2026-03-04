@extends('layouts.app')

@section('title', 'Donations Management')

@section('content')
<div class="container-fluid px-0 px-sm-3">
    <div class="row g-0 g-sm-3">
        <div class="col-12">
            <!-- Modern Card -->
            <div class="" style="border-radius: 0; overflow: hidden;">
                <!-- Card Header -->
                <div class="px-3 px-sm-4 py-2 py-sm-3" style="background: linear-gradient(145deg, #fafcff, #ffffff); border-bottom: 1px solid rgba(226, 232, 240, 0.6);">
                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 gap-sm-3">
                        <h5 class="mb-0" style="font-family: 'Space Grotesk', sans-serif; font-weight: 600; color: #0b1e33; font-size: 1rem sm:font-size-1.3rem;">
                            <i class="fas fa-hand-holding-heart me-2" style="color: #2563eb;"></i>
                            Donations
                        </h5>
                        <a href="{{ route('donations.create') }}" class="btn w-100 w-sm-auto" style="background: linear-gradient(145deg, #2563eb, #1d4ed8); color: white; border: none; border-radius: 40px; padding: 8px 18px; font-weight: 500; font-size: 0.9rem; box-shadow: 0 10px 20px -8px rgba(37,99,235,0.4);">
                            <i class="fas fa-plus me-2"></i>Add Donation
                        </a>
                    </div>
                </div>

                <div class="p-2 p-sm-4">
                    <!-- Statistics Cards - Modern gradient stats -->
                    <div class="row g-1 g-sm-3 mb-3 mb-sm-4">
                        <!-- Total Amount -->
                        <div class="col-6 col-md-3">
                            <div class="stat-compact p-2 p-sm-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 12px; box-shadow: 0 8px 16px -8px rgba(102,126,234,0.3);">
                                <div class="d-flex align-items-center gap-1 gap-sm-3">
                                    <div style="width: 32px; height: 32px; background: rgba(255,255,255,0.2); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-coins" style="font-size: 0.9rem;"></i>
                                    </div>
                                    <div>
                                        <p class="mb-0 text-white opacity-75" style="font-size: 0.55rem; line-height: 1.2;">Total</p>
                                        <h6 class="mb-0 text-white" style="font-size: 0.9rem; font-weight: 600; line-height: 1.2;">৳{{ number_format($totalAmount ?? 0) }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Paid -->
                        <div class="col-6 col-md-3">
                            <div class="stat-compact p-2 p-sm-3" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); border-radius: 12px; box-shadow: 0 8px 16px -8px rgba(17,153,142,0.3);">
                                <div class="d-flex align-items-center gap-1 gap-sm-3">
                                    <div style="width: 32px; height: 32px; background: rgba(255,255,255,0.2); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-check-circle" style="font-size: 0.9rem;"></i>
                                    </div>
                                    <div>
                                        <p class="mb-0 text-white opacity-75" style="font-size: 0.55rem;">Paid</p>
                                        <h6 class="mb-0 text-white" style="font-size: 0.9rem; font-weight: 600;">{{ $paidCount ?? 0 }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Unpaid -->
                        <div class="col-6 col-md-3">
                            <div class="stat-compact p-2 p-sm-3" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border-radius: 12px; box-shadow: 0 8px 16px -8px rgba(240,147,251,0.3);">
                                <div class="d-flex align-items-center gap-1 gap-sm-3">
                                    <div style="width: 32px; height: 32px; background: rgba(255,255,255,0.2); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-clock" style="font-size: 0.9rem;"></i>
                                    </div>
                                    <div>
                                        <p class="mb-0 text-white opacity-75" style="font-size: 0.55rem;">Unpaid</p>
                                        <h6 class="mb-0 text-white" style="font-size: 0.9rem; font-weight: 600;">{{ $unpaidCount ?? 0 }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- This Month -->
                        <div class="col-6 col-md-3">
                            <div class="stat-compact p-2 p-sm-3" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); border-radius: 12px; box-shadow: 0 8px 16px -8px rgba(79,172,254,0.3);">
                                <div class="d-flex align-items-center gap-1 gap-sm-3">
                                    <div style="width: 32px; height: 32px; background: rgba(255,255,255,0.2); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-calendar-alt" style="font-size: 0.9rem;"></i>
                                    </div>
                                    <div>
                                        <p class="mb-0 text-white opacity-75" style="font-size: 0.55rem;">This Month</p>
                                        <h6 class="mb-0 text-white" style="font-size: 0.9rem; font-weight: 600;">৳{{ number_format($thisMonthAmount ?? 0) }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filter Section - Modern collapsible -->
                    <div class="mb-3 mb-sm-4" style="background: #f8fafd; border-radius: 16px; border: 1px solid #eef2f8; overflow: hidden;">
                        <div class="p-2 p-sm-3" style="background: white; border-bottom: 1px solid #eef2f8;">
                            <h6 class="mb-0">
                                <a class="text-decoration-none d-flex align-items-center" data-bs-toggle="collapse" href="#filterSection" role="button" style="color: #1e293b;">
                                    <i class="fas fa-filter me-2" style="color: #2563eb; font-size: 0.8rem;"></i>
                                    <span style="font-weight: 600; font-size: 0.85rem;">Filter Donations</span>
                                    <i class="fas fa-chevron-down ms-auto" style="font-size: 0.7rem; color: #94a3b8;"></i>
                                </a>
                            </h6>
                        </div>
                        <div class="collapse" id="filterSection">
                            <div class="p-2 p-sm-4">
                                <form action="{{ route('donations.index') }}" method="GET">
                                    <div class="row g-1 g-sm-3">
                                        <div class="col-12 col-md-3">
                                            <input type="text" name="search" class="form-control form-control-sm" placeholder="Search name or phone" value="{{ request('search') }}" style="border-radius: 20px; padding: 6px 10px; border: 1px solid #e2e8f0; font-size: 0.8rem;">
                                        </div>
                                        <div class="col-12 col-md-2">
                                            <select name="status" class="form-select form-select-sm" style="border-radius: 20px; padding: 6px 10px; border: 1px solid #e2e8f0; font-size: 0.8rem;">
                                                <option value="">All Status</option>
                                                <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                                                <option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                                            </select>
                                        </div>
                                        <div class="col-12 col-md-2">
                                            <select name="payment_method" class="form-select form-select-sm" style="border-radius: 20px; padding: 6px 10px; border: 1px solid #e2e8f0; font-size: 0.8rem;">
                                                <option value="">All Methods</option>
                                                <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                                                <option value="bkash" {{ request('payment_method') == 'bkash' ? 'selected' : '' }}>bKash</option>
                                                <option value="nagad" {{ request('payment_method') == 'nagad' ? 'selected' : '' }}>Nagad</option>
                                            </select>
                                        </div>
                                        {{-- <div class="col-12 col-md-2">
                                            <select name="donor_type" class="form-select form-select-sm" style="border-radius: 20px; padding: 6px 10px; border: 1px solid #e2e8f0; font-size: 0.8rem;">
                                                <option value="">All Donors</option>
                                                <option value="existing" {{ request('donor_type') == 'existing' ? 'selected' : '' }}>Existing</option>
                                                <option value="new" {{ request('donor_type') == 'new' ? 'selected' : '' }}>New</option>
                                            </select>
                                        </div> --}}
                                        <div class="col-12 col-md-3">
                                            <div class="d-flex flex-column flex-sm-row gap-1">
                                                <input type="date" name="from_date" class="form-control form-control-sm" value="{{ request('from_date') }}" placeholder="From" style="border-radius: 20px; padding: 6px 10px; border: 1px solid #e2e8f0; font-size: 0.8rem;">
                                                <input type="date" name="to_date" class="form-control form-control-sm" value="{{ request('to_date') }}" placeholder="To" style="border-radius: 20px; padding: 6px 10px; border: 1px solid #e2e8f0; font-size: 0.8rem;">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column flex-sm-row justify-content-end gap-1 mt-2 mt-sm-3">
                                        <button type="submit" class="btn w-100 w-sm-auto" style="background: linear-gradient(145deg, #2563eb, #1d4ed8); color: white; border: none; border-radius: 20px; padding: 6px 16px; font-weight: 500; font-size: 0.8rem;">
                                            <i class="fas fa-search me-1"></i>Apply
                                        </button>
                                        <a href="{{ route('donations.index') }}" class="btn w-100 w-sm-auto" style="background: #f1f5f9; color: #475569; border: none; border-radius: 20px; padding: 6px 16px; font-weight: 500; font-size: 0.8rem;">
                                            <i class="fas fa-redo me-1"></i>Reset
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Mobile Donation Cards -->
                    <div class="d-block d-md-none">
                        @forelse($donations as $donation)
                        <div class="mobile-donation-card mb-2 p-2" style="background: white; border-radius: 16px; box-shadow: 0 2px 8px -2px rgba(0,0,0,0.03); border: 1px solid #edf2f7;">
                            <div class="d-flex justify-content-between align-items-start mb-1">
                                <div>
                                    <span class="fw-bold" style="color: #2563eb; font-size: 0.8rem;">#{{ $donation->id }}</span>
                                    <div class="mt-0">
                                        <span style="color: #1e293b; font-size: 0.9rem; font-weight: 500;">{{ $donation->name }}</span>
                                    </div>
                                </div>
                                <span class="badge" style="background: {{ $donation->paid_status == 'paid' ? '#dcfce7' : '#fee2e2' }}; color: {{ $donation->paid_status == 'paid' ? '#166534' : '#991b1b' }}; padding: 4px 8px; border-radius: 20px; font-size: 0.6rem;">
                                    <i class="fas fa-{{ $donation->paid_status == 'paid' ? 'check-circle' : 'clock' }} me-1"></i>
                                    {{ substr($donation->paid_status, 0, 1) }}
                                </span>
                            </div>
                            
                            <div class="row g-1 mb-1">
                                <div class="col-6">
                                    <small class="text-muted d-block" style="font-size: 0.6rem;">Phone</small>
                                    <span style="font-size: 0.8rem;">{{ $donation->phone ?? '—' }}</span>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted d-block" style="font-size: 0.6rem;">Amount</small>
                                    <span style="font-size: 0.9rem; font-weight: 600;">৳{{ number_format($donation->amount) }}</span>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted d-block" style="font-size: 0.6rem;">Method</small>
                                    <span class="badge" style="background: #eef2ff; color: #4338ca; padding: 3px 6px; border-radius: 16px; font-size: 0.6rem;">
                                        {{ ucfirst($donation->payment_method) }}
                                    </span>
                                </div>
                                {{-- <div class="col-6">
                                    <small class="text-muted d-block" style="font-size: 0.6rem;">Type</small>
                                    <span class="badge" style="background: {{ $donation->donor_id ? '#dbeafe' : '#f1f5f9' }}; color: {{ $donation->donor_id ? '#1e40af' : '#475569' }}; padding: 3px 6px; border-radius: 16px; font-size: 0.6rem;">
                                        {{ $donation->donor_id ? 'Existing' : 'New' }}
                                    </span>
                                </div> --}}
                                <div class="col-6">
                                    <small class="text-muted d-block" style="font-size: 0.6rem;">Date</small>
                                    <span style="font-size: 0.7rem;">{{ $donation->created_at->format('d M') }}</span>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted d-block" style="font-size: 0.6rem;">By</small>
                                    <span style="font-size: 0.7rem;">{{ $donation->user->name }}</span>
                                </div>
                            </div>
                            
                            <div class="d-flex gap-1 mt-1 pt-1" style="border-top: 1px solid #edf2f7;">
                                <a href="{{ route('donations.show', $donation) }}" class="btn btn-sm flex-fill" style="background: #f8fafc; color: #475569; border-radius: 16px; padding: 6px 0; border: 1px solid #e2e8f0; font-size: 0.7rem;">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                <a href="{{ route('donations.edit', $donation) }}" class="btn btn-sm flex-fill" style="background: #f8fafc; color: #2563eb; border-radius: 16px; padding: 6px 0; border: 1px solid #e2e8f0; font-size: 0.7rem;">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                @if($donation->paid_status == 'unpaid')
                                <form action="{{ route('donations.markAsPaid', $donation) }}" method="POST" class="flex-fill">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm w-100" style="background: #f8fafc; color: #10b981; border-radius: 16px; padding: 6px 0; border: 1px solid #e2e8f0; font-size: 0.7rem;" onclick="return confirm('Mark as paid?')">
                                        <i class="fas fa-check"></i> Pay
                                    </button>
                                </form>
                                @endif
                                <form action="{{ route('donations.destroy', $donation) }}" method="POST" class="flex-fill">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm w-100" style="background: #f8fafc; color: #ef4444; border-radius: 16px; padding: 6px 0; border: 1px solid #e2e8f0; font-size: 0.7rem;" onclick="return confirm('Delete?')">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-4">
                            <div style="width: 48px; height: 48px; background: #f1f5f9; border-radius: 16px; display: flex; align-items: center; justify-content: center; margin: 0 auto 12px;">
                                <i class="fas fa-hand-holding-heart" style="color: #94a3b8; font-size: 1.2rem;"></i>
                            </div>
                            <h6 style="color: #475569; font-size: 0.9rem; margin-bottom: 8px;">No donations found</h6>
                            <a href="{{ route('donations.create') }}" class="btn" style="background: linear-gradient(145deg, #2563eb, #1d4ed8); color: white; border: none; border-radius: 20px; padding: 6px 16px; font-size: 0.8rem;">
                                <i class="fas fa-plus me-1"></i>Add First
                            </a>
                        </div>
                        @endforelse
                    </div>

                    <!-- Desktop Table View -->
                    <div class="d-none d-md-block">
                        <div class="table-responsive">
                            <table class="table" style="border-collapse: separate; border-spacing: 0 8px;">
                                <thead>
                                    <tr>
                                        <th style="padding: 12px 16px; color: #64748b; font-weight: 500; font-size: 0.7rem; text-transform: uppercase;">ID</th>
                                        <th style="padding: 12px 16px; color: #64748b; font-weight: 500; font-size: 0.7rem; text-transform: uppercase;">Donor</th>
                                        <th style="padding: 12px 16px; color: #64748b; font-weight: 500; font-size: 0.7rem; text-transform: uppercase;">Phone</th>
                                        <th style="padding: 12px 16px; color: #64748b; font-weight: 500; font-size: 0.7rem; text-transform: uppercase;">Amount</th>
                                        <th style="padding: 12px 16px; color: #64748b; font-weight: 500; font-size: 0.7rem; text-transform: uppercase;">Method</th>
                                        <th style="padding: 12px 16px; color: #64748b; font-weight: 500; font-size: 0.7rem; text-transform: uppercase;">Status</th>
                                        {{-- <th style="padding: 12px 16px; color: #64748b; font-weight: 500; font-size: 0.7rem; text-transform: uppercase;">Type</th> --}}
                                        <th style="padding: 12px 16px; color: #64748b; font-weight: 500; font-size: 0.7rem; text-transform: uppercase;">Date</th>
                                        <th style="padding: 12px 16px; color: #64748b; font-weight: 500; font-size: 0.7rem; text-transform: uppercase;">Recorded By</th>
                                        <th style="padding: 12px 16px; color: #64748b; font-weight: 500; font-size: 0.7rem; text-transform: uppercase;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($donations as $donation)
                                    <tr style="background: white; border-radius: 20px; box-shadow: 0 4px 12px -4px rgba(0,0,0,0.03);">
                                        <td style="padding: 16px; border: none; border-radius: 20px 0 0 20px; font-weight: 500;">#{{ $donation->id }}</td>
                                        <td style="padding: 16px; border: none;">{{ $donation->name }}</td>
                                        <td style="padding: 16px; border: none;">{{ $donation->phone ?? '—' }}</td>
                                        <td style="padding: 16px; border: none; font-weight: 600;">৳{{ number_format($donation->amount) }}</td>
                                        <td style="padding: 16px; border: none;">
                                            <span class="badge" style="background: #eef2ff; color: #4338ca; padding: 4px 10px; border-radius: 20px;">
                                                {{ ucfirst($donation->payment_method) }}
                                            </span>
                                        </td>
                                        <td style="padding: 16px; border: none;">
                                            <span class="badge" style="background: {{ $donation->paid_status == 'paid' ? '#dcfce7' : '#fee2e2' }}; color: {{ $donation->paid_status == 'paid' ? '#166534' : '#991b1b' }}; padding: 4px 10px; border-radius: 20px;">
                                                {{ ucfirst($donation->paid_status) }}
                                            </span>
                                        </td>
                                        {{-- <td style="padding: 16px; border: none;">
                                            <span class="badge" style="background: {{ $donation->donor_id ? '#dbeafe' : '#f1f5f9' }}; color: {{ $donation->donor_id ? '#1e40af' : '#475569' }}; padding: 4px 10px; border-radius: 20px;">
                                                {{ $donation->donor_id ? 'Existing' : 'New' }}
                                            </span>
                                        </td> --}}
                                        <td style="padding: 16px; border: none;">{{ $donation->created_at->format('d M Y') }}</td>
                                        <td style="padding: 16px; border: none;">{{ $donation->user->name }}</td>
                                        <td style="padding: 16px; border: none; border-radius: 0 20px 20px 0;">
                                            <div class="d-flex gap-1">
                                                <a href="{{ route('donations.show', $donation) }}" class="btn btn-sm" style="width: 32px; height: 32px; border-radius: 10px; background: #f8fafc; color: #475569; display: flex; align-items: center; justify-content: center; border: 1px solid #e2e8f0;">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('donations.edit', $donation) }}" class="btn btn-sm" style="width: 32px; height: 32px; border-radius: 10px; background: #f8fafc; color: #2563eb; display: flex; align-items: center; justify-content: center; border: 1px solid #e2e8f0;">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                @if($donation->paid_status == 'unpaid')
                                                <form action="{{ route('donations.markAsPaid', $donation) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-sm" style="width: 32px; height: 32px; border-radius: 10px; background: #f8fafc; color: #10b981; display: flex; align-items: center; justify-content: center; border: 1px solid #e2e8f0;">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                                @endif
                                                <form action="{{ route('donations.destroy', $donation) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm" style="width: 32px; height: 32px; border-radius: 10px; background: #f8fafc; color: #ef4444; display: flex; align-items: center; justify-content: center; border: 1px solid #e2e8f0;">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="10" class="text-center py-5">
                                            <div style="width: 80px; height: 80px; background: #f1f5f9; border-radius: 30px; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                                                <i class="fas fa-hand-holding-heart fa-3x" style="color: #94a3b8;"></i>
                                            </div>
                                            <h6 style="color: #475569;">No donations found</h6>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-2 mt-sm-4">
                        <div class="pagination-wrapper" style="overflow-x: auto; max-width: 100%; padding: 2px 0;">
                            {{ $donations->withQueryString()->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Mobile-first optimizations */
@media (max-width: 768px) {
    .container-fluid {
        padding-left: 0 !important;
        padding-right: 0 !important;
    }
    
    .content-card {
        border-radius: 0 !important;
    }
    
    .stat-compact {
        border-radius: 10px !important;
        padding: 8px !important;
    }
    
    .stat-compact .d-flex {
        gap: 6px !important;
    }
    
    .mobile-donation-card {
        margin-bottom: 6px !important;
    }
    
    .row.g-1 {
        margin: -2px !important;
    }
    
    .row.g-1 > [class*="col-"] {
        padding: 2px !important;
    }
    
    .btn-sm {
        min-height: 36px;
    }
}

/* Hover effects only on desktop */
@media (hover: hover) {
    .stat-compact:hover {
        transform: translateY(-2px);
        transition: transform 0.2s;
    }
    
    tr:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 24px -8px rgba(0,0,0,0.08) !important;
        transition: all 0.2s;
    }
}
</style>
@endsection