@extends('layouts.auth')

@section('title', 'Đặt lại mật khẩu')

{{-- Panel --}}
@section('panel_badge', 'Thiết lập mật khẩu mới')
@section('panel_heading', 'Hoàn tất bước cuối để quay lại hệ thống.')
@section('panel_desc', 'Liên kết trong email đã dẫn bạn đến đây. Tạo mật khẩu mới an toàn và bắt đầu đăng nhập lại.')

@section('panel_card')
<div class="auth-panel-stats">
    <div class="auth-panel-stat">
        <strong>8+</strong>
        <span>Ký tự tối thiểu cho mật khẩu mới của bạn.</span>
    </div>
    <div class="auth-panel-stat">
        <strong>60'</strong>
        <span>Thời gian hiệu lực của liên kết đặt lại mật khẩu.</span>
    </div>
</div>
<div style="margin-top:14px; font-size:.85rem; opacity:.85; line-height:1.6;">
    "Một lần đổi mật khẩu gọn gàng để bạn trở lại lịch sự kiện, thông báo và điểm danh mà không cần tạo tài khoản mới."
</div>
@endsection

@section('back_link')
<a href="{{ route('login') }}" class="auth-back-link" data-nav>
    <i class="bi bi-arrow-left"></i>
    Trở lại đăng nhập
</a>
@endsection

@section('form_title', 'Tạo mật khẩu mới')
@section('form_subtitle', 'Chọn mật khẩu có ít nhất 8 ký tự. Sau khi lưu, bạn có thể đăng nhập lại ngay.')

@section('content')
<form action="{{ route('password.update') }}" method="POST" class="auth-form" id="form-reset">
    @csrf
    <input type="hidden" name="token" value="{{ $token }}">

    {{-- Email --}}
    <div class="auth-field">
        <label for="rp-email" class="auth-label">
            <i class="bi bi-envelope"></i> Email
        </label>
        <div class="auth-input-wrap">
            <i class="bi bi-person-circle auth-input-icon"></i>
            <input
                type="email"
                id="rp-email"
                name="email"
                class="auth-input-field @error('email') is-invalid @enderror"
                value="{{ old('email', $email) }}"
                placeholder="example@ntu.edu.vn"
                autocomplete="email"
                required>
        </div>
        @error('email')
            <span class="auth-helper is-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
        @enderror
    </div>

    {{-- Mật khẩu mới + xác nhận --}}
    <div class="auth-row">
        <div class="auth-field">
            <label for="rp-pw" class="auth-label">
                <i class="bi bi-lock"></i> Mật khẩu mới
            </label>
            <div class="auth-input-wrap">
                <i class="bi bi-key auth-input-icon"></i>
                <input
                    type="password"
                    id="rp-pw"
                    name="password"
                    class="auth-input-field @error('password') is-invalid @enderror"
                    placeholder="Tối thiểu 8 ký tự"
                    autocomplete="new-password"
                    required>
                <button type="button" class="auth-input-toggle" data-toggle-pw="rp-pw" aria-label="Hiện/ẩn mật khẩu" tabindex="-1">
                    <i class="bi bi-eye"></i>
                </button>
            </div>
            @error('password')
                <span class="auth-helper is-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
            @enderror
        </div>

        <div class="auth-field">
            <label for="rp-pw-confirm" class="auth-label">
                <i class="bi bi-shield-check"></i> Xác nhận
            </label>
            <div class="auth-input-wrap">
                <i class="bi bi-shield-lock auth-input-icon"></i>
                <input
                    type="password"
                    id="rp-pw-confirm"
                    name="password_confirmation"
                    class="auth-input-field"
                    placeholder="Nhập lại mật khẩu"
                    autocomplete="new-password"
                    required>
                <button type="button" class="auth-input-toggle" data-toggle-pw="rp-pw-confirm" aria-label="Hiện/ẩn mật khẩu" tabindex="-1">
                    <i class="bi bi-eye"></i>
                </button>
            </div>
        </div>
    </div>

    <div class="auth-tip">
        <strong>Gợi ý:</strong> Ưu tiên mật khẩu dễ nhớ với bạn nhưng khó đoán, tránh trùng mã sinh viên hoặc số điện thoại.
    </div>

    <button type="submit" class="auth-btn auth-btn-primary">
        <i class="bi bi-arrow-repeat"></i>
        Cập nhật mật khẩu
    </button>

    <p class="auth-footnote">
        Nhớ lại mật khẩu rồi? <a href="{{ route('login') }}" data-nav>Đăng nhập ngay</a>
    </p>
</form>
@endsection
