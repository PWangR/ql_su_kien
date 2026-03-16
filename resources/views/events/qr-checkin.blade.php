@extends('layouts.app')

@section('title', 'Điểm danh sự kiện')

@section('content')
<div class="container py-4" style="max-width:820px;">
    <div class="card shadow-sm border-0" style="border-radius:18px; overflow:hidden;">
        <div class="card-body p-4 p-md-5">
            <div class="d-flex align-items-start gap-3 mb-3">
                <div class="rounded-4" style="width:64px;height:64px;background:linear-gradient(135deg,#2563eb,#60a5fa);display:flex;align-items:center;justify-content:center;color:#fff;font-size:26px;font-weight:800;">
                    <i class="bi bi-check2-circle"></i>
                </div>
                <div>
                    <div class="text-uppercase fw-bold text-muted small mb-1">Điểm danh</div>
                    <h2 class="mb-1" style="font-weight:800;">{{ $suKien->ten_su_kien }}</h2>
                    <div class="text-muted">
                        <i class="bi bi-calendar-event me-1"></i> {{ $suKien->thoi_gian_bat_dau?->format('d/m/Y H:i') }}
                        <span class="mx-2">•</span>
                        <i class="bi bi-geo-alt me-1"></i> {{ $suKien->dia_diem ?: 'Đang cập nhật' }}
                    </div>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success d-flex align-items-center gap-2">
                    <i class="bi bi-patch-check-fill"></i> {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-error d-flex align-items-center gap-2">
                    <i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}
                </div>
            @endif

            <div class="p-3 rounded-4" style="background:#f8fafc;border:1px dashed #e2e8f0;">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <div class="text-muted small">Trạng thái</div>
                        <div class="fw-bold">
                            {{ $dangKy->trang_thai_label ?? 'Đã điểm danh' }}
                        </div>
                    </div>
                    <div>
                        <div class="text-muted small">MSSV</div>
                        <div class="fw-bold">{{ auth()->user()->ma_sinh_vien }}</div>
                    </div>
                    <div>
                        <div class="text-muted small">Họ tên</div>
                        <div class="fw-bold">{{ auth()->user()->ho_ten }}</div>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2 mt-4">
                <a href="{{ route('events.show', $suKien->ma_su_kien) }}" class="btn btn-primary">
                    <i class="bi bi-box-arrow-in-up-right me-1"></i> Xem sự kiện
                </a>
                <a href="{{ route('history.index') }}" class="btn btn-secondary">
                    <i class="bi bi-clock-history me-1"></i> Lịch sử của tôi
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
