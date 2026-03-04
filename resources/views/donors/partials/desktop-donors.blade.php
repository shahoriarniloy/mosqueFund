<div style="overflow-x: auto;">
    <table style="width: 100%; border-collapse: collapse; font-size: 0.85rem;">
        <thead>
            <tr style="background: #fafbfc; border-bottom: 1px solid #edf2f7;">
                <th style="padding: 10px 12px; text-align: left; font-size: 0.7rem; font-weight: 600; color: #4a5568;">ID</th>
                <th style="padding: 10px 12px; text-align: left; font-size: 0.7rem; font-weight: 600; color: #4a5568;">Name</th>
                <th style="padding: 10px 12px; text-align: left; font-size: 0.7rem; font-weight: 600; color: #4a5568;">Phone</th>
                <th style="padding: 10px 12px; text-align: left; font-size: 0.7rem; font-weight: 600; color: #4a5568;">Monthly</th>
                <th style="padding: 10px 12px; text-align: left; font-size: 0.7rem; font-weight: 600; color: #4a5568;">Status</th>
                <th style="padding: 10px 12px; text-align: left; font-size: 0.7rem; font-weight: 600; color: #4a5568;">Paid</th>
                <th style="padding: 10px 12px; text-align: left; font-size: 0.7rem; font-weight: 600; color: #4a5568;">Last</th>
                <th style="padding: 10px 12px; text-align: right; font-size: 0.7rem; font-weight: 600; color: #4a5568;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($donors as $donor)
                @php
                    $totalPaid = $donor->transactions()->where('paid_status', 'paid')->sum('amount');
                    $lastPayment = $donor->transactions()->where('paid_status', 'paid')->latest()->first();
                @endphp
                <tr style="border-bottom: 1px solid #edf2f7;">
                    <td style="padding: 10px 12px; color: #718096;">#{{ $donor->id }}</td>
                    <td style="padding: 10px 12px;">
                        <a href="{{ route('donors.show', $donor) }}" style="text-decoration: none; font-weight: 500; color: #2d3748;">
                            {{ $donor->name }}
                        </a>
                    </td>
                    <td style="padding: 10px 12px;">
                        @if($donor->phone)
                            <a href="tel:{{ $donor->phone }}" style="text-decoration: none; color: #718096; display: inline-flex; align-items: center; gap: 4px; transition: color 0.2s;" onmouseover="this.style.color='#2563eb'" onmouseout="this.style.color='#718096'">
                                <i class="fas fa-phone-alt" style="font-size: 0.6rem; color: #2563eb;"></i>
                                {{ $donor->phone }}
                            </a>
                        @else
                            <span style="color: #a0aec0;">—</span>
                        @endif
                    </td>
                    <td style="padding: 10px 12px; font-weight: 500; color: #667eea;">৳{{ number_format($donor->monthly_amount) }}</td>
                    <td style="padding: 10px 12px;">
                        <span style="padding: 2px 8px; border-radius: 20px; font-size: 0.65rem; font-weight: 500; 
                              {{ $donor->status == 'active' ? 'background: #c6f6d5; color: #22543d;' : 'background: #fed7d7; color: #742a2a;' }}">
                            {{ ucfirst($donor->status) }}
                        </span>
                    </td>
                    <td style="padding: 10px 12px; font-weight: 500; color: #48bb78;">৳{{ number_format($totalPaid) }}</td>
                    <td style="padding: 10px 12px; color: #718096;">
                        @if($lastPayment) {{ $lastPayment->created_at->format('d M') }} @else — @endif
                    </td>
                    <td style="padding: 10px 12px; text-align: right;">
                        <div style="display: inline-flex; gap: 4px;">
                            <a href="{{ route('donors.show', $donor) }}" style="width: 28px; height: 28px; display: inline-flex; align-items: center; justify-content: center; background: #f7fafc; border-radius: 6px; color: #718096; text-decoration: none; transition: all 0.2s;" 
                               onmouseover="this.style.background='#2563eb'; this.style.color='white'" 
                               onmouseout="this.style.background='#f7fafc'; this.style.color='#718096'" title="View donor details">
                                <i class="fas fa-eye" style="font-size: 0.8rem;"></i>
                            </a>
                            <a href="{{ route('donors.edit', $donor) }}" style="width: 28px; height: 28px; display: inline-flex; align-items: center; justify-content: center; background: #f7fafc; border-radius: 6px; color: #718096; text-decoration: none; transition: all 0.2s;"
                               onmouseover="this.style.background='#48bb78'; this.style.color='white'" 
                               onmouseout="this.style.background='#f7fafc'; this.style.color='#718096'" title="Edit donor">
                                <i class="fas fa-edit" style="font-size: 0.8rem;"></i>
                            </a>
                            @if($donor->phone)
                            <a href="tel:{{ $donor->phone }}" style="width: 28px; height: 28px; display: inline-flex; align-items: center; justify-content: center; background: #f7fafc; border-radius: 6px; color: #718096; text-decoration: none; transition: all 0.2s;"
                               onmouseover="this.style.background='#10b981'; this.style.color='white'" 
                               onmouseout="this.style.background='#f7fafc'; this.style.color='#718096'" title="Call donor">
                                <i class="fas fa-phone" style="font-size: 0.8rem;"></i>
                            </a>
                            @endif
                            <div class="dropdown" style="display: inline-block;">
                                <button style="width: 28px; height: 28px; background: #f7fafc; border: none; border-radius: 6px; color: #718096; cursor: pointer; transition: all 0.2s;" 
                                        data-bs-toggle="dropdown" 
                                        onmouseover="this.style.background='#94a3b8'; this.style.color='white'" 
                                        onmouseout="this.style.background='#f7fafc'; this.style.color='#718096'"
                                        title="More actions">
                                    <i class="fas fa-ellipsis-v" style="font-size: 0.8rem;"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" style="border-radius: 8px; border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.1); padding: 4px; min-width: 140px;">
                                    <li>
                                        <form action="{{ route('donors.toggleStatus', $donor) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="dropdown-item" style="padding: 6px 12px; font-size: 0.8rem;">
                                                <i class="fas fa-{{ $donor->status == 'active' ? 'ban' : 'check' }} me-2" style="width: 14px; color: {{ $donor->status == 'active' ? '#f59e0b' : '#48bb78' }};"></i>
                                                {{ $donor->status == 'active' ? 'Deactivate' : 'Activate' }}
                                            </button>
                                        </form>
                                    </li>
                                    {{-- <li>
                                        <form action="{{ route('donors.destroy', $donor) }}" method="POST"
                                              onsubmit="return confirm('Delete this donor?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item" style="padding: 6px 12px; font-size: 0.8rem; color: #f56565;">
                                                <i class="fas fa-trash me-2" style="width: 14px;"></i>Delete
                                            </button>
                                        </form>
                                    </li> --}}
                                </ul>
                            </div>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="padding: 40px; text-align: center;">
                        <div style="width: 48px; height: 48px; background: #f7fafc; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 12px;">
                            <i class="fas fa-users" style="font-size: 20px; color: #cbd5e0;"></i>
                        </div>
                        <p style="color: #718096; font-size: 0.9rem;">No donors found</p>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Add this CSS for better hover effects -->
<style>
/* Phone number hover effect */
td a[href^="tel"] {
    transition: all 0.2s ease;
    text-decoration: none;
    color: #718096;
}

td a[href^="tel"]:hover {
    color: #2563eb !important;
    transform: translateX(2px);
}

/* Action buttons hover effect */
td .btn-action {
    transition: all 0.2s ease;
}

td .btn-action:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

/* Mobile optimizations for phone links */
@media (max-width: 768px) {
    td a[href^="tel"] {
        padding: 4px 0;
        display: inline-block;
    }
    
    /* Add touch feedback */
    td a[href^="tel"]:active {
        opacity: 0.7;
    }
}
</style>