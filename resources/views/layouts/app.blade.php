<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Quản Lý Sự Kiện - Khoa CNTT NTU')</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Montserrat:wght@600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --primary:    #2563EB;
            --primary-dark: #1d4ed8;
            --secondary:  #0F172A;
            --bg:         #F8FAFC;
            --card:       #FFFFFF;
            --danger:     #EF4444;
            --success:    #22C55E;
            --warning:    #F59E0B;
            --text:       #1E293B;
            --text-light: #64748B;
            --border:     #E2E8F0;
            --header-h:   70px;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ========================== HEADER ========================== */
        header.site-header {
            position: sticky;
            top: 0;
            z-index: 1000;
            height: var(--header-h);
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border);
            box-shadow: 0 1px 12px rgba(0,0,0,0.06);
        }

        .header-inner {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 24px;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }

        /* Logo */
        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            flex-shrink: 0;
        }

        .logo-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary), #60a5fa);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 18px;
        }

        .logo-text {
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
            font-size: 17px;
            color: var(--secondary);
            line-height: 1.2;
        }

        .logo-text span {
            display: block;
            font-size: 11px;
            font-weight: 500;
            color: var(--text-light);
            font-family: 'Inter', sans-serif;
        }

        /* Nav */
        nav.main-nav {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        nav.main-nav a {
            text-decoration: none;
            color: var(--text-light);
            font-size: 14px;
            font-weight: 500;
            padding: 7px 14px;
            border-radius: 8px;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        nav.main-nav a:hover,
        nav.main-nav a.active {
            color: var(--primary);
            background: #eff6ff;
        }

        /* Avatar Dropdown */
        .user-menu { position: relative; }

        .user-avatar-btn {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 6px 12px 6px 6px;
            border-radius: 40px;
            border: 1.5px solid var(--border);
            background: #fff;
            cursor: pointer;
            transition: all 0.2s;
        }

        .user-avatar-btn:hover { border-color: var(--primary); box-shadow: 0 0 0 3px #dbeafe; }

        .avatar-circle {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), #60a5fa);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 14px;
            font-weight: 600;
            overflow: hidden;
        }

        .avatar-circle img { width: 100%; height: 100%; object-fit: cover; }

        .user-name { font-size: 13px; font-weight: 600; color: var(--text); max-width: 120px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }

        .dropdown-menu {
            position: absolute;
            right: 0;
            top: calc(100% + 8px);
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 14px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.12);
            min-width: 200px;
            padding: 8px;
            display: none;
            z-index: 100;
        }

        .dropdown-menu.show { display: block; animation: fadeDown 0.2s ease; }

        @keyframes fadeDown {
            from { opacity: 0; transform: translateY(-8px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .dropdown-menu a,
        .dropdown-menu button {
            display: flex;
            align-items: center;
            gap: 10px;
            width: 100%;
            padding: 9px 12px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 14px;
            color: var(--text);
            border: none;
            background: none;
            cursor: pointer;
            text-align: left;
            transition: background 0.15s;
        }

        .dropdown-menu a:hover, .dropdown-menu button:hover { background: var(--bg); }
        .dropdown-menu .divider { height: 1px; background: var(--border); margin: 6px 0; }
        .dropdown-menu .danger { color: var(--danger); }

        /* Notification Badge */
        .notif-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: var(--danger);
            color: #fff;
            font-size: 10px;
            font-weight: 700;
            border-radius: 20px;
            min-width: 18px;
            height: 18px;
            padding: 0 4px;
        }

        /* Hamburger */
        .hamburger {
            display: none;
            flex-direction: column;
            gap: 5px;
            background: none;
            border: none;
            cursor: pointer;
            padding: 4px;
        }

        .hamburger span {
            display: block;
            width: 24px;
            height: 2.5px;
            background: var(--text);
            border-radius: 2px;
            transition: all 0.3s;
        }

        /* ========================== MAIN ========================== */
        main.site-main { flex: 1; }

        /* ========================== FOOTER ========================== */
        footer.site-footer {
            background: var(--secondary);
            color: #94a3b8;
            padding: 32px 24px;
            margin-top: auto;
        }

        .footer-inner {
            max-width: 1280px;
            margin: 0 auto;
            display: flex;
            flex-wrap: wrap;
            gap: 24px;
            justify-content: space-between;
            align-items: center;
        }

        .footer-brand .logo-text { color: #fff; }
        .footer-brand .logo-text span { color: #94a3b8; }

        .footer-info { font-size: 13px; line-height: 1.8; }
        .footer-info a { color: #60a5fa; text-decoration: none; }

        .footer-bottom {
            border-top: 1px solid #1e293b;
            margin-top: 20px;
            padding-top: 16px;
            text-align: center;
            font-size: 12px;
            max-width: 1280px;
            margin-left: auto;
            margin-right: auto;
        }

        /* ========================== ALERTS ========================== */
        .alert {
            padding: 12px 18px;
            border-radius: 10px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 16px;
        }

        .alert-success { background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; }
        .alert-error   { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }
        .alert-info    { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }

        /* ========================== CHATBOT ========================== */
        .chatbot-float {
            position: fixed;
            bottom: 28px;
            right: 28px;
            z-index: 9999;
        }

        .chatbot-toggle {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), #60a5fa);
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 20px rgba(37,99,235,0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 22px;
            transition: transform 0.2s;
        }

        .chatbot-toggle:hover { transform: scale(1.1); }

        .chatbot-box {
            position: absolute;
            bottom: 70px;
            right: 0;
            width: 320px;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.15);
            overflow: hidden;
            display: none;
        }

        .chatbot-box.open { display: flex; flex-direction: column; animation: fadeUp 0.25s ease; }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .chatbot-head {
            background: linear-gradient(135deg, var(--primary), #60a5fa);
            color: #fff;
            padding: 14px 18px;
            font-weight: 600;
            font-size: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .chatbot-head button { background: none; border: none; color: #fff; cursor: pointer; font-size: 18px; }

        .chatbot-messages {
            padding: 16px;
            max-height: 240px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .chat-msg {
            padding: 10px 14px;
            border-radius: 12px;
            font-size: 13px;
            max-width: 85%;
            line-height: 1.5;
        }

        .chat-msg.bot {
            background: #f1f5f9;
            align-self: flex-start;
            border-bottom-left-radius: 4px;
        }

        .chatbot-quick {
            padding: 10px 16px 14px;
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
        }

        .quick-btn {
            background: #eff6ff;
            color: var(--primary);
            border: 1px solid #bfdbfe;
            border-radius: 20px;
            padding: 5px 12px;
            font-size: 12px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .quick-btn:hover { background: var(--primary); color: #fff; }

        /* ========================== RESPONSIVE ========================== */
        @media (max-width: 768px) {
            .hamburger { display: flex; }

            nav.main-nav {
                position: absolute;
                top: var(--header-h);
                left: 0;
                right: 0;
                background: #fff;
                flex-direction: column;
                padding: 12px;
                border-bottom: 1px solid var(--border);
                box-shadow: 0 6px 20px rgba(0,0,0,0.08);
                display: none;
            }

            nav.main-nav.mobile-open { display: flex; }
            nav.main-nav a { width: 100%; justify-content: flex-start; }

            .logo-text span { display: none; }

            .chatbot-box { width: calc(100vw - 56px); right: 0; }
        }
    </style>

    {{-- Vite Scripts & Styles --}}
    @vite(['resources/css/app.css', 'resources/css/loading.css', 'resources/js/app.js'])

    @yield('styles')
</head>
<body>

{{-- ========================== GLOBAL LOADING OVERLAY ========================== --}}
<x-loading-overlay />

<!-- ========================== HEADER ========================== -->
<header class="site-header">
    <div class="header-inner">
        <!-- Logo -->
        <a href="{{ route('home') }}" class="logo">
            <div class="logo-icon"><i class="bi bi-calendar-event-fill"></i></div>
            <div class="logo-text">
                Quản Lý Sự Kiện
                <span>Khoa CNTT - ĐH Nha Trang</span>
            </div>
        </a>

        <!-- Hamburger -->
        <button class="hamburger" id="hamburgerBtn" aria-label="Menu">
            <span></span><span></span><span></span>
        </button>

        <!-- Nav -->
        <nav class="main-nav" id="mainNav">
            <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">
                <i class="bi bi-house-fill"></i> Trang chủ
            </a>
            <a href="{{ route('events.index') }}" class="{{ request()->routeIs('events.*') ? 'active' : '' }}">
                <i class="bi bi-calendar3"></i> Sự kiện
            </a>
            <a href="{{ route('bau-cu.index') }}" class="{{ request()->routeIs('bau-cu.*') || request()->routeIs('bo-phieu.*') ? 'active' : '' }}">
                <i class="bi bi-clipboard2-check-fill"></i> Bầu cử
            </a>
            @auth
            <a href="{{ route('history.index') }}" class="{{ request()->routeIs('history.*') ? 'active' : '' }}">
                <i class="bi bi-clock-history"></i> Lịch sử
            </a>
            <a href="{{ route('notifications.index') }}" class="{{ request()->routeIs('notifications.*') ? 'active' : '' }}">
                <i class="bi bi-bell-fill"></i> Thông báo
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
        <div class="user-menu">
            <button class="user-avatar-btn" id="avatarBtn">
                <div class="avatar-circle">
                    @if(auth()->user()->duong_dan_anh)
                        <img src="{{ asset('storage/'.auth()->user()->duong_dan_anh) }}" alt="avatar">
                    @else
                        {{ mb_substr(auth()->user()->ho_ten, 0, 1) }}
                    @endif
                </div>
                <span class="user-name">{{ auth()->user()->ho_ten }}</span>
                <i class="bi bi-chevron-down" style="font-size:11px;color:var(--text-light)"></i>
            </button>

            <div class="dropdown-menu" id="dropdownMenu">
                <a href="{{ route('profile.index') }}"><i class="bi bi-person-circle"></i> Hồ sơ cá nhân</a>
                @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}"><i class="bi bi-shield-fill-check"></i> Quản trị viên</a>
                @endif
                <div class="divider"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="danger"><i class="bi bi-box-arrow-right"></i> Đăng xuất</button>
                </form>
            </div>
        </div>
        @else
        <a href="{{ route('login') }}" style="background:var(--primary);color:#fff;padding:8px 18px;border-radius:8px;text-decoration:none;font-size:14px;font-weight:600;display:flex;align-items:center;gap:6px;transition:background 0.2s;">
            <i class="bi bi-box-arrow-in-right"></i> Đăng nhập
        </a>
        @endauth
    </div>
</header>

<!-- ========================== MAIN ========================== -->
<main class="site-main">
    <div style="max-width:1280px;margin:0 auto;padding:24px;">
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
</main>

<!-- ========================== FOOTER ========================== -->
<footer class="site-footer">
    <div class="footer-inner">
        <div class="footer-brand">
            <div class="logo-text">
                Quản Lý Sự Kiện
                <span>Khoa CNTT - ĐH Nha Trang</span>
            </div>
        </div>
        <div class="footer-info">
            <strong style="color:#e2e8f0">Khoa Công Nghệ Thông Tin</strong><br>
            Trường Đại học Nha Trang<br>
            <a href="mailto:cntt@ntu.edu.vn">cntt@ntu.edu.vn</a>
        </div>
    </div>
    <div class="footer-bottom" style="max-width:1280px;margin:20px auto 0;padding-top:16px;border-top:1px solid #1e293b;text-align:center;font-size:12px;">
        &copy; {{ date('Y') }} Khoa CNTT - Đại học Nha Trang. All rights reserved.
    </div>
</footer>

<!-- ========================== CHATBOT ========================== -->
<div class="chatbot-float">
    <div class="chatbot-box" id="chatbotBox">
        <div class="chatbot-head">
            <span><i class="bi bi-robot"></i> Trợ lý sự kiện</span>
            <button onclick="toggleChatbot()"><i class="bi bi-x-lg"></i></button>
        </div>
        <div class="chatbot-messages" id="chatMessages">
            <div class="chat-msg bot">👋 Xin chào! Tôi là trợ lý sự kiện Khoa CNTT. Tôi có thể giúp bạn tìm hiểu về các sự kiện sắp diễn ra!</div>
        </div>
        <div class="chatbot-quick">
            <button class="quick-btn" onclick="chatbotAnswer('upcoming')">📅 Sự kiện sắp tới</button>
            <button class="quick-btn" onclick="chatbotAnswer('register')">✍️ Cách đăng ký</button>
            <button class="quick-btn" onclick="chatbotAnswer('location')">📍 Địa điểm</button>
            <button class="quick-btn" onclick="chatbotAnswer('points')">⭐ Điểm cộng</button>
        </div>
    </div>
    <button class="chatbot-toggle" onclick="toggleChatbot()" title="Trợ lý sự kiện">
        <i class="bi bi-chat-dots-fill" id="chatIcon"></i>
    </button>
</div>

<script>
// Dropdown avatar
const avatarBtn     = document.getElementById('avatarBtn');
const dropdownMenu  = document.getElementById('dropdownMenu');
if (avatarBtn) {
    avatarBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        dropdownMenu.classList.toggle('show');
    });
    document.addEventListener('click', () => dropdownMenu.classList.remove('show'));
}

// Hamburger mobile menu
const hamburger = document.getElementById('hamburgerBtn');
const mainNav   = document.getElementById('mainNav');
if (hamburger) {
    hamburger.addEventListener('click', () => {
        mainNav.classList.toggle('mobile-open');
    });
}

// Chatbot
function toggleChatbot() {
    const box  = document.getElementById('chatbotBox');
    const icon = document.getElementById('chatIcon');
    box.classList.toggle('open');
    icon.className = box.classList.contains('open') ? 'bi bi-x-lg' : 'bi bi-chat-dots-fill';
}

const chatbotAnswers = {
    upcoming: '📅 Bạn có thể xem danh sách sự kiện sắp diễn ra tại trang <b>Sự kiện</b>. Chúng tôi thường xuyên cập nhật các Hội thảo, Seminar và hoạt động ngoại khóa!',
    register: '✍️ Để đăng ký tham gia sự kiện:<br>1. Đăng nhập tài khoản<br>2. Vào trang chi tiết sự kiện<br>3. Nhấn nút <b>"Đăng ký tham gia"</b>',
    location: '📍 Các sự kiện thường được tổ chức tại Hội trường, Phòng chuyên đề và các cơ sở của Trường ĐH Nha Trang. Xem chi tiết trong từng sự kiện!',
    points:   '⭐ Mỗi sự kiện có điểm cộng riêng. Điểm được ghi nhận sau khi bạn tham gia và được xác nhận bởi ban tổ chức. Xem điểm tại <b>Lịch sử tham gia</b>!',
};

function chatbotAnswer(key) {
    const msgs = document.getElementById('chatMessages');
    const div  = document.createElement('div');
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
