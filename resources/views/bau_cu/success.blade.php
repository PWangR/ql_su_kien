@extends('layouts.app')
@section('title', 'Bỏ phiếu thành công')

@section('content')
<div style="max-width:500px;margin:40px auto;text-align:center;">
    <div class="card">
        <div class="card-body" style="padding:var(--space-2xl) var(--space-xl);">
            <div style="width:72px;height:72px;border:2px solid var(--success);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto var(--space-lg);">
                <i class="bi bi-check-lg" style="font-size:36px;color:var(--success);"></i>
            </div>
            <h1 style="font-size:1.5rem;color:var(--success);margin-bottom:8px;">Bỏ phiếu thành công!</h1>
            <p class="text-muted" style="margin-bottom:8px;">
                Phiếu bầu của bạn đã được ghi nhận cho cuộc bầu cử:
            </p>
            <p style="font-weight:600;font-size:1rem;margin-bottom:var(--space-lg);">
                {{ $bauCu->tieu_de }}
            </p>

            <div style="background:var(--success-bg);border:1px solid var(--success);border-radius:var(--border-radius);padding:14px;margin-bottom:var(--space-lg);text-align:left;">
                <p class="text-sm" style="color:var(--success);display:flex;align-items:center;gap:6px;">
                    <i class="bi bi-shield-check"></i>
                    Phiếu bầu được lưu ẩn danh. Không ai có thể biết bạn đã bầu cho ai.
                </p>
            </div>

            <div class="btn-group" style="justify-content:center;">
                <a href="{{ route('bau-cu.show', $bauCu->ma_bau_cu) }}" class="btn btn-primary">Xem cuộc bầu cử</a>
                <a href="{{ route('bau-cu.index') }}" class="btn btn-secondary">Tất cả bầu cử</a>
            </div>
        </div>
    </div>
</div>
@endsection
