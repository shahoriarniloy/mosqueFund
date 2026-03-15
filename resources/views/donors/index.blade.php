@extends('layouts.app')

@section('title', 'Donors Management')
@section('page-title', 'Donors')
@section('page-subtitle', 'Manage and track your donors')

@section('quick-actions')
    <a href="{{ route('donors.create') }}" class="quick-action" style="background: blue; color: white; border: none; border-radius: 30px; padding: 8px 18px; font-weight: 500; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 8px;">
        <i class="fas fa-plus-circle"></i> New Donor
    </a>
    {{-- <a href="#" class="quick-action">
        <i class="fas fa-file-export"></i> Export
    </a>
    <a href="#" class="quick-action">
        <i class="fas fa-chart-bar"></i> Analytics
    </a> --}}
@endsection

@section('content')
<!-- Stats Cards - Responsive Grid -->
<div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 8px; margin-bottom: 16px;">
    
    <!-- Total Donors Card -->
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 10px; padding: 12px;">
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <p style="color: rgba(255,255,255,0.8); font-size: 0.55rem; margin-bottom: 2px; letter-spacing: 0.3px;">TOTAL</p>
                <h4 style="color: white; font-size: 1.2rem; font-weight: 700; margin: 0; line-height: 1.2;">{{ $totalDonors }}</h4>
            </div>
            <div style="width: 32px; height: 32px; background: rgba(255,255,255,0.2); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-users" style="font-size: 14px; color: white;"></i>
            </div>
        </div>
    </div>

    <!-- Active Donors Card -->
    <div style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); border-radius: 10px; padding: 12px;">
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <p style="color: rgba(255,255,255,0.8); font-size: 0.55rem; margin-bottom: 2px;">ACTIVE</p>
                <h4 style="color: white; font-size: 1.2rem; font-weight: 700; margin: 0;">{{ $activeDonors }}</h4>
            </div>
            <div style="width: 32px; height: 32px; background: rgba(255,255,255,0.2); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-user-check" style="font-size: 14px; color: white;"></i>
            </div>
        </div>
    </div>

    <!-- Inactive Donors Card -->
    <div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border-radius: 10px; padding: 12px;">
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <p style="color: rgba(255,255,255,0.8); font-size: 0.55rem; margin-bottom: 2px;">INACTIVE</p>
                <h4 style="color: white; font-size: 1.2rem; font-weight: 700; margin: 0;">{{ $inactiveDonors }}</h4>
            </div>
            <div style="width: 32px; height: 32px; background: rgba(255,255,255,0.2); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-user-times" style="font-size: 14px; color: white;"></i>
            </div>
        </div>
    </div>

    <!-- Monthly Total Card -->
    <div style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); border-radius: 10px; padding: 12px;">
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <p style="color: rgba(255,255,255,0.8); font-size: 0.55rem; margin-bottom: 2px;">MONTHLY</p>
                <h4 style="color: white; font-size: 1rem; font-weight: 700; margin: 0;">৳{{ number_format($totalMonthlyCommitment) }}</h4>
            </div>
            <div style="width: 32px; height: 32px; background: rgba(255,255,255,0.2); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-hand-holding-heart" style="font-size: 14px; color: white;"></i>
            </div>
        </div>
    </div>
</div>

<!-- Main Card - More Compact -->
<div style="background: white; border-radius: 16px; box-shadow: 0 4px 16px rgba(0, 0, 0, 0.03); overflow: hidden;">
    <!-- Card Header with Search - Compact -->
    <div style="padding: 14px 16px; border-bottom: 1px solid #edf2f7; background: #fafbfc;">
        <div style="display: flex; flex-direction: column; gap: 10px;">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <h5 style="font-size: 0.95rem; font-weight: 600; color: #1a202c; margin: 0;">
                    <i class="fas fa-list me-2" style="color: #667eea;"></i>Donors List
                </h5>
                <span style="background: #e2e8f0; color: #4a5568; padding: 2px 10px; border-radius: 20px; font-size: 0.7rem; font-weight: 500;" id="totalCount">
                    {{ $donors->total() }} Total
                </span>
            </div>
            
            <!-- Live Search Form -->
            <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                <div style="flex: 1; min-width: 200px; position: relative;">
                    <i class="fas fa-search" style="position: absolute; left: 10px; top: 50%; transform: translateY(-50%); color: #a0aec0; font-size: 0.8rem;"></i>
                    <input type="text" id="liveSearch" value="{{ request('search') }}" 
                           placeholder="Search donors..." 
                           style="width: 100%; padding: 8px 10px 8px 32px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.85rem; outline: none;">
                    <div id="searchSpinner" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); display: none;">
                        <div class="spinner-border spinner-border-sm" style="color: #667eea; width: 14px; height: 14px;" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
                <select id="statusFilter" style="padding: 8px 10px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.85rem; min-width: 100px; background: white;">
                    <option value="">All</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
                <button id="clearFilters" style="padding: 8px 16px; background: #edf2f7; color: #4a5568; border: none; border-radius: 8px; font-size: 0.85rem; cursor: pointer; display: inline-flex; align-items: center; gap: 4px; {{ request('search') || request('status') ? '' : 'display: none;' }}">
                    <i class="fas fa-times"></i> Clear
                </button>
            </div>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div id="loadingOverlay" style="display: none; position: relative; width: 100%; height: 200px; background: rgba(255,255,255,0.8); z-index: 10; justify-content: center; align-items: center;">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <!-- Mobile View -->
    <div class="d-block d-md-none" id="mobileDonorsContainer">
        @include('donors.partials.mobile-donors', ['donors' => $donors])
    </div>

    <!-- Desktop Table View -->
    <div class="d-none d-md-block" id="desktopDonorsContainer">
        @include('donors.partials.desktop-donors', ['donors' => $donors])
    </div>

    <!-- Pagination Container -->
    <div id="paginationContainer" style="padding: 12px; border-top: 1px solid #edf2f7;">
    {{ $donors->links('pagination::bootstrap-5') }}
</div>
</div>

<!-- Responsive Styles -->
<style>
/* Mobile styles (default) */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 8px;
    margin-bottom: 16px;
}

/* Tablet and Desktop styles */
@media (min-width: 768px) {
    .stats-grid {
        grid-template-columns: repeat(4, 1fr);
    }
}

/* Add smooth transitions */
#mobileDonorsContainer, #desktopDonorsContainer {
    transition: opacity 0.3s ease;
}

#searchSpinner {
    transition: all 0.2s ease;
}

/* Loading overlay */
#loadingOverlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255,255,255,0.8);
    backdrop-filter: blur(2px);
}
</style>

@push('scripts')
<script>
let searchTimeout;

// Live search function
function performSearch() {
    const search = document.getElementById('liveSearch').value;
    const status = document.getElementById('statusFilter').value;
    
    // Show spinner
    document.getElementById('searchSpinner').style.display = 'block';
    
    // Build URL
    const url = new URL(window.location.href);
    url.searchParams.set('search', search);
    url.searchParams.set('status', status);
    url.searchParams.set('page', '1'); // Reset to first page
    
    // Fetch results
    fetch(url.toString(), {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.text())
    .then(html => {
        // Parse the HTML response
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        
        // Update mobile view
        const mobileContent = doc.querySelector('#mobileDonorsContainer');
        if (mobileContent) {
            document.getElementById('mobileDonorsContainer').innerHTML = mobileContent.innerHTML;
        }
        
        // Update desktop view
        const desktopContent = doc.querySelector('#desktopDonorsContainer');
        if (desktopContent) {
            document.getElementById('desktopDonorsContainer').innerHTML = desktopContent.innerHTML;
        }
        
        // Update pagination
        const pagination = doc.querySelector('#paginationContainer');
        if (pagination) {
            document.getElementById('paginationContainer').innerHTML = pagination.innerHTML;
        }
        
        // Update total count
        const totalCount = doc.querySelector('#totalCount');
        if (totalCount) {
            document.getElementById('totalCount').textContent = totalCount.textContent;
        }
        
        // Show/hide clear button
        const clearBtn = document.getElementById('clearFilters');
        if (search || status) {
            clearBtn.style.display = 'inline-flex';
        } else {
            clearBtn.style.display = 'none';
        }
        
        // Update URL without reloading
        window.history.pushState({}, '', url.toString());
    })
    .catch(error => console.error('Error:', error))
    .finally(() => {
        document.getElementById('searchSpinner').style.display = 'none';
    });
}

// Debounced search
document.getElementById('liveSearch').addEventListener('input', function() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(performSearch, 500); // Wait 500ms after typing stops
});

// Status filter change
document.getElementById('statusFilter').addEventListener('change', function() {
    performSearch();
});

// Clear filters
document.getElementById('clearFilters').addEventListener('click', function() {
    document.getElementById('liveSearch').value = '';
    document.getElementById('statusFilter').value = '';
    performSearch();
});

// Handle browser back/forward buttons
window.addEventListener('popstate', function() {
    location.reload();
});
</script>
@endpush

@endsection