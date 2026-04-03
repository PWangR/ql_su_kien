@extends('layouts.auth')

@section('title', 'Đăng ký')
@section('auth_body_class', 'auth-register')
@section('auth_kicker', 'Khởi tạo tài khoản mới')
@section('auth_headline', 'Tạo hồ sơ để tham gia, tích điểm và theo dõi các sự kiện.')
@section('auth_description', 'Trang đăng ký mới dùng cùng ngôn ngữ split layout với trang đăng nhập, pha mảng xanh sâu và lớp sáng mềm giống tinh thần ảnh mẫu nhưng tối ưu cho biểu mẫu sinh viên NTU.')
@section('auth_form_title', 'Tạo tài khoản sinh viên')
@section('auth_form_subtitle', 'Điền đúng thông tin để hệ thống gửi email xác thực và kích hoạt tài khoản của bạn ngay sau khi đăng ký.')

@section('auth_visual_card')
<div class="auth-checks">
    <div class="auth-check">
        <i class="bi bi-check2-circle"></i>
        <span>Nhận email xác thực ngay sau khi gửi biểu mẫu đăng ký.</span>
    </div>
    <div class="auth-check">
        <i class="bi bi-check2-circle"></i>
        <span>Theo dõi sự kiện, điểm danh và lịch sử tham gia trên cùng một tài khoản.</span>
    </div>
    <div class="auth-check">
        <i class="bi bi-check2-circle"></i>
        <span>Dùng chính địa chỉ email này để khôi phục mật khẩu khi cần.</span>
    </div>
</div>

<div class="auth-meta">
    <span class="auth-meta-badge"><i class="bi bi-mortarboard"></i> Dành cho sinh viên</span>
    <span class="auth-meta-badge"><i class="bi bi-patch-check"></i> Xác thực qua email</span>
</div>
@endsection

@section('content')
<form action="{{ route('register') }}" method="POST" class="auth-form">
    @csrf

    <div class="auth-form-grid">
        <div class="auth-field">
            <label for="ho_ten" class="auth-label">
                <i class="bi bi-person-badge"></i>
                Họ và tên
            </label>
            <div class="auth-input">
                <i class="bi bi-person"></i>
                <input
                    type="text"
                    id="ho_ten"
                    name="ho_ten"
                    class="form-control @error('ho_ten') is-invalid @enderror"
                    value="{{ old('ho_ten') }}"
                    placeholder="Nguyễn Văn A"
                    autocomplete="name"
                    required>
            </div>
        </div>

        <div class="auth-field">
            <label for="ma_sinh_vien" class="auth-label">
                <i class="bi bi-upc-scan"></i>
                Mã sinh viên
            </label>
            <div class="auth-input">
                <i class="bi bi-hash"></i>
                <input
                    type="text"
                    id="ma_sinh_vien"
                    name="ma_sinh_vien"
                    class="form-control @error('ma_sinh_vien') is-invalid @enderror"
                    value="{{ old('ma_sinh_vien') }}"
                    placeholder="Ví dụ: 62131234"
                    required>
            </div>
        </div>
    </div>

    <div class="auth-form-grid">
        <div class="auth-field">
            <label for="lop" class="auth-label">
                <i class="bi bi-diagram-3"></i>
                Lớp
            </label>
            <div class="auth-input">
                <i class="bi bi-collection"></i>
                <input
                    type="text"
                    id="lop"
                    name="lop"
                    class="form-control @error('lop') is-invalid @enderror"
                    value="{{ old('lop') }}"
                    placeholder="Ví dụ: 64.CNTT-1"
                    required>
            </div>
            <div class="auth-helper">Định dạng chuẩn: số.chữ-số, ví dụ <strong>64.CNTT-1</strong>.</div>
        </div>

        <div class="auth-field">
            <label for="email" class="auth-label">
                <i class="bi bi-envelope-paper"></i>
                Email
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
                    required>
            </div>
        </div>
    </div>

    <div class="auth-form-grid">
        <div class="auth-field">
            <label for="password" class="auth-label">
                <i class="bi bi-lock"></i>
                Mật khẩu
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
                    required>
            </div>
        </div>

        <div class="auth-field">
            <label for="password_confirmation" class="auth-label">
                <i class="bi bi-shield-check"></i>
                Xác nhận mật khẩu
            </label>
            <div class="auth-input">
                <i class="bi bi-check2-square"></i>
                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    class="form-control"
                    placeholder="Nhập lại mật khẩu"
                    autocomplete="new-password"
                    required>
            </div>
        </div>
    </div>

    <div class="auth-note-panel">
        <strong>Lưu ý:</strong> Sau khi tạo tài khoản, hệ thống sẽ gửi email xác thực trước khi bạn có thể đăng nhập và sử dụng đầy đủ chức năng.
    </div>

    <div class="auth-actions">
        <button type="submit" class="btn auth-submit">
            <i class="bi bi-person-check"></i>
            Đăng ký tài khoản
        </button>

        <a
            href="{{ route('login') }}"
            class="btn auth-alt-btn"
            data-auth-transition
            data-transition-direction="backward">
            <i class="bi bi-arrow-left-circle"></i>
            Quay lại đăng nhập
        </a>
    </div>

    <p class="auth-form-footnote">
        Cùng một email sẽ được dùng cho xác thực tài khoản và quên mật khẩu về sau.
    </p>
</form>
@endsection