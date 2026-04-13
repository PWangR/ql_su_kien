@extends('layouts.auth')

@section('title', 'Đăng xuất')

{{-- Panel --}}
@section('panel_badge', 'Phiên đăng nhập')
@section('panel_heading', 'Hẹn gặp lại bạn lần sau.')
@section('panel_desc', 'Bạn đã đăng xuất thành công. Dữ liệu phiên làm việc của bạn đã được xóa an toàn khỏi thiết bị này.')

@section('panel_card')
<div class="auth-panel-checks">
    <div class="auth-panel-check">
        <i class="bi bi-shield-check"></i>
        <span>Phiên làm việc đã được hủy và dữ liệu đăng nhập được xóa.</span>
    </div>
    <div class="auth-panel-check">
        <i class="bi bi-lock"></i>
        <span>Thông tin tài khoản của bạn vẫn được bảo vệ an toàn.</span>
    </div>
    <div class="auth-panel-check">
        <i class="bi bi-calendar2-check"></i>
        <span>Các đăng ký sự kiện và lịch sử điểm danh vẫn được lưu trữ.</span>
    </div>
</div>
@endsection

@section('form_title', 'Bạn đã đăng xuất')
@section('form_subtitle', '')

@section('content')
<div style="margin-top: 10px;">

    {{-- Success icon --}}
    <div style="text-align:center; margin-bottom:28px;">
        <div style="
            width: 72px; height: 72px;
            border-radius: 50%;
            background: linear-gradient(135deg, #dcfce7, #bbf7d0);
            display: inline-flex; align-items: center; justify-content: center;
            font-size: 2rem;
            color: #16a34a;
            margin-bottom: 16px;
            box-shadow: 0 0 0 8px #f0fdf4;
        ">
            <i class="bi bi-check-circle-fill"></i>
        </div>
        <h3 style="font-size:1.1rem; font-weight:700; color:var(--c-text); margin-bottom:8px;">
            Đăng xuất thành công
        </h3>
        <p style="font-size:.88rem; color:var(--c-text-2); line-height:1.65;">
            Cảm ơn bạn đã sử dụng hệ thống Quản Lý Sự Kiện NTU.<br>
            Hẹn gặp lại tại các sự kiện tiếp theo!
        </p>
    </div>

    {{-- Info banner --}}
    <div class="auth-alert is-success" style="margin-bottom:24px;">
        <i class="bi bi-shield-fill-check"></i>
        <span>Phiên đăng nhập đã được hủy. Thiết bị này không còn truy cập vào tài khoản của bạn.</span>
    </div>

    {{-- Actions --}}
    <div style="display:grid; gap:12px;">
        <a href="{{ route('login') }}" class="auth-btn auth-btn-primary" data-nav>
            <i class="bi bi-box-arrow-in-right"></i>
            Đăng nhập lại
        </a>
        <a href="{{ route('register') }}" class="auth-btn auth-btn-ghost" data-nav>
            <i class="bi bi-person-plus"></i>
            Tạo tài khoản mới
        </a>
    </div>

    <p class="auth-footnote" style="margin-top:20px;">
        Quên mật khẩu?
        <a href="{{ route('password.request') }}" data-nav>Khôi phục ngay</a>
    </p>
</div>
@endsection
