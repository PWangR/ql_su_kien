<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Tài khoản') - Quản Lý Sự Kiện NTU</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body.auth-screen{--auth-primary:#ff6f91;--auth-secondary:#f23d6d;--auth-soft:#fff5f8;margin:0;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:clamp(18px,3vw,32px);background:radial-gradient(circle at top left,rgba(255,255,255,.24),transparent 32%),linear-gradient(135deg,#10141d 0%,#171d2a 42%,#0f172a 100%);position:relative;overflow-x:hidden}
        body.auth-register{--auth-primary:#0e8bb4;--auth-secondary:#072f3d;--auth-soft:#effcff}
        body.auth-forgot{--auth-primary:#dc7c20;--auth-secondary:#8f4c18;--auth-soft:#fff7ed}
        body.auth-reset{--auth-primary:#2563eb;--auth-secondary:#173f97;--auth-soft:#eff6ff}
        .auth-ambient{position:absolute;border-radius:999px;filter:blur(8px);pointer-events:none}
        .auth-ambient.one{width:340px;height:340px;top:-90px;left:-40px;opacity:.68;background:linear-gradient(135deg,var(--auth-primary),transparent 72%)}
        .auth-ambient.two{width:420px;height:420px;right:-110px;bottom:-120px;opacity:.42;background:linear-gradient(135deg,rgba(255,255,255,.08),var(--auth-secondary))}
        .auth-shell{position:relative;z-index:1;width:min(1120px,100%);min-height:min(720px,calc(100vh - 36px));display:grid;grid-template-columns:minmax(320px,1.05fr) minmax(360px,.95fr);background:rgba(255,255,255,.82);border:1px solid rgba(255,255,255,.2);border-radius:32px;overflow:hidden;box-shadow:0 30px 90px rgba(7,10,18,.38);backdrop-filter:blur(20px);transition:opacity .3s ease,transform .3s ease,filter .3s ease}
        .auth-visual{position:relative;overflow:hidden;padding:clamp(28px,5vw,56px);display:flex;flex-direction:column;justify-content:space-between;gap:28px;color:#fff;background:linear-gradient(135deg,rgba(255,255,255,.12),transparent 44%),linear-gradient(160deg,var(--auth-primary) 0%,var(--auth-secondary) 72%);isolation:isolate}
        .auth-visual::before,.auth-visual::after{content:'';position:absolute;border-radius:32px;background:rgba(255,255,255,.11);transform:rotate(-18deg);z-index:-1}
        .auth-visual::before{width:320px;height:320px;top:-100px;right:-130px}
        .auth-visual::after{width:220px;height:220px;bottom:-80px;left:-80px;background:rgba(255,255,255,.08)}
        .auth-logo{display:inline-flex;align-items:center;gap:12px;color:#fff;text-decoration:none;font-weight:700;letter-spacing:.02em}
        .auth-logo-mark{width:50px;height:50px;border-radius:16px;display:inline-flex;align-items:center;justify-content:center;background:rgba(255,255,255,.14);border:1px solid rgba(255,255,255,.22);box-shadow:inset 0 1px 0 rgba(255,255,255,.16);font-size:1.2rem}
        .auth-logo small{display:block;opacity:.78;font-size:.74rem;font-weight:500;text-transform:uppercase;letter-spacing:.08em}
        .auth-kicker{display:inline-flex;align-items:center;gap:8px;width:fit-content;margin:28px 0 16px;padding:8px 14px;border-radius:999px;background:rgba(255,255,255,.14);border:1px solid rgba(255,255,255,.22);font-size:.82rem;letter-spacing:.04em}
        .auth-headline{margin:0;color:#fff;font-size:clamp(2.15rem,4.8vw,3.8rem);line-height:1.02;max-width:12ch}
        .auth-description{margin:18px 0 0;max-width:46ch;color:rgba(255,255,255,.88);font-size:1rem;line-height:1.72}
        .auth-visual-card{display:grid;gap:14px;width:min(100%,420px);padding:22px;border-radius:24px;background:rgba(9,18,34,.18);border:1px solid rgba(255,255,255,.14);backdrop-filter:blur(12px);box-shadow:0 18px 45px rgba(0,0,0,.12)}
        .auth-meta{display:flex;flex-wrap:wrap;gap:10px}
        .auth-meta-badge{display:inline-flex;align-items:center;gap:8px;padding:9px 12px;border-radius:999px;background:rgba(255,255,255,.1);color:rgba(255,255,255,.92);font-size:.82rem}
        .auth-stat-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:12px}
        .auth-stat{padding:16px;border-radius:18px;background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.14)}
        .auth-stat strong{display:block;margin-bottom:4px;font-size:1.38rem}
        .auth-stat span,.auth-quote,.auth-check{color:rgba(255,255,255,.92)}
        .auth-stat span{font-size:.84rem;line-height:1.55}
        .auth-quote{font-size:.95rem;line-height:1.7}
        .auth-quote strong{display:block;margin-top:10px;font-size:.84rem;text-transform:uppercase;letter-spacing:.06em}
        .auth-checks{display:grid;gap:10px}
        .auth-check{display:flex;align-items:flex-start;gap:12px;font-size:.92rem;line-height:1.65}
        .auth-check i{margin-top:4px}
        .auth-form-panel{display:flex;flex-direction:column;justify-content:center;gap:24px;padding:clamp(24px,4vw,42px);background:linear-gradient(180deg,rgba(255,255,255,.96),rgba(255,255,255,.92)),linear-gradient(180deg,#fff,#f8fbff)}
        .auth-nav{display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap}
        .auth-switch{display:inline-flex;align-items:center;gap:6px;padding:6px;background:#eef2f8;border:1px solid #dde5f0;border-radius:999px}
        .auth-switch a{padding:10px 16px;border-radius:999px;color:#64748b;font-size:.88rem;font-weight:700;text-decoration:none;transition:all .24s ease}
        .auth-switch a.active,.auth-switch a:hover{color:var(--auth-primary);background:#fff;box-shadow:0 8px 20px rgba(15,23,42,.08)}
        .auth-aux-link{display:inline-flex;align-items:center;gap:8px;color:#475569;font-size:.9rem;font-weight:600;text-decoration:none}
        .auth-card{padding:clamp(24px,3vw,34px);border-radius:28px;background:#fff;border:1px solid #e2e8f0;box-shadow:0 18px 48px rgba(15,23,42,.08)}
        .auth-card-title{margin:0;color:#0f172a;font-size:clamp(1.65rem,2vw,2rem)}
        .auth-card-subtitle{margin:10px 0 0;color:#64748b;font-size:.95rem;line-height:1.68}
        .auth-alert-stack{margin-top:22px}
        .auth-alert-stack .alert{border-radius:18px;align-items:flex-start}
        .auth-form{display:grid;gap:18px;margin-top:24px}
        .auth-form-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:16px}
        .auth-field,.auth-field-row{margin:0}
        .auth-field-row{display:flex;align-items:center;justify-content:space-between;gap:12px}
        .auth-label{display:inline-flex;align-items:center;gap:8px;margin-bottom:8px;color:#475569;font-size:.78rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase}
        .auth-inline-link{font-size:.86rem;font-weight:700;color:var(--auth-primary);text-decoration:none}
        .auth-input{position:relative}
        .auth-input i{position:absolute;top:50%;left:16px;transform:translateY(-50%);color:#94a3b8;font-size:1rem;pointer-events:none}
        .auth-input .form-control{width:100%;min-height:56px;padding:14px 16px 14px 48px;border-radius:16px;border:1px solid #d7deea;background:#fbfdff;box-shadow:inset 0 1px 0 rgba(255,255,255,.9);transition:border-color .22s ease,box-shadow .22s ease,transform .22s ease}
        .auth-input .form-control:focus{border-color:var(--auth-primary);box-shadow:0 0 0 4px rgba(37,99,235,.08);transform:translateY(-1px)}
        body.auth-login .auth-input .form-control:focus{box-shadow:0 0 0 4px rgba(255,111,145,.12)}
        body.auth-register .auth-input .form-control:focus{box-shadow:0 0 0 4px rgba(14,139,180,.12)}
        body.auth-forgot .auth-input .form-control:focus{box-shadow:0 0 0 4px rgba(220,124,32,.12)}
        .auth-input .form-control::placeholder{color:#94a3b8}
        .auth-helper{margin-top:8px;color:#64748b;font-size:.82rem;line-height:1.58}
        .auth-note-panel{padding:16px 18px;border-radius:18px;background:var(--auth-soft);border:1px solid rgba(148,163,184,.18);color:#334155;font-size:.9rem;line-height:1.68}
        .auth-note-panel strong{color:#0f172a}
        .auth-actions{display:grid;gap:12px;margin-top:4px}
        .auth-submit{width:100%;min-height:56px;border:none;border-radius:18px;background:linear-gradient(135deg,var(--auth-primary),var(--auth-secondary));color:#fff;font-size:.98rem;font-weight:700;text-transform:none;letter-spacing:.02em;box-shadow:0 18px 28px rgba(15,23,42,.14)}
        .auth-submit:hover{color:#fff;filter:brightness(1.02);transform:translateY(-1px)}
        .auth-alt-btn{width:100%;min-height:56px;border-radius:18px;border-width:1px;border-color:#d7deea;background:#fff;color:#334155;font-size:.95rem;font-weight:700;text-transform:none}
        .auth-alt-btn:hover{background:#fff;border-color:var(--auth-primary);color:var(--auth-primary)}
        .auth-form-footnote,.auth-footer{color:#64748b;line-height:1.68}
        .auth-form-footnote{font-size:.86rem;text-align:center}
        .auth-footer{font-size:.82rem}
        .auth-screen.is-entering .auth-shell{opacity:0;filter:blur(8px);transform:translateX(var(--auth-enter,42px)) scale(.985)}
        .auth-screen.is-entering.is-ready .auth-shell{opacity:1;filter:blur(0);transform:translateX(0) scale(1)}
        .auth-screen.is-leaving .auth-shell{opacity:0;filter:blur(8px);transform:translateX(var(--auth-leave,-42px)) scale(.985)}
        .auth-screen[data-transition-direction="backward"]{--auth-enter:-42px;--auth-leave:42px}
        @media (max-width:960px){.auth-shell{grid-template-columns:1fr;min-height:auto}.auth-headline{max-width:100%}}
        @media (max-width:640px){body.auth-screen{padding:12px}.auth-card,.auth-form-panel,.auth-visual{padding-left:18px;padding-right:18px}.auth-form-grid,.auth-stat-grid{grid-template-columns:1fr}.auth-nav{align-items:stretch}.auth-switch{width:100%;justify-content:space-between}.auth-switch a{flex:1;text-align:center}}
        @media (prefers-reduced-motion:reduce){.auth-shell,.auth-switch a,.auth-input .form-control,.btn{transition:none!important}}
    </style>
</head>
<body class="auth-screen @yield('auth_body_class', 'auth-login')" data-transition-direction="forward">
    <x-loading-overlay />
    <div class="auth-ambient one"></div>
    <div class="auth-ambient two"></div>
    <main class="auth-shell">
        <section class="auth-visual">
            <div>
                <a href="{{ route('login') }}" class="auth-logo" data-auth-transition data-transition-direction="backward">
                    <span class="auth-logo-mark"><i class="bi bi-calendar2-check-fill"></i></span>
                    <span>Quản Lý Sự Kiện NTU<small>Khoa Công Nghệ Thông Tin</small></span>
                </a>
                <div class="auth-kicker"><i class="bi bi-stars"></i><span>@yield('auth_kicker', 'Không gian tài khoản sinh viên')</span></div>
                <h1 class="auth-headline">@yield('auth_headline')</h1>
                <p class="auth-description">@yield('auth_description')</p>
            </div>
            <div class="auth-visual-card">@yield('auth_visual_card')</div>
        </section>
        <section class="auth-form-panel">
            <div class="auth-nav">
                <div class="auth-switch">
                    <a href="{{ route('login') }}" class="{{ request()->routeIs('login') ? 'active' : '' }}" data-auth-transition data-transition-direction="backward">Đăng nhập</a>
                    <a href="{{ route('register') }}" class="{{ request()->routeIs('register') ? 'active' : '' }}" data-auth-transition data-transition-direction="forward">Đăng ký</a>
                </div>
                @yield('auth_aux_link')
            </div>
            <div class="auth-card">
                <h2 class="auth-card-title">@yield('auth_form_title')</h2>
                <p class="auth-card-subtitle">@yield('auth_form_subtitle')</p>
                <div class="auth-alert-stack">@include('auth.partials.alerts')</div>
                @yield('content')
            </div>
            <p class="auth-footer">Cùng một hạ tầng mail đang phục vụ xác thực đăng ký cũng được dùng để gửi liên kết quên mật khẩu.</p>
        </section>
    </main>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const body = document.body;
            const savedDirection = sessionStorage.getItem('auth-transition-direction');
            if (savedDirection) {
                body.dataset.transitionDirection = savedDirection;
                body.classList.add('is-entering');
                requestAnimationFrame(() => body.classList.add('is-ready'));
                sessionStorage.removeItem('auth-transition-direction');
            }
            document.querySelectorAll('[data-auth-transition]').forEach((link) => {
                link.addEventListener('click', (event) => {
                    if (event.defaultPrevented || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey || link.target === '_blank') {
                        return;
                    }
                    event.preventDefault();
                    const direction = link.dataset.transitionDirection || 'forward';
                    sessionStorage.setItem('auth-transition-direction', direction);
                    body.dataset.transitionDirection = direction;
                    body.classList.add('is-leaving');
                    window.setTimeout(() => {
                        window.location.href = link.href;
                    }, 240);
                });
            });
        });
    </script>
</body>
</html>
