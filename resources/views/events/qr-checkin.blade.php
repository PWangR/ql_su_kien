@extends('layouts.app')

@section('title', 'Điểm danh sự kiện')

@section('content')
<div style="max-width:620px;margin:0 auto;">
<div class="card">
    <div class="card-body" style="padding:var(--space-xl);">

        <!-- Header -->
        <div style="text-align:center;margin-bottom:var(--space-xl);">
            <div style="width:72px;height:72px;border-radius:50%;border:2px solid var(--success);display:flex;align-items:center;justify-content:center;color:var(--success);font-size:32px;margin:0 auto var(--space-md);">
                <i class="bi bi-check2-circle"></i>
            </div>
            <h1 style="color:var(--success);font-size:1.5rem;margin-bottom:4px;">Điểm danh thành công!</h1>
            <p class="text-muted">{{ $suKien->ten_su_kien }}</p>
        </div>

        <!-- Event Info -->
        <div style="background:var(--bg-alt);border:1px solid var(--border);border-radius:var(--border-radius-md);padding:var(--space-md);margin-bottom:var(--space-lg);">
            <div style="display:flex;gap:12px;align-items:flex-start;">
                @if($suKien->anh_su_kien)
                <img src="{{ asset('storage/'.$suKien->anh_su_kien) }}" style="width:60px;height:60px;object-fit:cover;border:1px solid var(--border);">
                @else
                <div style="width:60px;height:60px;background:var(--card);border:1px solid var(--border);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="bi bi-calendar-event" style="color:var(--border);font-size:24px;"></i>
                </div>
                @endif
                <div>
                    <div style="font-weight:600;margin-bottom:4px;">{{ $suKien->ten_su_kien }}</div>
                    <div class="text-sm text-muted"><i class="bi bi-calendar-event"></i> {{ $suKien->thoi_gian_bat_dau?->format('d/m/Y H:i') }}</div>
                    <div class="text-sm text-muted"><i class="bi bi-geo-alt"></i> {{ $suKien->dia_diem ?: 'Đang cập nhật' }}</div>
                </div>
            </div>
        </div>

        <!-- Status -->
        <div style="background:var(--success-bg);border:1px solid var(--success);border-radius:var(--border-radius-md);padding:var(--space-md);text-align:center;margin-bottom:var(--space-lg);">
            <i class="bi bi-patch-check" style="color:var(--success);font-size:20px;display:block;margin-bottom:6px;"></i>
            <div style="font-weight:600;color:var(--success);margin-bottom:4px;">Bạn đã được ghi nhận tham gia sự kiện</div>
            <div class="text-sm" style="color:var(--success);">Điểm danh vào {{ now()->format('d/m/Y H:i') }}</div>
        </div>

        <!-- User Info -->
        <div class="input-grid" style="margin-bottom:var(--space-lg);">
            <div style="background:var(--bg-alt);border:1px solid var(--border);border-radius:var(--border-radius);padding:var(--space-md);">
                <div class="info-label">Mã sinh viên</div>
                <div style="font-weight:600;">{{ auth()->user()->ma_sinh_vien }}</div>
            </div>
            <div style="background:var(--bg-alt);border:1px solid var(--border);border-radius:var(--border-radius);padding:var(--space-md);">
                <div class="info-label">Họ tên</div>
                <div style="font-weight:600;">{{ auth()->user()->ho_ten }}</div>
            </div>
        </div>

        @if($suKien->diem_cong > 0)
        <div style="background:var(--bg-alt);border:1px solid var(--border);border-radius:var(--border-radius);padding:var(--space-md);margin-bottom:var(--space-lg);">
            <div class="info-label"><i class="bi bi-star" style="color:var(--warning);"></i> Điểm cộng</div>
            <div style="font-weight:700;color:var(--accent);font-family:var(--font-serif);font-size:1.125rem;">+{{ $suKien->diem_cong }} điểm</div>
        </div>
        @endif

        <!-- Actions -->
        <div class="btn-group" style="margin-top:var(--space-lg);">
            <a href="{{ route('events.show', $suKien->ma_su_kien) }}" class="btn btn-primary" style="flex:1;justify-content:center;">
                <i class="bi bi-box-arrow-up-right"></i> Xem sự kiện
            </a>
            <a href="{{ route('history.index') }}" class="btn btn-outline" style="flex:1;justify-content:center;">
                <i class="bi bi-clock-history"></i> Lịch sử
            </a>
        </div>
    </div>
</div>
</div>
@endsection