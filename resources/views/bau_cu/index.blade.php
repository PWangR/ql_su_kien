@extends('layouts.app')
@section('title', 'Bầu cử')

@section('styles')
<style>
    .election-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(340px,1fr)); gap:20px; }
    .election-card { background:#fff; border-radius:14px; border:1px solid #e2e8f0; overflow:hidden; transition:transform 0.2s,box-shadow 0.2s; }
    .election-card:hover { transform:translateY(-3px); box-shadow:0 8px 24px rgba(0,0,0,0.08); }
    .election-card-body { padding:20px; }
    .election-card-title { font-size:17px; font-weight:700; color:#1e293b; margin-bottom:8px; }
    .election-card-desc { font-size:13px; color:#64748b; margin-bottom:14px; line-height:1.5; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden; }
    .election-meta { display:flex; flex-wrap:wrap; gap:12px; font-size:12px; color:#64748b; margin-bottom:14px; }
    .election-meta span { display:flex; align-items:center; gap:4px; }
    .election-stats { display:flex; gap:16px; padding-top:14px; border-top:1px solid #f1f5f9; }
    .election-stat { text-align:center; flex:1; }
    .election-stat-val { font-size:18px; font-weight:800; color:#1e293b; font-family:'Montserrat',sans-serif; }
    .election-stat-label { font-size:11px; color:#94a3b8; font-weight:500; }
    .election-badge { display:inline-flex; padding:3px 10px; border-radius:20px; font-size:11px; font-weight:600; }
    .election-badge.active { background:#dcfce7; color:#15803d; }
    .election-badge.upcoming { background:#fef3c7; color:#92400e; }
    .election-badge.ended { background:#f1f5f9; color:#475569; }
</style>
@endsection

@section('content')
<div style="margin-bottom:24px;">
    <h1 style="font-family:'Montserrat',sans-serif;font-size:24px;font-weight:700;color:#0f172a;">
        <i class="bi bi-clipboard2-check-fill" style="color:#2563eb;"></i> Bầu cử
    </h1>
    <p class="text-muted" style="font-size:14px;">Tham gia bỏ phiếu cho các cuộc bầu cử đang diễn ra.</p>
</div>

@if($bauCus->count())
<div class="election-grid">
    @foreach($bauCus as $bc)
    <a href="{{ route('bau-cu.show', $bc->ma_bau_cu) }}" class="election-card" style="text-decoration:none;color:inherit;">
        <div class="election-card-body">
            <div class="d-flex justify-content-between align-items-center" style="margin-bottom:10px;">
                @php
                    $status = $bc->trang_thai_thuc_te;
                    $badgeClass = match($status) { 'dang_dien_ra'=>'active', 'nhap'=>'upcoming', default=>'ended' };
                @endphp
                <span class="election-badge {{ $badgeClass }}">{{ $bc->trang_thai_label }}</span>
            </div>
            <h3 class="election-card-title">{{ $bc->tieu_de }}</h3>
            @if($bc->mo_ta)
            <p class="election-card-desc">{{ $bc->mo_ta }}</p>
            @endif
            <div class="election-meta">
                <span><i class="bi bi-calendar3"></i> {{ $bc->thoi_gian_bat_dau->format('d/m/Y H:i') }}</span>
                <span><i class="bi bi-arrow-right"></i> {{ $bc->thoi_gian_ket_thuc->format('d/m/Y H:i') }}</span>
            </div>
            <div class="election-stats">
                <div class="election-stat">
                    <div class="election-stat-val">{{ $bc->so_ung_cu_vien }}</div>
                    <div class="election-stat-label">Ứng cử viên</div>
                </div>
                <div class="election-stat">
                    <div class="election-stat-val">{{ $bc->so_da_bo_phieu }}/{{ $bc->so_cu_tri }}</div>
                    <div class="election-stat-label">Đã bỏ phiếu</div>
                </div>
            </div>
        </div>
    </a>
    @endforeach
</div>
@else
<div style="text-align:center;padding:60px 20px;background:#fff;border-radius:14px;border:1px solid #e2e8f0;">
    <i class="bi bi-clipboard2-x" style="font-size:48px;color:#cbd5e1;"></i>
    <p style="color:#64748b;margin-top:12px;">Chưa có cuộc bầu cử nào đang diễn ra.</p>
</div>
@endif
@endsection
