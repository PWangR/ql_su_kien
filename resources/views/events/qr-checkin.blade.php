@extends('layouts.app')

@section('title', 'Điểm danh sự kiện')

@section('styles')
<style>
    .checkin-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        padding: 32px;
        max-width: 620px;
        margin: 0 auto;
    }

    .checkin-header {
        text-align: center;
        margin-bottom: 32px;
    }

    .checkin-icon {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: linear-gradient(135deg, #22c55e, #86efac);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 36px;
        margin: 0 auto 16px;
    }

    .checkin-status-box {
        background: linear-gradient(135deg, #f0fdf4, #dcfce7);
        border: 1px solid #86efac;
        border-radius: 12px;
        padding: 20px;
        margin: 24px 0;
    }

    .checkin-info {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
        margin: 24px 0;
    }

    .info-block {
        background: #f8fafc;
        border-radius: 10px;
        padding: 16px;
    }

    .info-label {
        font-size: 12px;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        margin-bottom: 6px;
    }

    .info-value {
        font-size: 15px;
        font-weight: 700;
        color: #1e293b;
    }

    .btn-group {
        display: flex;
        gap: 10px;
        margin-top: 28px;
    }

    .btn-checkin {
        background: linear-gradient(135deg, #22c55e, #16a34a);
        color: #fff;
        padding: 12px 24px;
        border-radius: 10px;
        font-weight: 600;
        text-decoration: none;
        text-align: center;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
        flex: 1;
    }

    .btn-checkin:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(34, 197, 94, .3);
    }

    .btn-secondary {
        background: #f1f5f9;
        color: #475569;
        padding: 12px 24px;
        border-radius: 10px;
        font-weight: 600;
        text-decoration: none;
        border: 1.5px solid #e2e8f0;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
        flex: 1;
        text-align: center;
        justify-content: center;
    }

    .btn-secondary:hover {
        background: #e2e8f0;
    }
</style>
@endsection

@section('content')
<div class="checkin-card">
    <!-- Header -->
    <div class="checkin-header">
        <div class="checkin-icon">
            <i class="bi bi-check2-circle"></i>
        </div>
        <h1 style="font-family:'Montserrat',sans-serif;font-size:24px;font-weight:800;color:#15803d;margin-bottom:8px;">
            Điểm danh thành công!
        </h1>
        <p style="color:#64748b;margin:0;">{{ $suKien->ten_su_kien }}</p>
    </div>

    <!-- Event Info -->
    <div style="background:#f8fafc;border-radius:12px;padding:16px;margin-bottom:24px;">
        <div style="display:flex;gap:12px;align-items:flex-start;">
            @if($suKien->anh_su_kien)
            <img src="{{ asset('storage/'.$suKien->anh_su_kien) }}" style="width:64px;height:64px;border-radius:10px;object-fit:cover;">
            @else
            <div style="width:64px;height:64px;border-radius:10px;background:linear-gradient(135deg,#dbeafe,#eff6ff);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i class="bi bi-calendar-event-fill" style="color:#2563eb;font-size:28px;"></i>
            </div>
            @endif
            <div style="flex:1;">
                <div style="font-weight:700;color:#1e293b;margin-bottom:4px;">{{ $suKien->ten_su_kien }}</div>
                <div style="font-size:13px;color:#64748b;margin-bottom:4px;">
                    <i class="bi bi-calendar-event"></i> {{ $suKien->thoi_gian_bat_dau?->format('d/m/Y H:i') }}
                </div>
                <div style="font-size:13px;color:#64748b;">
                    <i class="bi bi-geo-alt"></i> {{ $suKien->dia_diem ?: 'Đang cập nhật' }}
                </div>
            </div>
        </div>
    </div>

    <!-- Status -->
    <div class="checkin-status-box">
        <div style="text-align:center;">
            <i class="bi bi-patch-check-fill" style="color:#15803d;font-size:24px;margin-bottom:8px;display:block;"></i>
            <div style="font-weight:700;color:#15803d;margin-bottom:4px;">Bạn đã được ghi nhận tham gia sự kiện</div>
            <div style="font-size:13px;color:#16a34a;">Điểm danh vào {{ now()->format('d/m/Y H:i') }}</div>
        </div>
    </div>

    <!-- User Info -->
    <div class="checkin-info">
        <div class="info-block">
            <div class="info-label">Mã sinh viên</div>
            <div class="info-value">{{ auth()->user()->ma_sinh_vien }}</div>
        </div>
        <div class="info-block">
            <div class="info-label">Họ tên</div>
            <div class="info-value">{{ auth()->user()->ho_ten }}</div>
        </div>
    </div>

    <!-- Points Info -->
    @if($suKien->diem_cong > 0)
    <div class="info-block">
        <div class="info-label"><i class="bi bi-star-fill" style="color:#f59e0b;"></i> Điểm cộng</div>
        <div class="info-value" style="color:#d97706;">+{{ $suKien->diem_cong }} điểm</div>
    </div>
    @endif

    <!-- Actions -->
    <div class="btn-group">
        <a href="{{ route('events.show', $suKien->ma_su_kien) }}" class="btn-checkin">
            <i class="bi bi-box-arrow-up-right"></i> Xem sự kiện
        </a>
        <a href="{{ route('history.index') }}" class="btn-secondary">
            <i class="bi bi-clock-history"></i> Lịch sử
        </a>
    </div>
</div>
@endsection