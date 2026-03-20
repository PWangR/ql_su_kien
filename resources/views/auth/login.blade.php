<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Đăng Nhập - Quản Lý Sự Kiện NTU</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            max-width: 450px;
            width: 100%;
            padding: 50px 40px;
        }

        .login-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .login-header h1 {
            font-size: 28px;
            color: #333;
            margin-bottom: 10px;
            font-weight: 700;
        }

        .login-header p {
            color: #666;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-size: 14px;
            font-weight: 500;
        }

        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s;
        }

        .form-group input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            line-height: 1.5;
        }

        .alert-error {
            background: #fee;
            color: #c33;
            border-left: 4px solid #c33;
        }

        .alert-warning {
            background: #fffbeb;
            color: #92400e;
            border-left: 4px solid #f59e0b;
        }

        .alert-warning .alert-link {
            color: #b45309;
            font-weight: 600;
        }

        .alert-success {
            background: #f0fdf4;
            color: #166534;
            border-left: 4px solid #22c55e;
        }

        .btn-login {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 10px;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }

        .login-footer {
            text-align: center;
            margin-top: 25px;
            font-size: 14px;
            color: #666;
        }

        .login-footer a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            margin: 0 5px;
        }

        .login-footer a:hover {
            text-decoration: underline;
        }

        .btn-register {
            width: 100%;
            padding: 12px;
            background: #f0f0f0;
            color: #333;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 12px;
            text-decoration: none;
            display: block;
            text-align: center;
        }

        .btn-register:hover {
            background: #e0e0e0;
            border-color: #ccc;
        }

        .demo-accounts {
            background: #f5f7ff;
            border-radius: 8px;
            padding: 15px;
            margin-top: 25px;
            border: 1px solid #e0e7ff;
        }

        .demo-accounts h3 {
            font-size: 13px;
            color: #333;
            margin-bottom: 10px;
            font-weight: 600;
        }

        .demo-account {
            font-size: 12px;
            color: #666;
            margin-bottom: 8px;
            padding: 8px;
            background: white;
            border-radius: 5px;
        }

        .demo-account strong {
            color: #333;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="login-header">
            <h1>Quản Lý Sự Kiện</h1>
            <p>Khoa Công Nghệ Thông Tin - ĐH Nha Trang</p>
        </div>

        @if (session('error'))
        <div class="alert alert-error">
            <i class="bi bi-x-circle me-1"></i> {{ session('error') }}
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

        <form action="{{ route('login') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="email">Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="example@ntu.edu.vn"
                    required>
            </div>

            <div class="form-group">
                <label for="password">Mật khẩu</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Nhập mật khẩu"
                    required>
            </div>

            <button type="submit" class="btn-login">Đăng nhập</button>
            <a href="{{ route('register') }}" class="btn-register">Tạo tài khoản mới</a>
        </form>

        <div class="demo-accounts">
            <h3>📋 Tài khoản thử nghiệm</h3>
            <div class="demo-account">
                <strong>Admin:</strong> admin@example.com / password
            </div>
            <div class="demo-account">
                <strong>Sinh viên:</strong> student@example.com / password
            </div>
        </div>
    </div>
</body>

</html>