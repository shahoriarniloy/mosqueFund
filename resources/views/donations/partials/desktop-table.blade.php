<div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>Donor/Contributor</th>
                <th>Phone</th>
                <th>Type</th>
                <th class="text-end">Amount</th>
                <th>Payment Method</th>
                <th>Status</th>
                <th>Date</th>
                <th>Recorded By</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($donations as $donation)
                <tr>
                    <td>{{ $donations->firstItem() + $loop->index }}</td>
                    <td>
                        <div class="d-flex align-items-center">
                            @if($donation->donor)
                                <div class="donor-avatar me-2 bg-primary bg-opacity-10 text-primary">
                                    {{ substr($donation->donor->name, 0, 1) }}
                                </div>
                                <div>
                                    <a href="{{ route('donors.show', $donation->donor) }}" class="text-decoration-none fw-semibold">
                                        {{ $donation->donor->name }}
                                    </a>
                                    @if($donation->donor->status === 'inactive')
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary ms-1">Inactive</span>
                                    @endif
                                </div>
                            @elseif($donation->contributor)
                                <div class="contributor-avatar me-2 bg-info bg-opacity-10 text-info">
                                    {{ substr($donation->contributor->name, 0, 1) }}
                                </div>
                                <div>
                                    <a href="{{ route('contributors.show', $donation->contributor) }}" class="text-decoration-none fw-semibold">
                                        {{ $donation->contributor->name }}
                                    </a>
                                </div>
                            @else
                                <span class="text-muted">Unknown</span>
                            @endif
                        </div>
                    </td>
                    <td>
                        @if($donation->donor)
                            {{ $donation->donor->phone }}
                        @elseif($donation->contributor)
                            {{ $donation->contributor->phone }}
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td>
                        @if($donation->donor)
                            <span class="badge bg-primary bg-opacity-10 text-primary">
                                <i class="fas fa-calendar-check me-1"></i> Monthly
                            </span>
                        @elseif($donation->contributor)
                            <span class="badge bg-info bg-opacity-10 text-info">
                                <i class="fas fa-random me-1"></i> Random
                            </span>
                        @else
                            <span class="badge bg-secondary bg-opacity-10 text-secondary">Unknown</span>
                        @endif
                    </td>
                    <td class="text-end fw-semibold text-success">৳{{ number_format($donation->amount, 2) }}</td>
                    <td>
                        <span class="badge bg-light text-dark">
                            @if($donation->payment_method == 'cash')
                                <i class="fas fa-money-bill-wave me-1"></i> Cash
                            @elseif($donation->payment_method == 'bkash')
                                <i class="fas fa-mobile-alt me-1"></i> bKash
                            @elseif($donation->payment_method == 'nagad')
                                <i class="fas fa-mobile-alt me-1"></i> Nagad
                            @endif
                        </span>
                    </td>
                    <td>
                        @if($donation->paid_status == 'paid')
                            <span class="badge bg-success bg-opacity-10 text-success">
                                <i class="fas fa-check-circle me-1"></i> Paid
                            </span>
                        @else
                            <span class="badge bg-warning bg-opacity-10 text-warning">
                                <i class="fas fa-clock me-1"></i> Unpaid
                            </span>
                        @endif
                    </td>
                    <td>
                        <span title="{{ $donation->created_at->format('F j, Y g:i A') }}">
                            {{ $donation->created_at->format('M d, Y') }}
                        </span>
                        <br>
                        <small class="text-muted">{{ $donation->created_at->format('h:i A') }}</small>
                    </td>
                    <td>
                        @if($donation->user)
                            <span class="badge bg-light text-dark">
                                <i class="fas fa-user me-1"></i> {{ $donation->user->name }}
                            </span>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex gap-2 justify-content-center">
                            <a href="{{ route('donations.show', $donation) }}" 
                               class="btn btn-sm btn-light" 
                               title="View Details">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('donations.edit', $donation) }}" 
                               class="btn btn-sm btn-light" 
                               title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center py-5">
                        <div class="empty-state">
                            <i class="fas fa-hand-holding-heart fa-3x text-muted mb-3"></i>
                            <h5>No Donations Found</h5>
                            <p class="text-muted">Get started by recording your first donation.</p>
                            <a href="{{ route('donations.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Add Donation
                            </a>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<style>
    .donor-avatar, .contributor-avatar {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 0.9rem;
    }
    
    .donor-avatar {
        background: rgba(37, 99, 235, 0.1);
        color: #2563eb;
    }
    
    .contributor-avatar {
        background: rgba(6, 182, 212, 0.1);
        color: #0891b2;
    }
</style>