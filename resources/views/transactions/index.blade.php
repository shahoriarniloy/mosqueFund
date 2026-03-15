@extends('layouts.app')

@section('title', 'Transactions Management')
@section('page-title', 'Transactions')
@section('page-subtitle', 'Manage all monthly transactions')

@section('quick-actions')
    <a href="{{ route('transactions.create') }}" class="quick-action">
        <i class="fas fa-plus-circle"></i> New Transaction
    </a>
    <a href="{{ route('transaction-logs.index') }}" class="quick-action">
        <i class="fas fa-history"></i> Logs
    </a>
@endsection

@section('content')
<div class="container-fluid px-2 px-sm-3">
    <!-- Compact Statistics Cards -->
    <div class="row g-1 g-sm-2 mb-2" id="statsContainer">
        @include('transactions.partials.stats')
    </div>

    <!-- Filter Section -->
    <div class="mb-2" style="background: white; border-radius: 12px; border: 1px solid #eef2f8;">
        <div class="p-2">
            <div class="row g-1">
                <div class="col-12 col-md-3">
                    <div class="position-relative">
                        <input type="text" id="searchInput" class="form-control form-control-sm" placeholder="Search by donor..." value="{{ request('search') }}" style="border-radius: 20px; padding: 6px 12px; padding-right: 35px; font-size: 0.8rem;">
                        <div id="searchSpinner" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); display: none;">
                            <div class="spinner-border spinner-border-sm" style="color: #2563eb; width: 14px; height: 14px;"></div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-2">
                    <select id="donorFilter" class="form-select form-select-sm" style="border-radius: 20px; padding: 6px 12px; font-size: 0.8rem;">
                        <option value="">All Donors</option>
                        @foreach($donors as $donor)
                            <option value="{{ $donor->id }}" {{ request('donor_id') == $donor->id ? 'selected' : '' }}>{{ $donor->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <select id="monthFilter" class="form-select form-select-sm" style="border-radius: 20px; padding: 6px 12px; font-size: 0.8rem;">
                        <option value="">All Months</option>
                        @foreach($months as $month)
                            <option value="{{ $month->id }}" {{ request('month_id') == $month->id ? 'selected' : '' }}>{{ $month->name }} {{ $month->year }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <select id="statusFilter" class="form-select form-select-sm" style="border-radius: 20px; padding: 6px 12px; font-size: 0.8rem;">
                        <option value="">All Status</option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                    </select>
                </div>
                <div class="col-6 col-md-3">
                    <select id="paymentMethodFilter" class="form-select form-select-sm" style="border-radius: 20px; padding: 6px 12px; font-size: 0.8rem;">
                        <option value="">All Methods</option>
                        <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                        <option value="bkash" {{ request('payment_method') == 'bkash' ? 'selected' : '' }}>bKash</option>
                        <option value="nagad" {{ request('payment_method') == 'nagad' ? 'selected' : '' }}>Nagad</option>
                    </select>
                </div>
            </div>
            <div class="row g-1 mt-1">
                {{-- <div class="col-12 col-md-9">
                    <div class="d-flex gap-1">
                        <input type="date" id="fromDate" class="form-control form-control-sm" value="{{ request('from_date') }}" placeholder="From" style="border-radius: 20px; padding: 6px 12px; font-size: 0.8rem;">
                        <input type="date" id="toDate" class="form-control form-control-sm" value="{{ request('to_date') }}" placeholder="To" style="border-radius: 20px; padding: 6px 12px; font-size: 0.8rem;">
                    </div>
                </div> --}}
                <div class="col-12 col-md-3">
                    <div class="d-flex gap-1">
                        <button id="applyFilters" class="btn btn-sm w-100" style="background: linear-gradient(145deg, #2563eb, #1d4ed8); color: white; border: none; border-radius: 20px; padding: 6px 12px; font-size: 0.8rem;">
                            <i class="fas fa-filter me-1"></i> Apply
                        </button>
                        <button id="resetFilters" class="btn btn-sm w-100" style="background: #f1f5f9; color: #475569; border: none; border-radius: 20px; padding: 6px 12px; font-size: 0.8rem;">
                            <i class="fas fa-redo"></i> Reset
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Results Count -->
    <div class="d-flex justify-content-between align-items-center mb-2">
        <span id="resultsCount" style="font-size: 0.7rem; color: #64748b;">Loading...</span>
        <span id="clearFilters" style="font-size: 0.65rem; color: #2563eb; cursor: pointer; display: none;" onclick="clearAllFilters()">
            <i class="fas fa-times-circle me-1"></i> Clear all filters
        </span>
    </div>

    <!-- Mobile Cards Container -->
    <div id="mobileTransactionsContainer" class="d-block d-md-none">
        @include('transactions.partials.mobile-cards', ['transactions' => $transactions])
    </div>

    <!-- Desktop Table Container -->
    <div id="desktopTransactionsContainer" class="d-none d-md-block">
        @include('transactions.partials.desktop-table', ['transactions' => $transactions])
    </div>

    <!-- Pagination Container -->
    <div id="paginationContainer" class="d-flex justify-content-center mt-3">
        {{ $transactions->withQueryString()->links() }}
    </div>
</div>

<style>
/* Loading states */
#mobileTransactionsContainer, #desktopTransactionsContainer {
    transition: opacity 0.3s ease;
}

/* Hover effects */
tr {
    transition: all 0.2s ease;
}
tr:hover {
    background: #f8fafc;
}

.mobile-transaction-card {
    transition: all 0.2s ease;
}
.mobile-transaction-card:active {
    background: #f8fafc;
    transform: scale(0.99);
}

/* Stat hover */
.stat-compact {
    transition: all 0.2s ease;
}
.stat-compact:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 16px -4px rgba(0,0,0,0.1) !important;
}
</style>

@push('scripts')
<script>
let searchTimeout;
let currentRequest = null;

$(document).ready(function() {
    // Load initial results count
    updateResultsCount('{{ $transactions->total() }}');

    // Live search on input
    $('#searchInput').on('input', function() {
        clearTimeout(searchTimeout);
        
        // Show/hide clear button
        toggleClearButton();
        
        // Show spinner
        $('#searchSpinner').show();
        
        // Debounce search
        searchTimeout = setTimeout(function() {
            performSearch();
        }, 500);
    });

    // Filter on select change
    $('#donorFilter, #monthFilter, #statusFilter, #paymentMethodFilter, #fromDate, #toDate').on('change', function() {
        toggleClearButton();
        performSearch();
    });

    // Apply filters button
    $('#applyFilters').on('click', function() {
        performSearch();
    });

    // Reset filters button
    $('#resetFilters').on('click', function() {
        $('#searchInput').val('');
        $('#donorFilter').val('');
        $('#monthFilter').val('');
        $('#statusFilter').val('');
        $('#paymentMethodFilter').val('');
        $('#fromDate').val('');
        $('#toDate').val('');
        toggleClearButton();
        performSearch();
    });

    // Function to perform AJAX search
    function performSearch(page = 1) {
        // Abort previous request if exists
        if (currentRequest) {
            currentRequest.abort();
        }

        const search = $('#searchInput').val();
        const donorId = $('#donorFilter').val();
        const monthId = $('#monthFilter').val();
        const status = $('#statusFilter').val();
        const paymentMethod = $('#paymentMethodFilter').val();
        const fromDate = $('#fromDate').val();
        const toDate = $('#toDate').val();

        // Show loading state
        $('#searchSpinner').show();
        $('#mobileTransactionsContainer').css('opacity', '0.5');
        $('#desktopTransactionsContainer').css('opacity', '0.5');

        // Make AJAX request
        currentRequest = $.ajax({
            url: '{{ route("transactions.index") }}',
            method: 'GET',
            data: {
                search: search,
                donor_id: donorId,
                month_id: monthId,
                status: status,
                payment_method: paymentMethod,
                from_date: fromDate,
                to_date: toDate,
                page: page,
                ajax: true
            },
            success: function(response) {
                // Update mobile cards
                $('#mobileTransactionsContainer').html(response.mobileCards);
                
                // Update desktop table
                $('#desktopTransactionsContainer').html(response.desktopTable);
                
                // Update pagination
                $('#paginationContainer').html(response.pagination);
                
                // Update results count
                updateResultsCount(response.total);
                
                // Update stats if provided
                if (response.stats) {
                    $('#statsContainer').html(response.stats);
                }
                
                // Hide loading
                $('#searchSpinner').hide();
                $('#mobileTransactionsContainer').css('opacity', '1');
                $('#desktopTransactionsContainer').css('opacity', '1');
                
                // Update URL without reload
                updateUrl(search, donorId, monthId, status, paymentMethod, fromDate, toDate, page);
                
                currentRequest = null;
            },
            error: function(xhr) {
                if (xhr.statusText !== 'abort') {
                    console.error('Search failed:', xhr);
                }
                $('#searchSpinner').hide();
                $('#mobileTransactionsContainer').css('opacity', '1');
                $('#desktopTransactionsContainer').css('opacity', '1');
            }
        });
    }

    // Function to update results count
    function updateResultsCount(total) {
        $('#resultsCount').text(`Showing ${total} transaction${total != 1 ? 's' : ''}`);
    }

    // Function to toggle clear button
    function toggleClearButton() {
        const search = $('#searchInput').val();
        const donorId = $('#donorFilter').val();
        const monthId = $('#monthFilter').val();
        const status = $('#statusFilter').val();
        const paymentMethod = $('#paymentMethodFilter').val();
        const fromDate = $('#fromDate').val();
        const toDate = $('#toDate').val();
        
        if (search || donorId || monthId || status || paymentMethod || fromDate || toDate) {
            $('#clearFilters').show();
        } else {
            $('#clearFilters').hide();
        }
    }

    // Function to clear all filters
    window.clearAllFilters = function() {
        $('#searchInput').val('');
        $('#donorFilter').val('');
        $('#monthFilter').val('');
        $('#statusFilter').val('');
        $('#paymentMethodFilter').val('');
        $('#fromDate').val('');
        $('#toDate').val('');
        $('#clearFilters').hide();
        performSearch();
    };

    // Function to update URL
    function updateUrl(search, donorId, monthId, status, paymentMethod, fromDate, toDate, page) {
        const params = new URLSearchParams();
        if (search) params.set('search', search);
        if (donorId) params.set('donor_id', donorId);
        if (monthId) params.set('month_id', monthId);
        if (status) params.set('status', status);
        if (paymentMethod) params.set('payment_method', paymentMethod);
        if (fromDate) params.set('from_date', fromDate);
        if (toDate) params.set('to_date', toDate);
        if (page > 1) params.set('page', page);
        
        const newUrl = window.location.pathname + (params.toString() ? '?' + params.toString() : '');
        window.history.pushState({}, '', newUrl);
    }

    // Handle browser back/forward
    window.addEventListener('popstate', function() {
        location.reload();
    });

    // Handle pagination clicks
    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();
        const page = $(this).attr('href').split('page=')[1];
        performSearch(page);
    });
});
</script>
@endpush
@endsection