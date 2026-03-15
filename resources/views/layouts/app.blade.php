<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
    <title>@yield('title', 'Dashboard') - MosqueFund</title>

    <!-- Bootstrap CSS -->
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"> --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Space+Grotesk:wght@500;600&display=swap"
        rel="stylesheet">

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
            box-shadow: 0 20px 40px -12px rgba(0, 20, 40, 0.25);
            transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 1001;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: #cbd5e0 #f1f5f9;
        }

        .sidebar-modern::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar-modern::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        .sidebar-modern::-webkit-scrollbar-thumb {
            background: #cbd5e0;
            border-radius: 4px;
        }

        .sidebar-modern::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        .sidebar-modern.collapsed {
            width: var(--sidebar-collapsed-width);
        }

        /* Collapsed state - consolidated */
        .sidebar-modern.collapsed .section-title-modern span,
        .sidebar-modern.collapsed .nav-text-modern,
        .sidebar-modern.collapsed .user-details-compact,
        .sidebar-modern.collapsed .dropdown-trigger span,
        .sidebar-modern.collapsed .dropdown-trigger .fa-chevron-down,
        .sidebar-modern.collapsed #transactionsDropdown,
        .sidebar-modern.collapsed #donorsDropdown,
        .sidebar-modern.collapsed .sidebar-footer,
        .sidebar-modern.collapsed .expanded-only {
            display: none !important;
        }

        .sidebar-modern.collapsed .nav-item-modern,
        .sidebar-modern.collapsed .dropdown-trigger {
            justify-content: center;
            padding: 12px 0 !important;
            margin: 4px 8px;
        }

        .sidebar-modern.collapsed .nav-item-modern i,
        .sidebar-modern.collapsed .dropdown-trigger i:first-child {
            margin: 0 !important;
            font-size: 1.2rem !important;
        }

        .sidebar-modern.collapsed .user-section-compact {
            justify-content: center;
            padding: 8px 0;
        }

        .sidebar-modern.collapsed .user-avatar-micro {
            margin: 0 auto;
        }

        .sidebar-modern.collapsed .nav-item-modern:hover,
        .sidebar-modern.collapsed .dropdown-trigger:hover {
            background: rgba(37, 99, 235, 0.1);
        }

        .sidebar-modern.collapsed .nav-item-modern:hover {
            transform: scale(1.05);
        }

        /* Tooltip for collapsed state */
        .sidebar-modern.collapsed [title] {
            position: relative;
            cursor: pointer;
        }

        .sidebar-modern.collapsed [title]:hover:after {
            content: attr(title);
            position: absolute;
            left: 100%;
            top: 50%;
            transform: translateY(-50%);
            background: #1e293b;
            color: white;
            font-size: 0.7rem;
            padding: 4px 8px;
            border-radius: 4px;
            white-space: nowrap;
            margin-left: 8px;
            z-index: 1000;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
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

        .user-profile-trigger {
            transition: all 0.2s ease;
            border-radius: 8px;
        }

        .user-profile-trigger:hover {
            background: rgba(37, 99, 235, 0.05);
        }

        /* Top Bar */
        .top-bar {
            position: fixed;
            top: 20px;
            left: calc(var(--sidebar-width) + 40px);
            right: 20px;
            z-index: 1000;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            border-radius: 100px;
            padding: 8px 20px;
            box-shadow: 0 10px 40px -10px rgba(0, 0, 0, 0.1);
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
            box-shadow: 0 20px 40px -12px rgba(0, 20, 40, 0.12);
            border: 1px solid rgba(226, 232, 240, 0.6);
            width: 100%;
        }

        /* Menu Trigger Button */
        .menu-trigger {
            width: 44px;
            height: 44px;
            border: none;
            border-radius: 50%;
            background: #f8fafc;
            color: #334155;
            font-size: 1.2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
            border: 1px solid #e2e8f0;
        }

        .menu-trigger:hover {
            background: #2563eb;
            color: white;
            border-color: #2563eb;
            transform: scale(1.05);
        }

        /* Brand Link */
        .brand-link {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            transition: opacity 0.2s ease;
        }

        .brand-link:hover {
            opacity: 0.85;
        }

        .brand-icon {
            width: 44px;
            height: 44px;
            background: linear-gradient(135deg, #2563eb, #7c3aed);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .brand-icon i {
            font-size: 1.4rem;
            color: white;
        }

        .brand-text {
            display: flex;
            flex-direction: column;
        }

        .brand-name {
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 700;
            font-size: 1.2rem;
            color: #0f172a;
            line-height: 1.2;
        }

        .brand-tagline {
            font-size: 0.7rem;
            color: #64748b;
            letter-spacing: 0.3px;
        }

        /* Quick Actions Center */
        .quick-actions-center {
            display: flex;
            align-items: center;
            gap: 4px;
            background: #f1f5f9;
            padding: 4px;
            border-radius: 40px;
        }

        .quick-action-btn {
            width: 36px;
            height: 36px;
            border-radius: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #475569;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .quick-action-btn:hover {
            background: #2563eb;
            color: white;
            transform: translateY(-2px);
        }

        /* Search Wrapper */
        .search-wrapper {
            position: relative;
            width: 260px;
        }

        .search-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 0.9rem;
            pointer-events: none;
        }

        .search-input {
            width: 100%;
            height: 44px;
            padding: 0 16px 0 42px;
            border: 2px solid transparent;
            border-radius: 40px;
            background: #f1f5f9;
            font-size: 0.9rem;
            color: #1e293b;
            transition: all 0.2s ease;
        }

        .search-input:focus {
            outline: none;
            border-color: #2563eb;
            background: white;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.15);
        }

        .search-shortcut {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 0.7rem;
            color: #94a3b8;
            background: #ffffff;
            padding: 2px 6px;
            border-radius: 6px;
            border: 1px solid #e2e8f0;
        }

        /* Notification Bell */
        .notification-bell {
            width: 44px;
            height: 44px;
            border-radius: 30px;
            background: #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #475569;
            cursor: pointer;
            transition: all 0.2s ease;
            position: relative;
        }

        .notification-bell:hover {
            background: #2563eb;
            color: white;
            transform: translateY(-2px);
        }

        .notification-dot {
            position: absolute;
            top: 10px;
            right: 12px;
            width: 8px;
            height: 8px;
            background: #ef4444;
            border-radius: 50%;
            border: 2px solid #f1f5f9;
        }

        .notification-bell:hover .notification-dot {
            border-color: #2563eb;
        }

        /* User Menu Trigger */
        .user-menu-trigger {
            background: none;
            border: none;
            padding: 4px 8px 4px 4px;
            border-radius: 40px;
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
        }

        .user-menu-trigger:hover {
            background: #ffffff;
            border-color: #2563eb;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.15);
        }

        .user-info {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            line-height: 1.3;
        }

        .user-name {
            font-weight: 600;
            font-size: 0.9rem;
            color: #0f172a;
        }

        .user-role {
            font-size: 0.65rem;
            color: #64748b;
        }

        .dropdown-arrow {
            font-size: 0.7rem;
            color: #94a3b8;
            margin-right: 4px;
            transition: transform 0.2s ease;
        }

        .user-menu-trigger[aria-expanded="true"] .dropdown-arrow {
            transform: rotate(180deg);
        }

        /* User Avatar Large */
        .user-avatar-large {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, #2563eb, #7c3aed);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 1.2rem;
        }

        /* Sidebar navigation */
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
            transition: all 0.2s ease;
            font-weight: 500;
            position: relative;
        }

        .nav-item-modern i {
            width: 18px;
            font-size: 0.9rem;
            color: #64748b;
            transition: all 0.2s ease;
        }

        .nav-item-modern::after {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 3px;
            height: 0;
            background: #2563eb;
            border-radius: 0 3px 3px 0;
            transition: height 0.2s ease;
        }

        .nav-item-modern.active::after {
            height: 60%;
        }

        .nav-item-modern:hover::after {
            height: 40%;
            background: rgba(37, 99, 235, 0.3);
        }

        .nav-item-modern:hover {
            background: rgba(37, 99, 235, 0.05);
            color: #2563eb;
            transform: translateX(4px);
        }

        .nav-item-modern:hover i {
            color: #2563eb;
        }

        .nav-item-modern.active {
            background: linear-gradient(90deg, rgba(37, 99, 235, 0.1), transparent);
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
            background: rgba(37, 99, 235, 0.05);
        }

        .dropdown-trigger.active {
            background: linear-gradient(90deg, rgba(37, 99, 235, 0.1), transparent);
            border-left: 2px solid #2563eb;
        }

        #transactionsDropdown,
        #donorsDropdown {
            margin-left: 16px;
            transition: all 0.3s ease;
        }

        #transactionsDropdown .nav-item-modern,
        #donorsDropdown .nav-item-modern {
            padding: 8px 12px;
            margin: 2px 6px;
            border-radius: 10px;
            padding-left: 28px !important;
        }

        #transactionsDropdown .nav-item-modern i,
        #donorsDropdown .nav-item-modern i {
            width: 16px;
            font-size: 0.8rem;
            margin-left: 8px;
        }

        #transactionsDropdown .nav-item-modern span,
        #donorsDropdown .nav-item-modern span {
            font-size: 0.75rem;
        }

        #transactionsDropdown .nav-item-modern:hover,
        #donorsDropdown .nav-item-modern:hover {
            background: rgba(37, 99, 235, 0.05);
            transform: translateX(4px);
        }

        #transactionsDropdown .nav-item-modern.active,
        #donorsDropdown .nav-item-modern.active {
            background: linear-gradient(90deg, rgba(37, 99, 235, 0.1), transparent);
            color: #2563eb;
        }

        #transactionsDropdown .nav-item-modern.active i,
        #donorsDropdown .nav-item-modern.active i {
            color: #2563eb;
        }

        /* Dropdown Menu */
        .dropdown-menu {
            border: none;
            border-radius: 20px;
            box-shadow: 0 20px 40px -12px rgba(0, 20, 40, 0.25);
            padding: 8px;
            min-width: 240px;
            margin-top: 12px !important;
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }

        .dropdown-header {
            padding: 12px;
        }

        .dropdown-item {
            padding: 10px 14px;
            border-radius: 12px;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: all 0.2s ease;
        }

        .dropdown-item:hover {
            background: #f1f5f9;
            transform: translateX(4px);
        }

        .dropdown-item.text-danger:hover {
            background: #fee2e2;
        }

        .dropdown-icon {
            width: 20px;
            font-size: 1rem;
            color: #64748b;
        }

        .dropdown-item:hover .dropdown-icon {
            color: #2563eb;
        }

        .dropdown-item.text-danger:hover .dropdown-icon {
            color: #dc2626;
        }

        /* Dropdown animations */
        .animate-dropdown {
            animation: slideDown 0.2s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
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

        /* Sidebar Footer */
        .sidebar-footer {
            margin-top: 12px;
            padding-top: 8px;
            border-top: 1px solid rgba(226, 232, 240, 0.3);
            font-size: 0.6rem;
        }

        /* Badge styles */
        .badge {
            font-weight: 500;
            border-radius: 30px;
        }

        /* Keyboard hint */
        .keyboard-hint {
            font-size: 0.5rem;
            opacity: 0.5;
            margin-left: 4px;
        }

        /* Brand area link styles */
        a:has(.brand-area) {
            transition: opacity 0.2s ease;
            border-radius: 60px;
        }

        a:has(.brand-area):hover {
            opacity: 0.8;
        }

        /* Prevent menu button from triggering the link */
        #menuToggleModern {
            position: relative;
            z-index: 10;
        }

        /* Mobile adjustments */
        @media (max-width: 768px) {
            :root {
                --sidebar-width: 280px;
            }

            .top-bar {
                left: 20px;
                right: 20px;
                border-radius: 30px;
                padding: 6px 16px;
            }

            .sidebar-modern {
                left: -300px;
                transition: left 0.3s;
            }

            .sidebar-modern.mobile-open {
                left: 20px;
            }

            .main-wrapper {
                margin-left: 5px;
                margin-right: 5px;
            }

            body.sidebar-collapsed .main-wrapper {
                margin-left: 20px;
            }

            .brand-text,
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

            #transactionsDropdown .nav-item-modern,
            #donorsDropdown .nav-item-modern {
                padding: 8px 12px;
            }

            #transactionsDropdown .nav-item-modern span,
            #donorsDropdown .nav-item-modern span {
                font-size: 0.8rem;
            }

            .user-menu-trigger {
                padding: 4px;
            }

            .notification-bell {
                width: 40px;
                height: 40px;
            }
        }

        /* Overlay */
        .sidebar-overlay-modern {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 10, 30, 0.4);
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

        <!-- Sidebar - Modern Compact with Enhanced Desktop UX -->
        <div class="sidebar-modern" id="sidebarModern">
            <!-- Compact User Section - Always shows avatar only when collapsed -->
            <div class="user-section-compact dropdown mb-2">
                <div class="d-flex align-items-center gap-2 px-3 py-2 user-profile-trigger"
                    style="cursor: pointer; border-bottom: 1px solid rgba(226, 232, 240, 0.3);" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    <div class="user-avatar-micro">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div class="user-details-compact expanded-only">
                        <div>{{ Auth::user()->name }}</div>
                        <div>Administrator</div>
                    </div>
                    <i class="fas fa-chevron-down ms-auto expanded-only"
                        style="font-size: 0.7rem; color: #64748b; transition: transform 0.3s;"></i>
                </div>

                <!-- Dropdown Menu (same as before) -->
                <ul class="dropdown-menu dropdown-menu-end animate-dropdown"
                    style="border-radius: 16px; border: none; box-shadow: 0 20px 40px -12px rgba(0,20,40,0.25); padding: 8px; min-width: 220px; margin-top: 8px; backdrop-filter: blur(10px); background: rgba(255,255,255,0.95);">
                    <li>
                        <form method="POST" action="{{ route('logout') }}" id="logout-form-sidebar">
                            @csrf
                            <button type="submit" class="dropdown-item"
                                style="padding: 12px 16px; border-radius: 10px; font-size: 0.85rem; color: #ef4444; display: flex; align-items: center; gap: 12px; width: 100%; border: none; background: none;">
                                <div
                                    style="width: 32px; height: 32px; background: linear-gradient(135deg, #ef4444, #dc2626); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-sign-out-alt" style="color: white; font-size: 0.9rem;"></i>
                                </div>
                                <div>
                                    <div style="font-weight: 500;">Logout</div>
                                    <small style="color: #64748b;">End your session</small>
                                </div>
                            </button>
                        </form>
                    </li>
                </ul>
            </div>

            <!-- Main Navigation -->
            <div class="nav-section-compact">
                <div class="section-title-modern px-3 expanded-only">
                    <span>MAIN</span>
                </div>
                <a href="{{ route('dashboard') }}"
                    class="nav-item-modern {{ request()->routeIs('dashboard') ? 'active' : '' }}" title="Dashboard">
                    <i class="fas fa-home"></i>
                    <span class="nav-text-modern expanded-only">Dashboard</span>
                    <span class="ms-auto expanded-only badge bg-primary bg-opacity-10 text-primary"
                        style="font-size: 0.5rem; padding: 2px 6px;">Home</span>
                </a>
            </div>

            <div class="nav-section-compact">
    <div class="section-title-modern px-3 expanded-only">Donations</div>

    <!-- Monthly Donor List -->
    @php
        $monthlyDonorsCount = \App\Models\Donor::where('status', 'active')->count();
    @endphp
    <a href="{{ route('donors.index') }}"
        class="nav-item-modern {{ request()->routeIs('donors.index') || (request()->routeIs('donors.*') && !request()->routeIs('donors.random')) ? 'active' : '' }}"
        style="display: flex; align-items: center; gap: 10px; padding: 8px 12px; margin: 2px 6px; border-radius: 12px; transition: all 0.2s;"
        title="Monthly Donors">
        <i class="fas fa-users"
            style="width: 18px; font-size: 0.9rem; color: {{ request()->routeIs('donors.index') || (request()->routeIs('donors.*') && !request()->routeIs('donors.random')) ? '#2563eb' : '#64748b' }};"></i>
        <span class="nav-text-modern expanded-only"
            style="color: {{ request()->routeIs('donors.index') || (request()->routeIs('donors.*') && !request()->routeIs('donors.random')) ? '#2563eb' : '#334155' }};">Monthly Donor List</span>
        @if ($monthlyDonorsCount > 0)
            <span class="ms-auto expanded-only badge"
                style="background: #f1f5f9; color: #475569; font-size: 0.55rem; padding: 2px 6px;">
                {{ $monthlyDonorsCount }}
            </span>
        @endif
    </a>


    <!-- Fixed Monthly -->
    <a href="{{ route('transactions.index') }}"
        class="nav-item-modern {{ request()->routeIs('transactions.*') ? 'active' : '' }}"
        style="display: flex; align-items: center; gap: 10px; padding: 8px 12px; margin: 2px 6px; border-radius: 12px; transition: all 0.2s;"
        title="Monthly Transactions">
        <i class="fas fa-exchange-alt"
            style="width: 18px; font-size: 0.9rem; color: {{ request()->routeIs('transactions.*') ? '#2563eb' : '#64748b' }};"></i>
        <span class="nav-text-modern expanded-only"
            style="color: {{ request()->routeIs('transactions.*') ? '#2563eb' : '#334155' }};">Monthly Transactions</span>
        <span class="ms-auto expanded-only badge"
            style="background: #f1f5f9; color: #475569; font-size: 0.55rem; padding: 2px 6px;">
            {{ \App\Models\Transaction::count() }}
        </span>
    </a>

    <!-- Random/One-time -->
    <a href="{{ route('donations.index') }}"
        class="nav-item-modern {{ request()->routeIs('donations.*') ? 'active' : '' }}"
        style="display: flex; align-items: center; gap: 10px; padding: 8px 12px; margin: 2px 6px; border-radius: 12px; transition: all 0.2s;"
        title="Donations">
        <i class="fas fa-hand-holding-heart"
            style="width: 18px; font-size: 0.9rem; color: {{ request()->routeIs('donations.*') ? '#2563eb' : '#64748b' }};"></i>
        <span class="nav-text-modern expanded-only"
            style="color: {{ request()->routeIs('donations.*') ? '#2563eb' : '#334155' }};">Random/One-time</span>
        <span class="ms-auto expanded-only badge"
            style="background: #f1f5f9; color: #475569; font-size: 0.55rem; padding: 2px 6px;">
            {{ \App\Models\Donation::count() }}
        </span>
    </a>

    
    <!-- Random Donor List -->
    @php
        $randomDonorsCount = \App\Models\Contributor::count();
    @endphp
    <a href="{{ route('contributors.index') }}"
        class="nav-item-modern {{ request()->routeIs('contributors.index') ? 'active' : '' }}"
        style="display: flex; align-items: center; gap: 10px; padding: 8px 12px; margin: 2px 6px; border-radius: 12px; transition: all 0.2s;"
        title="Random Donors">
        <i class="fas fa-random"
            style="width: 18px; font-size: 0.9rem; color: {{ request()->routeIs('contributors.index') ? '#2563eb' : '#64748b' }};"></i>
        <span class="nav-text-modern expanded-only"
            style="color: {{ request()->routeIs('contributors.index') ? '#2563eb' : '#334155' }};">Random Donor List</span>
        @if ($randomDonorsCount > 0)
            <span class="ms-auto expanded-only badge"
                style="background: #f1f5f9; color: #475569; font-size: 0.55rem; padding: 2px 6px;">
                {{ $randomDonorsCount }}
            </span>
        @endif
    </a>
</div>

<div class="nav-section-compact">
    <div class="section-title-modern px-3 expanded-only">REPORTS & ANALYTICS</div>
    <a href="{{ route('due.index') }}"
        class="nav-item-modern {{ request()->routeIs('due.*') ? 'active' : '' }}" title="Due Payments">
        <i class="fas fa-clock"></i>
        <span class="nav-text-modern expanded-only">Due Payments</span>
        @php
            $dueCount = \App\Models\Donor::whereHas('transactions', function ($q) {
                $q->where('paid_status', 'unpaid');
            })->count();
        @endphp
        @if ($dueCount > 0)
            <span class="ms-auto expanded-only badge"
                style="background: #fee2e2; color: #991b1b; font-size: 0.55rem; padding: 2px 6px;">{{ $dueCount }}</span>
        @endif
    </a>
    <a href="{{ route('analytics.index') }}"
        class="nav-item-modern {{ request()->routeIs('analytics.*') ? 'active' : '' }}" title="Analytics">
        <i class="fas fa-chart-line"></i>
        <span class="nav-text-modern expanded-only">Analytics</span>
    </a>
</div>

<div class="nav-section-compact">
    <div class="section-title-modern px-3 expanded-only">AUDIT & LOGS</div>
    <a href="{{ route('donation-logs.index') }}"
        class="nav-item-modern {{ request()->routeIs('donation-logs.*') ? 'active' : '' }}"
        title="Donation Logs">
        <i class="fas fa-history"></i>
        <span class="nav-text-modern expanded-only">Donation Logs</span>
    </a>

    <a href="{{ route('transaction-logs.index') }}"
        class="nav-item-modern {{ request()->routeIs('transaction-logs.*') ? 'active' : '' }}"
        title="Transaction Logs">
        <i class="fas fa-history"></i>
        <span class="nav-text-modern expanded-only">Transaction Logs</span>
    </a>

    <a href="{{ route('registration-logs.index') }}"
        class="nav-item-modern {{ request()->routeIs('registration-logs.*') ? 'active' : '' }}"
        title="Donation Logs">
        <i class="fas fa-history"></i>
        <span class="nav-text-modern expanded-only">User Logs</span>
    </a>
</div>
        </div>

        <!-- Top Bar -->
        <div class="top-bar" id="topBar">
            <!-- Left Section: Logo & Menu -->
            <div class="d-flex align-items-center gap-3">
                <button class="menu-trigger" id="menuToggleModern" aria-label="Toggle menu">
                    <i class="fas fa-bars"></i>
                </button>

                <a href="{{ route('dashboard') }}" class="brand-link">
                    <div class="brand-icon">
                        <i class="fas fa-mosque"></i>
                    </div>
                    <div class="brand-text">
                        <span class="brand-name">মসজিদ ই কোবা</span>
                        <span class="brand-tagline">তহবিল ব্যবস্থাপনা</span>
                    </div>
                </a>
            </div>

            <!-- Right Section: User Menu & Notifications -->
            <div class="d-flex align-items-center gap-2">
                <!-- User Menu Dropdown -->
                <div class="dropdown">
                    <button class="user-menu-trigger" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="user-avatar-modern">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <div class="user-info d-none d-md-block">
                            <span class="user-name">{{ Auth::user()->name }}</span>
                            <span class="user-role">Administrator</span>
                        </div>
                        <i class="fas fa-chevron-down dropdown-arrow"></i>
                    </button>

                    <ul class="dropdown-menu dropdown-menu-end animate-dropdown">
                        <!-- User Header -->
                        <li class="dropdown-header">
                            <div class="d-flex align-items-center gap-3 p-2">
                                <div class="user-avatar-large">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                                <div>
                                    <h6 class="mb-0">{{ Auth::user()->name }}</h6>
                                    <small class="text-muted">Administrator</small>
                                </div>
                            </div>
                        </li>

                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        <!-- Menu Items -->
                        <li>
                            <a class="dropdown-item" href="#">
                                <i class="fas fa-user-circle dropdown-icon"></i>
                                <span>Profile</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#">
                                <i class="fas fa-cog dropdown-icon"></i>
                                <span>Settings</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#">
                                <i class="fas fa-shield-alt dropdown-icon"></i>
                                <span>Security</span>
                            </a>
                        </li>

                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="fas fa-sign-out-alt dropdown-icon"></i>
                                    <span>Logout</span>
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
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
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <div class="d-flex">
                            <i class="fas fa-exclamation-triangle me-3 mt-1"></i>
                            <div>
                                <strong>Please fix the following errors:</strong>
                                <ul class="mb-0 mt-2 ps-3">
                                    @foreach ($errors->all() as $error)
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

        <!-- Logout Confirmation Script -->
        <script>
            $(document).ready(function() {
                $('#logout-form-sidebar').on('submit', function(e) {
                    e.preventDefault();
                    if (confirm('Are you sure you want to logout?')) {
                        this.submit();
                    }
                });
            });
        </script>
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
        // Dropdown toggle function for Transactions
        function toggleTransactionsDropdown() {
            const dropdown = document.getElementById('transactionsDropdown');
            const arrow = document.getElementById('dropdownArrow');

            if (dropdown.style.display === 'none' || dropdown.style.display === '') {
                // Slide down animation
                dropdown.style.display = 'block';
                dropdown.style.opacity = '0';
                dropdown.style.transform = 'translateY(-10px)';

                setTimeout(() => {
                    dropdown.style.transition = 'all 0.3s ease';
                    dropdown.style.opacity = '1';
                    dropdown.style.transform = 'translateY(0)';
                }, 10);

                arrow.style.transform = 'rotate(180deg)';
            } else {
                // Slide up animation
                dropdown.style.opacity = '0';
                dropdown.style.transform = 'translateY(-10px)';

                setTimeout(() => {
                    dropdown.style.display = 'none';
                }, 200);

                arrow.style.transform = 'rotate(0deg)';
            }
        }

        // Dropdown toggle function for Donors
        function toggleDonorsDropdown() {
            const dropdown = document.getElementById('donorsDropdown');
            const arrow = document.getElementById('donorsDropdownArrow');

            if (dropdown.style.display === 'none' || dropdown.style.display === '') {
                // Slide down animation
                dropdown.style.display = 'block';
                dropdown.style.opacity = '0';
                dropdown.style.transform = 'translateY(-10px)';

                setTimeout(() => {
                    dropdown.style.transition = 'all 0.3s ease';
                    dropdown.style.opacity = '1';
                    dropdown.style.transform = 'translateY(0)';
                }, 10);

                arrow.style.transform = 'rotate(180deg)';
            } else {
                // Slide up animation
                dropdown.style.opacity = '0';
                dropdown.style.transform = 'translateY(-10px)';

                setTimeout(() => {
                    dropdown.style.display = 'none';
                }, 200);

                arrow.style.transform = 'rotate(0deg)';
            }
        }

        $(document).ready(function() {
            const $sidebar = $('#sidebarModern');
            const $body = $('body');
            const $overlay = $('#sidebarOverlayModern');
            const $menuBtn = $('#menuToggleModern');

            // Enhanced hover effects for nav items
            $('.nav-item-modern').hover(
                function() {
                    if (!$(this).hasClass('active') && $(window).width() > 768) {
                        $(this).css('background', 'rgba(37, 99, 235, 0.05)');
                        $(this).css('transform', 'translateX(4px)');
                    }
                },
                function() {
                    if (!$(this).hasClass('active')) {
                        $(this).css('background', 'transparent');
                        $(this).css('transform', 'translateX(0)');
                    }
                }
            );

            // Enhanced dropdown trigger hover
            $('.dropdown-trigger').hover(
                function() {
                    if (!$(this).hasClass('active')) {
                        $(this).css('background', 'rgba(37, 99, 235, 0.05)');
                    }
                },
                function() {
                    if (!$(this).hasClass('active')) {
                        $(this).css('background', 'transparent');
                    }
                }
            );

            // Keyboard shortcut for dropdown (Ctrl+Shift+T)
            $(document).on('keydown', function(e) {
                if (e.ctrlKey && e.shiftKey && e.key === 'T') {
                    e.preventDefault();
                    toggleTransactionsDropdown();
                }
            });

            // Keep dropdown open if on relevant pages
            if (window.location.href.includes('donations') || window.location.href.includes('transactions')) {
                $('#transactionsDropdown').show().css({
                    'opacity': '1',
                    'transform': 'translateY(0)'
                });
                $('#dropdownArrow').css('transform', 'rotate(180deg)');
            }

            if (window.location.href.includes('donors') || window.location.href.includes('donors/random')) {
                $('#donorsDropdown').show().css({
                    'opacity': '1',
                    'transform': 'translateY(0)'
                });
                $('#donorsDropdownArrow').css('transform', 'rotate(180deg)');
            }

            // Desktop collapse toggle
            $menuBtn.on('click', function(e) {
                e.stopPropagation();
                if ($(window).width() > 768) {
                    $sidebar.toggleClass('collapsed');
                    $body.toggleClass('sidebar-collapsed');
                    localStorage.setItem('sidebarModernCollapsed', $sidebar.hasClass('collapsed'));

                    // Change icon direction
                    let icon = $(this).find('i');
                    if ($sidebar.hasClass('collapsed')) {
                        icon.removeClass('fa-bars').addClass('fa-chevron-right');

                        // Add tooltips for collapsed state
                        setTimeout(function() {
                            $('.sidebar-modern.collapsed .nav-item-modern').each(function() {
                                const title = $(this).attr('title');
                                if (title) {
                                    $(this).attr('data-bs-toggle', 'tooltip');
                                    $(this).attr('data-bs-placement', 'right');
                                }
                            });
                            // Initialize tooltips
                            var tooltipTriggerList = [].slice.call(document.querySelectorAll(
                                '[data-bs-toggle="tooltip"]'));
                            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                                return new bootstrap.Tooltip(tooltipTriggerEl);
                            });
                        }, 100);

                    } else {
                        icon.removeClass('fa-chevron-right').addClass('fa-bars');
                        // Remove tooltips
                        $('.nav-item-modern').removeAttr('data-bs-toggle data-bs-placement');
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
            $overlay.on('click', function() {
                $sidebar.removeClass('mobile-open');
                $overlay.fadeOut(200);
                $('body').css('overflow', '');
                $('#menuToggleModern i').removeClass('fa-times').addClass('fa-bars');
            });

            // Close mobile on nav link click
            $('.nav-item-modern').on('click', function() {
                if ($(window).width() <= 768) {
                    $sidebar.removeClass('mobile-open');
                    $overlay.fadeOut(200);
                    $('body').css('overflow', '');
                    $('#menuToggleModern i').removeClass('fa-times').addClass('fa-bars');
                }
            });

            // Handle window resize
            $(window).on('resize', function() {
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
