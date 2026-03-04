<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - MosqueFund</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Space+Grotesk:wght@500;600&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #f0f2f5;
            color: #1a2639;
            overflow-x: hidden;
        }

        /* Fixed widths */
        :root {
            --sidebar-width: 240px;
            --sidebar-collapsed-width: 70px;
            --top-bar-height: 80px;
        }

        /* Sidebar */
        .sidebar-modern {
            position: fixed;
            top: 20px;
            left: 20px;
            bottom: 20px;
            width: var(--sidebar-width);
            background: rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            border-radius: 32px;
            padding: 16px 8px;
            box-shadow: 0 20px 40px -12px rgba(0,20,40,0.25);
            transition: width 0.25s ease;
            z-index: 1001;
            overflow-y: auto;
        }

        .sidebar-modern.collapsed {
            width: var(--sidebar-collapsed-width);
        }

        .sidebar-modern.collapsed .section-title-modern span,
        .sidebar-modern.collapsed .nav-text-modern,
        .sidebar-modern.collapsed .user-details-compact,
        .sidebar-modern.collapsed .dropdown-trigger span,
        .sidebar-modern.collapsed .dropdown-trigger .fa-chevron-down,
        .sidebar-modern.collapsed #transactionsDropdown {
            display: none !important;
        }

        .sidebar-modern.collapsed .nav-item-modern {
            justify-content: center;
            padding: 8px 0;
        }

        .sidebar-modern.collapsed .nav-item-modern i {
            margin: 0;
            font-size: 1.1rem;
        }

        .sidebar-modern.collapsed .dropdown-trigger {
            justify-content: center !important;
            padding: 8px 0 !important;
        }

        .sidebar-modern.collapsed .dropdown-trigger i:first-child {
            margin: 0 !important;
            font-size: 1.1rem !important;
        }

        .sidebar-modern.collapsed .user-section-compact {
            justify-content: center;
            padding: 8px 0;
        }

        .sidebar-modern.collapsed .user-avatar-micro {
            margin: 0;
        }

        /* Compact User Section */
        .user-section-compact {
            border-bottom: 1px solid rgba(226, 232, 240, 0.3);
            margin-bottom: 8px;
        }

        .user-avatar-micro {
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, #3b82f6, #8b5cf6);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 0.8rem;
        }

        .user-details-compact {
            line-height: 1.2;
        }

        .user-details-compact div:first-child {
            font-size: 0.75rem;
            font-weight: 600;
            color: #1e293b;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 120px;
        }

        .user-details-compact div:last-child {
            font-size: 0.6rem;
            color: #64748b;
        }

        /* Top Bar */
        .top-bar {
            position: fixed;
            top: 20px;
            left: calc(var(--sidebar-width) + 40px);
            right: 20px;
            z-index: 1000;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            border-radius: 60px;
            padding: 12px 24px;
            box-shadow: 0 10px 30px -10px rgba(0,0,0,0.08);
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: left 0.25s ease;
        }

        body.sidebar-collapsed .top-bar {
            left: calc(var(--sidebar-collapsed-width) + 40px);
        }

        /* Main Content */
        .main-wrapper {
            margin-left: calc(var(--sidebar-width) + 40px);
            margin-top: calc(var(--top-bar-height) + 20px);
            margin-right: 20px;
            margin-bottom: 20px;
            transition: margin-left 0.25s ease;
            min-height: calc(100vh - var(--top-bar-height) - 40px);
        }

        body.sidebar-collapsed .main-wrapper {
            margin-left: calc(var(--sidebar-collapsed-width) + 40px);
        }

        /* Content card */
        .content-card {
            background: white;
            border-radius: 36px;
            padding: 24px;
            box-shadow: 0 20px 40px -12px rgba(0,20,40,0.12);
            border: 1px solid rgba(226, 232, 240, 0.6);
            width: 100%;
        }

        /* Brand area */
        .brand-area {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .menu-btn-modern {
            background: #ffffff;
            border: 1px solid #e9ecf0;
            border-radius: 50%;
            width: 42px;
            height: 42px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #2f3b5c;
            font-size: 1.2rem;
            cursor: pointer;
            transition: 0.2s;
            box-shadow: 0 4px 8px rgba(0,0,0,0.02);
        }

        .logo-modern {
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 600;
            font-size: 1.4rem;
            background: linear-gradient(135deg, #0f2b4b, #1e4a7a);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: -0.5px;
        }

        .logo-modern i {
            background: #1e4a7a;
            -webkit-text-fill-color: white;
            background: linear-gradient(145deg, #2563eb, #1e4a7a);
            padding: 8px;
            border-radius: 14px;
            margin-right: 8px;
            font-size: 1rem;
            color: white;
        }

        /* User avatar */
        .user-avatar-modern {
            width: 48px;
            height: 48px;
            background: linear-gradient(145deg, #2563eb, #1e4a7a);
            border-radius: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 1.1rem;
            box-shadow: 0 6px 12px rgba(37,99,235,0.2);
            cursor: pointer;
        }

        /* Sidebar navigation - Compact */
        .section-title-modern {
            padding: 12px 12px 4px;
            font-size: 0.6rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            color: #64748b;
        }

        .nav-item-modern {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 12px;
            margin: 2px 6px;
            border-radius: 12px;
            color: #475569;
            text-decoration: none;
            transition: all 0.2s;
            font-weight: 500;
        }

        .nav-item-modern i {
            width: 18px;
            font-size: 0.9rem;
            color: #64748b;
        }

        .nav-item-modern:hover {
            background: rgba(37, 99, 235, 0.08);
            color: #2563eb;
        }

        .nav-item-modern:hover i {
            color: #2563eb;
        }

        .nav-item-modern.active {
            background: linear-gradient(90deg, rgba(37, 99, 235, 0.1), transparent);
            border-left: 2px solid #2563eb;
            color: #2563eb;
        }

        .nav-item-modern.active i {
            color: #2563eb;
        }

        .nav-text-modern {
            font-size: 0.8rem;
        }

        /* Dropdown styles */
        .dropdown-container {
            position: relative;
        }

        .dropdown-trigger {
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 8px 12px;
            margin: 2px 6px;
            border-radius: 12px;
            transition: all 0.2s;
        }

        .dropdown-trigger:hover {
            background: rgba(37, 99, 235, 0.08);
        }

        .dropdown-trigger.active {
            background: linear-gradient(90deg, rgba(37, 99, 235, 0.1), transparent);
            border-left: 2px solid #2563eb;
        }

        #transactionsDropdown {
            margin-left: 20px;
            transition: all 0.2s;
        }

        #transactionsDropdown .nav-item-modern {
            padding: 6px 12px;
            margin: 2px 6px;
            border-radius: 10px;
        }

        #transactionsDropdown .nav-item-modern i {
            width: 16px;
            font-size: 0.8rem;
        }

        #transactionsDropdown .nav-item-modern span {
            font-size: 0.75rem;
        }

        #transactionsDropdown .nav-item-modern:hover {
            background: rgba(37, 99, 235, 0.08);
            color: #2563eb;
        }

        #transactionsDropdown .nav-item-modern.active {
            background: linear-gradient(90deg, rgba(37, 99, 235, 0.1), transparent);
            border-left: 2px solid #2563eb;
            color: #2563eb;
        }

        #transactionsDropdown .nav-item-modern.active i {
            color: #2563eb;
        }

        /* Page header */
        .page-header-modern {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 28px;
            flex-wrap: wrap;
            gap: 16px;
        }

        .page-header-modern h1 {
            font-size: 1.8rem;
            font-weight: 600;
            font-family: 'Space Grotesk', sans-serif;
            color: #0b1e33;
            margin: 0;
        }

        .page-header-modern small {
            font-size: 0.9rem;
            font-weight: 400;
            color: #5f6c84;
            margin-left: 8px;
        }

        /* Quick actions */
        .quick-actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .quick-action {
            padding: 8px 16px;
            background: #f1f5f9;
            border-radius: 40px;
            color: #475569;
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s;
            border: 1px solid #e2e8f0;
        }

        .quick-action:hover {
            background: #2563eb;
            color: white;
            border-color: #2563eb;
        }

        /* Mobile adjustments */
        @media (max-width: 768px) {
            :root {
                --sidebar-width: 280px;
            }

            .top-bar {
                left: 20px;
                right: 20px;
            }

            .sidebar-modern {
                left: -300px;
                transition: left 0.3s;
            }

            .sidebar-modern.mobile-open {
                left: 20px;
            }

            .main-wrapper {
                margin-left: 20px;
                margin-right: 20px;
            }

            body.sidebar-collapsed .main-wrapper {
                margin-left: 20px;
            }

            .brand-area .logo-modern span {
                display: none;
            }

            .nav-item-modern {
                padding: 10px 14px;
            }

            .nav-item-modern i {
                font-size: 1rem;
                width: 20px;
            }

            .nav-text-modern {
                font-size: 0.85rem;
            }

            #transactionsDropdown .nav-item-modern {
                padding: 8px 12px;
            }
            
            #transactionsDropdown .nav-item-modern span {
                font-size: 0.8rem;
            }
        }

        /* Overlay */
        .sidebar-overlay-modern {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,10,30,0.4);
            backdrop-filter: blur(2px);
            z-index: 999;
        }

        /* Print styles */
        @media print {
            .sidebar-modern,
            .top-bar,
            .quick-action,
            .btn,
            footer,
            .sidebar-overlay-modern {
                display: none !important;
            }

            .main-wrapper {
                margin: 0 !important;
                padding: 20px !important;
            }

            .content-card {
                box-shadow: none !important;
                border: 1px solid #e2e8f0 !important;
            }
        }
    </style>

    @stack('styles')
</head>
<body>
    @auth
        <!-- Sidebar Overlay (Mobile) -->
        <div class="sidebar-overlay-modern" id="sidebarOverlayModern"></div>
        
        <!-- Sidebar - Modern Compact -->
        <div class="sidebar-modern" id="sidebarModern">
            <!-- Compact User Section -->
            <div class="user-section-compact d-flex align-items-center gap-2 px-3 py-2 mb-2">
                <div class="user-avatar-micro">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div class="user-details-compact">
                    <div>{{ Auth::user()->name }}</div>
                    <div>Administrator</div>
                </div>
            </div>

            <!-- Main Navigation -->
            <div class="nav-section-compact">
                <div class="section-title-modern">MAIN</div>
                <a href="{{ route('dashboard') }}" class="nav-item-modern {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home"></i>
                    <span class="nav-text-modern">Dashboard</span>
                </a>
            </div>

            

            
           
            <div class="nav-section-compact">
                <div class="section-title-modern">MANAGEMENT</div>
                <a href="{{ route('donors.index') }}" class="nav-item-modern {{ request()->routeIs('donors.*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i>
                    <span class="nav-text-modern">Donors</span>
                </a>
                
                <!-- All Transactions Dropdown -->
                <div class="dropdown-container">
                    <div class="dropdown-trigger {{ request()->routeIs('donations.*') || request()->routeIs('transactions.*') ? 'active' : '' }}" 
                         onclick="toggleTransactionsDropdown()">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <i class="fas fa-exchange-alt" style="width: 18px; font-size: 0.9rem; color: {{ request()->routeIs('donations.*') || request()->routeIs('transactions.*') ? '#2563eb' : '#64748b' }};"></i>
                            <span class="nav-text-modern" style="color: {{ request()->routeIs('donations.*') || request()->routeIs('transactions.*') ? '#2563eb' : '#334155' }};">All Transactions</span>
                        </div>
                        <i class="fas fa-chevron-down" id="dropdownArrow" style="font-size: 0.7rem; color: #64748b; transition: transform 0.2s;"></i>
                    </div>
                    
                    <!-- Dropdown Menu Items -->
                    <div id="transactionsDropdown" style="display: none;">
                        <a href="{{ route('donations.index') }}" class="nav-item-modern {{ request()->routeIs('donations.*') ? 'active' : '' }}">
                            <i class="fas fa-hand-holding-heart"></i>
                            <span class="nav-text-modern">Donations</span>
                        </a>
                        
                        <a href="{{ route('transactions.index') }}" class="nav-item-modern {{ request()->routeIs('transactions.*') ? 'active' : '' }}">
                            <i class="fas fa-exchange-alt"></i>
                            <span class="nav-text-modern">Monthly Transactions</span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="nav-section-compact">
                <div class="section-title-modern">REPORTS</div>
                <a href="{{ route('analytics.index') }}" class="nav-item-modern {{ request()->routeIs('analytics.*') ? 'active' : '' }}">
                    <i class="fas fa-chart-line"></i>
                    <span class="nav-text-modern">Analytics</span>
                </a>
                
                <a href="{{ route('due.index') }}" class="nav-item-modern {{ request()->routeIs('due.*') ? 'active' : '' }}">
                    <i class="fas fa-clock"></i>
                    <span class="nav-text-modern">Due Payments</span>
                </a>
            </div>

            <div class="nav-section-compact">
                <div class="section-title-modern">AUDIT</div>
                <a href="{{ route('donation-logs.index') }}" class="nav-item-modern {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-history"></i>
                    <span class="nav-text-modern">Donation Logs</span>
                </a>
                <a href="{{ route('transaction-logs.index') }}" class="nav-item-modern {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-history"></i>
                    <span class="nav-text-modern">Transaction Logs</span>
                </a>
            </div>
        </div>

        <!-- Top Bar -->
        <div class="top-bar" id="topBar">
            <div class="brand-area">
                <div class="menu-btn-modern" id="menuToggleModern">
                    <i class="fas fa-bars"></i>
                </div>
                <div class="logo-modern">
                    <i class="fas fa-mosque"></i>
                    <span>MosqueFund</span>
                </div>
            </div>
            <div class="dropdown">
                <div class="user-avatar-modern" data-bs-toggle="dropdown" style="cursor: pointer;">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="fas fa-sign-out-alt me-2"></i>Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-wrapper">
            <div class="content-card">
                <!-- Page Header -->
                <div class="page-header-modern">
                    <h1>
                        @yield('page-title', 'Dashboard')
                        <small>@yield('page-subtitle', 'Overview')</small>
                    </h1>
                    <div class="quick-actions">
                        @yield('quick-actions')
                    </div>
                </div>

                <!-- Alerts -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <div class="d-flex">
                            <i class="fas fa-exclamation-triangle me-3 mt-1"></i>
                            <div>
                                <strong>Please fix the following errors:</strong>
                                <ul class="mb-0 mt-2 ps-3">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Page Content -->
                @yield('content')
            </div>
        </div>

        <!-- Mobile Overlay -->
        <div class="sidebar-overlay-modern" id="sidebarOverlayModern"></div>

    @else
        <!-- Guest Layout -->
        <div class="container mt-4">
            @yield('content')
        </div>
    @endauth

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
        // Dropdown toggle function
        function toggleTransactionsDropdown() {
            const dropdown = document.getElementById('transactionsDropdown');
            const arrow = document.getElementById('dropdownArrow');
            
            if (dropdown.style.display === 'none' || dropdown.style.display === '') {
                dropdown.style.display = 'block';
                arrow.style.transform = 'rotate(180deg)';
            } else {
                dropdown.style.display = 'none';
                arrow.style.transform = 'rotate(0deg)';
            }
        }

        $(document).ready(function () {
            const $sidebar = $('#sidebarModern');
            const $body = $('body');
            const $overlay = $('#sidebarOverlayModern');
            const $menuBtn = $('#menuToggleModern');

            // Keep dropdown open if on donations or transactions pages
            if (window.location.href.includes('donations') || window.location.href.includes('transactions')) {
                $('#transactionsDropdown').show();
                $('#dropdownArrow').css('transform', 'rotate(180deg)');
            }

            // Desktop collapse toggle
            $menuBtn.on('click', function (e) {
                e.stopPropagation();
                if ($(window).width() > 768) {
                    $sidebar.toggleClass('collapsed');
                    $body.toggleClass('sidebar-collapsed');
                    localStorage.setItem('sidebarModernCollapsed', $sidebar.hasClass('collapsed'));
                    
                    // Change icon direction
                    let icon = $(this).find('i');
                    if ($sidebar.hasClass('collapsed')) {
                        icon.removeClass('fa-bars').addClass('fa-chevron-right');
                    } else {
                        icon.removeClass('fa-chevron-right').addClass('fa-bars');
                    }
                } else {
                    // Mobile open/close
                    $sidebar.toggleClass('mobile-open');
                    $overlay.fadeToggle(200);
                    $('body').css('overflow', $sidebar.hasClass('mobile-open') ? 'hidden' : '');
                    
                    // Change icon for mobile
                    let icon = $(this).find('i');
                    if ($sidebar.hasClass('mobile-open')) {
                        icon.removeClass('fa-bars').addClass('fa-times');
                    } else {
                        icon.removeClass('fa-times').addClass('fa-bars');
                    }
                }
            });

            // Load saved state
            if ($(window).width() > 768) {
                let collapsed = localStorage.getItem('sidebarModernCollapsed') === 'true';
                if (collapsed) {
                    $sidebar.addClass('collapsed');
                    $body.addClass('sidebar-collapsed');
                    $('#menuToggleModern i').removeClass('fa-bars').addClass('fa-chevron-right');
                }
            }

            // Close mobile on overlay click
            $overlay.on('click', function () {
                $sidebar.removeClass('mobile-open');
                $overlay.fadeOut(200);
                $('body').css('overflow', '');
                $('#menuToggleModern i').removeClass('fa-times').addClass('fa-bars');
            });

            // Close mobile on nav link click
            $('.nav-item-modern').on('click', function () {
                if ($(window).width() <= 768) {
                    $sidebar.removeClass('mobile-open');
                    $overlay.fadeOut(200);
                    $('body').css('overflow', '');
                    $('#menuToggleModern i').removeClass('fa-times').addClass('fa-bars');
                }
            });

            // Handle window resize
            $(window).on('resize', function () {
                if ($(window).width() > 768) {
                    $sidebar.removeClass('mobile-open');
                    $overlay.hide();
                    $('body').css('overflow', '');
                    
                    // Reset mobile icon
                    $('#menuToggleModern i').removeClass('fa-times').addClass('fa-bars');
                    
                    // Restore desktop collapsed state
                    let collapsed = localStorage.getItem('sidebarModernCollapsed') === 'true';
                    $sidebar.toggleClass('collapsed', collapsed);
                    $body.toggleClass('sidebar-collapsed', collapsed);
                    
                    if (collapsed) {
                        $('#menuToggleModern i').removeClass('fa-bars').addClass('fa-chevron-right');
                    } else {
                        $('#menuToggleModern i').removeClass('fa-chevron-right').addClass('fa-bars');
                    }
                }
            });

            // Auto-hide alerts
            setTimeout(function() {
                $('.alert').fadeOut(500);
            }, 5000);
        });
    </script>

    @stack('scripts')
</body>
</html>