@forelse($donors as $donor)
    @php
        $totalPaid = $donor->transactions()->where('paid_status', 'paid')->sum('amount');
        $lastPayment = $donor->transactions()->where('paid_status', 'paid')->latest()->first();
    @endphp
    
    <div style="padding: 12px; border-bottom: 1px solid #edf2f7;">
        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 6px;">
            <div>
                <a href="{{ route('donors.show', $donor) }}" style="text-decoration: none; font-weight: 600; font-size: 0.95rem; color: #1a202c;">
                    {{ $donor->name }}
                </a>
                <div style="display: flex; align-items: center; gap: 6px; margin-top: 2px; flex-wrap: wrap;">
                    @if($donor->phone)
                        <a href="tel:{{ $donor->phone }}" style="font-size: 0.75rem; color: #718096; text-decoration: none; display: inline-flex; align-items: center; gap: 4px; transition: all 0.2s;" 
                           onmouseover="this.style.color='#2563eb'" 
                           onmouseout="this.style.color='#718096'"
                           onclick="event.stopPropagation();">
                            <i class="fas fa-phone-alt me-1" style="color: #2563eb; font-size: 0.6rem;"></i>
                            {{ $donor->phone }}
                        </a>
                    @else
                        <span style="font-size: 0.75rem; color: #a0aec0;">
                            <i class="fas fa-phone-alt me-1" style="color: #cbd5e0;"></i>No phone
                        </span>
                    @endif
                    <span style="padding: 2px 8px; border-radius: 20px; font-size: 0.65rem; font-weight: 500; 
                          {{ $donor->status == 'active' ? 'background: #c6f6d5; color: #22543d;' : 'background: #fed7d7; color: #742a2a;' }}">
                        {{ ucfirst($donor->status) }}
                    </span>
                </div>
            </div>
            
            <!-- Dropdown Menu -->
            <div class="dropdown">
                <button style="background: none; border: none; padding: 4px; cursor: pointer;" data-bs-toggle="dropdown">
                    <i class="fas fa-ellipsis-v" style="color: #718096; font-size: 0.9rem;"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end" style="border-radius: 8px; border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.1); padding: 4px; min-width: 140px;">
                    <li>
                        <a class="dropdown-item" href="{{ route('donors.show', $donor) }}" style="padding: 6px 12px; font-size: 0.85rem;">
                            <i class="fas fa-eye me-2" style="color: #667eea; width: 16px;"></i>View
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('donors.edit', $donor) }}" style="padding: 6px 12px; font-size: 0.85rem;">
                            <i class="fas fa-edit me-2" style="color: #48bb78; width: 16px;"></i>Edit
                        </a>
                    </li>
                    @if($donor->phone)
                    <li>
                        <a class="dropdown-item" href="tel:{{ $donor->phone }}" style="padding: 6px 12px; font-size: 0.85rem;">
                            <i class="fas fa-phone me-2" style="color: #10b981; width: 16px;"></i>Call
                        </a>
                    </li>
                    @endif
                    <li>
                        <form action="{{ route('donors.toggleStatus', $donor) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="dropdown-item" style="padding: 6px 12px; font-size: 0.85rem;">
                                <i class="fas fa-{{ $donor->status == 'active' ? 'ban' : 'check' }} me-2" 
                                   style="color: {{ $donor->status == 'active' ? '#f6ad55' : '#48bb78' }}; width: 16px;"></i>
                                {{ $donor->status == 'active' ? 'Deactivate' : 'Activate' }}
                            </button>
                        </form>
                    </li>
                    <li><hr class="dropdown-divider" style="margin: 4px 0;"></li>
                    <li>
                        <form action="{{ route('donors.destroy', $donor) }}" method="POST"
                              onsubmit="return confirm('Delete this donor?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="dropdown-item" style="padding: 6px 12px; font-size: 0.85rem; color: #f56565;">
                                <i class="fas fa-trash me-2" style="width: 16px;"></i>Delete
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>

        @if($donor->address)
            <p style="font-size: 0.75rem; color: #718096; margin-bottom: 6px;">
                <i class="fas fa-map-marker-alt me-1" style="color: #a0aec0;"></i>{{ Str::limit($donor->address, 40) }}
            </p>
        @endif

        <!-- Compact Stats Grid -->
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 4px; margin-top: 6px;">
            <div style="background: #f7fafc; border-radius: 6px; padding: 6px; text-align: center;">
                <div style="font-size: 0.6rem; color: #718096; margin-bottom: 2px;">Monthly</div>
                <div style="font-weight: 600; font-size: 0.85rem; color: #667eea;">৳{{ number_format($donor->monthly_amount) }}</div>
            </div>
            <div style="background: #f7fafc; border-radius: 6px; padding: 6px; text-align: center;">
                <div style="font-size: 0.6rem; color: #718096; margin-bottom: 2px;">Paid</div>
                <div style="font-weight: 600; font-size: 0.85rem; color: #48bb78;">৳{{ number_format($totalPaid) }}</div>
            </div>
            <div style="background: #f7fafc; border-radius: 6px; padding: 6px; text-align: center;">
                <div style="font-size: 0.6rem; color: #718096; margin-bottom: 2px;">Last</div>
                <div style="font-weight: 600; font-size: 0.85rem; color: #4299e1;">
                    @if($lastPayment) {{ $lastPayment->created_at->format('d M') }} @else — @endif
                </div>
            </div>
        </div>
        
        <!-- Quick Call Button (Mobile Only - visible if phone exists) -->
        @if($donor->phone)
        <div style="margin-top: 8px; display: flex; justify-content: flex-end;">
            <a href="tel:{{ $donor->phone }}" style="background: #f1f5f9; color: #2563eb; padding: 6px 16px; border-radius: 30px; font-size: 0.75rem; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; border: 1px solid #e2e8f0;">
                <i class="fas fa-phone-alt" style="font-size: 0.7rem;"></i>
                Call Donor
            </a>
        </div>
        @endif
    </div>
@empty
    <div style="text-align: center; padding: 40px 16px;">
        <div style="width: 48px; height: 48px; background: #f7fafc; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 12px;">
            <i class="fas fa-users" style="font-size: 20px; color: #cbd5e0;"></i>
        </div>
        <h6 style="color: #2d3748; margin-bottom: 4px; font-size: 0.95rem;">No Donors Found</h6>
        <p style="color: #718096; font-size: 0.8rem; margin-bottom: 12px;">Try adjusting your search or add a new donor</p>
        <a href="{{ route('donors.create') }}" style="padding: 8px 16px; background: #667eea; color: white; border-radius: 8px; text-decoration: none; display: inline-flex; align-items: center; gap: 4px; font-size: 0.85rem;">
            <i class="fas fa-plus-circle"></i> Add Donor
        </a>
    </div>
@endforelse

<!-- Add this style block for better mobile interactions -->
<style>
/* Phone link hover/tap effects */
a[href^="tel"] {
    transition: all 0.2s ease;
    -webkit-tap-highlight-color: rgba(37, 99, 235, 0.1);
}

a[href^="tel"]:hover {
    color: #2563eb !important;
    transform: translateX(2px);
}

a[href^="tel"]:active {
    opacity: 0.7;
    transform: scale(0.98);
}

/* Quick call button hover */
a[href^="tel"].quick-call {
    transition: all 0.2s ease;
}

a[href^="tel"].quick-call:hover {
    background: #2563eb !important;
    color: white !important;
    border-color: #2563eb !important;
}

/* Mobile optimizations */
@media (max-width: 768px) {
    a[href^="tel"] {
        padding: 4px 0;
        display: inline-block;
    }
    
    /* Larger tap target for call button */
    .quick-call {
        padding: 10px 20px !important;
        min-height: 44px;
    }
    
    /* Prevent zoom on tap */
    a[href^="tel"], button {
        touch-action: manipulation;
    }
}
</style>