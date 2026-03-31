<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Đăng Ký — Quản Lý Sự Kiện NTU</title>

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

        .register-page {
            max-width: 480px;
            width: 100%;
        }

        .register-seal {
            width: 70px;
            height: 70px;
            border: 2.5px solid var(--accent);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto var(--space-md);
            color: var(--accent);
            font-size: 28px;
        }

        .register-title {
            font-family: var(--font-serif);
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text);
            text-align: center;
            margin-bottom: 4px;
        }

        .register-subtitle {
            text-align: center;
            font-size: 0.8125rem;
            color: var(--text-muted);
            margin-bottom: var(--space-xl);
        }

        .register-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--border-radius-md);
            padding: var(--space-xl);
        }

        .register-footer {
            text-align: center;
            margin-top: var(--space-lg);
            font-size: 0.8125rem;
            color: var(--text-light);
        }

        .register-footer a {
            font-weight: 600;
        }
    </style>
</head>
<body>
    <x-loading-overlay />

    <div class="register-page">
        <div class="register-seal">
            <i class="bi bi-person-plus"></i>
        </div>

        <h1 class="register-title">Tạo tài khoản</h1>
        <p class="register-subtitle">Đăng ký để tham gia các sự kiện Khoa CNTT</p>

        @if ($errors->any())
        <div class="alert alert-error">
            <i class="bi bi-x-circle"></i>
            <div>
                @foreach ($errors->all() as $error)
                {{ $error }}<br>
                @endforeach
            </div>
        </div>
        @endif

        <div class="register-card">
            <form action="{{ route('register') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="ho_ten" class="form-label">Họ và tên</label>
                    <input type="text" id="ho_ten" name="ho_ten" class="form-control @error('ho_ten') is-invalid @enderror"
                           value="{{ old('ho_ten') }}" placeholder="Nguyễn Văn A" required>
                    @error('ho_ten')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email') }}" placeholder="example@ntu.edu.vn" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="input-grid">
                    <div class="form-group">
                        <label for="password" class="form-label">Mật khẩu</label>
                        <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror"
                               placeholder="Tối thiểu 8 ký tự" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">Xác nhận mật khẩu</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control"
                               placeholder="Nhập lại mật khẩu" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="ma_sinh_vien" class="form-label">Mã sinh viên (tùy chọn)</label>
                    <input type="text" id="ma_sinh_vien" name="ma_sinh_vien" class="form-control"
                        value="{{ old('ma_sinh_vien') }}" placeholder="VD: 62131234">
                </div>

                <div class="form-group">
                    <label for="lop" class="form-label">Lớp <span style="color:#e74c3c;">*</span></label>
                    <input type="text" id="lop" name="lop" class="form-control @error('lop') is-invalid @enderror"
                        value="{{ old('lop') }}" placeholder="VD: 64.CNTT-1" required>
                    <small style="color: var(--text-muted);">Định dạng: số.chữ-số (Ví dụ: 64.CNTT-1)</small>
                    @error('lop')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary w-full" style="padding:12px;">Đăng ký</button>
            </form>
        </div>

        <p class="register-footer">
            Đã có tài khoản? <a href="{{ route('login') }}">Đăng nhập</a>
        </p>
    </div>
</body>
</html>
