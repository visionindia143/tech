<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') - {{ $adminSettings['site_name'] }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/backend/css/custom.css') }}">
    <style>
        :root {
            --admin-primary: {{ $adminSettings['theme_primary'] }};
            --admin-primary-dark: {{ $adminSettings['theme_primary_dark'] }};
            --admin-sidebar: #1A1B2F;
            --admin-sidebar-hover: #252742;
            --admin-dark: #111827;
            --admin-gray: #6B7280;
            --admin-light: #F9FAFB;
            --admin-border: #E5E7EB;
            --admin-white: #FFFFFF;
            --admin-success: #10B981;
            --admin-warning: #F59E0B;
            --admin-danger: #EF4444;
            --admin-info: #3B82F6;
            --admin-radius: 10px;
            --sidebar-width: 260px;
            --topbar-height: 64px;
        }

        *,
        *::before,
        *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background: var(--admin-light);
            color: var(--admin-dark);
            min-height: 100vh;
        }

        /* ========== SIDEBAR ========== */
        .sidebar {
            width: var(--sidebar-width);
            background: var(--admin-sidebar);
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            overflow-y: auto;
            overflow-x: hidden;
            z-index: 200;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            flex-direction: column;
            scrollbar-width: thin;
            scrollbar-color: rgba(255, 255, 255, 0.1) transparent;
        }

        .sidebar::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.15);
            border-radius: 4px;
        }

        .sidebar-brand {
            padding: 0 20px;
            height: var(--topbar-height);
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.07);
            flex-shrink: 0;
        }

        .sidebar-logo-img {
            height: 36px;
            width: auto;
            max-width: 130px;
            object-fit: contain;
        }

        .sidebar-logo-icon {
            width: 36px;
            height: 36px;
            border-radius: 9px;
            background: var(--admin-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1rem;
            flex-shrink: 0;
        }

        .sidebar-brand-text {
            color: white;
            font-weight: 700;
            font-size: 1.05rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .sidebar-brand-sub {
            font-size: 0.7rem;
            color: rgba(255, 255, 255, 0.4);
            font-weight: 400;
            display: block;
            margin-top: 1px;
        }

        .sidebar-nav {
            padding: 12px 0 20px;
            flex: 1;
        }

        .sidebar-label {
            padding: 16px 24px 5px;
            font-size: 0.68rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: rgba(255, 255, 255, 0.28);
            font-weight: 600;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 10px 24px;
            color: rgba(255, 255, 255, 0.58);
            text-decoration: none;
            font-size: 0.88rem;
            font-weight: 500;
            transition: all 0.18s;
            border-left: 3px solid transparent;
            white-space: nowrap;
        }

        .sidebar-link i {
            width: 18px;
            text-align: center;
            font-size: 0.9rem;
            flex-shrink: 0;
        }

        .sidebar-link:hover {
            background: var(--admin-sidebar-hover);
            color: rgba(255, 255, 255, 0.9);
            border-left-color: rgba(255, 107, 53, 0.4);
        }

        .sidebar-link.active {
            background: var(--admin-sidebar-hover);
            color: white;
            border-left-color: var(--admin-primary);
        }

        /* Close button for mobile sidebar */
        .sidebar-close {
            display: none;
            position: absolute;
            top: 14px;
            right: 14px;
            background: rgba(255, 255, 255, 0.1);
            border: none;
            color: white;
            width: 32px;
            height: 32px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1rem;
            align-items: center;
            justify-content: center;
        }

        /* ========== OVERLAY ========== */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 199;
            backdrop-filter: blur(2px);
        }

        .sidebar-overlay.active {
            display: block;
        }

        /* ========== MAIN CONTENT ========== */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ========== TOPBAR ========== */
        .topbar {
            background: var(--admin-white);
            height: var(--topbar-height);
            padding: 0 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid var(--admin-border);
            position: sticky;
            top: 0;
            z-index: 100;
            gap: 14px;
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 14px;
            min-width: 0;
        }

        .topbar-title {
            font-size: 1.2rem;
            font-weight: 700;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-shrink: 0;
        }

        .btn-hamburger {
            display: none;
            background: none;
            border: none;
            font-size: 1.25rem;
            cursor: pointer;
            color: var(--admin-dark);
            padding: 6px;
            border-radius: 8px;
            transition: background 0.15s;
            flex-shrink: 0;
        }

        .btn-hamburger:hover {
            background: var(--admin-light);
        }

        /* Admin user badge in topbar */
        .topbar-user {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 6px 14px 6px 6px;
            border-radius: 50px;
            border: 1px solid var(--admin-border);
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--admin-dark);
            cursor: default;
        }

        .topbar-user-avatar {
            width: 30px;
            height: 30px;
            background: var(--admin-primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 0.75rem;
            font-weight: 700;
        }

        /* ========== CONTENT AREA ========== */
        .content-area {
            padding: 24px 28px;
            flex: 1;
        }

        /* ========== ALERTS ========== */
        .admin-alert {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 13px 18px;
            border-radius: var(--admin-radius);
            margin-bottom: 20px;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .admin-alert-success {
            background: #ECFDF5;
            color: #065F46;
            border: 1px solid #A7F3D0;
        }

        .admin-alert-danger {
            background: #FEF2F2;
            color: #991B1B;
            border: 1px solid #FECACA;
        }

        .admin-alert-warning {
            background: #FFFBEB;
            color: #92400E;
            border: 1px solid #FDE68A;
        }

        /* ========== STATS GRID ========== */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 18px;
            margin-bottom: 28px;
        }

        .stat-card {
            background: white;
            border-radius: var(--admin-radius);
            padding: 20px 22px;
            border: 1px solid var(--admin-border);
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            flex-shrink: 0;
        }

        .stat-card h3 {
            font-size: 1.65rem;
            font-weight: 700;
            line-height: 1;
            margin-bottom: 4px;
        }

        .stat-card p {
            color: var(--admin-gray);
            font-size: 0.8rem;
            font-weight: 500;
        }

        /* ========== PANELS ========== */
        .panel {
            background: white;
            border-radius: var(--admin-radius);
            border: 1px solid var(--admin-border);
            margin-bottom: 22px;
            overflow: hidden;
        }

        .panel-header {
            padding: 16px 22px;
            border-bottom: 1px solid var(--admin-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .panel-header h2 {
            font-size: 1rem;
            font-weight: 600;
        }

        .panel-body {
            padding: 22px;
        }

        /* ========== TABLES ========== */
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 500px;
        }

        .data-table th {
            text-align: left;
            padding: 11px 14px;
            background: var(--admin-light);
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--admin-gray);
            font-weight: 600;
            border-bottom: 1px solid var(--admin-border);
            white-space: nowrap;
        }

        .data-table td {
            padding: 12px 14px;
            border-bottom: 1px solid var(--admin-border);
            font-size: 0.87rem;
            vertical-align: middle;
        }

        .data-table tr:last-child td {
            border-bottom: none;
        }

        .data-table tr:hover td {
            background: #FAFBFC;
        }

        .data-table img {
            width: 48px;
            height: 48px;
            object-fit: cover;
            border-radius: 8px;
        }

        /* ========== BADGES ========== */
        .badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 50px;
            font-size: 0.73rem;
            font-weight: 600;
            white-space: nowrap;
        }

        .badge-success {
            background: #ECFDF5;
            color: #065F46;
        }

        .badge-warning {
            background: #FFFBEB;
            color: #92400E;
        }

        .badge-danger {
            background: #FEF2F2;
            color: #991B1B;
        }

        .badge-info {
            background: #EFF6FF;
            color: #1E40AF;
        }

        .badge-secondary {
            background: #F3F4F6;
            color: #374151;
        }

        /* ========== BUTTONS ========== */
        .btn-admin {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 0.83rem;
            font-weight: 600;
            border: none;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.18s;
            font-family: 'Outfit', sans-serif;
            white-space: nowrap;
        }

        .btn-admin-primary {
            background: var(--admin-primary);
            color: white;
        }

        .btn-admin-primary:hover {
            background: var(--admin-primary-dark);
        }

        .btn-admin-success {
            background: var(--admin-success);
            color: white;
        }

        .btn-admin-success:hover {
            filter: brightness(0.9);
        }

        .btn-admin-danger {
            background: var(--admin-danger);
            color: white;
        }

        .btn-admin-danger:hover {
            filter: brightness(0.9);
        }

        .btn-admin-info {
            background: var(--admin-info);
            color: white;
        }

        .btn-admin-info:hover {
            filter: brightness(0.9);
        }

        .btn-admin-outline {
            background: transparent;
            border: 1px solid var(--admin-border);
            color: var(--admin-dark);
        }

        .btn-admin-outline:hover {
            background: var(--admin-light);
        }

        .btn-admin-sm {
            padding: 5px 10px;
            font-size: 0.77rem;
        }

        .btn-group {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
        }

        /* ========== FORMS ========== */
        .admin-form .form-group {
            margin-bottom: 18px;
        }

        .admin-form label {
            display: block;
            font-weight: 600;
            font-size: 0.83rem;
            margin-bottom: 6px;
            color: var(--admin-dark);
        }

        .admin-form .form-control {
            width: 100%;
            padding: 9px 13px;
            border: 1px solid var(--admin-border);
            border-radius: 8px;
            font-family: 'Outfit', sans-serif;
            font-size: 0.88rem;
            transition: border 0.18s, box-shadow 0.18s;
            background: white;
            color: var(--admin-dark);
        }

        .admin-form .form-control:focus {
            outline: none;
            border-color: var(--admin-primary);
            box-shadow: 0 0 0 3px rgba(255, 107, 53, 0.1);
        }

        .admin-form textarea.form-control {
            min-height: 96px;
            resize: vertical;
        }

        .admin-form .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .admin-form .form-hint {
            font-size: 0.76rem;
            color: var(--admin-gray);
            margin-top: 4px;
        }

        .admin-form .form-check {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            font-weight: 500;
            font-size: 0.88rem;
        }

        .admin-form .form-check input[type="checkbox"] {
            width: 16px;
            height: 16px;
            cursor: pointer;
            accent-color: var(--admin-primary);
        }

        /* ========== FILTER BAR ========== */
        .filter-bar {
            display: flex;
            gap: 10px;
            margin-bottom: 18px;
            flex-wrap: wrap;
            align-items: center;
        }

        .filter-bar input,
        .filter-bar select {
            padding: 8px 12px;
            border: 1px solid var(--admin-border);
            border-radius: 8px;
            font-family: 'Outfit', sans-serif;
            font-size: 0.85rem;
            background: white;
        }

        /* ========== PAGINATION ========== */
        .admin-pagination {
            display: flex;
            gap: 4px;
            margin-top: 18px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .admin-pagination a,
        .admin-pagination span {
            padding: 7px 13px;
            border-radius: 6px;
            font-size: 0.8rem;
            border: 1px solid var(--admin-border);
            color: var(--admin-dark);
            text-decoration: none;
            font-weight: 500;
        }

        .admin-pagination .active {
            background: var(--admin-primary);
            color: white;
            border-color: var(--admin-primary);
        }

        /* ========== SIDEBAR-GRID LAYOUT (create/edit pages) ========== */
        /* Usage: <div class="admin-sidebar-grid"> main | sidebar </div> */
        .admin-sidebar-grid {
            display: grid;
            grid-template-columns: 1fr 320px;
            gap: 24px;
            align-items: start;
        }

        /* ========== RESPONSIVE ========== */
        @media (max-width: 1280px) {
            .stats-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 1024px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .content-area {
                padding: 20px 22px;
            }

            .admin-sidebar-grid {
                grid-template-columns: 1fr 280px;
            }
        }

        @media (max-width: 900px) {
            :root {
                --sidebar-width: 0px;
            }

            .sidebar {
                width: 260px;
                transform: translateX(-100%);
                z-index: 200;
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .sidebar-close {
                display: flex;
            }

            .main-content {
                margin-left: 0;
            }

            .btn-hamburger {
                display: flex;
            }

            .topbar {
                padding: 0 16px;
            }

            .content-area {
                padding: 16px;
            }

            .admin-form .form-row {
                grid-template-columns: 1fr;
                gap: 0;
            }

            /* Stack sidebar below main on tablets */
            .admin-sidebar-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 640px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 12px;
            }

            .panel-body {
                padding: 16px;
            }

            .panel-header {
                padding: 14px 16px;
            }

            .topbar-title {
                font-size: 1rem;
            }

            .topbar-user span {
                display: none;
            }
        }

        @media (max-width: 420px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
    @yield('styles')
</head>

<body>

    {{-- Sidebar Overlay --}}
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

    {{-- Sidebar --}}
    <aside class="sidebar" id="sidebar">
        <button class="sidebar-close" id="sidebarClose" onclick="closeSidebar()"><i class="fas fa-times"></i></button>

        <div class="sidebar-brand">
            @if ($adminSettings['site_logo'])
                <img src="{{ asset('assets/frontend/img/Pretty-pet-white-logo.png') }}"
                    alt="{{ $adminSettings['site_name'] }}" class="sidebar-logo-img">
            @else
                <div class="sidebar-logo-icon"><i class="fas fa-paw"></i></div>
                <div>
                    <span class="sidebar-brand-text">{{ $adminSettings['site_name'] }}</span>
                    <span class="sidebar-brand-sub">Admin Panel</span>
                </div>
            @endif
        </div>

        <nav class="sidebar-nav">
            <div class="sidebar-label">Main</div>
            <a href="{{ route('admin.dashboard') }}"
                class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-th-large"></i> Dashboard
            </a>
            <a href="{{ route('admin.service-city-seo.index') }}"
                class="sidebar-link {{ request()->routeIs('admin.service-city-seo.*') ? 'active' : '' }}">
                <i class="fas fa-magnifying-glass"></i> Seo Settings
            </a>
            <div class="sidebar-label">Vaccinations</div>
            <a href="{{ route('admin.vaccination-types.index') }}"
                class="sidebar-link {{ request()->routeIs('admin.vaccination-types.*') ? 'active' : '' }}">
                <i class="fas fa-syringe"></i> Vaccination Types
            </a>
            <a href="{{ route('admin.pet-types.index') }}"
                class="sidebar-link {{ request()->routeIs('admin.pet-types.*') ? 'active' : '' }}">
                <i class="fas fa-paw"></i> Pet Types
            </a>
            <a href="{{ route('admin.bookings.index') }}"
                class="sidebar-link {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}">
                <i class="fas fa-syringe"></i> Vaccination Bookings
            </a>

            <div class="sidebar-label">Appointment</div>
            <a href="{{ route('admin.appointment-bookings.index') }}"
                class="sidebar-link {{ request()->routeIs('admin.appointment-bookings.*') ? 'active' : '' }}">
                <i class="fas fa-calendar-check"></i> Appointment Bookings
            </a>
            <a href="{{ route('admin.appointment-types.index') }}"
                class="sidebar-link {{ request()->routeIs('admin.appointment-types.*') ? 'active' : '' }}">
                <i class="fas fa-concierge-bell"></i> Appointment Types
            </a>

            <div class="sidebar-label">Diagnostics</div>
            <a href="{{ route('admin.test-types.index') }}"
                class="sidebar-link {{ request()->routeIs('admin.test-types.*') ? 'active' : '' }}">
                <i class="fas fa-vials"></i> Diagnostic Tests
            </a>
            <a href="{{ route('admin.test-bookings.index') }}"
                class="sidebar-link {{ request()->routeIs('admin.test-bookings.*') ? 'active' : '' }}">
                <i class="fas fa-clipboard-check"></i> Test Bookings
            </a>

            <div class="sidebar-label">Adoption</div>
            <a href="{{ route('admin.pet-adoptions.index') }}"
                class="sidebar-link {{ request()->routeIs('admin.pet-adoptions.*') ? 'active' : '' }}">
                <i class="fas fa-paw"></i> Pets for Adoption
            </a>
            <a href="{{ route('admin.adoption-bookings.index') }}"
                class="sidebar-link {{ request()->routeIs('admin.adoption-bookings.*') ? 'active' : '' }}">
                <i class="fas fa-heart"></i> Adoption Bookings
            </a>

            <div class="sidebar-label">Nutrition</div>
            <a href="{{ route('admin.nutrition-products.index') }}"
                class="sidebar-link {{ request()->routeIs('admin.nutrition-products.*') ? 'active' : '' }}">
                <i class="fas fa-bowl-food"></i> Nutrition Products
            </a>
            <a href="{{ route('admin.pet-types-for-nutrition.index') }}"
                class="sidebar-link {{ request()->routeIs('admin.pet-types-for-nutrition.*') ? 'active' : '' }}">
                <i class="fas fa-paw"></i> Pet Types for Nutrition
            </a>

            <div class="sidebar-label">Services</div>
            <a href="{{ route('admin.services.index') }}"
                class="sidebar-link {{ request()->routeIs('admin.services.*') ? 'active' : '' }}">
                <i class="fas fa-concierge-bell"></i> Services
            </a>
            <a href="{{ route('admin.cities.index') }}"
                class="sidebar-link {{ request()->routeIs('admin.cities.*') ? 'active' : '' }}">
                <i class="fas fa-city"></i> Cities
            </a>


            <div class="sidebar-label">Content</div>
            <a href="{{ route('admin.blogs.index') }}"
                class="sidebar-link {{ request()->routeIs('admin.blogs.*') ? 'active' : '' }}">
                <i class="fas fa-blog"></i> Blogs
            </a>
            <a href="{{ route('admin.faqs.index') }}"
                class="sidebar-link {{ request()->routeIs('admin.faqs.*') ? 'active' : '' }}">
                <i class="fas fa-question-circle"></i> FAQs
            </a>
            <a href="{{ route('admin.testimonials.index') }}"
                class="sidebar-link {{ request()->routeIs('admin.testimonials.*') ? 'active' : '' }}">
                <i class="fas fa-comments"></i> Testimonials
            </a>
            <a href="{{ route('admin.animal-types.index') }}"
                class="sidebar-link {{ request()->routeIs('admin.animal-types.*') ? 'active' : '' }}">
                <i class="fas fa-paw"></i> Animal Types
            </a>
            <a href="{{ route('admin.sliders.index') }}"
                class="sidebar-link {{ request()->routeIs('admin.sliders.*') ? 'active' : '' }}">
                <i class="fas fa-images"></i> Sliders
            </a>
            <a href="{{ route('admin.galleries.index') }}"
                class="sidebar-link {{ request()->routeIs('admin.galleries.*') ? 'active' : '' }}">
                <i class="fas fa-camera"></i> Gallery
            </a>
            <a href="{{ route('admin.pages.index') }}"
                class="sidebar-link {{ request()->routeIs('admin.pages.*') ? 'active' : '' }}">
                <i class="fas fa-file-alt"></i> Pages
            </a>
            <a href="{{ route('admin.founders.index') }}"
                class="sidebar-link {{ request()->routeIs('admin.founders.*') ? 'active' : '' }}">
                <i class="fas fa-users"></i> Founders
            </a>




            <div class="sidebar-label">System</div>
            @php $sidebarUnread = $adminSettings['unread_contacts']; @endphp
            <a href="{{ route('admin.contacts.index') }}"
                class="sidebar-link {{ request()->routeIs('admin.contacts.*') ? 'active' : '' }}"
                style="justify-content:space-between;">
                <span style="display:flex;align-items:center;gap:11px;"><i class="fas fa-envelope"
                        style="width:18px;text-align:center;font-size:0.9rem;flex-shrink:0;"></i> Messages</span>
                @if ($sidebarUnread > 0)
                    <span
                        style="background:var(--admin-danger);color:white;border-radius:50px;padding:1px 7px;font-size:0.68rem;font-weight:700;flex-shrink:0;">{{ $sidebarUnread }}</span>
                @endif
            </a>
            <a href="{{ route('admin.settings.index') }}"
                class="sidebar-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                <i class="fas fa-cog"></i> Settings
            </a>
            <a href="{{ route('admin.integrations.index') }}"
                class="sidebar-link {{ request()->routeIs('admin.integrations.*') ? 'active' : '' }}">
                <i class="fas fa-key"></i> API & Integrations
            </a>
            <a href="{{ route('admin.account') }}"
                class="sidebar-link {{ request()->routeIs('admin.account*') ? 'active' : '' }}">
                <i class="fas fa-user-shield"></i> My Account
            </a>
            <a href="{{ route('home') }}" class="sidebar-link" target="_blank">
                <i class="fas fa-external-link-alt"></i> View Site
            </a>
            <a href="{{ route('admin.logout') }}" class="sidebar-link"
                onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
            <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display:none;">@csrf
            </form>
        </nav>
    </aside>

    {{-- Main Content --}}
    <div class="main-content">
        <div class="topbar">
            <div class="topbar-left">
                <button class="btn-hamburger" onclick="openSidebar()" aria-label="Menu">
                    <i class="fas fa-bars"></i>
                </button>
                <h1 class="topbar-title">@yield('title', 'Dashboard')</h1>
            </div>
            <div class="topbar-right">
                @yield('actions')
                {{-- Notification Bell --}}
                @php
                    $unreadContacts = $adminSettings['unread_contacts'];
                    $pendingBookings = $adminSettings['pending_bookings'];
                    $totalNotifs = $unreadContacts + $pendingBookings;
                @endphp
                <div style="position:relative;">
                    <button onclick="toggleNotifDropdown()"
                        style="background:none;border:1px solid var(--admin-border);width:38px;height:38px;border-radius:50%;cursor:pointer;font-size:1rem;color:var(--admin-dark);display:flex;align-items:center;justify-content:center;position:relative;transition:background 0.15s;"
                        id="notifBtn">
                        <i class="fas fa-bell"></i>
                        @if ($totalNotifs > 0)
                            <span
                                style="position:absolute;top:-4px;right:-4px;background:var(--admin-danger);color:white;border-radius:50%;width:18px;height:18px;font-size:0.65rem;font-weight:700;display:flex;align-items:center;justify-content:center;">{{ $totalNotifs > 99 ? '99+' : $totalNotifs }}</span>
                        @endif
                    </button>
                    <div id="notifDropdown"
                        style="display:none;position:absolute;right:0;top:46px;background:white;border:1px solid var(--admin-border);border-radius:10px;width:280px;box-shadow:0 8px 24px rgba(0,0,0,0.12);z-index:300;">
                        <div
                            style="padding:12px 16px;border-bottom:1px solid var(--admin-border);font-weight:600;font-size:0.85rem;">
                            Notifications</div>
                        @if ($unreadContacts > 0)
                            <a href="{{ route('admin.contacts.index', ['status' => 'unread']) }}"
                                onclick="closeNotifDropdown()"
                                style="display:flex;align-items:center;gap:12px;padding:12px 16px;text-decoration:none;color:var(--admin-dark);border-bottom:1px solid var(--admin-border);transition:background 0.15s;"
                                onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background=''">
                                <div
                                    style="width:36px;height:36px;background:#EFF6FF;border-radius:8px;display:flex;align-items:center;justify-content:center;color:#3B82F6;flex-shrink:0;">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <div>
                                    <div style="font-size:0.85rem;font-weight:600;">{{ $unreadContacts }} Unread
                                        Message{{ $unreadContacts > 1 ? 's' : '' }}</div>
                                    <div style="font-size:0.75rem;color:var(--admin-gray);">Contact form submissions
                                    </div>
                                </div>
                            </a>
                        @endif
                        @if ($pendingBookings > 0)
                            <a href="{{ route('admin.bookings.index', ['status' => 'pending']) }}"
                                onclick="closeNotifDropdown()"
                                style="display:flex;align-items:center;gap:12px;padding:12px 16px;text-decoration:none;color:var(--admin-dark);border-bottom:1px solid var(--admin-border);transition:background 0.15s;"
                                onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background=''">
                                <div
                                    style="width:36px;height:36px;background:#FFFBEB;border-radius:8px;display:flex;align-items:center;justify-content:center;color:#F59E0B;flex-shrink:0;">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div>
                                    <div style="font-size:0.85rem;font-weight:600;">{{ $pendingBookings }} Pending
                                        Booking{{ $pendingBookings > 1 ? 's' : '' }}</div>
                                    <div style="font-size:0.75rem;color:var(--admin-gray);">Vaccination, diagnostic
                                        &amp; adoption</div>
                                </div>
                            </a>
                        @endif
                        @if ($totalNotifs === 0)
                            <div
                                style="padding:20px 16px;text-align:center;color:var(--admin-gray);font-size:0.85rem;">
                                <i class="fas fa-check-circle"
                                    style="display:block;font-size:1.5rem;margin-bottom:6px;opacity:0.4;"></i>All
                                caught up!
                            </div>
                        @endif
                        <div style="padding:10px 16px;text-align:center;">
                            <a href="{{ route('admin.contacts.index') }}"
                                style="font-size:0.8rem;color:var(--admin-primary);text-decoration:none;font-weight:600;">View
                                all messages</a>
                        </div>
                    </div>
                </div>
                @auth
                    <div class="topbar-user">
                        <div class="topbar-user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                        <span>{{ auth()->user()->name }}</span>
                    </div>
                @endauth
            </div>
        </div>

        <div class="content-area">
            @if (session('success'))
                <div class="admin-alert admin-alert-success">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="admin-alert admin-alert-danger">
                    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <script>
        function toggleNotifDropdown() {
            var d = document.getElementById('notifDropdown');
            d.style.display = d.style.display === 'none' ? 'block' : 'none';
        }

        function closeNotifDropdown() {
            document.getElementById('notifDropdown').style.display = 'none';
        }
        document.addEventListener('click', function(e) {
            var btn = document.getElementById('notifBtn');
            var dropdown = document.getElementById('notifDropdown');
            if (btn && dropdown && !btn.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.style.display = 'none';
            }
        });

        function openSidebar() {
            document.getElementById('sidebar').classList.add('open');
            document.getElementById('sidebarOverlay').classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeSidebar() {
            document.getElementById('sidebar').classList.remove('open');
            document.getElementById('sidebarOverlay').classList.remove('active');
            document.body.style.overflow = '';
        }
        // Close sidebar on resize to desktop
        window.addEventListener('resize', function() {
            if (window.innerWidth > 900) {
                closeSidebar();
            }
        });
    </script>

    @yield('scripts')
</body>

</html>
