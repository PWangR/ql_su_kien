@extends('layouts.auth')

@section('title', 'Đăng ký')

{{-- Panel --}}
@section('panel_badge', 'Khởi tạo tài khoản')
@section('panel_heading', 'Tạo hồ sơ để tham gia, tích điểm và theo dõi sự kiện.')
@section('panel_desc', 'Điền đúng thông tin sinh viên để hệ thống gửi email xác thực và kích hoạt tài khoản của bạn.')

@section('panel_card')
<div class="auth-panel-checks">
    <div class="auth-panel-check">
        <i class="bi bi-check2-circle"></i>
        <span>Nhận email xác thực ngay sau khi gửi biểu mẫu đăng ký.</span>
    </div>
    <div class="auth-panel-check">
        <i class="bi bi-check2-circle"></i>
        <span>Theo dõi sự kiện, điểm danh và lịch sử tham gia trên cùng một tài khoản.</span>
    </div>
    <div class="auth-panel-check">
        <i class="bi bi-check2-circle"></i>
        <span>Email đăng ký sẽ được dùng để khôi phục mật khẩu khi cần.</span>
    </div>
</div>
<div class="auth-panel-badges">
    <span class="auth-panel-chip"><i class="bi bi-mortarboard"></i> Sinh viên NTU</span>
    <span class="auth-panel-chip"><i class="bi bi-patch-check"></i> Xác thực email</span>
</div>
@endsection

{{-- Tabs --}}
@section('tabs')
<nav class="auth-tabs" aria-label="Điều hướng tài khoản">
    <a href="{{ route('login') }}"
       class="auth-tab"
       data-nav>
        <i class="bi bi-box-arrow-in-right"></i> Đăng nhập
    </a>
    <a href="{{ route('register') }}"
       class="auth-tab active"
       aria-current="page"
       data-nav>
        <i class="bi bi-person-plus"></i> Đăng ký
    </a>
</nav>
@endsection

@section('form_title', 'Tạo tài khoản sinh viên')
@section('form_subtitle', 'Điền đúng thông tin để hệ thống kích hoạt tài khoản qua email.')

@section('content')
<form action="{{ route('register') }}" method="POST" class="auth-form" id="form-register">
    @csrf

    {{-- Họ tên + MSSV --}}
    <div class="auth-row">
        <div class="auth-field">
            <label for="reg-ho-ten" class="auth-label">
                <i class="bi bi-person-badge"></i> Họ và tên
            </label>
            <div class="auth-input-wrap">
                <i class="bi bi-person auth-input-icon"></i>
                <input
                    type="text"
                    id="reg-ho-ten"
                    name="ho_ten"
                    class="auth-input-field @error('ho_ten') is-invalid @enderror"
                    value="{{ old('ho_ten') }}"
                    placeholder="Nguyễn Văn A"
                    autocomplete="name"
                    required>
            </div>
            @error('ho_ten')
                <span class="auth-helper is-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
            @enderror
        </div>

        <div class="auth-field">
            <label for="reg-mssv" class="auth-label">
                <i class="bi bi-upc-scan"></i> Mã sinh viên
            </label>
            <div class="auth-input-wrap">
                <i class="bi bi-hash auth-input-icon"></i>
                <input
                    type="text"
                    id="reg-mssv"
                    name="ma_sinh_vien"
                    class="auth-input-field @error('ma_sinh_vien') is-invalid @enderror"
                    value="{{ old('ma_sinh_vien') }}"
                    placeholder="62131234"
                    required>
            </div>
            @error('ma_sinh_vien')
                <span class="auth-helper is-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
            @enderror
        </div>
    </div>

    {{-- Lớp + Email --}}
    <div class="auth-row">
        <div class="auth-field">
            <label for="reg-lop" class="auth-label">
                <i class="bi bi-diagram-3"></i> Lớp
            </label>
            <div class="auth-input-wrap">
                <i class="bi bi-collection auth-input-icon"></i>
                <input
                    type="text"
                    id="reg-lop"
                    name="lop"
                    class="auth-input-field @error('lop') is-invalid @enderror"
                    value="{{ old('lop') }}"
                    placeholder="64.CNTT-1"
                    required>
            </div>
            @error('lop')
                <span class="auth-helper is-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
            @enderror
            <span class="auth-helper">Định dạng: <strong>64.CNTT-1</strong></span>
        </div>

        <div class="auth-field">
            <label for="reg-email" class="auth-label">
                <i class="bi bi-envelope-paper"></i> Email
            </label>
            <div class="auth-input-wrap">
                <i class="bi bi-envelope auth-input-icon"></i>
                <input
                    type="email"
                    id="reg-email"
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
        </div>
    </div>

    {{-- Mật khẩu + Xác nhận --}}
    <div class="auth-row">
        <div class="auth-field">
            <label for="reg-pw" class="auth-label">
                <i class="bi bi-lock"></i> Mật khẩu
            </label>
            <div class="auth-input-wrap">
                <i class="bi bi-key auth-input-icon"></i>
                <input
                    type="password"
                    id="reg-pw"
                    name="password"
                    class="auth-input-field @error('password') is-invalid @enderror"
                    placeholder="Tối thiểu 8 ký tự"
                    autocomplete="new-password"
                    required>
                <button type="button" class="auth-input-toggle" data-toggle-pw="reg-pw" aria-label="Hiện/ẩn mật khẩu" tabindex="-1">
                    <i class="bi bi-eye"></i>
                </button>
            </div>
            @error('password')
                <span class="auth-helper is-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
            @enderror
        </div>

        <div class="auth-field">
            <label for="reg-pw-confirm" class="auth-label">
                <i class="bi bi-shield-check"></i> Xác nhận
            </label>
            <div class="auth-input-wrap">
                <i class="bi bi-check2-square auth-input-icon"></i>
                <input
                    type="password"
                    id="reg-pw-confirm"
                    name="password_confirmation"
                    class="auth-input-field"
                    placeholder="Nhập lại mật khẩu"
                    autocomplete="new-password"
                    required>
                <button type="button" class="auth-input-toggle" data-toggle-pw="reg-pw-confirm" aria-label="Hiện/ẩn mật khẩu" tabindex="-1">
                    <i class="bi bi-eye"></i>
                </button>
            </div>
        </div>
    </div>

    {{-- Tip --}}
    <div class="auth-tip">
        <strong>Lưu ý:</strong> Sau khi đăng ký, hệ thống sẽ gửi email xác thực trước khi bạn có thể sử dụng đầy đủ chức năng.
    </div>

    {{-- Actions --}}
    <button type="submit" class="auth-btn auth-btn-primary">
        <i class="bi bi-person-check"></i>
        Đăng ký tài khoản
    </button>

    <a href="{{ route('login') }}" class="auth-btn auth-btn-ghost" data-nav>
        <i class="bi bi-arrow-left-circle"></i>
        Quay lại đăng nhập
    </a>

    <p class="auth-footnote">
        Đã có tài khoản? <a href="{{ route('login') }}" data-nav>Đăng nhập tại đây</a>
    </p>
</form>
@endsection