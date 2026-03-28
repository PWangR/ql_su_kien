@extends('layouts.app')
@section('title', $bauCu->tieu_de)

@section('styles')
<style>
.candidate-item { display:flex; align-items:flex-start; gap:14px; padding:var(--space-md); border:1px solid var(--border); border-radius:var(--border-radius); margin-bottom:8px; transition:border-color 0.2s; }
.candidate-item:hover { border-color:var(--accent); }
.candidate-num { width:32px; height:32px; border:1.5px solid var(--accent); border-radius:50%; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:0.8125rem; color:var(--accent); flex-shrink:0; }
.result-bar-wrap { background:var(--bg-alt); border:1px solid var(--border-light); height:24px; position:relative; overflow:hidden; }
.result-bar-fill { height:100%; transition:width 0.5s; background:var(--accent); }
.result-bar-text { position:absolute; left:50%; top:50%; transform:translate(-50%,-50%); font-size:0.6875rem; font-weight:700; color:#fff; }
</style>
@endsection

@section('content')
<div style="margin-bottom:var(--space-md);">
    <a href="{{ route('bau-cu.index') }}" style="color:var(--accent);font-size:0.8125rem;">
        <i class="bi bi-arrow-left"></i> Tất cả cuộc bầu cử
    </a>
</div>

{{-- Info Card --}}
<div class="card" style="margin-bottom:var(--space-lg);">
    <div class="card-body">
        <h1 style="font-size:1.35rem;margin-bottom:8px;">{{ $bauCu->tieu_de }}</h1>
        @if($bauCu->mo_ta)
        <p class="text-light" style="font-size:0.875rem;margin-bottom:var(--space-md);line-height:1.6;">{!! nl2br(e($bauCu->mo_ta)) !!}</p>
        @endif
        <div style="display:flex;flex-wrap:wrap;gap:var(--space-md);font-size:0.8125rem;color:var(--text-muted);margin-bottom:var(--space-md);">
            <span><i class="bi bi-calendar3"></i> {{ $bauCu->thoi_gian_bat_dau->format('d/m/Y H:i') }} → {{ $bauCu->thoi_gian_ket_thuc->format('d/m/Y H:i') }}</span>
            <span><i class="bi bi-check2-square"></i> Chọn {{ $bauCu->so_chon_toi_thieu }} – {{ $bauCu->so_chon_toi_da }} ứng cử viên</span>
        </div>

        @php $status = $bauCu->trang_thai_thuc_te; @endphp
        @if($status === 'dang_dien_ra')
            @if($daBoPhieu)
            <div class="alert alert-success"><i class="bi bi-check-circle"></i> Bạn đã bỏ phiếu thành công cho cuộc bầu cử này.</div>
            @elseif($laCuTri)
            <a href="{{ route('bo-phieu.ballot', $bauCu->ma_bau_cu) }}" class="btn btn-primary">
                <i class="bi bi-clipboard2-check"></i> Bỏ phiếu ngay
            </a>
            @else
            <div class="alert alert-error"><i class="bi bi-exclamation-circle"></i> Bạn không có trong danh sách cử tri.</div>
            @endif
        @elseif($status === 'hoan_thanh')
        <div class="alert alert-info"><i class="bi bi-info-circle"></i> Cuộc bầu cử đã kết thúc.</div>
        @else
        <div class="alert alert-warning"><i class="bi bi-exclamation-triangle"></i> Cuộc bầu cử chưa bắt đầu.</div>
        @endif
    </div>
</div>

{{-- Candidate List --}}
<div class="card" style="margin-bottom:var(--space-lg);">
    <div class="card-header">
        <div class="card-title"><i class="bi bi-people"></i> Danh sách ứng cử viên</div>
    </div>
    <div class="card-body">
        @foreach($ungCuViens as $i => $ucv)
        <div class="candidate-item">
            <span class="candidate-num">{{ $i + 1 }}</span>
            <div style="flex:1;">
                <h4 style="font-size:0.9375rem;font-weight:600;margin-bottom:4px;">{{ $ucv->ho_ten }}</h4>
                <p class="text-sm text-muted">
                    Lớp: {{ $ucv->lop }} | MSSV: {{ $ucv->ma_sinh_vien }}
                    @if($ucv->diem_trung_binh) | ĐTB: {{ number_format($ucv->diem_trung_binh, 2) }} @endif
                    @if($ucv->diem_ren_luyen) | ĐRL: {{ number_format($ucv->diem_ren_luyen, 1) }} @endif
                </p>
                @if($ucv->gioi_thieu)
                <p class="text-sm text-light" style="margin-top:4px;">{{ $ucv->gioi_thieu }}</p>
                @endif
            </div>
        </div>
        @endforeach
    </div>
</div>

{{-- Results --}}
@if($bauCu->hien_thi_ket_qua && $ketQua)
<div class="card" style="margin-bottom:var(--space-lg);">
    <div class="card-header">
        <div class="card-title"><i class="bi bi-bar-chart"></i> Kết quả bình chọn</div>
        <a href="{{ route('bau-cu.ket-qua', $bauCu->ma_bau_cu) }}" class="btn btn-outline btn-sm">Xem realtime →</a>
    </div>
    <div class="card-body">
        @php $maxPhieu = $ketQua->max('so_phieu') ?: 1; @endphp
        @foreach($ketQua as $ucv)
        @php $pct = $soVoted > 0 ? round($ucv->so_phieu / $soVoted * 100, 1) : 0; @endphp
        <div style="margin-bottom:12px;">
            <div style="display:flex;justify-content:space-between;font-size:0.8125rem;margin-bottom:4px;">
                <span style="font-weight:600;">{{ $ucv->ho_ten }}</span>
                <span class="text-muted">{{ $ucv->so_phieu }} phiếu ({{ $pct }}%)</span>
            </div>
            <div class="result-bar-wrap">
                <div class="result-bar-fill" style="width:{{ round($ucv->so_phieu / $maxPhieu * 100) }}%"></div>
                @if($ucv->so_phieu > 0)<span class="result-bar-text">{{ $ucv->so_phieu }}</span>@endif
            </div>
        </div>
        @endforeach
        <p class="text-sm text-muted" style="margin-top:14px;">Đã bỏ phiếu: {{ $soVoted }}/{{ $soCuTri }}</p>
    </div>
</div>
@endif
@endsection
