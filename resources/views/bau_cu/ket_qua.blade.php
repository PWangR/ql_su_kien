@extends('layouts.app')
@section('title', 'Kết quả bầu cử')

@section('styles')
<style>
.live-bar-wrap { background:var(--bg-alt); border:1px solid var(--border-light); height:24px; position:relative; overflow:hidden; }
.live-bar-fill { height:100%; transition:width 0.6s; background:var(--accent); }
.live-bar-text { position:absolute; left:50%; top:50%; transform:translate(-50%,-50%); font-size:0.6875rem; font-weight:700; color:#fff; }
</style>
@endsection

@section('content')
<div style="max-width:700px;margin:0 auto;">
    <div style="margin-bottom:var(--space-md);">
        <a href="{{ route('bau-cu.show', $bauCu->ma_bau_cu) }}" style="color:var(--accent);font-size:0.8125rem;">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>
    </div>

    <div class="card" style="margin-bottom:var(--space-lg);">
        <div class="card-body">
            <h1 style="font-size:1.35rem;margin-bottom:4px;">Kết quả bầu cử</h1>
            <p class="text-muted" style="margin-bottom:var(--space-md);">{{ $bauCu->tieu_de }}</p>
            <div style="display:flex;gap:var(--space-xl);">
                <div style="text-align:center;">
                    <p style="font-family:var(--font-serif);font-size:1.75rem;font-weight:700;color:var(--accent);" id="totalVoted">{{ $soVoted }}</p>
                    <p class="text-xs text-muted uppercase">Đã bỏ phiếu</p>
                </div>
                <div style="text-align:center;">
                    <p style="font-family:var(--font-serif);font-size:1.75rem;font-weight:700;color:var(--text-muted);">{{ $soCuTri }}</p>
                    <p class="text-xs text-muted uppercase">Tổng cử tri</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card" id="resultsBox">
        <div class="card-body">
            @if($ketQua->count())
            @php $maxPhieu = $ketQua->max('so_phieu') ?: 1; @endphp
            @foreach($ketQua as $ucv)
            @php $pct = $soVoted > 0 ? round($ucv->so_phieu / $soVoted * 100, 1) : 0; @endphp
            <div style="margin-bottom:14px;" data-ucv="{{ $ucv->ma_ung_cu_vien }}">
                <div style="display:flex;justify-content:space-between;font-size:0.8125rem;margin-bottom:4px;">
                    <span style="font-weight:600;">{{ $ucv->ho_ten }} <span class="text-muted">({{ $ucv->lop }})</span></span>
                    <span class="text-muted vote-info">{{ $ucv->so_phieu }} phiếu ({{ $pct }}%)</span>
                </div>
                <div class="live-bar-wrap">
                    <div class="live-bar-fill" style="width:{{ round($ucv->so_phieu / $maxPhieu * 100) }}%"></div>
                    @if($ucv->so_phieu > 0)<span class="live-bar-text">{{ $ucv->so_phieu }}</span>@endif
                </div>
            </div>
            @endforeach
            @else
            <p class="text-muted" style="text-align:center;padding:30px;">Chưa có dữ liệu.</p>
            @endif

            <p class="text-xs text-muted" style="text-align:center;margin-top:var(--space-lg);">Tự động cập nhật mỗi 10 giây</p>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
setInterval(function() {
    fetch("{{ route('api.bau-cu.ket-qua', $bauCu->ma_bau_cu) }}")
        .then(r => r.json())
        .then(data => {
            document.getElementById('totalVoted').textContent = data.so_da_bo_phieu;
        })
        .catch(() => {});
}, 10000);
</script>
@endsection
