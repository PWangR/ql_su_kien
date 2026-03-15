@extends('layouts.app')
@section('title', $bauCu->tieu_de)

@section('styles')
<style>
    .candidate-list { display:flex; flex-direction:column; gap:12px; }
    .candidate-item { display:flex; align-items:flex-start; gap:14px; padding:16px; background:#fff; border:1px solid #e2e8f0; border-radius:12px; transition:all 0.2s; }
    .candidate-item:hover { border-color:#bfdbfe; background:#f8fafc; }
    .candidate-num { width:36px; height:36px; background:#dbeafe; color:#2563eb; border-radius:50%; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:14px; flex-shrink:0; }
    .candidate-info h4 { font-size:15px; font-weight:600; color:#1e293b; margin-bottom:4px; }
    .candidate-info p { font-size:13px; color:#64748b; }
    .result-bar-wrap { background:#e2e8f0; border-radius:8px; height:24px; position:relative; overflow:hidden; }
    .result-bar-fill { height:100%; border-radius:8px; transition:width 0.5s; background:linear-gradient(90deg,#2563eb,#60a5fa); }
    .result-bar-text { position:absolute; left:50%; top:50%; transform:translate(-50%,-50%); font-size:11px; font-weight:700; color:#fff; text-shadow:0 1px 2px rgba(0,0,0,0.3); }
</style>
@endsection

@section('content')
<div style="margin-bottom:16px;">
    <a href="{{ route('bau-cu.index') }}" style="color:#2563eb;text-decoration:none;font-size:13px;">
        <i class="bi bi-arrow-left"></i> Tất cả cuộc bầu cử
    </a>
</div>

{{-- Info Card --}}
<div style="background:#fff;border-radius:14px;border:1px solid #e2e8f0;padding:24px;margin-bottom:20px;">
    <h1 style="font-size:22px;font-weight:700;color:#0f172a;margin-bottom:8px;">{{ $bauCu->tieu_de }}</h1>
    @if($bauCu->mo_ta)
    <p style="color:#64748b;font-size:14px;margin-bottom:16px;line-height:1.6;">{!! nl2br(e($bauCu->mo_ta)) !!}</p>
    @endif
    <div style="display:flex;flex-wrap:wrap;gap:16px;font-size:13px;color:#64748b;margin-bottom:16px;">
        <span><i class="bi bi-calendar3"></i> {{ $bauCu->thoi_gian_bat_dau->format('d/m/Y H:i') }} → {{ $bauCu->thoi_gian_ket_thuc->format('d/m/Y H:i') }}</span>
        <span><i class="bi bi-check2-square"></i> Chọn {{ $bauCu->so_chon_toi_thieu }} – {{ $bauCu->so_chon_toi_da }} ứng cử viên</span>
    </div>

    @php $status = $bauCu->trang_thai_thuc_te; @endphp
    @if($status === 'dang_dien_ra')
        @if($daBoPhieu)
        <div style="background:#f0fdf4;color:#15803d;padding:12px 18px;border-radius:10px;font-size:14px;display:flex;align-items:center;gap:8px;">
            <i class="bi bi-check-circle-fill"></i> Bạn đã bỏ phiếu thành công cho cuộc bầu cử này.
        </div>
        @elseif($laCuTri)
        <a href="{{ route('bo-phieu.ballot', $bauCu->ma_bau_cu) }}" style="display:inline-flex;align-items:center;gap:8px;padding:12px 24px;background:#2563eb;color:#fff;border-radius:10px;text-decoration:none;font-weight:600;font-size:15px;transition:background 0.2s;">
            <i class="bi bi-clipboard2-check-fill"></i> Bỏ phiếu ngay
        </a>
        @else
        <div style="background:#fef2f2;color:#b91c1c;padding:12px 18px;border-radius:10px;font-size:14px;display:flex;align-items:center;gap:8px;">
            <i class="bi bi-exclamation-circle-fill"></i> Bạn không có trong danh sách cử tri.
        </div>
        @endif
    @elseif($status === 'hoan_thanh')
    <div style="background:#f1f5f9;color:#475569;padding:12px 18px;border-radius:10px;font-size:14px;">
        Cuộc bầu cử đã kết thúc.
    </div>
    @else
    <div style="background:#fef3c7;color:#92400e;padding:12px 18px;border-radius:10px;font-size:14px;">
        Cuộc bầu cử chưa bắt đầu.
    </div>
    @endif
</div>

{{-- Candidate List --}}
<div style="background:#fff;border-radius:14px;border:1px solid #e2e8f0;margin-bottom:20px;">
    <div style="padding:16px 20px;border-bottom:1px solid #e2e8f0;">
        <h2 style="font-size:17px;font-weight:700;color:#1e293b;">Danh sách ứng cử viên</h2>
    </div>
    <div style="padding:16px 20px;">
        <div class="candidate-list">
            @foreach($ungCuViens as $i => $ucv)
            <div class="candidate-item">
                <span class="candidate-num">{{ $i + 1 }}</span>
                <div class="candidate-info" style="flex:1;">
                    <h4>{{ $ucv->ho_ten }}</h4>
                    <p>
                        Lớp: {{ $ucv->lop }} | MSSV: {{ $ucv->ma_sinh_vien }}
                        @if($ucv->diem_trung_binh) | ĐTB: {{ number_format($ucv->diem_trung_binh, 2) }} @endif
                        @if($ucv->diem_ren_luyen) | ĐRL: {{ number_format($ucv->diem_ren_luyen, 1) }} @endif
                    </p>
                    @if($ucv->gioi_thieu)
                    <p style="margin-top:6px;">{{ $ucv->gioi_thieu }}</p>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Results --}}
@if($bauCu->hien_thi_ket_qua && $ketQua)
<div style="background:#fff;border-radius:14px;border:1px solid #e2e8f0;margin-bottom:20px;">
    <div style="padding:16px 20px;border-bottom:1px solid #e2e8f0;display:flex;justify-content:space-between;align-items:center;">
        <h2 style="font-size:17px;font-weight:700;color:#1e293b;">Kết quả bình chọn</h2>
        <a href="{{ route('bau-cu.ket-qua', $bauCu->ma_bau_cu) }}" style="color:#2563eb;text-decoration:none;font-size:13px;font-weight:600;">
            Xem realtime →
        </a>
    </div>
    <div style="padding:20px;">
        @php $maxPhieu = $ketQua->max('so_phieu') ?: 1; @endphp
        @foreach($ketQua as $ucv)
        @php $pct = $soVoted > 0 ? round($ucv->so_phieu / $soVoted * 100, 1) : 0; @endphp
        <div style="margin-bottom:12px;">
            <div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:4px;">
                <span style="font-weight:600;">{{ $ucv->ho_ten }}</span>
                <span style="color:#64748b;">{{ $ucv->so_phieu }} phiếu ({{ $pct }}%)</span>
            </div>
            <div class="result-bar-wrap">
                <div class="result-bar-fill" style="width:{{ round($ucv->so_phieu / $maxPhieu * 100) }}%"></div>
            </div>
        </div>
        @endforeach
        <p style="font-size:13px;color:#94a3b8;margin-top:14px;">Đã bỏ phiếu: {{ $soVoted }}/{{ $soCuTri }}</p>
    </div>
</div>
@endif
@endsection
