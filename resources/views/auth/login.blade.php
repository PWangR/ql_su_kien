<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Đăng Nhập — Quản Lý Sự Kiện NTU</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            background: var(--bg);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: var(--space-lg);
        }

        .login-page {
            max-width: 420px;
            width: 100%;
        }

        .login-seal {
            width: 80px;
            height: 80px;
            border: 2.5px solid var(--accent);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto var(--space-lg);
            color: var(--accent);
            font-size: 32px;
        }

        .login-title {
            font-family: var(--font-serif);
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--text);
            text-align: center;
            margin-bottom: 4px;
        }

        .login-subtitle {
            text-align: center;
            font-size: 0.8125rem;
            color: var(--text-muted);
            margin-bottom: var(--space-xl);
            letter-spacing: 0.02em;
        }

        .login-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--border-radius-md);
            padding: var(--space-xl) var(--space-xl) var(--space-lg);
        }

        .login-divider {
            border: none;
            border-top: 1px solid var(--border-light);
            margin: var(--space-lg) 0;
        }

        .demo-section {
            margin-top: var(--space-lg);
            padding: var(--space-md);
            border: 1px solid var(--border-light);
            border-radius: var(--border-radius);
            background: var(--bg-alt);
        }

        .demo-section h4 {
            font-family: var(--font-sans);
            font-size: 0.6875rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--text-light);
            margin-bottom: var(--space-sm);
        }

        .demo-item {
            font-size: 0.8125rem;
            color: var(--text-light);
            padding: 6px 10px;
            background: var(--card);
            border: 1px solid var(--border-light);
            border-radius: var(--border-radius);
            margin-bottom: 6px;
            font-family: var(--font-mono);
        }

        .demo-item strong {
            color: var(--text);
            font-family: var(--font-sans);
        }

        .login-footer-text {
            text-align: center;
            margin-top: var(--space-xl);
            font-size: 0.75rem;
            color: var(--text-muted);
            letter-spacing: 0.03em;
        }
    </style>
</head>
<body>
    <x-loading-overlay />

    <div class="login-page">
        <!-- Seal -->
        <div class="login-seal">
            <i class="bi bi-mortarboard-fill"></i>
        </div>

        <h1 class="login-title">Quản Lý Sự Kiện</h1>
        <p class="login-subtitle">Khoa Công Nghệ Thông Tin — Đại học Nha Trang</p>

        <!-- Alerts -->
        @if (session('error'))
        <div class="alert alert-error">
            <i class="bi bi-x-circle"></i> {{ session('error') }}
        </div>
        @elseif ($errors->any())
        <div class="alert alert-error">
            <i class="bi bi-x-circle"></i> Email hoặc mật khẩu không đúng hoặc tài khoản bị khóa
        </div>
        @endif

        @if (session('warning'))
        <div class="alert alert-warning">
            <i class="bi bi-exclamation-triangle"></i>
            {!! session('warning') !!}
        </div>
        @endif

        @if (session('success'))
        <div class="alert alert-success">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
        </div>
        @endif

        <!-- Login Form -->
        <div class="login-card">
            <form action="{{ route('login') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" class="form-control"
                           value="{{ old('email') }}" placeholder="example@ntu.edu.vn" required>
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Mật khẩu</label>
                    <input type="password" id="password" name="password" class="form-control"
                           placeholder="Nhập mật khẩu" required>
                </div>

                <button type="submit" class="btn btn-primary w-full" style="padding:12px;">Đăng nhập</button>

                <hr class="login-divider">

                <a href="{{ route('register') }}" class="btn btn-outline w-full" style="padding:12px;">
                    Tạo tài khoản mới
                </a>
            </form>
        </div>

        <!-- Demo Accounts -->
        <div class="demo-section">
            <h4>Tài khoản thử nghiệm</h4>
            <div class="demo-item">
                <strong>Admin:</strong> admin@example.com / password
            </div>
            <div class="demo-item">
                <strong>Sinh viên:</strong> student@example.com / password
            </div>
        </div>

        <p class="login-footer-text">
            &copy; {{ date('Y') }} Khoa CNTT — ĐH Nha Trang
        </p>
    </div>
</body>
</html>