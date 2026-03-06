<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Đăng Nhập - Quản Lý Sự Kiện NTU</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Montserrat:wght@700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 50%, #0f172a 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }

        /* Animated background blobs */
        body::before {
            content: '';
            position: absolute;
            width: 600px; height: 600px;
            background: radial-gradient(circle, rgba(37,99,235,0.3) 0%, transparent 70%);
            top: -200px; left: -100px;
            animation: blob 8s ease-in-out infinite alternate;
            pointer-events: none;
        }

        body::after {
            content: '';
            position: absolute;
            width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(96,165,250,0.2) 0%, transparent 70%);
            bottom: -150px; right: -50px;
            animation: blob 10s ease-in-out infinite alternate-reverse;
            pointer-events: none;
        }

        @keyframes blob {
            from { transform: translate(0, 0) scale(1); }
            to   { transform: translate(40px, 30px) scale(1.1); }
        }

        /* Card */
        .login-card {
            background: rgba(255,255,255,0.98);
            border-radius: 24px;
            width: 100%;
            max-width: 440px;
            padding: 48px 40px;
            box-shadow: 0 25px 60px rgba(0,0,0,0.4);
            position: relative;
            z-index: 1;
            animation: slideUp 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(40px) scale(0.95); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }

        /* Logo */
        .login-logo {
            text-align: center;
            margin-bottom: 32px;
        }

        .logo-icon-big {
            width: 72px;
            height: 72px;
            background: linear-gradient(135deg, #2563EB, #60a5fa);
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            color: #fff;
            margin-bottom: 16px;
            box-shadow: 0 8px 24px rgba(37,99,235,0.35);
        }

        .login-title {
            font-family: 'Montserrat', sans-serif;
            font-size: 22px;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.3;
        }

        .login-subtitle {
            font-size: 13px;
            color: #64748b;
            margin-top: 4px;
        }

        /* Divider */
        .divider-line {
            border: none;
            border-top: 1px solid #e2e8f0;
            margin: 20px 0;
        }

        /* Error */
        .error-box {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #b91c1c;
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 13.5px;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
            animation: shake 0.4s ease;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25%       { transform: translateX(-6px); }
            75%       { transform: translateX(6px); }
        }

        /* Form */
        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 6px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 16px;
            pointer-events: none;
            transition: color 0.2s;
        }

        .form-control {
            width: 100%;
            padding: 12px 14px 12px 42px;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            font-size: 14px;
            font-family: 'Inter', sans-serif;
            color: #1e293b;
            background: #f8fafc;
            transition: all 0.2s;
            outline: none;
        }

        .form-control:focus {
            border-color: #2563eb;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
        }

        .form-control:focus ~ .input-icon,
        .input-wrapper:focus-within .input-icon {
            color: #2563eb;
        }

        /* Password toggle */
        .pwd-toggle {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #94a3b8;
            font-size: 16px;
            padding: 0;
            transition: color 0.2s;
        }

        .pwd-toggle:hover { color: #2563eb; }

        /* Button */
        .btn-login {
            width: 100%;
            padding: 13px;
            background: linear-gradient(135deg, #2563eb, #3b82f6);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 700;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0 4px 14px rgba(37,99,235,0.35);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 8px;
        }

        .btn-login:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(37,99,235,0.45);
        }

        .btn-login:active { transform: translateY(0); }

        /* Forgot */
        .forgot-link {
            text-align: center;
            margin-top: 16px;
            font-size: 13px;
            color: #64748b;
        }

        .forgot-link a {
            color: #2563eb;
            text-decoration: none;
            font-weight: 500;
        }

        .forgot-link a:hover { text-decoration: underline; }

        /* Demo accounts */
        .demo-accounts {
            background: #f0f9ff;
            border: 1px solid #bae6fd;
            border-radius: 10px;
            padding: 14px 16px;
            margin-top: 20px;
        }

        .demo-accounts h4 {
            font-size: 12px;
            font-weight: 600;
            color: #0369a1;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .demo-row {
            font-size: 12px;
            color: #0c4a6e;
            margin: 3px 0;
            display: flex;
            justify-content: space-between;
        }

        .demo-badge {
            background: #0369a1;
            color: #fff;
            padding: 1px 7px;
            border-radius: 6px;
            font-size: 10px;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <!-- Logo -->
        <div class="login-logo">
            <div class="logo-icon-big"><i class="bi bi-calendar-event-fill"></i></div>
            <div class="login-title">Quản Lý Sự Kiện</div>
            <div class="login-subtitle">Khoa Công Nghệ Thông Tin - ĐH Nha Trang</div>
        </div>

        <hr class="divider-line">

        <!-- Error Alert -->
        @if(session('error'))
        <div class="error-box">
            <i class="bi bi-exclamation-circle-fill"></i>
            {{ session('error') }}
        </div>
        @endif

        <!-- Form -->
        <form method="POST" action="/login">
            @csrf

            <div class="form-group">
                <label for="email">Email</label>
                <div class="input-wrapper">
                    <input
                        type="email"
                        id="email"
                        name="email"
                        class="form-control"
                        placeholder="example@ntu.edu.vn"
                        value="{{ old('email') }}"
                        required
                        autocomplete="email"
                    >
                    <i class="bi bi-envelope-fill input-icon"></i>
                </div>
            </div>

            <div class="form-group">
                <label for="password">Mật khẩu</label>
                <div class="input-wrapper">
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-control"
                        placeholder="Nhập mật khẩu"
                        required
                        autocomplete="current-password"
                    >
                    <i class="bi bi-lock-fill input-icon"></i>
                    <button type="button" class="pwd-toggle" onclick="togglePwd()" id="pwdToggle">
                        <i class="bi bi-eye-fill" id="pwdIcon"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn-login">
                <i class="bi bi-box-arrow-in-right"></i> Đăng nhập
            </button>
        </form>

        <div class="forgot-link">
            Quên mật khẩu? <a href="#">Liên hệ quản trị viên</a>
        </div>

        <!-- Demo accounts -->
        <div class="demo-accounts">
            <h4><i class="bi bi-info-circle"></i> Tài khoản thử nghiệm</h4>
            <div class="demo-row">
                <span>admin@local.test / 12345678</span>
                <span class="demo-badge">Admin</span>
            </div>
            <div class="demo-row">
                <span>sv@local.test / 12345678</span>
                <span class="demo-badge" style="background:#059669;">SV</span>
            </div>
        </div>
    </div>

    <script>
        function togglePwd() {
            const input = document.getElementById('password');
            const icon  = document.getElementById('pwdIcon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'bi bi-eye-slash-fill';
            } else {
                input.type = 'password';
                icon.className = 'bi bi-eye-fill';
            }
        }
    </script>
</body>
</html>