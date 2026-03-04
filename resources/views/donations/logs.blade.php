@extends('layouts.app')

@section('title', 'Donation History')
@section('page-title', 'Donation History')
@section('page-subtitle', 'View edit history for donation #' . $donation->id)

@section('page-actions')
    <a href="{{ route('donations.show', $donation) }}" class="btn" style="background: #f1f5f9; color: #475569; border: none; border-radius: 30px; padding: 8px 18px; font-weight: 500; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 8px;">
        <i class="fas fa-arrow-left"></i> Back to Donation
    </a>
@endsection

@section('content')
<div class="container-fluid px-2 px-sm-3">
    <div class="row g-3">
        <div class="col-12">
            <div class="content-card" style="border-radius: 24px; overflow: hidden; background: white; box-shadow: 0 20px 40px -12px rgba(0,20,40,0.12); border: 1px solid rgba(226, 232, 240, 0.6);">
                <div class="px-4 py-3" style="background: linear-gradient(145deg, #fafcff, #ffffff); border-bottom: 1px solid rgba(226, 232, 240, 0.6);">
                    <div class="d-flex align-items-center gap-3">
                        <div style="width: 40px; height: 40px; background: #eef2ff; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-history" style="color: #2563eb;"></i>
                        </div>
                        <div>
                            <h5 class="mb-0" style="font-weight: 600;">Edit History</h5>
                            <small class="text-secondary">Donation #{{ $donation->id }} - {{ $donation->name }}</small>
                        </div>
                    </div>
                </div>
                
                <div class="p-4">
                    @if($logs->count() > 0)
                        <!-- Desktop Timeline View -->
                        <div class="d-none d-md-block">
                            <div class="table-responsive">
                                <table class="table" style="border-collapse: separate; border-spacing: 0 8px;">
                                    <thead>
                                        <tr>
                                            <th style="padding: 8px 12px; font-size: 0.7rem; font-weight: 600; color: #64748b;">Date & Time</th>
                                            <th style="padding: 8px 12px; font-size: 0.7rem; font-weight: 600; color: #64748b;">User</th>
                                            <th style="padding: 8px 12px; font-size: 0.7rem; font-weight: 600; color: #64748b;">Action</th>
                                            <th style="padding: 8px 12px; font-size: 0.7rem; font-weight: 600; color: #64748b;">Field</th>
                                            <th style="padding: 8px 12px; font-size: 0.7rem; font-weight: 600; color: #64748b;">Old Value</th>
                                            <th style="padding: 8px 12px; font-size: 0.7rem; font-weight: 600; color: #64748b;">New Value</th>
                                            <th style="padding: 8px 12px; font-size: 0.7rem; font-weight: 600; color: #64748b;">IP Address</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($logs as $log)
                                        <tr style="background: white; border-radius: 16px; box-shadow: 0 2px 8px -2px rgba(0,0,0,0.02); border: 1px solid #edf2f7;">
                                            <td style="padding: 12px; border: none; border-radius: 16px 0 0 16px;">
                                                <span style="font-size: 0.8rem;">{{ $log->created_at->format('d M Y, h:i A') }}</span>
                                                <small class="text-secondary d-block" style="font-size: 0.6rem;">{{ $log->created_at->diffForHumans() }}</small>
                                            </td>
                                            <td style="padding: 12px; border: none;">
                                                <div class="d-flex align-items-center gap-2">
                                                    <div style="width: 28px; height: 28px; background: linear-gradient(135deg, #667eea, #764ba2); border-radius: 6px; display: flex; align-items: center; justify-content: center;">
                                                        <span class="text-white fw-bold" style="font-size: 0.7rem;">{{ substr($log->user->name, 0, 1) }}</span>
                                                    </div>
                                                    <span style="font-size: 0.8rem;">{{ $log->user->name }}</span>
                                                </div>
                                            </td>
                                            <td style="padding: 12px; border: none;">
                                                @if($log->action == 'created')
                                                    <span class="badge rounded-pill px-3 py-1" style="background: #dbeafe; color: #1e40af; font-size: 0.65rem;">
                                                        <i class="fas fa-plus-circle me-1"></i> Created
                                                    </span>
                                                @elseif($log->action == 'updated')
                                                    <span class="badge rounded-pill px-3 py-1" style="background: #fef3c7; color: #92400e; font-size: 0.65rem;">
                                                        <i class="fas fa-edit me-1"></i> Updated
                                                    </span>
                                                @elseif($log->action == 'deleted')
                                                    <span class="badge rounded-pill px-3 py-1" style="background: #fee2e2; color: #991b1b; font-size: 0.65rem;">
                                                        <i class="fas fa-trash me-1"></i> Deleted
                                                    </span>
                                                @endif
                                            </td>
                                            <td style="padding: 12px; border: none;">
                                                @if($log->field_name)
                                                    <span style="font-size: 0.75rem; font-weight: 500;">{{ ucfirst(str_replace('_', ' ', $log->field_name)) }}</span>
                                                @else
                                                    <span class="text-secondary" style="font-size: 0.7rem;">—</span>
                                                @endif
                                            </td>
                                            <td style="padding: 12px; border: none;">
                                                @if($log->old_value !== null)
                                                    @if($log->field_name == 'amount')
                                                        <span style="color: #ef4444; font-size: 0.8rem;">৳{{ number_format($log->old_value, 2) }}</span>
                                                    @elseif($log->field_name == 'paid_status')
                                                        <span class="badge" style="background: {{ $log->old_value == 'paid' ? '#dcfce7' : '#fee2e2' }}; color: {{ $log->old_value == 'paid' ? '#166534' : '#991b1b' }}; font-size: 0.6rem;">{{ ucfirst($log->old_value) }}</span>
                                                    @else
                                                        <span style="font-size: 0.75rem;">{{ $log->old_value ?: '<em>Empty</em>' }}</span>
                                                    @endif
                                                @else
                                                    <span class="text-secondary" style="font-size: 0.7rem;">—</span>
                                                @endif
                                            </td>
                                            <td style="padding: 12px; border: none;">
                                                @if($log->new_value !== null)
                                                    @if($log->field_name == 'amount')
                                                        <span style="color: #10b981; font-size: 0.8rem;">৳{{ number_format($log->new_value, 2) }}</span>
                                                    @elseif($log->field_name == 'paid_status')
                                                        <span class="badge" style="background: {{ $log->new_value == 'paid' ? '#dcfce7' : '#fee2e2' }}; color: {{ $log->new_value == 'paid' ? '#166534' : '#991b1b' }}; font-size: 0.6rem;">{{ ucfirst($log->new_value) }}</span>
                                                    @else
                                                        <span style="font-size: 0.75rem;">{{ $log->new_value ?: '<em>Empty</em>' }}</span>
                                                    @endif
                                                @else
                                                    <span class="text-secondary" style="font-size: 0.7rem;">—</span>
                                                @endif
                                            </td>
                                            <td style="padding: 12px; border: none; border-radius: 0 16px 16px 0;">
                                                <span style="font-size: 0.7rem; font-family: monospace;">{{ $log->ip_address ?: '—' }}</span>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Mobile Card View -->
                        <div class="d-block d-md-none">
                            @foreach($logs as $log)
                            <div class="mobile-log-card mb-2 p-3" style="background: white; border-radius: 16px; box-shadow: 0 2px 8px -2px rgba(0,0,0,0.03); border: 1px solid #edf2f7;">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div class="d-flex align-items-center gap-2">
                                        <div style="width: 36px; height: 36px; background: linear-gradient(135deg, #667eea, #764ba2); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                            <span class="text-white fw-bold">{{ substr($log->user->name, 0, 1) }}</span>
                                        </div>
                                        <div>
                                            <span style="font-weight: 600; font-size: 0.8rem;">{{ $log->user->name }}</span>
                                            <span style="font-size: 0.6rem; color: #64748b; display: block;">{{ $log->created_at->format('d M Y, h:i A') }}</span>
                                        </div>
                                    </div>
                                    @if($log->action == 'created')
                                        <span class="badge rounded-pill px-3 py-1" style="background: #dbeafe; color: #1e40af; font-size: 0.6rem;">Created</span>
                                    @elseif($log->action == 'updated')
                                        <span class="badge rounded-pill px-3 py-1" style="background: #fef3c7; color: #92400e; font-size: 0.6rem;">Updated</span>
                                    @elseif($log->action == 'deleted')
                                        <span class="badge rounded-pill px-3 py-1" style="background: #fee2e2; color: #991b1b; font-size: 0.6rem;">Deleted</span>
                                    @endif
                                </div>
                                
                                @if($log->field_name)
                                <div class="mt-2 p-2 rounded-3" style="background: #f8fafc;">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span style="font-size: 0.65rem; color: #64748b;">Field</span>
                                        <span style="font-size: 0.7rem; font-weight: 500;">{{ ucfirst(str_replace('_', ' ', $log->field_name)) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <span style="font-size: 0.6rem; color: #64748b;">Old</span>
                                            @if($log->field_name == 'amount')
                                                <div style="color: #ef4444; font-size: 0.8rem;">৳{{ number_format($log->old_value, 2) }}</div>
                                            @elseif($log->field_name == 'paid_status')
                                                <span class="badge" style="background: {{ $log->old_value == 'paid' ? '#dcfce7' : '#fee2e2' }}; color: {{ $log->old_value == 'paid' ? '#166534' : '#991b1b' }};">{{ ucfirst($log->old_value) }}</span>
                                            @else
                                                <div style="font-size: 0.75rem;">{{ $log->old_value ?: '—' }}</div>
                                            @endif
                                        </div>
                                        <div>
                                            <span style="font-size: 0.6rem; color: #64748b;">New</span>
                                            @if($log->field_name == 'amount')
                                                <div style="color: #10b981; font-size: 0.8rem;">৳{{ number_format($log->new_value, 2) }}</div>
                                            @elseif($log->field_name == 'paid_status')
                                                <span class="badge" style="background: {{ $log->new_value == 'paid' ? '#dcfce7' : '#fee2e2' }}; color: {{ $log->new_value == 'paid' ? '#166534' : '#991b1b' }};">{{ ucfirst($log->new_value) }}</span>
                                            @else
                                                <div style="font-size: 0.75rem;">{{ $log->new_value ?: '—' }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @else
                                <div class="mt-2 p-2 rounded-3" style="background: #f8fafc;">
                                    <span style="font-size: 0.7rem; color: #64748b;">Donation was {{ $log->action }}</span>
                                </div>
                                @endif
                                
                                <div class="mt-2 text-end">
                                    <small style="font-size: 0.55rem; color: #94a3b8;">IP: {{ $log->ip_address ?: 'N/A' }}</small>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $logs->links() }}
                        </div>
                    @else
                        <!-- No Logs -->
                        <div class="text-center py-5">
                            <div style="width: 80px; height: 80px; background: #f1f5f9; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                                <i class="fas fa-history fa-3x" style="color: #94a3b8;"></i>
                            </div>
                            <h5 style="color: #1e293b; margin-bottom: 8px;">No Edit History</h5>
                            <p style="color: #64748b; margin-bottom: 20px;">This donation hasn't been modified since it was created.</p>
                            <a href="{{ route('donations.show', $donation) }}" class="btn" style="background: linear-gradient(145deg, #2563eb, #1d4ed8); color: white; border: none; border-radius: 40px; padding: 10px 24px;">
                                <i class="fas fa-arrow-left me-2"></i>Back to Donation
                            </a>
                        </div>
                    @endif
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
    
    .mobile-log-card {
        transition: all 0.2s ease;
    }
    
    .mobile-log-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px -4px rgba(37,99,235,0.1) !important;
    }
}

/* Desktop row hover */
tr {
    transition: all 0.2s ease;
}

tr:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 16px -4px rgba(37,99,235,0.1) !important;
}
</style>
@endsection