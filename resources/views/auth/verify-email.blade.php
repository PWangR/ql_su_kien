@extends('layouts.auth')

@section('title', 'Xác thực Email')

{{-- Panel --}}
@section('panel_badge', 'Kích hoạt tài khoản')
@section('panel_heading', 'Một bước nữa để bắt đầu hành trình của bạn.')
@section('panel_desc', 'Chúng tôi đã gửi email xác thực. Nhấp vào liên kết trong hộp thư để kích hoạt tài khoản và tham gia vào hệ thống sự kiện NTU.')

@section('panel_card')
    <div class="auth-panel-checks">
        <div class="auth-panel-check">
            <i class="bi bi-envelope-open"></i>
            <span>Kiểm tra hộp thư đến và thư mục Spam / Junk.</span>
        </div>
        <div class="auth-panel-check">
            <i class="bi bi-clock-history"></i>
            <span>Liên kết xác thực có hiệu lực trong 60 phút kể từ khi gửi.</span>
        </div>
        <div class="auth-panel-check">
            <i class="bi bi-arrow-repeat"></i>
            <span>Nếu không nhận được, bạn có thể yêu cầu gửi lại bất kỳ lúc nào.</span>
        </div>
    </div>
@endsection

@section('form_title', 'Xác thực địa chỉ Email')
@section('form_subtitle', '')

@section('content')
    <div style="margin-top: 10px;">

        {{-- Status icon --}}
        <div style="text-align:center; margin-bottom:28px;">
            <div style="
                width: 72px; height: 72px;
                border-radius: 50%;
                background: linear-gradient(135deg, #dbeafe, #bfdbfe);
                display: inline-flex; align-items: center; justify-content: center;
                font-size: 2rem;
                color: var(--c-brand);
                margin-bottom: 16px;
                box-shadow: 0 0 0 8px #eff6ff;
            ">
                <i class="bi bi-envelope-check"></i>
            </div>
            <p style="font-size:.9rem; color:var(--c-text-2); line-height:1.65;">
                Chúng tôi đã gửi email xác thực đến địa chỉ email của bạn. Vui lòng nhấp vào liên kết trong email để hoàn
                tất đăng ký.
            </p>
        </div>

        {{-- Info panels --}}
        <div style="display:grid; gap:10px; margin-bottom:24px;">
            <div class="auth-alert is-info">
                <i class="bi bi-info-circle-fill"></i>
                <span>Email xác thực đã được gửi đến hộp thư của bạn. Nếu không thấy, hãy kiểm tra thư mục
                    <strong>Spam</strong> hoặc <strong>Junk</strong>.</span>
            </div>
            <div class="auth-alert is-warning">
                <i class="bi bi-clock-fill"></i>
                <span><strong>Lưu ý:</strong> Liên kết xác thực sẽ hết hạn sau <strong>60 phút</strong>. Hãy kiểm tra email
                    ngay.</span>
            </div>
        </div>

        {{-- Resend form --}}
        <form method="POST" action="{{ route('verification.resend') }}" style="margin-bottom:12px;">
            @csrf
            <input type="hidden" name="email" value="{{ auth()->user()->email ?? request()->query('email') }}">
            <button type="submit" class="auth-btn auth-btn-primary">
                <i class="bi bi-arrow-repeat"></i>
                Gửi lại email xác thực
            </button>
        </form>

        {{-- Logout --}}
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="auth-btn auth-btn-ghost">
                <i class="bi bi-box-arrow-left"></i>
                Đăng xuất và dùng tài khoản khác
            </button>
        </form>
    </div>
@endsection