<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Tài khoản') — Quản Lý Sự Kiện NTU</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* =====================================================
           AUTH DESIGN SYSTEM — QL SỰ KIỆN NTU
           Clean · Responsive · Mobile-first
        ===================================================== */
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --c-brand: #2563eb;
            --c-brand-dark: #1d4ed8;
            --c-brand-light: #dbeafe;
            --c-accent: #06b6d4;
            --c-success: #16a34a;
            --c-warning: #d97706;
            --c-danger: #dc2626;
            --c-text: #0f172a;
            --c-text-2: #475569;
            --c-text-3: #94a3b8;
            --c-border: #e2e8f0;
            --c-bg: #f8fafc;
            --c-surface: #ffffff;
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 20px;
            --radius-xl: 28px;
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, .06), 0 1px 2px rgba(0, 0, 0, .04);
            --shadow-md: 0 4px 16px rgba(0, 0, 0, .08), 0 2px 6px rgba(0, 0, 0, .04);
            --shadow-lg: 0 20px 60px rgba(0, 0, 0, .10), 0 8px 24px rgba(0, 0, 0, .06);
            --transition: 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            font-size: 15px;
            line-height: 1.6;
            color: var(--c-text);
            background: var(--c-bg);
            min-height: 100vh;
        }

        /* ── Page wrapper ── */
        .auth-page {
            min-height: 100vh;
            display: flex;
            align-items: stretch;
            position: relative;
            overflow: hidden;
        }

        /* ── Left decorative panel ── */
        .auth-panel {
            flex: 0 0 42%;
            display: none;
            /* hidden on mobile */
            flex-direction: column;
            justify-content: space-between;
            padding: 48px 52px;
            background: linear-gradient(150deg, #1e3a8a 0%, #1d4ed8 45%, #0891b2 100%);
            color: #fff;
            position: relative;
            overflow: hidden;
        }

        .auth-panel::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(circle at 15% 85%, rgba(6, 182, 212, .28) 0%, transparent 55%),
                radial-gradient(circle at 85% 15%, rgba(255, 255, 255, .10) 0%, transparent 50%);
            pointer-events: none;
        }

        .auth-panel-blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(60px);
            pointer-events: none;
        }

        .auth-panel-blob.b1 {
            width: 320px;
            height: 320px;
            top: -80px;
            right: -80px;
            background: rgba(255, 255, 255, .07);
        }

        .auth-panel-blob.b2 {
            width: 280px;
            height: 280px;
            bottom: -60px;
            left: -60px;
            background: rgba(6, 182, 212, .18);
        }

        .auth-panel-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: #fff;
            font-weight: 700;
            font-size: .95rem;
            letter-spacing: .02em;
            position: relative;
            z-index: 1;
        }

        .auth-panel-logo-icon {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            background: rgba(255, 255, 255, .15);
            border: 1px solid rgba(255, 255, 255, .25);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
        }

        .auth-panel-logo small {
            display: block;
            font-size: .72rem;
            font-weight: 400;
            opacity: .8;
            margin-top: 1px;
        }

        .auth-panel-body {
            position: relative;
            z-index: 1;
        }

        .auth-panel-badge {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 6px 14px;
            background: rgba(255, 255, 255, .15);
            border: 1px solid rgba(255, 255, 255, .25);
            border-radius: 999px;
            font-size: .78rem;
            font-weight: 600;
            letter-spacing: .05em;
            text-transform: uppercase;
            margin-bottom: 24px;
        }

        .auth-panel-heading {
            font-size: clamp(1.9rem, 3.2vw, 2.8rem);
            font-weight: 800;
            line-height: 1.1;
            letter-spacing: -.02em;
            margin-bottom: 16px;
        }

        .auth-panel-desc {
            font-size: .92rem;
            line-height: 1.7;
            opacity: .85;
            max-width: 38ch;
        }

        .auth-panel-card {
            position: relative;
            z-index: 1;
            padding: 20px 22px;
            border-radius: var(--radius-md);
            background: rgba(255, 255, 255, .1);
            border: 1px solid rgba(255, 255, 255, .18);
            backdrop-filter: blur(8px);
        }

        .auth-panel-stats {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 14px;
        }

        .auth-panel-stat {
            padding: 14px 16px;
            border-radius: var(--radius-sm);
            background: rgba(255, 255, 255, .08);
            border: 1px solid rgba(255, 255, 255, .12);
        }

        .auth-panel-stat strong {
            display: block;
            font-size: 1.35rem;
            font-weight: 700;
            margin-bottom: 3px;
        }

        .auth-panel-stat span {
            font-size: .78rem;
            opacity: .8;
            line-height: 1.4;
        }

        .auth-panel-checks {
            display: grid;
            gap: 10px;
        }

        .auth-panel-check {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            font-size: .88rem;
            line-height: 1.5;
            opacity: .9;
        }

        .auth-panel-check i {
            margin-top: 2px;
            color: #6ee7f7;
            flex-shrink: 0;
        }

        .auth-panel-badges {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 12px;
        }

        .auth-panel-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 12px;
            border-radius: 999px;
            background: rgba(255, 255, 255, .13);
            border: 1px solid rgba(255, 255, 255, .2);
            font-size: .78rem;
            font-weight: 500;
        }

        /* ── Right form panel ── */
        .auth-form-side {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: clamp(24px, 5vw, 48px) clamp(20px, 6vw, 64px);
            background: var(--c-surface);
            min-height: 100vh;
            position: relative;
        }

        /* Mobile top logo (shown only on small screens) */
        .auth-mobile-header {
            display: none;
            align-items: center;
            gap: 10px;
            margin-bottom: 32px;
            align-self: flex-start;
            text-decoration: none;
            color: var(--c-text);
        }

        .auth-mobile-header-icon {
            width: 36px;
            height: 36px;
            border-radius: 9px;
            background: var(--c-brand-light);
            color: var(--c-brand);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
        }

        .auth-mobile-header span {
            font-weight: 700;
            font-size: .9rem;
        }

        .auth-mobile-header small {
            display: block;
            font-size: .72rem;
            color: var(--c-text-2);
            font-weight: 400;
        }

        .auth-form-wrap {
            width: 100%;
            max-width: 440px;
        }

        /* Tab switcher */
        .auth-tabs {
            display: flex;
            gap: 2px;
            padding: 4px;
            background: #f1f5f9;
            border-radius: var(--radius-sm);
            border: 1px solid var(--c-border);
            margin-bottom: 28px;
        }

        .auth-tab {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            padding: 9px 16px;
            border-radius: 6px;
            font-size: .85rem;
            font-weight: 600;
            color: var(--c-text-2);
            text-decoration: none;
            transition: all var(--transition);
            border: 1px solid transparent;
            white-space: nowrap;
        }

        .auth-tab.active,
        .auth-tab:hover {
            background: var(--c-surface);
            color: var(--c-brand);
            border-color: var(--c-border);
            box-shadow: var(--shadow-sm);
        }

        .auth-tab.active {
            color: var(--c-brand);
            font-weight: 700;
        }

        /* Aux back link */
        .auth-back-link {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            font-size: .85rem;
            font-weight: 600;
            color: var(--c-text-2);
            text-decoration: none;
            padding: 7px 14px;
            border-radius: var(--radius-sm);
            border: 1px solid var(--c-border);
            transition: all var(--transition);
            margin-bottom: 28px;
            align-self: flex-start;
        }

        .auth-back-link:hover {
            color: var(--c-brand);
            border-color: var(--c-brand);
            background: var(--c-brand-light);
        }

        /* Card heading */
        .auth-heading {
            font-size: clamp(1.4rem, 2.5vw, 1.75rem);
            font-weight: 800;
            color: var(--c-text);
            letter-spacing: -.02em;
            line-height: 1.2;
        }

        .auth-subheading {
            margin-top: 8px;
            font-size: .88rem;
            color: var(--c-text-2);
            line-height: 1.65;
        }

        /* Alerts */
        .auth-alerts {
            margin: 18px 0 2px;
            display: grid;
            gap: 10px;
        }

        .auth-alert {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding: 12px 14px;
            border-radius: var(--radius-sm);
            font-size: .85rem;
            line-height: 1.5;
            border: 1px solid transparent;
        }

        .auth-alert i {
            flex-shrink: 0;
            margin-top: 1px;
            font-size: 1rem;
        }

        .auth-alert.is-error {
            background: #fef2f2;
            border-color: #fecaca;
            color: #991b1b;
        }

        .auth-alert.is-error i {
            color: #dc2626;
        }

        .auth-alert.is-success {
            background: #f0fdf4;
            border-color: #bbf7d0;
            color: #166534;
        }

        .auth-alert.is-success i {
            color: #16a34a;
        }

        .auth-alert.is-info {
            background: #eff6ff;
            border-color: #bfdbfe;
            color: #1e40af;
        }

        .auth-alert.is-info i {
            color: #2563eb;
        }

        .auth-alert.is-warning {
            background: #fffbeb;
            border-color: #fde68a;
            color: #92400e;
        }

        .auth-alert.is-warning i {
            color: #d97706;
        }

        /* Form layout */
        .auth-form {
            margin-top: 24px;
            display: grid;
            gap: 18px;
        }

        .auth-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
        }

        .auth-field {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .auth-label {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: .78rem;
            font-weight: 700;
            color: var(--c-text);
            letter-spacing: .03em;
            text-transform: uppercase;
        }

        .auth-label i {
            font-size: .85rem;
            color: var(--c-text-3);
        }

        .auth-label-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .auth-label-row .auth-label {
            text-transform: uppercase;
        }

        .auth-link-sm {
            font-size: .78rem;
            font-weight: 600;
            color: var(--c-brand);
            text-decoration: none;
            transition: opacity var(--transition);
        }

        .auth-link-sm:hover {
            opacity: .75;
            text-decoration: underline;
        }

        .auth-input-wrap {
            position: relative;
            display: flex;
            align-items: center;
        }

        .auth-input-icon {
            position: absolute;
            left: 13px;
            font-size: 1rem;
            color: var(--c-text-3);
            pointer-events: none;
            z-index: 1;
            transition: color var(--transition);
        }

        .auth-input-toggle {
            position: absolute;
            right: 12px;
            background: none;
            border: none;
            cursor: pointer;
            color: var(--c-text-3);
            font-size: 1rem;
            padding: 4px;
            transition: color var(--transition);
            line-height: 1;
        }

        .auth-input-toggle:hover {
            color: var(--c-brand);
        }

        .auth-input-field {
            width: 100%;
            height: 48px;
            padding: 0 42px;
            border: 1.5px solid var(--c-border);
            border-radius: var(--radius-sm);
            background: #fcfcfd;
            font-size: .92rem;
            font-family: inherit;
            color: var(--c-text);
            transition: border-color var(--transition), box-shadow var(--transition), background var(--transition);
            -webkit-appearance: none;
        }

        .auth-input-field::placeholder {
            color: var(--c-text-3);
        }

        .auth-input-field:focus {
            outline: none;
            border-color: var(--c-brand);
            background: #fff;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, .12);
        }

        .auth-input-field:focus~.auth-input-icon,
        .auth-input-wrap:focus-within .auth-input-icon {
            color: var(--c-brand);
        }

        .auth-input-field.is-invalid {
            border-color: var(--c-danger);
            box-shadow: 0 0 0 3px rgba(220, 38, 38, .10);
        }

        .auth-helper {
            font-size: .76rem;
            color: var(--c-text-3);
            line-height: 1.5;
        }

        .auth-helper.is-error {
            color: var(--c-danger);
        }

        /* Info/tip box */
        .auth-tip {
            padding: 12px 16px;
            border-radius: var(--radius-sm);
            background: #f0f9ff;
            border: 1px solid #bae6fd;
            font-size: .83rem;
            color: #0c4a6e;
            line-height: 1.6;
        }

        .auth-tip strong {
            color: #075985;
        }

        /* Divider */
        .auth-divider {
            display: flex;
            align-items: center;
            gap: 12px;
            color: var(--c-text-3);
            font-size: .78rem;
            font-weight: 500;
        }

        .auth-divider::before,
        .auth-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--c-border);
        }

        /* Buttons */
        .auth-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            height: 50px;
            border-radius: var(--radius-sm);
            font-family: inherit;
            font-size: .92rem;
            font-weight: 700;
            cursor: pointer;
            transition: all var(--transition);
            text-decoration: none;
            border: none;
            letter-spacing: .01em;
        }

        .auth-btn-primary {
            background: linear-gradient(135deg, var(--c-brand), var(--c-brand-dark));
            color: #fff;
            box-shadow: 0 4px 14px rgba(37, 99, 235, .3);
        }

        .auth-btn-primary:hover {
            box-shadow: 0 6px 20px rgba(37, 99, 235, .4);
            transform: translateY(-1px);
        }

        .auth-btn-primary:active {
            transform: translateY(0);
        }

        .auth-btn-primary:disabled {
            opacity: .6;
            cursor: not-allowed;
            transform: none;
        }

        .auth-btn-ghost {
            background: transparent;
            color: var(--c-text-2);
            border: 1.5px solid var(--c-border);
        }

        .auth-btn-ghost:hover {
            background: var(--c-bg);
            border-color: #94a3b8;
            color: var(--c-text);
        }

        .auth-btn-outline-brand {
            background: var(--c-brand-light);
            color: var(--c-brand);
            border: 1.5px solid #bfdbfe;
        }

        .auth-btn-outline-brand:hover {
            background: #bfdbfe;
        }

        /* Footnote */
        .auth-footnote {
            text-align: center;
            font-size: .82rem;
            color: var(--c-text-3);
            line-height: 1.6;
            margin-top: 4px;
        }

        .auth-footnote a {
            color: var(--c-brand);
            font-weight: 600;
            text-decoration: none;
        }

        .auth-footnote a:hover {
            text-decoration: underline;
        }

        /* Page footer */
        .auth-page-footer {
            margin-top: 32px;
            padding-top: 20px;
            border-top: 1px solid var(--c-border);
            font-size: .78rem;
            color: var(--c-text-3);
            text-align: center;
            line-height: 1.6;
        }

        /* ── Page transitions ── */
        .auth-page.is-entering .auth-form-side {
            opacity: 0;
            transform: translateX(30px);
        }

        .auth-page.is-entering.is-ready .auth-form-side {
            opacity: 1;
            transform: translateX(0);
            transition: opacity .28s ease, transform .28s cubic-bezier(.25, .46, .45, .94);
        }

        .auth-page.is-leaving .auth-form-side {
            opacity: 0;
            transform: translateX(-30px);
            transition: opacity .22s ease, transform .22s cubic-bezier(.55, .06, .68, .19);
        }

        /* ── Responsive: show panel on md+ ── */
        @media (min-width: 768px) {
            .auth-panel {
                display: flex;
            }

            .auth-mobile-header {
                display: none !important;
            }

            .auth-form-side {
                min-height: unset;
            }
        }

        @media (max-width: 767px) {
            .auth-mobile-header {
                display: flex;
            }

            .auth-row {
                grid-template-columns: 1fr;
            }

            .auth-form-side {
                padding: 28px 20px 40px;
                justify-content: flex-start;
            }

            .auth-form-wrap {
                max-width: 100%;
            }
        }

        @media (max-width: 400px) {
            .auth-tabs {
                flex-wrap: nowrap;
                gap: 2px;
            }
        }

        /* ── Reduced motion ── */
        @media (prefers-reduced-motion: reduce) {

            *,
            *::before,
            *::after {
                transition: none !important;
                animation: none !important;
            }
        }
    </style>
</head>

<body>
    <x-loading-overlay />
    <div class="auth-page @yield('auth_page_class', '')" id="auth-page">

        {{-- ─────────────── LEFT DECORATIVE PANEL ─────────────── --}}
        <aside class="auth-panel">
            <div class="auth-panel-blob b1"></div>
            <div class="auth-panel-blob b2"></div>

            {{-- Logo --}}
            <a href="{{ route('login') }}" class="auth-panel-logo" data-nav>
                <span class="auth-panel-logo-icon"><i class="bi bi-calendar2-check-fill"></i></span>
                <span>
                    Quản Lý Sự Kiện NTU
                    <small>Khoa Công Nghệ Thông Tin</small>
                </span>
            </a>

            {{-- Body --}}
            <div class="auth-panel-body">
                <div class="auth-panel-badge">
                    <i class="bi bi-stars"></i>
                    @yield('panel_badge', 'Không gian sinh viên')
                </div>
                <h1 class="auth-panel-heading">@yield('panel_heading', 'Quản lý Sự kiện & Điểm danh')</h1>
                <p class="auth-panel-desc">
                    @yield('panel_desc', 'Đăng nhập bằng tài khoản sinh viên NTU để theo dõi lịch sự kiện, đăng ký tham gia và điểm danh QR.')
                </p>
            </div>

            {{-- Bottom card --}}
            <div class="auth-panel-card">
                @yield('panel_card')
            </div>
        </aside>

        {{-- ─────────────── RIGHT FORM PANEL ─────────────── --}}
        <main class="auth-form-side">

            {{-- Mobile logo (only visible on small screens) --}}
            <a href="{{ route('login') }}" class="auth-mobile-header" data-nav>
                <span class="auth-mobile-header-icon"><i class="bi bi-calendar2-check-fill"></i></span>
                <span>
                    Quản Lý Sự Kiện NTU
                    <small>Khoa Công Nghệ Thông Tin</small>
                </span>
            </a>

            <div class="auth-form-wrap">

                {{-- Back link (optional, used by forgot/reset pages) --}}
                @hasSection('back_link')
                    @yield('back_link')
                @endif

                {{-- Tab switcher (login / register) --}}
                @hasSection('tabs')
                    @yield('tabs')
                @endif

                {{-- Heading --}}
                <div class="mb-2">
                    <h2 class="auth-heading">@yield('form_title')</h2>
                    <p class="auth-subheading">@yield('form_subtitle')</p>
                </div>

                {{-- Alerts --}}
                <div class="auth-alerts">@include('auth.partials.alerts')</div>

                {{-- Form content --}}
                @yield('content')

                {{-- Footer --}}
                <div class="auth-page-footer">
                    Hệ thống quản lý sự kiện &amp; điểm danh — Khoa CNTT, NTU
                </div>

            </div>
        </main>

    </div>

    <script>
        (function () {
            var page = document.getElementById('auth-page');
            var saved = sessionStorage.getItem('auth-dir');
            if (saved) {
                page.classList.add('is-entering');
                requestAnimationFrame(function () {
                    requestAnimationFrame(function () {
                        page.classList.add('is-ready');
                    });
                });
                sessionStorage.removeItem('auth-dir');
            }

            document.addEventListener('click', function (e) {
                var link = e.target.closest('[data-nav]');
                if (!link || !link.href) return;
                if (e.metaKey || e.ctrlKey || e.shiftKey || e.altKey || link.target === '_blank') return;
                e.preventDefault();
                page.classList.add('is-leaving');
                var dest = link.href;
                setTimeout(function () { window.location.href = dest; }, 220);
            });

            // Password visibility toggle
            document.addEventListener('click', function (e) {
                var btn = e.target.closest('[data-toggle-pw]');
                if (!btn) return;
                var targetId = btn.dataset.togglePw;
                var input = document.getElementById(targetId);
                if (!input) return;
                var isText = input.type === 'text';
                input.type = isText ? 'password' : 'text';
                btn.querySelector('i').className = isText ? 'bi bi-eye' : 'bi bi-eye-slash';
            });
        })();
    </script>
</body>

</html>