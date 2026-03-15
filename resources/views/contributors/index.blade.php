{{-- resources/views/contributors/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Contributors - MosqueFund')
@section('page-title', 'Contributors')
@section('page-subtitle', 'Random/One-time donors')

@section('quick-actions')
    <a href="{{ route('contributors.export') }}" class="quick-action">
        <i class="fas fa-download"></i>
        <span>Export</span>
    </a>
@endsection

@section('content')
<div class="container-fluid px-0">
    <!-- Main Table Card -->
    <div class="content-card-modern">
        <!-- Search -->
        <div class="row g-3 mb-4">
            <div class="col-12 col-md-6">
                <form action="{{ route('contributors.index') }}" method="GET" class="d-flex gap-2">
                    <div class="search-box flex-grow-1">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" 
                               name="search" 
                               class="form-control" 
                               placeholder="Search by name or phone..." 
                               value="{{ request('search') }}">
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i>
                    </button>
                    @if(request('search'))
                        <a href="{{ route('contributors.index') }}" class="btn btn-light" title="Clear filters">
                            <i class="fas fa-times"></i>
                        </a>
                    @endif
                </form>
            </div>
        </div>

        <!-- Contributors Table -->
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Count</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($contributors as $contributor)
                        <tr>
                            <td>{{ $contributors->firstItem() + $loop->index }}</td>
                            <td>
                                <a href="{{ route('contributors.show', $contributor) }}" class="text-decoration-none fw-semibold">
                                    {{ $contributor->name }}
                                </a>
                            </td>
                            <td>{{ $contributor->phone }}</td>
                            <td>{{ $contributor->donation_count }}</td>

                            <td>{{ $contributor->created_at->format('M d, Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5">
                                <div class="empty-state">
                                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                    <h5>No Contributors Found</h5>
                                    <p class="text-muted">Contributors will appear automatically when donations are added.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4">
            <div class="text-muted small mb-2 mb-md-0">
                Showing {{ $contributors->firstItem() ?? 0 }} to {{ $contributors->lastItem() ?? 0 }} of {{ $contributors->total() }} contributors
            </div>
            <div>
                {{ $contributors->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .content-card-modern {
        background: white;
        border-radius: 24px;
        padding: 20px;
        box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.05);
        border: 1px solid rgba(226, 232, 240, 0.6);
    }
    
    .search-box {
        position: relative;
    }
    
    .search-icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        z-index: 10;
    }
    
    .search-box input {
        padding-left: 35px;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        background: #f8fafc;
    }
    
    .table thead th {
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        color: #64748b;
        border-bottom: 2px solid #e2e8f0;
    }
    
    .table tbody td {
        padding: 16px 8px;
        border-bottom: 1px solid #e2e8f0;
    }
    
    .empty-state {
        padding: 40px 20px;
        text-align: center;
    }
    
    .btn-primary {
        background: #2563eb;
        border: none;
        border-radius: 12px;
    }
</style>
@endpush