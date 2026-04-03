@extends('layouts.auth')

@section('title', 'Đăng nhập')
@section('auth_body_class', 'auth-login')
@section('auth_kicker', 'Bảng điều khiển cá nhân')
@section('auth_headline', 'Chạm nhịp vào mọi sự kiện của khoa.')
@section('auth_description', 'Đăng nhập để không bỏ lỡ thông báo mới, lịch diễn ra và các hoạt động tích điểm của Khoa CNTT.')
@section('auth_form_title', 'Đăng nhập hệ thống')
@section('auth_form_subtitle', 'Sử dụng email đã đăng ký để theo dõi lịch sự kiện, điểm danh và lịch sử tham gia của bạn.')

@section('auth_visual_card')
<div class="auth-meta">
    <span class="auth-meta-badge"><i class="bi bi-envelope-check"></i> Email xác thực</span>
    <span class="auth-meta-badge"><i class="bi bi-shield-lock"></i> Bảo mật phiên</span>
</div>

<div class="auth-stat-grid">
    <div class="auth-stat">
        <strong>24/7</strong>
        <span>Truy cập cổng sự kiện mọi lúc trên hệ thống web nội bộ.</span>
    </div>
    <div class="auth-stat">
        <strong>1 chạm</strong>
        <span>Xem nhanh lịch sử đăng ký và điểm danh cá nhân.</span>
    </div>
</div>
@endsection

@section('content')
<form action="{{ route('login') }}" method="POST" class="auth-form">
    @csrf

    <div class="auth-field">
        <label for="email" class="auth-label">
            <i class="bi bi-person-circle"></i>
            Email
        </label>
        <div class="auth-input">
            <i class="bi bi-envelope"></i>
            <input
                type="email"
                id="email"
                name="email"
                class="form-control"
                value="{{ old('email') }}"
                placeholder="example@ntu.edu.vn"
                autocomplete="email"
                required>
        </div>
    </div>

    <div class="auth-field">
        <div class="auth-field-row">
            <label for="password" class="auth-label" style="margin-bottom:0;">
                <i class="bi bi-key"></i>
                Mật khẩu
            </label>
            <a href="{{ route('password.request') }}" class="auth-inline-link">
                Quên mật khẩu?
            </a>
        </div>

        <div class="auth-input">
            <i class="bi bi-lock"></i>
            <input
                type="password"
                id="password"
                name="password"
                class="form-control"
                placeholder="Nhập mật khẩu của bạn"
                autocomplete="current-password"
                required>
        </div>
    </div>

    <div class="auth-note-panel">
        <strong>Mẹo nhanh:</strong> Nếu tài khoản đã đăng ký nhưng chưa xác thực email, hệ thống sẽ hướng bạn tới màn hình gửi lại email xác thực ngay từ thông báo đăng nhập.
    </div>

    <div class="auth-actions">
        <button type="submit" class="btn auth-submit">
            <i class="bi bi-box-arrow-in-right"></i>
            Đăng nhập
        </button>

        <a
            href="{{ route('register') }}"
            class="btn auth-alt-btn"
            data-auth-transition
            data-transition-direction="forward">
            <i class="bi bi-person-plus"></i>
            Tạo tài khoản mới
        </a>
    </div>

    <p class="auth-form-footnote">
        Chưa từng sử dụng hệ thống? Tạo tài khoản để nhận email xác thực và bắt đầu đăng ký sự kiện.
    </p>
</form>
@endsection