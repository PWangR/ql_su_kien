@extends('layouts.auth')

@section('title', 'Quên mật khẩu')
@section('auth_body_class', 'auth-forgot')
@section('auth_kicker', 'Khôi phục truy cập')
@section('auth_headline', 'Quên mật khẩu cũng không làm bạn lỡ nhịp sự kiện.')
@section('auth_description', 'Nhập email đã dùng khi đăng ký. Hệ thống sẽ gửi liên kết đặt lại mật khẩu qua chính kênh SMTP đang phục vụ email xác thực tài khoản.')
@section('auth_form_title', 'Gửi liên kết đặt lại mật khẩu')
@section('auth_form_subtitle', 'Liên kết sẽ được gửi tới hộp thư của bạn và chỉ có hiệu lực trong một khoảng thời gian ngắn để đảm bảo an toàn.')

@section('auth_aux_link')
    <a href="{{ route('login') }}" class="auth-aux-link" data-auth-transition data-transition-direction="backward">
        <i class="bi bi-arrow-left"></i>
        Trở lại đăng nhập
    </a>
@endsection

@section('auth_visual_card')
    <div class="auth-checks">
        <div class="auth-check">
            <i class="bi bi-send-check"></i>
            <span>Email khôi phục được gửi bằng cùng cấu hình SMTP Gmail của dự án.</span>
        </div>
        <div class="auth-check">
            <i class="bi bi-clock-history"></i>
            <span>Liên kết có thời hạn để hạn chế rủi ro chia sẻ trái phép.</span>
        </div>
        <div class="auth-check">
            <i class="bi bi-shield-lock"></i>
            <span>Sau khi đổi mật khẩu, bạn có thể đăng nhập lại ngay với mật khẩu mới.</span>
        </div>
    </div>
@endsection

@section('content')
    <form action="{{ route('password.email') }}" method="POST" class="auth-form">
        @csrf

        <div class="auth-field">
            <label for="email" class="auth-label">
                <i class="bi bi-envelope-paper-heart"></i>
                Email đã đăng ký
            </label>
            <div class="auth-input">
                <i class="bi bi-envelope"></i>
                <input
                    type="email"
                    id="email"
                    name="email"
                    class="form-control @error('email') is-invalid @enderror"
                    value="{{ old('email') }}"
                    placeholder="example@ntu.edu.vn"
                    autocomplete="email"
                    required
                >
            </div>
            <div class="auth-helper">Hãy dùng đúng email đã tạo tài khoản để hệ thống tìm và gửi liên kết khôi phục.</div>
        </div>

        <div class="auth-note-panel">
            <strong>Không thấy email?</strong> Hãy kiểm tra thêm mục Spam hoặc Junk sau khi gửi yêu cầu.
        </div>

        <div class="auth-actions">
            <button type="submit" class="btn auth-submit">
                <i class="bi bi-envelope-arrow-up"></i>
                Gửi liên kết khôi phục
            </button>
        </div>

        <p class="auth-form-footnote">
            Nếu đã nhớ lại mật khẩu, bạn có thể quay về trang đăng nhập mà không cần gửi yêu cầu mới.
        </p>
    </form>
@endsection
