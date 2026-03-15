@extends('layouts.app')

@section('title', 'Registration Logs')
@section('page-title', 'Registration Logs')
@section('page-subtitle', 'Monitor user registrations')

@section('quick-actions')
    <a href="{{ route('registration-logs.export') }}?{{ http_build_query(request()->all()) }}" class="quick-action">
        <i class="fas fa-download"></i> Export
    </a>
@endsection

@section('content')
<div class="container-fluid px-2 px-sm-3">
    <!-- Stats Cards -->
    <div class="row g-2 g-sm-3 mb-3">
        <div class="col-6 col-md-3">
            <div class="stat-card p-2" style="background: linear-gradient(135deg, #667eea, #764ba2); border-radius: 10px;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-white opacity-75" style="font-size: 0.6rem;">TOTAL</span>
                        <h4 class="text-white mb-0" style="font-size: 1.3rem;">{{ $stats['total'] }}</h4>
                    </div>
                    <i class="fas fa-users text-white opacity-50" style="font-size: 1.5rem;"></i>
                </div>
            </div>
        </div>
        
        <div class="col-6 col-md-3">
            <div class="stat-card p-2" style="background: linear-gradient(135deg, #10b981, #059669); border-radius: 10px;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-white opacity-75" style="font-size: 0.6rem;">SUCCESS</span>
                        <h4 class="text-white mb-0" style="font-size: 1.3rem;">{{ $stats['successful'] }}</h4>
                    </div>
                    <i class="fas fa-check-circle text-white opacity-50" style="font-size: 1.5rem;"></i>
                </div>
            </div>
        </div>
        
        <div class="col-6 col-md-3">
            <div class="stat-card p-2" style="background: linear-gradient(135deg, #ef4444, #dc2626); border-radius: 10px;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-white opacity-75" style="font-size: 0.6rem;">FAILED</span>
                        <h4 class="text-white mb-0" style="font-size: 1.3rem;">{{ $stats['failed'] }}</h4>
                    </div>
                    <i class="fas fa-exclamation-circle text-white opacity-50" style="font-size: 1.5rem;"></i>
                </div>
            </div>
        </div>
        
        <div class="col-6 col-md-3">
            <div class="stat-card p-2" style="background: linear-gradient(135deg, #f59e0b, #d97706); border-radius: 10px;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-white opacity-75" style="font-size: 0.6rem;">RATE</span>
                        <h4 class="text-white mb-0" style="font-size: 1.3rem;">{{ $stats['success_rate'] }}%</h4>
                    </div>
                    <i class="fas fa-chart-line text-white opacity-50" style="font-size: 1.5rem;"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="card mb-3 border-0 shadow-sm">
        <div class="card-body">
            <form action="{{ route('registration-logs.index') }}" method="GET" class="row g-2">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control" placeholder="Search name, phone, IP..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="success" {{ request('status') == 'success' ? 'selected' : '' }}>Success</option>
                        <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="created_by" class="form-select">
                        <option value="">All Creators</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('created_by') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}" placeholder="From">
                </div>
                <div class="col-md-2">
                    <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}" placeholder="To">
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Logs Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-3 py-2">Time</th>
                            <th class="py-2">Name</th>
                            <th class="py-2">Phone</th>
                            <th class="py-2">Created By</th>
                            <th class="py-2">Status</th>
                            <th class="py-2">Browser/Platform</th>
                            <th class="py-2">Device</th>
                            <th class="py-2">Location</th>
                            <th class="py-2">IP Address</th>
                            <th class="px-3 py-2">Error</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                        <tr>
                            <td class="px-3 py-1" style="font-size: 0.8rem;">
                                {{ $log->created_at->format('d M Y H:i') }}
                            </td>
                            <td class="py-1">
                                <strong>{{ $log->name }}</strong>
                                @if($log->user)
                                    <br><small class="text-muted">User ID: {{ $log->user->id }}</small>
                                @endif
                            </td>
                            <td class="py-1">{{ $log->phone }}</td>
                            <td class="py-1">
                                @if($log->creator)
                                    <span class="badge bg-info bg-opacity-10 text-info px-2 py-1">
                                        <i class="fas fa-user-cog me-1"></i>{{ $log->creator->name }}
                                    </span>
                                @else
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary px-2 py-1">
                                        <i class="fas fa-robot me-1"></i>System/Auto
                                    </span>
                                @endif
                            </td>
                            <td class="py-1">
                                @if($log->is_successful)
                                    <span class="badge bg-success bg-opacity-10 text-success px-2 py-1">
                                        <i class="fas fa-check-circle me-1"></i>Success
                                    </span>
                                @else
                                    <span class="badge bg-danger bg-opacity-10 text-danger px-2 py-1">
                                        <i class="fas fa-exclamation-circle me-1"></i>Failed
                                    </span>
                                @endif
                            </td>
                            <td class="py-1">
                                {{ $log->browser ?? 'Unknown' }}
                                <br><small class="text-muted">{{ $log->platform ?? '' }}</small>
                            </td>
                            <td class="py-1">{{ $log->device ?? 'Unknown' }}</td>
                            <td class="py-1">{{ $log->location ?? 'Unknown' }}</td>
                            <td class="py-1"><code>{{ $log->ip_address }}</code></td>
                            <td class="px-3 py-1" style="max-width: 200px;">
                                @if(!$log->is_successful && $log->error_message)
                                    <span class="text-danger small" title="{{ $log->error_message }}">
                                        <i class="fas fa-exclamation-triangle me-1"></i>
                                        {{ Str::limit($log->error_message, 30) }}
                                    </span>
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="fas fa-clipboard-list fa-3x mb-3 opacity-25"></i>
                                    <p class="mb-0">No registration logs found</p>
                                    <p class="small">Try adjusting your filters or clear them to see more results</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="px-3 py-3 border-top d-flex justify-content-between align-items-center">
                <div class="text-muted small">
                    @if($logs->total() > 0)
                        Showing {{ $logs->firstItem() }} to {{ $logs->lastItem() }} of {{ $logs->total() }} entries
                    @else
                        Showing 0 entries
                    @endif
                </div>
                <div>
                    {{ $logs->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .stat-card {
        transition: transform 0.2s;
        cursor: default;
    }
    .stat-card:hover {
        transform: translateY(-2px);
    }
    .badge {
        font-weight: 500;
        font-size: 0.75rem;
        white-space: nowrap;
    }
    code {
        font-size: 0.75rem;
        background: #f8f9fa;
        padding: 0.2rem 0.4rem;
        border-radius: 4px;
        color: #d63384;
    }
    .table td {
        vertical-align: middle;
        font-size: 0.85rem;
    }
    .table th {
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #6c757d;
    }
    .form-control, .form-select {
        font-size: 0.9rem;
        border-color: #e9ecef;
    }
    .form-control:focus, .form-select:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.2rem rgba(13,110,253,.15);
    }
    .btn-primary {
        background: linear-gradient(135deg, #667eea, #764ba2);
        border: none;
    }
    .btn-primary:hover {
        background: linear-gradient(135deg, #5a6fd6, #6a4292);
    }
</style>
@endpush

@push('scripts')
<script>
    // Auto-submit form when dropdowns change (optional)
    document.querySelectorAll('select[name="status"], select[name="created_by"]').forEach(select => {
        select.addEventListener('change', function() {
            this.form.submit();
        });
    });
</script>
@endpush