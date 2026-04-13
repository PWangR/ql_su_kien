@extends('layouts.auth')

@section('title', 'Quên mật khẩu')

{{-- Panel --}}
@section('panel_badge', 'Khôi phục truy cập')
@section('panel_heading', 'Quên mật khẩu cũng không làm bạn lỡ nhịp sự kiện.')
@section('panel_desc', 'Nhập email đã dùng khi đăng ký. Hệ thống sẽ gửi liên kết đặt lại mật khẩu qua kênh SMTP bảo mật.')

@section('panel_card')
<div class="auth-panel-checks">
    <div class="auth-panel-check">
        <i class="bi bi-send-check"></i>
        <span>Email khôi phục gửi bằng cùng cấu hình SMTP của hệ thống.</span>
    </div>
    <div class="auth-panel-check">
        <i class="bi bi-clock-history"></i>
        <span>Liên kết có thời hạn, hạn chế rủi ro chia sẻ trái phép.</span>
    </div>
    <div class="auth-panel-check">
        <i class="bi bi-shield-lock"></i>
        <span>Sau khi đổi mật khẩu, bạn có thể đăng nhập ngay với mật khẩu mới.</span>
    </div>
</div>
@endsection

@section('back_link')
<a href="{{ route('login') }}" class="auth-back-link" data-nav>
    <i class="bi bi-arrow-left"></i>
    Trở lại đăng nhập
</a>
@endsection

@section('form_title', 'Đặt lại mật khẩu')
@section('form_subtitle', 'Nhập email tài khoản để nhận liên kết khôi phục. Kiểm tra cả thư mục Spam nếu không thấy.')

@section('content')
<form action="{{ route('password.email') }}" method="POST" class="auth-form" id="form-forgot">
    @csrf

    <div class="auth-field">
        <label for="fp-email" class="auth-label">
            <i class="bi bi-envelope-paper-heart"></i> Email đã đăng ký
        </label>
        <div class="auth-input-wrap">
            <i class="bi bi-envelope auth-input-icon"></i>
            <input
                type="email"
                id="fp-email"
                name="email"
                class="auth-input-field @error('email') is-invalid @enderror"
                value="{{ old('email') }}"
                placeholder="example@ntu.edu.vn"
                autocomplete="email"
                required>
        </div>
        @error('email')
            <span class="auth-helper is-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
        @enderror
        <span class="auth-helper">Dùng đúng email đã tạo tài khoản để hệ thống gửi liên kết khôi phục.</span>
    </div>

    <div class="auth-tip">
        <strong>Không thấy email?</strong> Hãy kiểm tra thêm mục <em>Spam</em> hoặc <em>Junk</em> sau khi gửi yêu cầu.
    </div>

    <button type="submit" class="auth-btn auth-btn-primary">
        <i class="bi bi-envelope-arrow-up"></i>
        Gửi liên kết khôi phục
    </button>

    <a href="{{ route('login') }}" class="auth-btn auth-btn-ghost" data-nav>
        <i class="bi bi-arrow-left"></i>
        Quay lại đăng nhập
    </a>

    <p class="auth-footnote">
        Nếu đã nhớ lại mật khẩu, bạn có thể
        <a href="{{ route('login') }}" data-nav>đăng nhập ngay</a> mà không cần gửi yêu cầu mới.
    </p>
</form>
@endsection
