<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Đăng Ký - Quản Lý Sự Kiện NTU</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Montserrat:wght@700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body{
            font-family:'Inter',sans-serif;
            min-height:100vh;
            background: radial-gradient(circle at 20% 20%, rgba(37,99,235,0.2), transparent 35%), radial-gradient(circle at 80% 0%, rgba(236,72,153,0.15), transparent 30%), #0f172a;
            display:flex;align-items:center;justify-content:center;padding:20px;position:relative;overflow:hidden;
        }
        .card{
            background:rgba(255,255,255,0.98);
            border-radius:24px;
            width:100%;max-width:520px;
            padding:40px;
            box-shadow:0 25px 60px rgba(0,0,0,0.35);
            position:relative;z-index:1;
        }
        h1{font-family:'Montserrat',sans-serif;font-size:24px;font-weight:800;color:#0f172a;margin:0;}
        .subtitle{color:#64748b;font-size:13px;margin-top:4px;}
        .form-group{margin-bottom:16px;}
        .form-group label{display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px;}
        .form-control{
            width:100%;padding:12px 14px;border:1.5px solid #e2e8f0;border-radius:10px;
            font-size:14px;font-family:'Inter',sans-serif;background:#f8fafc;transition:all 0.2s;
        }
        .form-control:focus{border-color:#2563eb;background:#fff;box-shadow:0 0 0 3px rgba(37,99,235,0.1);outline:none;}
        .btn-primary{
            width:100%;padding:13px;background:linear-gradient(135deg,#2563eb,#3b82f6);
            color:#fff;border:none;border-radius:10px;font-size:15px;font-weight:700;cursor:pointer;
            box-shadow:0 4px 14px rgba(37,99,235,0.35);transition:all 0.2s;display:flex;align-items:center;justify-content:center;gap:8px;
        }
        .btn-primary:hover{transform:translateY(-1px);box-shadow:0 6px 20px rgba(37,99,235,0.45);}
        .switch-auth{text-align:center;margin-top:14px;font-size:13px;color:#64748b;}
        .switch-auth a{color:#2563eb;text-decoration:none;font-weight:600;}
        .error-box{background:#fef2f2;border:1px solid #fecaca;color:#b91c1c;padding:12px 16px;border-radius:10px;font-size:13.5px;display:flex;align-items:center;gap:8px;margin-bottom:14px;}
    </style>
</head>
<body>
    <div class="card">
        <div style="text-align:center;margin-bottom:22px;">
            <div style="width:72px;height:72px;background:linear-gradient(135deg,#2563EB,#60a5fa);border-radius:20px;display:inline-flex;align-items:center;justify-content:center;font-size:32px;color:#fff;box-shadow:0 8px 24px rgba(37,99,235,0.35);margin-bottom:12px;">
                <i class="bi bi-stars"></i>
            </div>
            <h1>Đăng ký tài khoản</h1>
            <div class="subtitle">Tham gia và quản lý sự kiện của Khoa CNTT</div>
        </div>

        @if ($errors->any())
            <div class="error-box">
                <i class="bi bi-exclamation-circle-fill"></i>
                <div>
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="form-group">
                <label for="ho_ten">Họ tên</label>
                <input type="text" id="ho_ten" name="ho_ten" class="form-control" value="{{ old('ho_ten') }}" required>
            </div>
            <div class="form-group">
                <label for="ma_sinh_vien">Mã sinh viên</label>
                <input type="text" id="ma_sinh_vien" name="ma_sinh_vien" class="form-control" value="{{ old('ma_sinh_vien') }}" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required>
            </div>
            <div class="form-group">
                <label for="password">Mật khẩu</label>
                <input type="password" id="password" name="password" class="form-control" required minlength="8">
            </div>
            <div class="form-group">
                <label for="password_confirmation">Nhập lại mật khẩu</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required minlength="8">
            </div>

            <button type="submit" class="btn-primary">
                <i class="bi bi-person-check"></i> Tạo tài khoản
            </button>
        </form>

        <div class="switch-auth">
            Đã có tài khoản? <a href="{{ route('login') }}">Đăng nhập</a>
        </div>
    </div>
</body>
</html>
