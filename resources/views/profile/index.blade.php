@extends('layouts.app')

@section('title', 'Hồ sơ cá nhân')

@section('content')
<div style="max-width:700px;margin:0 auto;">
<div style="background:#fff;border-radius:16px;border:1px solid #e2e8f0;overflow:hidden;">

    <!-- Header hồ sơ -->
    <div style="background:linear-gradient(135deg,#0f172a,#1e3a8a);padding:32px;text-align:center;position:relative;">
        <div style="width:80px;height:80px;border-radius:50%;background:linear-gradient(135deg,#2563eb,#60a5fa);display:flex;align-items:center;justify-content:center;color:#fff;font-size:32px;font-weight:800;margin:0 auto 14px;border:3px solid rgba(255,255,255,0.3);overflow:hidden;">
            @if($user->duong_dan_anh)
            <img src="{{ asset('storage/'.$user->duong_dan_anh) }}" style="width:100%;height:100%;object-fit:cover;">
            @else
            {{ mb_substr($user->ho_ten, 0, 1) }}
            @endif
        </div>
        <h2 style="font-family:'Montserrat',sans-serif;font-size:20px;font-weight:800;color:#fff;">{{ $user->ho_ten }}</h2>
        <p style="color:#93c5fd;font-size:13px;margin-top:4px;">{{ $user->email }}</p>
        <span style="background:rgba(37,99,235,.3);color:#93c5fd;padding:3px 12px;border-radius:20px;font-size:12px;font-weight:600;margin-top:8px;display:inline-block;">
            {{ $user->vaiTro->ten_vai_tro ?? '' }}
        </span>
    </div>

    <!-- Form chỉnh sửa -->
    <div style="padding:28px;">
        @if($errors->any())
        <div style="background:#fef2f2;border:1px solid #fecaca;color:#b91c1c;padding:12px 16px;border-radius:10px;margin-bottom:18px;font-size:14px;">
            <i class="bi bi-exclamation-circle-fill"></i>
            @foreach($errors->all() as $e)<div>• {{ $e }}</div>@endforeach
        </div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
            @csrf @method('PUT')

            <h3 style="font-size:15px;font-weight:700;margin-bottom:16px;padding-bottom:10px;border-bottom:1px solid #e2e8f0;">
                <i class="bi bi-person-fill" style="color:#2563eb;"></i> Thông tin cá nhân
            </h3>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;" class="profile-grid">
                <div style="margin-bottom:16px;">
                    <label style="display:block;font-size:13px;font-weight:600;margin-bottom:6px;">MSSV</label>
                    <input type="text" value="{{ $user->ma_sinh_vien }}" disabled style="display:block;width:100%;padding:10px 14px;border:1.5px solid #e2e8f0;border-radius:8px;font-size:14px;background:#f8fafc;color:#94a3b8;">
                </div>
                <div style="margin-bottom:16px;">
                    <label style="display:block;font-size:13px;font-weight:600;margin-bottom:6px;">Email</label>
                    <input type="text" value="{{ $user->email }}" disabled style="display:block;width:100%;padding:10px 14px;border:1.5px solid #e2e8f0;border-radius:8px;font-size:14px;background:#f8fafc;color:#94a3b8;">
                </div>
            </div>

            <div style="margin-bottom:16px;">
                <label style="display:block;font-size:13px;font-weight:600;margin-bottom:6px;">Họ và tên *</label>
                <input type="text" name="ho_ten" value="{{ old('ho_ten', $user->ho_ten) }}" required
                    style="display:block;width:100%;padding:10px 14px;border:1.5px solid #e2e8f0;border-radius:8px;font-size:14px;background:#f8fafc;outline:none;font-family:'Inter',sans-serif;"
                    onfocus="this.style.borderColor='#2563eb';this.style.background='#fff'"
                    onblur="this.style.borderColor='#e2e8f0';this.style.background='#f8fafc'">
            </div>

            <div style="margin-bottom:16px;">
                <label style="display:block;font-size:13px;font-weight:600;margin-bottom:6px;">Số điện thoại</label>
                <input type="text" name="so_dien_thoai" value="{{ old('so_dien_thoai', $user->so_dien_thoai) }}"
                    style="display:block;width:100%;padding:10px 14px;border:1.5px solid #e2e8f0;border-radius:8px;font-size:14px;background:#f8fafc;outline:none;font-family:'Inter',sans-serif;">
            </div>

            <div style="margin-bottom:20px;">
                <label style="display:block;font-size:13px;font-weight:600;margin-bottom:6px;">Ảnh đại diện</label>
                <input type="file" name="avatar" accept="image/*"
                    style="display:block;width:100%;padding:10px 14px;border:1.5px dashed #e2e8f0;border-radius:8px;font-size:14px;background:#f8fafc;cursor:pointer;">
            </div>

            <h3 style="font-size:15px;font-weight:700;margin-bottom:16px;padding-bottom:10px;border-bottom:1px solid #e2e8f0;margin-top:24px;">
                <i class="bi bi-shield-lock-fill" style="color:#2563eb;"></i> Đổi mật khẩu
                <span style="font-size:12px;font-weight:400;color:#94a3b8;">(để trống nếu không đổi)</span>
            </h3>

            <div style="margin-bottom:16px;">
                <label style="display:block;font-size:13px;font-weight:600;margin-bottom:6px;">Mật khẩu hiện tại</label>
                <input type="password" name="mat_khau_cu"
                    style="display:block;width:100%;padding:10px 14px;border:1.5px solid #e2e8f0;border-radius:8px;font-size:14px;background:#f8fafc;outline:none;font-family:'Inter',sans-serif;">
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                <div style="margin-bottom:16px;">
                    <label style="display:block;font-size:13px;font-weight:600;margin-bottom:6px;">Mật khẩu mới</label>
                    <input type="password" name="mat_khau_moi"
                        style="display:block;width:100%;padding:10px 14px;border:1.5px solid #e2e8f0;border-radius:8px;font-size:14px;background:#f8fafc;outline:none;font-family:'Inter',sans-serif;">
                </div>
                <div style="margin-bottom:16px;">
                    <label style="display:block;font-size:13px;font-weight:600;margin-bottom:6px;">Xác nhận mật khẩu</label>
                    <input type="password" name="mat_khau_moi_confirmation"
                        style="display:block;width:100%;padding:10px 14px;border:1.5px solid #e2e8f0;border-radius:8px;font-size:14px;background:#f8fafc;outline:none;font-family:'Inter',sans-serif;">
                </div>
            </div>

            <div style="margin-top:24px;display:flex;gap:10px;">
                <button type="submit" style="background:linear-gradient(135deg,#2563eb,#3b82f6);color:#fff;border:none;border-radius:10px;padding:12px 28px;font-size:14px;font-weight:700;cursor:pointer;font-family:'Inter',sans-serif;display:flex;align-items:center;gap:8px;box-shadow:0 4px 14px rgba(37,99,235,.3);">
                    <i class="bi bi-check-circle-fill"></i> Lưu thay đổi
                </button>
            </div>
        </form>
    </div>
</div>
</div>
@endsection
