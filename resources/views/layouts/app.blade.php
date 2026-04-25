<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Hệ thống Quản Lý Sự Kiện — Khoa Công Nghệ Thông Tin, Đại học Nha Trang">
    <title>@yield('title', 'Quản Lý Sự Kiện — Khoa CNTT, ĐH Nha Trang')</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @yield('styles')
</head>
<body style="display:flex;flex-direction:column;min-height:100vh;">

<x-loading-overlay />

<header class="site-header">
    <div class="header-inner">
        <a href="{{ route('home') }}" class="logo">
            <div class="logo-seal"><i class="bi bi-mortarboard-fill"></i></div>
            <div>
                <div class="logo-text">Quản Lý Sự Kiện</div>
                <span class="logo-sub">Khoa CNTT — ĐH Nha Trang</span>
            </div>
        </a>

        <button class="hamburger" id="hamburgerBtn" aria-label="Menu">
            <span></span><span></span><span></span>
        </button>

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
            <a href="{{ route('events.scanner') }}" class="{{ request()->routeIs('events.scanner') ? 'active' : '' }}">
                <i class="bi bi-qr-code-scan"></i> Quét QR
            </a>
            <a href="{{ route('notifications.index') }}" class="{{ request()->routeIs('notifications.*') ? 'active' : '' }}">
                <i class="bi bi-bell"></i> Thông báo
                @php
                    $unreadCount = \App\Models\ThongBao::where('ma_sinh_vien', auth()->id())
                        ->where('da_doc', false)
                        ->count();
                @endphp
                @if($unreadCount > 0)
                    <span class="notif-badge">{{ $unreadCount }}</span>
                @endif
            </a>
            @endauth
        </nav>

        @auth
        <div class="user-menu dropdown">
            <button class="user-avatar-btn" id="avatarBtn" type="button">
                <div class="avatar">
                    @if(auth()->user()->duong_dan_anh)
                        <img src="{{ asset('storage/' . auth()->user()->duong_dan_anh) }}" alt="avatar">
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

@if(auth()->check())
<div class="chatbot-float">
    <div class="chatbot-box" id="chatbotBox">
        <div class="chatbot-head">
            <span><i class="bi bi-chat-text"></i> Trợ lý sự kiện</span>
            <button type="button" onclick="toggleChatbot()"><i class="bi bi-x-lg"></i></button>
        </div>
        <div class="chatbot-messages" id="chatMessages">
            <div class="chat-msg bot">Xin chào! Tôi ưu tiên trả lời bằng dữ liệu hệ thống về sự kiện, đăng ký, địa điểm, thời gian và điểm.</div>
        </div>
        <div class="chatbot-quick">
            <button class="quick-btn" type="button" onclick="sendQuickChat('Có sự kiện nào sắp diễn ra không?')">Sự kiện sắp tới</button>
            <button class="quick-btn" type="button" onclick="sendQuickChat('Cách đăng ký tham gia sự kiện là gì?')">Cách đăng ký</button>
            <button class="quick-btn" type="button" onclick="sendQuickChat('Sự kiện đang diễn ra ở đâu?')">Địa điểm</button>
            <button class="quick-btn" type="button" onclick="sendQuickChat('Điểm cộng của các sự kiện được tính thế nào?')">Điểm cộng</button>
        </div>
        <div class="chatbot-input-wrap">
            <form id="chatbotForm" class="chatbot-form" data-skip-loading="true">
                <input type="text" id="chatInput" class="chatbot-input" placeholder="Nhập câu hỏi về sự kiện..." maxlength="500" autocomplete="off">
                <button type="submit" class="chatbot-send" id="chatSendBtn" aria-label="Gửi">
                    <i class="bi bi-send"></i>
                </button>
            </form>
            <div class="chatbot-status" id="chatbotStatus"></div>
        </div>
    </div>
    <button class="chatbot-toggle" type="button" onclick="toggleChatbot()" title="Trợ lý sự kiện">
        <i class="bi bi-chat-dots" id="chatIcon"></i>
    </button>
</div>
@endif

<script>
const avatarBtn = document.getElementById('avatarBtn');
const dropdownMenu = document.getElementById('dropdownMenu');
if (avatarBtn && dropdownMenu) {
    avatarBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        dropdownMenu.classList.toggle('show');
    });
    document.addEventListener('click', () => dropdownMenu.classList.remove('show'));
}

const hamburger = document.getElementById('hamburgerBtn');
const mainNav = document.getElementById('mainNav');
if (hamburger && mainNav) {
    hamburger.addEventListener('click', () => mainNav.classList.toggle('mobile-open'));
}

function toggleChatbot() {
    const box = document.getElementById('chatbotBox');
    const icon = document.getElementById('chatIcon');

    if (!box || !icon) {
        return;
    }

    box.classList.toggle('open');
    icon.className = box.classList.contains('open') ? 'bi bi-x-lg' : 'bi bi-chat-dots';
}

const chatbotForm = document.getElementById('chatbotForm');
const chatInput = document.getElementById('chatInput');
const chatMessages = document.getElementById('chatMessages');
const chatbotStatus = document.getElementById('chatbotStatus');
const chatSendBtn = document.getElementById('chatSendBtn');

if (chatbotForm) {
    chatbotForm.addEventListener('submit', async (event) => {
        event.preventDefault();
        await sendChatMessage(chatInput.value);
    });
}

async function sendQuickChat(message) {
    const box = document.getElementById('chatbotBox');

    if (box && !box.classList.contains('open')) {
        toggleChatbot();
    }

    await sendChatMessage(message);
}

async function sendChatMessage(message) {
    const text = (message || '').trim();

    if (!text || !chatMessages || !chatSendBtn) {
        return;
    }

    appendChatMessage('user', escapeHtml(text));

    if (chatInput) {
        chatInput.value = '';
    }

    setChatbotLoading(true, 'Đang tra cứu dữ liệu...');

    try {
        const response = await axios.post('{{ route('chatbot.ask') }}', {
            message: text,
        }, {
            skipLoading: true,
        });

        appendChatMessage('bot', formatBotReply(response.data.reply));
    } catch (error) {
        const message = error?.response?.data?.message || 'Không thể lấy phản hồi từ chatbot lúc này.';
        appendChatMessage('bot', escapeHtml(message));
    } finally {
        setChatbotLoading(false, '');
    }
}

function appendChatMessage(role, html) {
    if (!chatMessages) {
        return;
    }

    const div = document.createElement('div');
    div.className = `chat-msg ${role}`;
    div.innerHTML = html;
    chatMessages.appendChild(div);
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

function setChatbotLoading(isLoading, message) {
    if (chatSendBtn) {
        chatSendBtn.disabled = isLoading;
    }

    if (chatInput) {
        chatInput.disabled = isLoading;
    }

    if (chatbotStatus) {
        chatbotStatus.textContent = message || '';
    }
}

function formatBotReply(text) {
    return escapeHtml(text || '').replace(/\n/g, '<br>');
}

function escapeHtml(text) {
    return (text || '')
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#039;');
}

setTimeout(() => {
    document.querySelectorAll('.alert').forEach((a) => {
        a.style.transition = 'opacity 0.5s';
        a.style.opacity = '0';
        setTimeout(() => a.remove(), 500);
    });
}, 4000);
</script>

@yield('scripts')
</body>
</html>
