@extends('layouts.app')
@section('title', 'Bỏ phiếu thành công')

@section('content')
<div style="max-width:500px;margin:40px auto;text-align:center;">
    <div style="background:#fff;border-radius:14px;border:1px solid #e2e8f0;padding:40px 28px;">
        <div style="width:80px;height:80px;background:linear-gradient(135deg,#22c55e,#86efac);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 20px;">
            <i class="bi bi-check-lg" style="font-size:40px;color:#fff;"></i>
        </div>
        <h1 style="font-size:24px;font-weight:700;color:#15803d;margin-bottom:8px;">Bỏ phiếu thành công!</h1>
        <p style="color:#64748b;font-size:14px;margin-bottom:8px;">
            Phiếu bầu của bạn đã được ghi nhận cho cuộc bầu cử:
        </p>
        <p style="font-size:16px;font-weight:600;color:#1e293b;margin-bottom:24px;">
            {{ $bauCu->tieu_de }}
        </p>

        <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:10px;padding:14px;margin-bottom:24px;text-align:left;">
            <p style="font-size:13px;color:#15803d;display:flex;align-items:center;gap:6px;">
                <i class="bi bi-shield-fill-check"></i>
                Phiếu bầu được lưu ẩn danh. Không ai có thể biết bạn đã bầu cho ai.
            </p>
        </div>

        <div style="display:flex;gap:10px;justify-content:center;">
            <a href="{{ route('bau-cu.show', $bauCu->ma_bau_cu) }}"
               style="padding:10px 20px;background:#2563eb;color:#fff;border-radius:8px;text-decoration:none;font-weight:600;font-size:14px;">
                Xem cuộc bầu cử
            </a>
            <a href="{{ route('bau-cu.index') }}"
               style="padding:10px 20px;background:#f1f5f9;color:#475569;border-radius:8px;text-decoration:none;font-weight:600;font-size:14px;border:1px solid #e2e8f0;">
                Tất cả bầu cử
            </a>
        </div>
    </div>
</div>
@endsection
