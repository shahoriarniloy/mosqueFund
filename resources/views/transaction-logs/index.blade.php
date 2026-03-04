@extends('layouts.app')

@section('title', 'Transaction Logs')
@section('page-title', 'Transaction Audit Logs')
@section('page-subtitle', 'Complete history of all transaction changes')

@section('quick-actions')
    <a href="{{ route('transaction-logs.export') }}?{{ http_build_query(request()->all()) }}" class="quick-action">
        <i class="fas fa-file-export"></i> Export
    </a>
    <a href="#" class="quick-action" onclick="window.print()">
        <i class="fas fa-print"></i> Print
    </a>
@endsection

@section('content')
<div class="container-fluid px-2 px-sm-3">
    <!-- Summary Cards -->
    <div class="row g-2 g-sm-3 mb-3">
        <div class="col-6 col-md-3">
            <div class="stat-compact p-2 p-sm-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 12px;">
                <div class="d-flex align-items-center gap-2">
                    <div style="width: 32px; height: 32px; background: rgba(255,255,255,0.2); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-history" style="font-size: 0.9rem; color: white;"></i>
                    </div>
                    <div>
                        <p class="mb-0 text-white opacity-75" style="font-size: 0.55rem;">TOTAL LOGS</p>
                        <h6 class="mb-0 text-white" style="font-size: 0.9rem;">{{ \App\Models\TransactionLog::count() }}</h6>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-6 col-md-3">
            <div class="stat-compact p-2 p-sm-3" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: 12px;">
                <div class="d-flex align-items-center gap-2">
                    <div style="width: 32px; height: 32px; background: rgba(255,255,255,0.2); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-plus-circle" style="font-size: 0.9rem; color: white;"></i>
                    </div>
                    <div>
                        <p class="mb-0 text-white opacity-75" style="font-size: 0.55rem;">CREATED</p>
                        <h6 class="mb-0 text-white" style="font-size: 0.9rem;">{{ \App\Models\TransactionLog::where('action', 'created')->count() }}</h6>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-6 col-md-3">
            <div class="stat-compact p-2 p-sm-3" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border-radius: 12px;">
                <div class="d-flex align-items-center gap-2">
                    <div style="width: 32px; height: 32px; background: rgba(255,255,255,0.2); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-edit" style="font-size: 0.9rem; color: white;"></i>
                    </div>
                    <div>
                        <p class="mb-0 text-white opacity-75" style="font-size: 0.55rem;">UPDATED</p>
                        <h6 class="mb-0 text-white" style="font-size: 0.9rem;">{{ \App\Models\TransactionLog::where('action', 'updated')->count() }}</h6>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-6 col-md-3">
            <div class="stat-compact p-2 p-sm-3" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); border-radius: 12px;">
                <div class="d-flex align-items-center gap-2">
                    <div style="width: 32px; height: 32px; background: rgba(255,255,255,0.2); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-trash" style="font-size: 0.9rem; color: white;"></i>
                    </div>
                    <div>
                        <p class="mb-0 text-white opacity-75" style="font-size: 0.55rem;">DELETED</p>
                        <h6 class="mb-0 text-white" style="font-size: 0.9rem;">{{ \App\Models\TransactionLog::where('action', 'deleted')->count() }}</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="content-card mb-3" style="border-radius: 16px; background: white;">
        <div class="p-3">
            <form action="{{ route('transaction-logs.index') }}" method="GET" class="row g-2">
                <div class="col-12 col-md-2">
                    <select name="action" class="form-select form-select-sm" style="border-radius: 20px;">
                        <option value="">All Actions</option>
                        @foreach($actions as $action)
                            <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>
                                {{ ucfirst($action) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-12 col-md-3">
                    <select name="user_id" class="form-select form-select-sm" style="border-radius: 20px;">
                        <option value="">All Users</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-12 col-md-3">
                    <select name="donor_id" class="form-select form-select-sm" style="border-radius: 20px;">
                        <option value="">All Donors</option>
                        @foreach($donors as $donor)
                            <option value="{{ $donor->id }}" {{ request('donor_id') == $donor->id ? 'selected' : '' }}>
                                {{ $donor->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-12 col-md-4">
                    <div class="d-flex gap-1">
                        <input type="date" name="from_date" class="form-control form-control-sm" value="{{ request('from_date') }}" placeholder="From" style="border-radius: 20px;">
                        <input type="date" name="to_date" class="form-control form-control-sm" value="{{ request('to_date') }}" placeholder="To" style="border-radius: 20px;">
                    </div>
                </div>
                
                <div class="col-12 text-end">
                    <button type="submit" class="btn" style="background: linear-gradient(145deg, #2563eb, #1d4ed8); color: white; border: none; border-radius: 20px; padding: 6px 24px; font-size: 0.8rem;">
                        <i class="fas fa-filter me-1"></i> Apply Filters
                    </button>
                    <a href="{{ route('transaction-logs.index') }}" class="btn" style="background: #f1f5f9; color: #475569; border: none; border-radius: 20px; padding: 6px 24px; font-size: 0.8rem;">
                        <i class="fas fa-redo me-1"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Logs Table -->
    <div class="content-card" style="border-radius: 24px; overflow: hidden; background: white;">
        <div class="px-4 py-3" style="background: linear-gradient(145deg, #f8fafc, #f1f5f9); border-bottom: 1px solid #e2e8f0;">
            <div class="d-flex align-items-center justify-content-between">
                <h5 class="mb-0" style="font-weight: 600; color: #1e293b; font-size: 1rem;">
                    <i class="fas fa-list me-2" style="color: #2563eb;"></i>
                    Transaction Audit Trail
                </h5>
                <span class="badge rounded-pill px-3 py-1" style="background: #2563eb; color: white; font-size: 0.7rem;">
                    {{ $logs->total() }} Records
                </span>
            </div>
        </div>
        
        <div class="p-3">
            <!-- Desktop Table View -->
            <div class="d-none d-md-block">
                <div class="table-responsive">
                    <table class="table" style="border-collapse: separate; border-spacing: 0 4px; width: 100%; font-size: 0.75rem;">
                        <thead>
                            <tr>
                                <th style="padding: 8px; font-weight: 600; color: #64748b;">Date/Time</th>
                                <th style="padding: 8px; font-weight: 600; color: #64748b;">Transaction</th>
                                <th style="padding: 8px; font-weight: 600; color: #64748b;">Donor</th>
                                <th style="padding: 8px; font-weight: 600; color: #64748b;">User</th>
                                <th style="padding: 8px; font-weight: 600; color: #64748b;">Action</th>
                                <th style="padding: 8px; font-weight: 600; color: #64748b;">Field</th>
                                <th style="padding: 8px; font-weight: 600; color: #64748b;">Old Value</th>
                                <th style="padding: 8px; font-weight: 600; color: #64748b;">New Value</th>
                                <th style="padding: 8px; font-weight: 600; color: #64748b;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($logs as $log)
                            <tr style="background: white; border-radius: 12px; box-shadow: 0 2px 6px -2px rgba(0,0,0,0.02); border: 1px solid #edf2f7;">
                                <td style="padding: 10px 8px; border: none;">
                                    <span style="font-size: 0.7rem;">{{ $log->created_at->format('d M Y') }}</span>
                                    <small class="text-secondary d-block" style="font-size: 0.55rem;">{{ $log->created_at->format('h:i A') }}</small>
                                </td>
                                <td style="padding: 10px 8px; border: none;">
                                    @if($log->transaction)
                                        <a href="{{ route('transactions.show', $log->transaction) }}" style="color: #2563eb; text-decoration: none; font-weight: 500;">
                                            #{{ $log->transaction->id }}
                                        </a>
                                        <small class="text-secondary d-block" style="font-size: 0.55rem;">{{ $log->transaction->month->name ?? '' }}</small>
                                    @else
                                        <span class="text-danger">[Deleted]</span>
                                    @endif
                                </td>
                                <td style="padding: 10px 8px; border: none;">
                                    @if($log->transaction && $log->transaction->donor)
                                        <span>{{ $log->transaction->donor->name }}</span>
                                    @else
                                        <span class="text-secondary">—</span>
                                    @endif
                                </td>
                                <td style="padding: 10px 8px; border: none;">
                                    @if($log->user)
                                        <div class="d-flex align-items-center gap-1">
                                            <div style="width: 22px; height: 22px; background: linear-gradient(135deg, #667eea, #764ba2); border-radius: 4px; display: flex; align-items: center; justify-content: center;">
                                                <span class="text-white" style="font-size: 0.55rem;">{{ substr($log->user->name, 0, 1) }}</span>
                                            </div>
                                            <span style="font-size: 0.65rem;">{{ $log->user->name }}</span>
                                        </div>
                                    @endif
                                </td>
                                <td style="padding: 10px 8px; border: none;">
                                    @if($log->action == 'created')
                                        <span class="badge rounded-pill px-2 py-1" style="background: #dbeafe; color: #1e40af; font-size: 0.55rem;">Created</span>
                                    @elseif($log->action == 'updated')
                                        <span class="badge rounded-pill px-2 py-1" style="background: #fef3c7; color: #92400e; font-size: 0.55rem;">Updated</span>
                                    @elseif($log->action == 'deleted')
                                        <span class="badge rounded-pill px-2 py-1" style="background: #fee2e2; color: #991b1b; font-size: 0.55rem;">Deleted</span>
                                    @endif
                                </td>
                                <td style="padding: 10px 8px; border: none;">
                                    @if($log->field_name)
                                        <span style="font-size: 0.65rem;">{{ ucfirst(str_replace('_', ' ', $log->field_name)) }}</span>
                                    @else
                                        <span class="text-secondary" style="font-size: 0.55rem;">—</span>
                                    @endif
                                </td>
                                <td style="padding: 10px 8px; border: none;">
                                    @if($log->old_value !== null)
                                        @if($log->field_name == 'amount')
                                            <span style="color: #ef4444; font-size: 0.65rem;">৳{{ number_format($log->old_value, 2) }}</span>
                                        @elseif($log->field_name == 'paid_status')
                                            <span class="badge" style="background: {{ $log->old_value == 'paid' ? '#dcfce7' : '#fee2e2' }}; color: {{ $log->old_value == 'paid' ? '#166534' : '#991b1b' }}; font-size: 0.5rem;">{{ $log->old_value }}</span>
                                        @else
                                            <span style="font-size: 0.6rem;">{{ Str::limit($log->old_value, 10) }}</span>
                                        @endif
                                    @else
                                        <span class="text-secondary" style="font-size: 0.55rem;">—</span>
                                    @endif
                                </td>
                                <td style="padding: 10px 8px; border: none;">
                                    @if($log->new_value !== null)
                                        @if($log->field_name == 'amount')
                                            <span style="color: #10b981; font-size: 0.65rem;">৳{{ number_format($log->new_value, 2) }}</span>
                                        @elseif($log->field_name == 'paid_status')
                                            <span class="badge" style="background: {{ $log->new_value == 'paid' ? '#dcfce7' : '#fee2e2' }}; color: {{ $log->new_value == 'paid' ? '#166534' : '#991b1b' }}; font-size: 0.5rem;">{{ $log->new_value }}</span>
                                        @else
                                            <span style="font-size: 0.6rem;">{{ Str::limit($log->new_value, 10) }}</span>
                                        @endif
                                    @else
                                        <span class="text-secondary" style="font-size: 0.55rem;">—</span>
                                    @endif
                                </td>
                                <td style="padding: 10px 8px; border: none;">
                                    <a href="{{ route('transaction-logs.show', $log) }}" class="btn btn-sm" style="background: #f1f5f9; color: #475569; border-radius: 20px; padding: 2px 8px; font-size: 0.55rem;">
                                        <i class="fas fa-eye me-1"></i> Details
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <div style="width: 48px; height: 48px; background: #f1f5f9; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 12px;">
                                        <i class="fas fa-history" style="color: #94a3b8; font-size: 1.2rem;"></i>
                                    </div>
                                    <h6 style="color: #475569; font-size: 0.9rem;">No logs found</h6>
                                    <p style="color: #64748b; font-size: 0.7rem;">Try adjusting your filters</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Mobile Card View -->
            <div class="d-block d-md-none">
                @forelse($logs as $log)
                <div class="mobile-log-card mb-2 p-2" style="background: white; border-radius: 16px; box-shadow: 0 2px 8px -2px rgba(0,0,0,0.03); border: 1px solid #edf2f7;">
                    <!-- Header -->
                    <div class="d-flex justify-content-between align-items-start mb-1">
                        <div class="d-flex align-items-center gap-2">
                            <div style="width: 32px; height: 32px; background: linear-gradient(135deg, #667eea, #764ba2); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                <span class="text-white fw-bold" style="font-size: 0.7rem;">{{ $log->transaction ? '#'.$log->transaction->id : '?' }}</span>
                            </div>
                            <div>
                                <span style="font-weight: 600; font-size: 0.75rem;">
                                    @if($log->transaction && $log->transaction->donor)
                                        {{ $log->transaction->donor->name }}
                                    @else
                                        Unknown Donor
                                    @endif
                                </span>
                                <small class="text-secondary d-block" style="font-size: 0.55rem;">{{ $log->created_at->format('d M Y, h:i A') }}</small>
                            </div>
                        </div>
                        @if($log->action == 'created')
                            <span class="badge" style="background: #dbeafe; color: #1e40af; font-size: 0.5rem;">Created</span>
                        @elseif($log->action == 'updated')
                            <span class="badge" style="background: #fef3c7; color: #92400e; font-size: 0.5rem;">Updated</span>
                        @elseif($log->action == 'deleted')
                            <span class="badge" style="background: #fee2e2; color: #991b1b; font-size: 0.5rem;">Deleted</span>
                        @endif
                    </div>
                    
                    <!-- User Info -->
                    <div class="d-flex align-items-center gap-1 mb-1">
                        <div style="width: 18px; height: 18px; background: linear-gradient(135deg, #3b82f6, #8b5cf6); border-radius: 4px; display: flex; align-items: center; justify-content: center;">
                            <span class="text-white" style="font-size: 0.45rem;">{{ substr($log->user->name ?? 'U', 0, 1) }}</span>
                        </div>
                        <span style="font-size: 0.6rem;">{{ $log->user->name ?? 'Unknown User' }}</span>
                    </div>
                    
                    <!-- Change Details -->
                    @if($log->field_name)
                    <div class="mt-1 p-1 rounded-2" style="background: #f8fafc;">
                        <div class="d-flex justify-content-between align-items-center">
                            <span style="font-size: 0.55rem; font-weight: 500; color: #2563eb;">{{ ucfirst(str_replace('_', ' ', $log->field_name)) }}</span>
                            <div class="d-flex gap-2">
                                <div>
                                    <small class="text-danger d-block" style="font-size: 0.45rem;">Old</small>
                                    @if($log->field_name == 'amount')
                                        <span style="color: #ef4444; font-size: 0.6rem;">৳{{ number_format($log->old_value, 2) }}</span>
                                    @else
                                        <span style="font-size: 0.55rem;">{{ Str::limit($log->old_value, 8) }}</span>
                                    @endif
                                </div>
                                <div>
                                    <small class="text-success d-block" style="font-size: 0.45rem;">New</small>
                                    @if($log->field_name == 'amount')
                                        <span style="color: #10b981; font-size: 0.6rem;">৳{{ number_format($log->new_value, 2) }}</span>
                                    @else
                                        <span style="font-size: 0.55rem;">{{ Str::limit($log->new_value, 8) }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <!-- Action Button -->
                    <div class="mt-2 text-end">
                        <a href="{{ route('transaction-logs.show', $log) }}" class="btn btn-sm" style="background: #2563eb; color: white; border-radius: 30px; padding: 4px 12px; font-size: 0.55rem;">
                            <i class="fas fa-eye me-1"></i> View Details
                        </a>
                    </div>
                </div>
                @empty
                <div class="text-center py-4">
                    <div style="width: 48px; height: 48px; background: #f1f5f9; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 12px;">
                        <i class="fas fa-history" style="color: #94a3b8; font-size: 1.2rem;"></i>
                    </div>
                    <h6 style="color: #475569; font-size: 0.9rem;">No logs found</h6>
                    <p style="color: #64748b; font-size: 0.7rem;">Try adjusting your filters</p>
                </div>
                @endforelse
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $logs->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>

<style>
tr {
    transition: all 0.2s ease;
}
tr:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px -2px rgba(37,99,235,0.1) !important;
}
.mobile-log-card {
    transition: all 0.2s ease;
}
.mobile-log-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 16px -4px rgba(37,99,235,0.1) !important;
}
</style>
@endsection