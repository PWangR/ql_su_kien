<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — Quản Lý Sự Kiện</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @yield('styles')
</head>

<body class="admin-shell" style="display:flex;min-height:100vh;">

    <x-loading-overlay />

    <!-- Sidebar Overlay (mobile) -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- ════════════ SIDEBAR ════════════ -->
    <aside class="sidebar" id="sidebar">
        <a href="{{ route('admin.dashboard') }}" class="sidebar-brand">
            <div class="sidebar-brand-seal"><i class="bi bi-mortarboard-fill"></i></div>
            <div>
                <div class="sidebar-brand-text">Admin Panel</div>
                <span class="sidebar-brand-sub">Quản Lý Sự Kiện</span>
            </div>
            <button type="button" class="sidebar-close" id="sidebarClose" aria-label="Đóng menu">
                <i class="bi bi-x-lg"></i>
            </button>
        </a>

        <nav class="sidebar-nav">
            <div class="sidebar-label">Tổng quan</div>
            <a href="{{ route('admin.dashboard') }}"
                class="{{ request()->routeIs('admin.dashboard*') ? 'active' : '' }}">
                <i class="bi bi-columns-gap"></i> Dashboard
            </a>

            <div class="sidebar-label">Quản lý</div>
            <a href="{{ route('admin.su-kien.index') }}"
                class="{{ request()->routeIs('admin.su-kien*') ? 'active' : '' }}">
                <i class="bi bi-calendar3"></i> Sự kiện
            </a>
            <a href="{{ route('admin.nguoi-dung.index') }}"
                class="{{ request()->routeIs('admin.nguoi-dung*') ? 'active' : '' }}">
                <i class="bi bi-people"></i> Người dùng
            </a>
            <a href="{{ route('admin.thong-bao.index') }}"
                class="{{ request()->routeIs('admin.thong-bao*') ? 'active' : '' }}">
                <i class="bi bi-bell"></i> Thông Báo
            </a>
            <a href="{{ route('admin.media.index') }}" class="{{ request()->routeIs('admin.media*') ? 'active' : '' }}">
                <i class="bi bi-images"></i> Thư viện media
            </a>
            <a href="{{ route('admin.templates.index') }}"
                class="{{ request()->routeIs('admin.templates*') ? 'active' : '' }}">
                <i class="bi bi-file-earmark-text"></i> Templates
            </a>
            <a href="{{ route('admin.bau-cu.index') }}"
                class="{{ request()->routeIs('admin.bau-cu*') ? 'active' : '' }}">
                <i class="bi bi-clipboard2-check"></i> Bầu cử
            </a>

            <div class="sidebar-label">Báo cáo</div>
            <a href="{{ route('admin.bao-cao.index') }}"
                class="{{ request()->routeIs('admin.bao-cao*') ? 'active' : '' }}">
                <i class="bi bi-file-earmark-excel"></i> Xuất báo cáo
            </a>
            <a href="{{ route('admin.thong-ke.index') }}"
                class="{{ request()->routeIs('admin.thong-ke*') ? 'active' : '' }}">
                <i class="bi bi-bar-chart"></i> Thống kê
            </a>

            <div class="sidebar-label">Điểm danh</div>
            <a href="{{ route('admin.diem-danh.index') }}"
                class="{{ request()->routeIs('admin.diem-danh.index') ? 'active' : '' }}">
                <i class="bi bi-qr-code"></i> QR sự kiện
            </a>
            <a href="{{ route('admin.diem-danh.scanner') }}"
                class="{{ request()->routeIs('admin.diem-danh.scanner') ? 'active' : '' }}">
                <i class="bi bi-camera"></i> Quét QR người dùng
            </a>

            <div class="sidebar-label">Cài đặt</div>
            <a href="{{ route('admin.smtp.index') }}" class="{{ request()->routeIs('admin.smtp*') ? 'active' : '' }}">
                <i class="bi bi-envelope"></i> Cấu hình SMTP
            </a>
            <a href="{{ route('admin.gemini.index') }}"
                class="{{ request()->routeIs('admin.gemini*') ? 'active' : '' }}">
                <i class="bi bi-robot"></i> Gemini AI chatbot
            </a>
            <a href="{{ route('admin.activity-logs.index') }}"
                class="{{ request()->routeIs('admin.activity-logs*') ? 'active' : '' }}">
                <i class="bi bi-clock-history"></i> Log hoạt động
            </a>
        </nav>

        <div class="sidebar-footer">
            <div class="sidebar-user">
                <div class="sidebar-user-avatar">
                    @if(auth()->user()->duong_dan_anh)
                        <img src="{{ asset('storage/' . auth()->user()->duong_dan_anh) }}" alt="avatar"
                            style="width:100%;height:100%;object-fit:cover;">
                    @else
                        {{ mb_substr(auth()->user()->ho_ten, 0, 1) }}
                    @endif
                </div>
                <div style="flex:1;overflow:hidden;">
                    <div class="sidebar-user-name">{{ auth()->user()->ho_ten }}</div>
                    <div class="sidebar-user-role">Quản trị viên</div>
                </div>
            </div>
        </div>
    </aside>

    <!-- ════════════ TOPBAR ════════════ -->
    <header class="topbar">
        <div class="topbar-left">
            <button class="sidebar-toggle" id="sidebarToggle" type="button" aria-label="Mở menu" aria-controls="sidebar" aria-expanded="false">
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

    <!-- ════════════ CONTENT ════════════ -->
    <div class="admin-content">
        <div class="content-inner">
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="bi bi-check-circle"></i> {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-error">
                    <i class="bi bi-exclamation-circle"></i> {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <nav class="admin-mobile-nav" aria-label="Điều hướng admin nhanh">
        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard*') ? 'active' : '' }}">
            <i class="bi bi-columns-gap"></i>
            <span>Tổng quan</span>
        </a>
        <a href="{{ route('admin.su-kien.index') }}" class="{{ request()->routeIs('admin.su-kien*') ? 'active' : '' }}">
            <i class="bi bi-calendar3"></i>
            <span>Sự kiện</span>
        </a>
        <a href="{{ route('admin.media.index') }}" class="{{ request()->routeIs('admin.media*') ? 'active' : '' }}">
            <i class="bi bi-images"></i>
            <span>Media</span>
        </a>
        <a href="{{ route('admin.thong-ke.index') }}" class="{{ request()->routeIs('admin.thong-ke*') ? 'active' : '' }}">
            <i class="bi bi-bar-chart"></i>
            <span>Thống kê</span>
        </a>
        <a href="{{ route('admin.diem-danh.index') }}" class="{{ request()->routeIs('admin.diem-danh*') ? 'active' : '' }}">
            <i class="bi bi-qr-code"></i>
            <span>QR</span>
        </a>
    </nav>

    <script>
        // Sidebar mobile toggle
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebarClose = document.getElementById('sidebarClose');

        function setSidebarOpen(isOpen) {
            sidebar.classList.toggle('open', isOpen);
            sidebarOverlay.classList.toggle('show', isOpen);
            sidebarToggle?.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            document.body.classList.toggle('sidebar-is-open', isOpen);
        }

        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', () => {
                setSidebarOpen(!sidebar.classList.contains('open'));
            });
        }

        if (sidebarOverlay) {
            sidebarOverlay.addEventListener('click', () => {
                setSidebarOpen(false);
            });
        }

        if (sidebarClose) {
            sidebarClose.addEventListener('click', (event) => {
                event.preventDefault();
                event.stopPropagation();
                setSidebarOpen(false);
            });
        }

        document.querySelectorAll('.sidebar-nav a').forEach((link) => {
            link.addEventListener('click', () => {
                if (window.matchMedia('(max-width: 900px)').matches) {
                    setSidebarOpen(false);
                }
            });
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                setSidebarOpen(false);
            }
        });

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
