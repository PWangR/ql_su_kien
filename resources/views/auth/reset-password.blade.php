@extends('layouts.auth')

@section('title', 'Đặt lại mật khẩu')
@section('auth_body_class', 'auth-reset')
@section('auth_kicker', 'Thiết lập mật khẩu mới')
@section('auth_headline', 'Hoàn tất bước cuối để quay lại hệ thống.')
@section('auth_description', 'Liên kết trong email sẽ mở trang này để bạn tạo mật khẩu mới an toàn hơn và tiếp tục sử dụng tài khoản đã đăng ký.')
@section('auth_form_title', 'Đặt lại mật khẩu')
@section('auth_form_subtitle', 'Chọn mật khẩu mới có ít nhất 8 ký tự. Sau khi lưu thành công, bạn có thể đăng nhập lại ngay.')

@section('auth_aux_link')
    <a href="{{ route('login') }}" class="auth-aux-link" data-auth-transition data-transition-direction="backward">
        <i class="bi bi-arrow-left"></i>
        Trở lại đăng nhập
    </a>
@endsection

@section('auth_visual_card')
    <div class="auth-stat-grid">
        <div class="auth-stat">
            <strong>8+</strong>
            <span>Ký tự tối thiểu cho mật khẩu mới của bạn.</span>
        </div>
        <div class="auth-stat">
            <strong>60'</strong>
            <span>Thời gian hiệu lực mặc định của liên kết reset.</span>
        </div>
    </div>

    <div class="auth-quote">
        "Một lần đổi mật khẩu gọn gàng để bạn trở lại lịch sự kiện, thông báo và điểm danh mà không cần tạo tài khoản mới."
    </div>
@endsection

@section('content')
    <form action="{{ route('password.update') }}" method="POST" class="auth-form">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <div class="auth-field">
            <label for="email" class="auth-label">
                <i class="bi bi-envelope"></i>
                Email
            </label>
            <div class="auth-input">
                <i class="bi bi-person-circle"></i>
                <input
                    type="email"
                    id="email"
                    name="email"
                    class="form-control @error('email') is-invalid @enderror"
                    value="{{ old('email', $email) }}"
                    placeholder="example@ntu.edu.vn"
                    autocomplete="email"
                    required
                >
            </div>
        </div>

        <div class="auth-form-grid">
            <div class="auth-field">
                <label for="password" class="auth-label">
                    <i class="bi bi-lock"></i>
                    Mật khẩu mới
                </label>
                <div class="auth-input">
                    <i class="bi bi-key"></i>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-control @error('password') is-invalid @enderror"
                        placeholder="Tối thiểu 8 ký tự"
                        autocomplete="new-password"
                        required
                    >
                </div>
            </div>

            <div class="auth-field">
                <label for="password_confirmation" class="auth-label">
                    <i class="bi bi-check2-square"></i>
                    Xác nhận mật khẩu
                </label>
                <div class="auth-input">
                    <i class="bi bi-shield-lock"></i>
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        class="form-control"
                        placeholder="Nhập lại mật khẩu"
                        autocomplete="new-password"
                        required
                    >
                </div>
            </div>
        </div>

        <div class="auth-note-panel">
            <strong>Gợi ý:</strong> Hãy ưu tiên mật khẩu dễ nhớ với bạn nhưng khó đoán với người khác, tránh trùng với mã sinh viên hoặc số điện thoại.
        </div>

        <div class="auth-actions">
            <button type="submit" class="btn auth-submit">
                <i class="bi bi-arrow-repeat"></i>
                Cập nhật mật khẩu
            </button>
        </div>
    </form>
@endsection
