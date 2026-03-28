@extends('layouts.app')
@section('title', 'Bầu cử')

@section('content')
<div style="margin-bottom:var(--space-lg);padding-bottom:var(--space-md);border-bottom:1px solid var(--border);">
    <h1 style="font-size:1.75rem;margin-bottom:4px;">Bầu cử</h1>
    <p class="text-muted">Tham gia bỏ phiếu cho các cuộc bầu cử đang diễn ra.</p>
</div>

@if($bauCus->count())
<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(340px,1fr));gap:var(--space-lg);">
    @foreach($bauCus as $bc)
    <a href="{{ route('bau-cu.show', $bc->ma_bau_cu) }}" class="card" style="text-decoration:none;color:inherit;transition:border-color 0.2s;">
        <div class="card-body">
            <div style="margin-bottom:10px;">
                @php
                    $status = $bc->trang_thai_thuc_te;
                    $badgeMap = [
                        'dang_dien_ra' => 'badge-success',
                        'nhap'         => 'badge-warning',
                    ];
                    $badgeClass = $badgeMap[$status] ?? 'badge-secondary';
                @endphp
                <span class="badge {{ $badgeClass }}">{{ $bc->trang_thai_label }}</span>
            </div>
            <h3 style="font-size:1.05rem;margin-bottom:8px;">{{ $bc->tieu_de }}</h3>
            @if($bc->mo_ta)
            <p class="text-sm text-light" style="margin-bottom:14px;line-height:1.5;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">{{ $bc->mo_ta }}</p>
            @endif
            <div style="display:flex;flex-wrap:wrap;gap:12px;font-size:0.75rem;color:var(--text-muted);margin-bottom:14px;">
                <span style="display:flex;align-items:center;gap:4px;"><i class="bi bi-calendar3"></i> {{ $bc->thoi_gian_bat_dau->format('d/m/Y H:i') }}</span>
                <span style="display:flex;align-items:center;gap:4px;"><i class="bi bi-arrow-right"></i> {{ $bc->thoi_gian_ket_thuc->format('d/m/Y H:i') }}</span>
            </div>
            <div style="display:flex;gap:var(--space-md);padding-top:14px;border-top:1px solid var(--border-light);">
                <div style="text-align:center;flex:1;">
                    <div style="font-family:var(--font-serif);font-size:1.125rem;font-weight:700;">{{ $bc->so_ung_cu_vien }}</div>
                    <div class="text-xs text-muted" style="text-transform:uppercase;letter-spacing:0.05em;">Ứng cử viên</div>
                </div>
                <div style="text-align:center;flex:1;">
                    <div style="font-family:var(--font-serif);font-size:1.125rem;font-weight:700;">{{ $bc->so_da_bo_phieu }}/{{ $bc->so_cu_tri }}</div>
                    <div class="text-xs text-muted" style="text-transform:uppercase;letter-spacing:0.05em;">Đã bỏ phiếu</div>
                </div>
            </div>
        </div>
    </a>
    @endforeach
</div>
@else
<div class="card" style="text-align:center;padding:var(--space-3xl);">
    <i class="bi bi-clipboard2-x" style="font-size:48px;color:var(--border);"></i>
    <p class="text-muted" style="margin-top:12px;">Chưa có cuộc bầu cử nào đang diễn ra.</p>
</div>
@endif
@endsection
