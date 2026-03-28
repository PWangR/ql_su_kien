@extends('admin.layout')
@section('title', 'Kết quả bầu cử')
@section('page-title', 'Kết quả bầu cử')

@section('styles')
<style>
.result-bar-wrap { background:var(--bg-alt); border:1px solid var(--border-light); border-radius:12px; height:28px; position:relative; overflow:hidden; }
.result-bar-fill { height:100%; transition:width 0.6s cubic-bezier(0.4, 0, 0.2, 1); background:var(--accent); }
.result-bar-text { position:absolute; left:50%; top:50%; transform:translate(-50%,-50%); font-size:0.75rem; font-weight:700; color:#fff; }
</style>
@endsection

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.bau-cu.show', $bauCu->ma_bau_cu) }}" style="color:var(--accent);text-decoration:none;font-size:0.875rem;">
        <i class="bi bi-arrow-left"></i> {{ $bauCu->tieu_de }}
    </a>
</div>

<div class="stat-grid" style="margin-bottom:var(--space-xl);">
    <div class="stat-card">
        <div class="stat-icon"><i class="bi bi-people"></i></div>
        <div>
            <div class="stat-value">{{ $soCuTri }}</div>
            <div class="stat-label">Tổng cử tri</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon"><i class="bi bi-check-circle"></i></div>
        <div>
            <div class="stat-value">{{ $soVoted }}</div>
            <div class="stat-label">Đã bỏ phiếu</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon"><i class="bi bi-percent"></i></div>
        <div>
            <div class="stat-value">{{ $soCuTri > 0 ? round($soVoted/$soCuTri*100,1) : 0 }}%</div>
            <div class="stat-label">Tỉ lệ tham gia</div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="bi bi-bar-chart"></i> Kết quả chi tiết</h3>
        <span class="text-xs text-muted" id="lastUpdate">Cập nhật: {{ now()->format('H:i:s') }}</span>
    </div>
    <div class="card-body" id="resultContainer">
        @if($ketQua->count())
        @php $maxPhieu = $ketQua->max('so_phieu') ?: 1; @endphp
        @foreach($ketQua as $ucv)
        @php $pct = $soVoted > 0 ? round($ucv->so_phieu / $soVoted * 100, 1) : 0; @endphp
        <div style="margin-bottom:16px;" data-ucv-id="{{ $ucv->ma_ung_cu_vien }}">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px;">
                <span class="font-semibold text-sm">{{ $ucv->ho_ten }} <span class="text-xs text-muted">({{ $ucv->lop }})</span></span>
                <span class="text-xs text-muted result-info">{{ $ucv->so_phieu }} phiếu ({{ $pct }}%)</span>
            </div>
            <div class="result-bar-wrap">
                <div class="result-bar-fill" style="width:{{ $maxPhieu > 0 ? round($ucv->so_phieu / $maxPhieu * 100) : 0 }}%"></div>
                @if($ucv->so_phieu > 0)
                <span class="result-bar-text">{{ $ucv->so_phieu }}</span>
                @endif
            </div>
        </div>
        @endforeach
        @else
        <p class="text-muted" style="text-align:center;padding:var(--space-3xl);">Chưa có dữ liệu.</p>
        @endif
    </div>
</div>

<p class="text-xs text-muted mt-4 text-center">Tự động cập nhật mỗi 10 giây</p>
@endsection

@section('scripts')
<script>
setInterval(function() {
    fetch("{{ route('admin.bau-cu.ket-qua.api', $bauCu->ma_bau_cu) }}")
        .then(r => r.json())
        .then(data => {
            document.getElementById('lastUpdate').textContent = 'Cập nhật: ' + new Date().toLocaleTimeString('vi');
            // Cập nhật DOM tự động có thể chèn logic vào đây sau.
        })
        .catch(() => {});
}, 10000);
</script>
@endsection
