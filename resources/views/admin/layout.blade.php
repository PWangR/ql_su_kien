<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') - Quản Lý Sự Kiện</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Montserrat:wght@700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --primary:    #2563EB;
            --primary-dk: #1d4ed8;
            --secondary:  #0F172A;
            --sidebar-w:  240px;
            --header-h:   64px;
            --bg:         #F1F5F9;
            --card:       #FFFFFF;
            --danger:     #EF4444;
            --success:    #22C55E;
            --warning:    #F59E0B;
            --text:       #1E293B;
            --text-light: #64748B;
            --border:     #E2E8F0;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--text);
            display: flex;
            min-height: 100vh;
        }

        /* ===================== SIDEBAR ===================== */
        .sidebar {
            width: var(--sidebar-w);
            background: var(--secondary);
            min-height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 200;
            display: flex;
            flex-direction: column;
            transition: transform 0.3s ease;
        }

        .sidebar-brand {
            height: var(--header-h);
            padding: 0 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            text-decoration: none;
        }

        .sidebar-brand-icon {
            width: 36px; height: 36px;
            background: linear-gradient(135deg, var(--primary), #60a5fa);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-size: 16px; flex-shrink: 0;
        }

        .sidebar-brand-text {
            font-family: 'Montserrat', sans-serif;
            font-size: 14px; font-weight: 700;
            color: #fff; line-height: 1.3;
        }

        .sidebar-brand-text span { display: block; font-size: 10px; font-weight: 400; color: #94a3b8; font-family: 'Inter', sans-serif; }

        .sidebar-nav {
            padding: 16px 12px;
            flex: 1;
            overflow-y: auto;
        }

        .sidebar-label {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #475569;
            padding: 12px 8px 6px;
        }

        .sidebar-nav a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: 10px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            color: #94a3b8;
            margin-bottom: 2px;
            transition: all 0.2s;
        }

        .sidebar-nav a i { font-size: 16px; flex-shrink: 0; }

        .sidebar-nav a:hover {
            background: rgba(255,255,255,0.07);
            color: #fff;
        }

        .sidebar-nav a.active {
            background: linear-gradient(135deg, rgba(37,99,235,0.7), rgba(59,130,246,0.5));
            color: #fff;
            box-shadow: 0 2px 8px rgba(37,99,235,0.3);
        }

        .sidebar-footer {
            padding: 16px 12px;
            border-top: 1px solid rgba(255,255,255,0.08);
        }

        .sidebar-user {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: 10px;
            background: rgba(255,255,255,0.05);
        }

        .sidebar-user-avatar {
            width: 34px; height: 34px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), #60a5fa);
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-size: 13px; font-weight: 700; flex-shrink: 0;
            overflow: hidden;
        }

        .sidebar-user-info { flex: 1; overflow: hidden; }
        .sidebar-user-name { font-size: 13px; font-weight: 600; color: #fff; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .sidebar-user-role { font-size: 11px; color: #64748b; }

        /* ===================== TOPBAR ===================== */
        .topbar {
            position: fixed;
            top: 0;
            left: var(--sidebar-w);
            right: 0;
            height: var(--header-h);
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--border);
            z-index: 100;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 28px;
            box-shadow: 0 1px 6px rgba(0,0,0,0.06);
        }

        .topbar-left {
            display: flex; align-items: center; gap: 12px;
        }

        .page-title {
            font-family: 'Montserrat', sans-serif;
            font-size: 17px; font-weight: 700;
            color: var(--secondary);
        }

        .topbar-right { display: flex; align-items: center; gap: 12px; }

        .topbar-btn {
            width: 36px; height: 36px;
            border-radius: 8px;
            border: 1px solid var(--border);
            background: #fff;
            display: flex; align-items: center; justify-content: center;
            color: var(--text-light);
            cursor: pointer;
            font-size: 16px;
            text-decoration: none;
            transition: all 0.2s;
        }

        .topbar-btn:hover { border-color: var(--primary); color: var(--primary); }

        .topbar-logout {
            display: flex; align-items: center; gap: 6px;
            padding: 7px 14px;
            background: var(--danger);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
            transition: background 0.2s;
        }

        .topbar-logout:hover { background: #dc2626; }

        /* ===================== CONTENT ===================== */
        .admin-content {
            margin-left: var(--sidebar-w);
            padding-top: var(--header-h);
            min-height: 100vh;
            flex: 1;
        }

        .content-inner {
            padding: 28px;
        }

        /* ===================== COMPONENTS ===================== */
        .alert {
            padding: 12px 18px;
            border-radius: 10px;
            font-size: 14px;
            display: flex; align-items: center; gap: 10px;
            margin-bottom: 20px;
        }

        .alert-success { background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; }
        .alert-error   { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }

        .card {
            background: var(--card);
            border-radius: 14px;
            border: 1px solid var(--border);
            box-shadow: 0 1px 8px rgba(0,0,0,0.05);
            overflow: hidden;
        }

        .card-header {
            padding: 16px 20px;
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between;
            background: #fff;
        }

        .card-title {
            font-size: 15px; font-weight: 700; color: var(--text);
            display: flex; align-items: center; gap: 8px;
        }

        .card-body { padding: 20px; }

        .stat-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 28px;
        }

        .stat-card {
            background: var(--card);
            border-radius: 14px;
            padding: 20px;
            border: 1px solid var(--border);
            display: flex; align-items: center; gap: 16px;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .stat-card:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(0,0,0,0.08); }

        .stat-icon {
            width: 52px; height: 52px;
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 22px; flex-shrink: 0;
        }

        .stat-info { flex: 1; }
        .stat-value { font-size: 26px; font-weight: 800; color: var(--text); font-family: 'Montserrat', sans-serif; }
        .stat-label { font-size: 12px; color: var(--text-light); margin-top: 2px; font-weight: 500; }

        /* Table */
        .table-resp { overflow-x: auto; }

        table { width: 100%; border-collapse: collapse; }
        thead tr { background: #f8fafc; }
        th { padding: 12px 16px; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: var(--text-light); border-bottom: 1px solid var(--border); text-align: left; white-space: nowrap; }
        td { padding: 14px 16px; font-size: 14px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
        tbody tr:hover { background: #f8fafc; }
        tbody tr:last-child td { border-bottom: none; }

        /* Badges */
        .badge {
            display: inline-flex; align-items: center;
            padding: 3px 10px; border-radius: 20px;
            font-size: 11.5px; font-weight: 600;
        }

        .badge-primary  { background: #dbeafe; color: #1d4ed8; }
        .badge-success  { background: #dcfce7; color: #15803d; }
        .badge-danger   { background: #fee2e2; color: #b91c1c; }
        .badge-warning  { background: #fef3c7; color: #92400e; }
        .badge-secondary{ background: #f1f5f9; color: #475569; }

        /* Buttons */
        .btn {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 13px; font-weight: 600;
            border: none; cursor: pointer;
            text-decoration: none;
            transition: all 0.2s;
            font-family: 'Inter', sans-serif;
        }

        .btn-primary { background: var(--primary); color: #fff; }
        .btn-primary:hover { background: var(--primary-dk); }
        .btn-danger  { background: var(--danger); color: #fff; }
        .btn-danger:hover { background: #dc2626; }
        .btn-secondary { background: #f1f5f9; color: var(--text); border: 1px solid var(--border); }
        .btn-secondary:hover { background: #e2e8f0; }
        .btn-sm { padding: 5px 10px; font-size: 12px; }
        .btn-warning { background: var(--warning); color: #fff; }
        .btn-success { background: var(--success); color: #fff; }

        /* Form */
        .form-label { display: block; font-size: 13px; font-weight: 600; color: var(--text); margin-bottom: 6px; }
        .form-control {
            display: block; width: 100%;
            padding: 10px 14px;
            border: 1.5px solid var(--border);
            border-radius: 8px;
            font-size: 14px; font-family: 'Inter', sans-serif; color: var(--text);
            background: #f8fafc;
            transition: all 0.2s; outline: none;
        }

        .form-control:focus { border-color: var(--primary); background: #fff; box-shadow: 0 0 0 3px rgba(37,99,235,0.08); }
        .form-control.is-invalid { border-color: var(--danger); }
        .invalid-feedback { font-size: 12px; color: var(--danger); margin-top: 4px; }

        textarea.form-control { resize: vertical; min-height: 100px; }

        .mb-1 { margin-bottom: 8px; }
        .mb-2 { margin-bottom: 14px; }
        .mb-3 { margin-bottom: 20px; }
        .mb-4 { margin-bottom: 28px; }
        .d-flex { display: flex; } .align-items-center { align-items: center; } .justify-content-between { justify-content: space-between; }
        .gap-2 { gap: 8px; } .gap-3 { gap: 14px; }
        .mt-3 { margin-top: 20px; }
        .text-muted { color: var(--text-light); }
        .text-sm { font-size: 12px; }

        .input-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        @media (max-width: 600px) { .input-grid { grid-template-columns: 1fr; } }

        /* Sidebar mobile toggle */
        .sidebar-toggle {
            display: none;
            background: none; border: none; cursor: pointer;
            font-size: 22px; color: var(--text);
        }

        @media (max-width: 900px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .admin-content { margin-left: 0; }
            .topbar { left: 0; }
            .sidebar-toggle { display: flex; }
        }

        /* Sidebar overlay */
        .sidebar-overlay {
            display: none;
            position: fixed; inset: 0; background: rgba(0,0,0,0.5);
            z-index: 199;
        }
        .sidebar-overlay.show { display: block; }
    </style>

    {{-- Vite Scripts & Styles --}}
    @vite(['resources/css/app.css', 'resources/css/loading.css', 'resources/js/app.js'])

    @yield('styles')
</head>
<body>

{{-- ========================== GLOBAL LOADING OVERLAY ========================== --}}
<x-loading-overlay />

<!-- Sidebar Overlay (mobile) -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- ===================== SIDEBAR ===================== -->
<aside class="sidebar" id="sidebar">
    <a href="{{ route('admin.dashboard') }}" class="sidebar-brand">
        <div class="sidebar-brand-icon"><i class="bi bi-calendar-event-fill"></i></div>
        <div class="sidebar-brand-text">
            Admin Panel
            <span>Quản Lý Sự Kiện</span>
        </div>
    </a>

    <nav class="sidebar-nav">
        <div class="sidebar-label">Tổng quan</div>
        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard*') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>

        <div class="sidebar-label">Quản lý</div>
        <a href="{{ route('admin.su-kien.index') }}" class="{{ request()->routeIs('admin.su-kien*') ? 'active' : '' }}">
            <i class="bi bi-calendar3"></i> Quản lý sự kiện
        </a>
        <a href="{{ route('admin.nguoi-dung.index') }}" class="{{ request()->routeIs('admin.nguoi-dung*') ? 'active' : '' }}">
            <i class="bi bi-people-fill"></i> Quản lý người dùng
        </a>
        <a href="{{ route('admin.media.index') }}" class="{{ request()->routeIs('admin.media*') ? 'active' : '' }}">
            <i class="bi bi-images"></i> Thư viện media
        </a>
        <a href="{{ route('admin.templates.index') }}" class="{{ request()->routeIs('admin.templates*') ? 'active' : '' }}">
            <i class="bi bi-file-earmark-text-fill"></i> Template bài đăng
        </a>
        <a href="{{ route('admin.bau-cu.index') }}" class="{{ request()->routeIs('admin.bau-cu*') ? 'active' : '' }}">
            <i class="bi bi-clipboard2-check-fill"></i> Bầu cử
        </a>

        <div class="sidebar-label">Báo cáo</div>
        <a href="{{ route('admin.thong-ke.index') }}" class="{{ request()->routeIs('admin.thong-ke*') ? 'active' : '' }}">
            <i class="bi bi-bar-chart-fill"></i> Thống kê
        </a>
    </nav>

    <div class="sidebar-footer">
        <div class="sidebar-user">
            <div class="sidebar-user-avatar">
                @if(auth()->user()->duong_dan_anh)
                    <img src="{{ asset('storage/'.auth()->user()->duong_dan_anh) }}" alt="avatar" style="width:100%;height:100%;object-fit:cover;">
                @else
                    {{ mb_substr(auth()->user()->ho_ten, 0, 1) }}
                @endif
            </div>
            <div class="sidebar-user-info">
                <div class="sidebar-user-name">{{ auth()->user()->ho_ten }}</div>
                <div class="sidebar-user-role">Quản trị viên</div>
            </div>
        </div>
    </div>
</aside>

<!-- ===================== TOPBAR ===================== -->
<header class="topbar">
    <div class="topbar-left">
        <button class="sidebar-toggle" id="sidebarToggle">
            <i class="bi bi-list"></i>
        </button>
        <h1 class="page-title">@yield('page-title', 'Dashboard')</h1>
    </div>
    <div class="topbar-right">
        <a href="{{ route('home') }}" class="topbar-btn" title="Xem Website">
            <i class="bi bi-globe"></i>
        </a>
        <form method="POST" action="{{ route('logout') }}" style="display:inline;">
            @csrf
            <button type="submit" class="topbar-logout">
                <i class="bi bi-box-arrow-right"></i> Đăng xuất
            </button>
        </form>
    </div>
</header>

<!-- ===================== CONTENT ===================== -->
<div class="admin-content">
    <div class="content-inner">
        @if(session('success'))
        <div class="alert alert-success">
            <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="alert alert-error">
            <i class="bi bi-exclamation-circle-fill"></i> {{ session('error') }}
        </div>
        @endif

        @yield('content')
    </div>
</div>

<script>
// Sidebar mobile toggle
const sidebar        = document.getElementById('sidebar');
const sidebarOverlay = document.getElementById('sidebarOverlay');
const sidebarToggle  = document.getElementById('sidebarToggle');

if (sidebarToggle) {
    sidebarToggle.addEventListener('click', () => {
        sidebar.classList.toggle('open');
        sidebarOverlay.classList.toggle('show');
    });
}

if (sidebarOverlay) {
    sidebarOverlay.addEventListener('click', () => {
        sidebar.classList.remove('open');
        sidebarOverlay.classList.remove('show');
    });
}

// Auto-hide alerts
setTimeout(() => {
    document.querySelectorAll('.alert').forEach(a => {
        a.style.transition = 'opacity 0.5s';
        a.style.opacity = '0';
        setTimeout(() => a.remove(), 500);
    });
}, 4000);
</script>

@yield('scripts')
</body>
</html>