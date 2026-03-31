@extends('layouts.app')

@section('title', 'Hồ sơ cá nhân')

@section('content')
    <div style="max-width:700px;margin:0 auto;">
        <div class="card">

            <!-- Profile Header -->
            <div style="background:var(--accent);padding:var(--space-xl);text-align:center;">
                <div
                    style="width:80px;height:80px;border-radius:50%;border:2px solid rgba(255,255,255,0.3);display:flex;align-items:center;justify-content:center;color:#fff;font-family:var(--font-serif);font-size:2rem;font-weight:700;margin:0 auto var(--space-md);overflow:hidden;background:rgba(255,255,255,0.1);">
                    @if($user->duong_dan_anh)
                        <img src="{{ asset('storage/' . $user->duong_dan_anh) }}"
                            style="width:100%;height:100%;object-fit:cover;">
                    @else
                        {{ mb_substr($user->ho_ten, 0, 1) }}
                    @endif
                </div>
                <h2 style="font-family:var(--font-serif);font-size:1.25rem;font-weight:700;color:#fff;">{{ $user->ho_ten }}
                </h2>
                <p style="color:rgba(255,255,255,0.6);font-size:0.8125rem;margin-top:4px;">{{ $user->email }}</p>
                <span class="badge"
                    style="background:rgba(255,255,255,0.15);color:rgba(255,255,255,0.8);border-color:rgba(255,255,255,0.3);margin-top:8px;">
                    {{ $user->vai_tro === 'admin' ? 'Quản trị viên' : 'Sinh viên' }}
                </span>
            </div>
            <h2 style="font-family:var(--font-serif);font-size:1.25rem;font-weight:700;color:#fff;">{{ $user->ho_ten }}</h2>
            <p style="color:rgba(255,255,255,0.6);font-size:0.8125rem;margin-top:4px;">{{ $user->email }}</p>
            <span class="badge"
                style="background:rgba(255,255,255,0.15);color:rgba(255,255,255,0.8);border-color:rgba(255,255,255,0.3);margin-top:8px;">
                {{ $user->vai_tro === 'admin' ? 'Quản trị viên' : 'Sinh viên' }}
            </span>
        </div>

        <!-- Form -->
        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-error">
                    <i class="bi bi-exclamation-circle"></i>
                    <div>@foreach($errors->all() as $e)<div>• {{ $e }}</div>@endforeach</div>
                </div>
            @endif

            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                @csrf @method('PUT')

                <h3
                    style="font-size:0.9375rem;margin-bottom:var(--space-md);padding-bottom:var(--space-sm);border-bottom:1px solid var(--border);">
                    <i class="bi bi-person" style="color:var(--accent);"></i> Thông tin cá nhân
                </h3>

                <div class="input-grid">
                    <div class="form-group">
                        <label class="form-label">MSSV</label>
                        <input type="text" value="{{ $user->ma_sinh_vien }}" disabled class="form-control"
                            style="opacity:0.6;">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="text" value="{{ $user->email }}" disabled class="form-control" style="opacity:0.6;">
                    </div>
                    <div class="input-grid">
                        <div class="form-group">
                            <label class="form-label">Lớp *</label>
                            <input type="text" name="lop" value="{{ old('lop', $user->lop) }}" required
                                class="form-control @error('lop') is-invalid @enderror" placeholder="VD: 64.CNTT-1">
                            @error('lop')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Họ và tên *</label>
                        <input type="text" name="ho_ten" value="{{ old('ho_ten', $user->ho_ten) }}" required
                            class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Số điện thoại</label>
                        <input type="text" name="so_dien_thoai" value="{{ old('so_dien_thoai', $user->so_dien_thoai) }}"
                            class="form-control">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Ảnh đại diện</label>
                        <input type="file" name="avatar" accept="image/*" class="form-control" style="border-style:dashed;">
                    </div>


                    <h3
                        style="font-size:0.9375rem;margin-top:var(--space-xl);margin-bottom:var(--space-md);padding-bottom:var(--space-sm);border-bottom:1px solid var(--border);">
                        <i class="bi bi-shield-lock" style="color:var(--accent);"></i> Đổi mật khẩu
                        <span class="text-sm text-muted" style="font-weight:400;">(để trống nếu không đổi)</span>
                    </h3>

                    <div class="form-group">
                        < <label class="form-label">Mật khẩu hiện tại</label>
                            <input type="password" name="mat_khau_cu" class="form-control">
                    </div>

                    <div class="input-grid">
                        <div class="form-group">
                            <label class="form-label">Mật khẩu mới</label>
                            <input type="password" name="mat_khau_moi" class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Xác nhận mật khẩu</label>
                            <input type="password" name="mat_khau_moi_confirmation" class="form-control">
                        </div>

                        <div style="margin-top:var(--space-lg);">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Lưu thay đổi
                            </button>
                        </div>
            </form>
        </div>
    </div>
    </div>
@endsection