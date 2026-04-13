@extends('layouts.auth')

@section('title', 'Đăng nhập')

{{-- Panel --}}
@section('panel_badge', 'Cổng thông tin sinh viên')
@section('panel_heading', 'Quản lý Sự kiện Khoa Công Nghệ Thông Tin')
@section('panel_desc', 'Đăng nhập bằng tài khoản sinh viên NTU để không bỏ lỡ thông báo, lịch diễn ra và tích điểm các sự kiện của Khoa CNTT.')

@section('panel_card')
    <div class="auth-panel-stats">
        <div class="auth-panel-stat">
            <strong>QR</strong>
            <span>Điểm danh nhanh bằng mã QR động, chính xác tức thì.</span>
        </div>
        <div class="auth-panel-stat">
            <strong>Thực tế</strong>
            <span>Theo dõi lịch sử đăng ký & minh chứng tham gia.</span>
        </div>
    </div>
    <div class="auth-panel-badges">
        <span class="auth-panel-chip"><i class="bi bi-envelope-check"></i> Email xác thực</span>
        <span class="auth-panel-chip"><i class="bi bi-shield-lock"></i> Bảo mật phiên</span>
    </div>
@endsection

{{-- Tabs --}}
@section('tabs')
    <nav class="auth-tabs" aria-label="Điều hướng tài khoản">
        <a href="{{ route('login') }}" class="auth-tab active" aria-current="page" data-nav>
            <i class="bi bi-box-arrow-in-right"></i> Đăng nhập
        </a>
        <a href="{{ route('register') }}" class="auth-tab" data-nav>
            <i class="bi bi-person-plus"></i> Đăng ký
        </a>
    </nav>
@endsection

@section('form_title', 'Đăng nhập hệ thống')
@section('form_subtitle', 'Vui lòng sử dụng email đã đăng ký với Khoa để tiếp tục.')

@section('content')
    <form action="{{ route('login') }}" method="POST" class="auth-form" id="form-login">
        @csrf

        {{-- Email --}}
        <div class="auth-field">
            <label for="login-email" class="auth-label">
                <i class="bi bi-envelope"></i> Email
            </label>
            <div class="auth-input-wrap">
                <i class="bi bi-envelope auth-input-icon"></i>
                <input type="email" id="login-email" name="email"
                    class="auth-input-field @error('email') is-invalid @enderror" value="{{ old('email') }}"
                    placeholder="example@ntu.edu.vn" autocomplete="email" required>
            </div>
            @error('email')
                <span class="auth-helper is-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
            @enderror
        </div>

        {{-- Password --}}
        <div class="auth-field">
            <div class="auth-label-row">
                <label for="login-password" class="auth-label">
                    <i class="bi bi-lock"></i> Mật khẩu
                </label>
                <a href="{{ route('password.request') }}" class="auth-link-sm" data-nav>
                    Quên mật khẩu?
                </a>
            </div>
            <div class="auth-input-wrap">
                <i class="bi bi-lock auth-input-icon"></i>
                <input type="password" id="login-password" name="password"
                    class="auth-input-field @error('password') is-invalid @enderror" placeholder="Nhập mật khẩu của bạn"
                    autocomplete="current-password" required>
                <button type="button" class="auth-input-toggle" data-toggle-pw="login-password"
                    aria-label="Hiện/ẩn mật khẩu" tabindex="-1">
                    <i class="bi bi-eye"></i>
                </button>
            </div>
            @error('password')
                <span class="auth-helper is-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
            @enderror
        </div>

        {{-- Tip --}}
        <div class="auth-tip">
            <strong>Mẹo:</strong> Nếu tài khoản chưa xác thực email, hệ thống sẽ hướng dẫn bạn gửi lại email xác thực.
        </div>

        {{-- Actions --}}
        <button type="submit" class="auth-btn auth-btn-primary">
            <i class="bi bi-box-arrow-in-right"></i>
            Đăng nhập
        </button>

        <div class="auth-divider">hoặc</div>

        <a href="{{ route('register') }}" class="auth-btn auth-btn-ghost" data-nav>
            <i class="bi bi-person-plus"></i>
            Tạo tài khoản mới
        </a>

        <p class="auth-footnote">
            Chưa có tài khoản?
            <a href="{{ route('register') }}" data-nav>Đăng ký ngay</a>
        </p>
    </form>
@endsection