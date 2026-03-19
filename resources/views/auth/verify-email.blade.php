@extends('layouts.app')

@section('title', 'Xác thực Email')

@section('content')
<div style="min-height:100vh;display:flex;align-items:center;justify-content:center;background:#f8fafc;">
    <div style="width:100%;max-width:420px;background:#fff;padding:40px;border-radius:16px;box-shadow:0 4px 20px rgba(0,0,0,0.08);border:1px solid #e2e8f0;">

        <div style="text-align:center;margin-bottom:32px;">
            <i class="bi bi-envelope-check" style="font-size:64px;color:#16a34a;display:block;margin-bottom:16px;"></i>
            <h2 style="font-size:24px;font-weight:800;color:#0f172a;margin-bottom:8px;">Xác thực Email</h2>
            <p style="color:#64748b;font-size:14px;">Vui lòng kiểm tra email của bạn để hoàn tất đăng ký</p>
        </div>

        <div style="background:#dcfce7;border-left:4px solid #16a34a;padding:16px;border-radius:8px;margin-bottom:24px;">
            <p style="color:#15803d;font-size:14px;margin:0;">
                <i class="bi bi-info-circle me-2"></i>
                Chúng tôi đã gửi email xác thực đến địa chỉ email của bạn.
                Nhấp vào liên kết trong email để xác thực tài khoản.
            </p>
        </div>

        <div style="background:#fef3c7;border-left:4px solid #d97706;padding:16px;border-radius:8px;margin-bottom:24px;">
            <p style="color:#b45309;font-size:13px;margin:0;">
                <i class="bi bi-clock me-2"></i>
                <strong>Lưu ý:</strong> Liên kết xác thực sẽ hết hạn sau 1 giờ.
                Nếu bạn không nhận được email, vui lòng kiểm tra thư mục Spam hoặc Junk.
            </p>
        </div>

        <form method="POST" action="{{ route('verification.resend') }}" style="margin-bottom:16px;">
            @csrf
            <input type="hidden" name="email" value="{{ auth()->user()->email ?? request()->query('email') }}">
            <button type="submit" class="btn btn-primary w-100" style="width:100%;padding:12px;font-size:14px;font-weight:600;">
                <i class="bi bi-arrow-repeat me-2"></i> Gửi lại email xác thực
            </button>
        </form>

        <a href="{{ route('logout') }}" class="btn btn-outline-secondary" style="width:100%;padding:12px;font-size:14px;font-weight:600;display:block;text-align:center;text-decoration:none;color:#475569;border:1.5px solid #cbd5e1;border-radius:10px;transition:all 0.2s;">
            <i class="bi bi-box-arrow-left me-2"></i> Đăng xuất
        </a>

        <div style="margin-top:24px;padding-top:24px;border-top:1px solid #e2e8f0;text-align:center;font-size:13px;color:#64748b;">
            <p style="margin:0;">Cần giúp đỡ? <a href="#" style="color:#2563eb;text-decoration:none;">Liên hệ hỗ trợ</a></p>
        </div>
    </div>
</div>
@endsection