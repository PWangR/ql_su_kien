<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Hệ thống Quản Lý Sự Kiện — Khoa Công Nghệ Thông Tin, Đại học Nha Trang">
    <title>@yield('title', 'Quản Lý Sự Kiện — Khoa CNTT, ĐH Nha Trang')</title>

    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    {{-- Vite Assets --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @yield('styles')
</head>
<body style="display:flex;flex-direction:column;min-height:100vh;">

{{-- Loading Overlay --}}
<x-loading-overlay />

<!-- ════════════ HEADER ════════════ -->
<header class="site-header">
    <div class="header-inner">
        <!-- Logo -->
        <a href="{{ route('home') }}" class="logo">
            <div class="logo-seal"><i class="bi bi-mortarboard-fill"></i></div>
            <div>
                <div class="logo-text">Quản Lý Sự Kiện</div>
                <span class="logo-sub">Khoa CNTT — ĐH Nha Trang</span>
            </div>
        </a>

        <!-- Hamburger -->
        <button class="hamburger" id="hamburgerBtn" aria-label="Menu">
            <span></span><span></span><span></span>
        </button>

        <!-- Nav -->
        <nav class="main-nav" id="mainNav">
            <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">
                <i class="bi bi-house"></i> Trang chủ
            </a>
            <a href="{{ route('events.index') }}" class="{{ request()->routeIs('events.*') ? 'active' : '' }}">
                <i class="bi bi-calendar3"></i> Sự kiện
            </a>
            <a href="{{ route('bau-cu.index') }}" class="{{ request()->routeIs('bau-cu.*') || request()->routeIs('bo-phieu.*') ? 'active' : '' }}">
                <i class="bi bi-clipboard2-check"></i> Bầu cử
            </a>
            @auth
            <a href="{{ route('history.index') }}" class="{{ request()->routeIs('history.*') ? 'active' : '' }}">
                <i class="bi bi-clock-history"></i> Lịch sử
            </a>
            <a href="{{ route('notifications.index') }}" class="{{ request()->routeIs('notifications.*') ? 'active' : '' }}">
                <i class="bi bi-bell"></i> Thông báo
                @php
                    $unreadCount = \App\Models\ThongBao::where('ma_nguoi_dung', auth()->id())->where('da_doc', false)->count();
                @endphp
                @if($unreadCount > 0)
                    <span class="notif-badge">{{ $unreadCount }}</span>
                @endif
            </a>
            @endauth
        </nav>

        <!-- User Menu -->
        @auth
        <div class="user-menu dropdown">
            <button class="user-avatar-btn" id="avatarBtn">
                <div class="avatar">
                    @if(auth()->user()->duong_dan_anh)
                        <img src="{{ asset('storage/'.auth()->user()->duong_dan_anh) }}" alt="avatar">
                    @else
                        {{ mb_substr(auth()->user()->ho_ten, 0, 1) }}
                    @endif
                </div>
                <span class="user-name">{{ auth()->user()->ho_ten }}</span>
                <i class="bi bi-chevron-down" style="font-size:10px;color:var(--text-muted)"></i>
            </button>

            <div class="dropdown-menu" id="dropdownMenu">
                <a href="{{ route('profile.index') }}"><i class="bi bi-person"></i> Hồ sơ cá nhân</a>
                @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}"><i class="bi bi-shield-check"></i> Quản trị viên</a>
                @endif
                <div class="divider"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="danger"><i class="bi bi-box-arrow-right"></i> Đăng xuất</button>
                </form>
            </div>
        </div>
        @else
        <a href="{{ route('login') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-box-arrow-in-right"></i> Đăng nhập
        </a>
        @endauth
    </div>
</header>

<!-- ════════════ MAIN ════════════ -->
<main class="site-main" style="flex:1;">
    <div style="max-width:var(--max-width);margin:0 auto;padding:var(--space-lg);">
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
</main>

<!-- ════════════ FOOTER ════════════ -->
<footer class="site-footer">
    <div class="footer-inner">
        <div class="footer-brand">
            <div class="logo-text">Quản Lý Sự Kiện</div>
            <span class="logo-sub">Khoa CNTT — ĐH Nha Trang</span>
        </div>
        <div class="footer-info">
            <strong style="color:#E8E4DC;">Khoa Công Nghệ Thông Tin</strong><br>
            Trường Đại học Nha Trang<br>
            <a href="mailto:cntt@ntu.edu.vn">cntt@ntu.edu.vn</a>
        </div>
    </div>
    <div class="footer-bottom">
        &copy; {{ date('Y') }} Khoa CNTT — Đại học Nha Trang
    </div>
</footer>

<!-- ════════════ CHATBOT ════════════ -->
<div class="chatbot-float">
    <div class="chatbot-box" id="chatbotBox">
        <div class="chatbot-head">
            <span><i class="bi bi-chat-text"></i> Trợ lý sự kiện</span>
            <button onclick="toggleChatbot()"><i class="bi bi-x-lg"></i></button>
        </div>
        <div class="chatbot-messages" id="chatMessages">
            <div class="chat-msg bot">Xin chào! Tôi là trợ lý sự kiện Khoa CNTT. Tôi có thể giúp bạn tìm hiểu về các sự kiện sắp diễn ra.</div>
        </div>
        <div class="chatbot-quick">
            <button class="quick-btn" onclick="chatbotAnswer('upcoming')">Sự kiện sắp tới</button>
            <button class="quick-btn" onclick="chatbotAnswer('register')">Cách đăng ký</button>
            <button class="quick-btn" onclick="chatbotAnswer('location')">Địa điểm</button>
            <button class="quick-btn" onclick="chatbotAnswer('points')">Điểm cộng</button>
        </div>
    </div>
    <button class="chatbot-toggle" onclick="toggleChatbot()" title="Trợ lý sự kiện">
        <i class="bi bi-chat-dots" id="chatIcon"></i>
    </button>
</div>

<script>
// Dropdown
const avatarBtn = document.getElementById('avatarBtn');
const dropdownMenu = document.getElementById('dropdownMenu');
if (avatarBtn) {
    avatarBtn.addEventListener('click', (e) => { e.stopPropagation(); dropdownMenu.classList.toggle('show'); });
    document.addEventListener('click', () => dropdownMenu.classList.remove('show'));
}

// Hamburger
const hamburger = document.getElementById('hamburgerBtn');
const mainNav = document.getElementById('mainNav');
if (hamburger) {
    hamburger.addEventListener('click', () => mainNav.classList.toggle('mobile-open'));
}

// Chatbot
function toggleChatbot() {
    const box = document.getElementById('chatbotBox');
    const icon = document.getElementById('chatIcon');
    box.classList.toggle('open');
    icon.className = box.classList.contains('open') ? 'bi bi-x-lg' : 'bi bi-chat-dots';
}

const chatbotAnswers = {
    upcoming: 'Bạn có thể xem danh sách sự kiện sắp diễn ra tại trang <b>Sự kiện</b>. Chúng tôi thường xuyên cập nhật các Hội thảo, Seminar và hoạt động ngoại khóa.',
    register: 'Để đăng ký tham gia sự kiện:<br>1. Đăng nhập tài khoản<br>2. Vào trang chi tiết sự kiện<br>3. Nhấn nút <b>"Đăng ký tham gia"</b>',
    location: 'Các sự kiện thường được tổ chức tại Hội trường, Phòng chuyên đề và các cơ sở của Trường ĐH Nha Trang. Xem chi tiết trong từng sự kiện.',
    points: 'Mỗi sự kiện có điểm cộng riêng. Điểm được ghi nhận sau khi bạn tham gia và được xác nhận bởi ban tổ chức. Xem điểm tại <b>Lịch sử tham gia</b>.',
};

function chatbotAnswer(key) {
    const msgs = document.getElementById('chatMessages');
    const div = document.createElement('div');
    div.className = 'chat-msg bot';
    div.innerHTML = chatbotAnswers[key];
    msgs.appendChild(div);
    msgs.scrollTop = msgs.scrollHeight;
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
