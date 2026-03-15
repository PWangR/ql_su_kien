@extends('admin.layout')
@section('title', 'Kết quả bầu cử')
@section('page-title', 'Kết quả bầu cử')

@section('styles')
<style>
    .result-bar-wrap { background:#e2e8f0; border-radius:8px; height:28px; position:relative; overflow:hidden; }
    .result-bar-fill { height:100%; border-radius:8px; transition:width 0.6s; background:linear-gradient(90deg,#2563eb,#60a5fa); }
    .result-bar-text { position:absolute; left:50%; top:50%; transform:translate(-50%,-50%); font-size:12px; font-weight:700; color:#fff; text-shadow:0 1px 2px rgba(0,0,0,0.3); }
</style>
@endsection

@section('content')
<div class="mb-3">
    <a href="{{ route('admin.bau-cu.show', $bauCu->ma_bau_cu) }}" style="color:var(--primary);text-decoration:none;font-size:13px;">
        <i class="bi bi-arrow-left"></i> {{ $bauCu->tieu_de }}
    </a>
</div>

<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-icon" style="background:#dbeafe;color:#2563eb;"><i class="bi bi-people-fill"></i></div>
        <div class="stat-info">
            <div class="stat-value">{{ $soCuTri }}</div>
            <div class="stat-label">Tổng cử tri</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#dcfce7;color:#15803d;"><i class="bi bi-check-circle-fill"></i></div>
        <div class="stat-info">
            <div class="stat-value">{{ $soVoted }}</div>
            <div class="stat-label">Đã bỏ phiếu</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#fef3c7;color:#92400e;"><i class="bi bi-percent"></i></div>
        <div class="stat-info">
            <div class="stat-value">{{ $soCuTri > 0 ? round($soVoted/$soCuTri*100,1) : 0 }}%</div>
            <div class="stat-label">Tỉ lệ tham gia</div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="bi bi-bar-chart-fill"></i> Kết quả chi tiết</h3>
        <span class="text-muted text-sm" id="lastUpdate">Cập nhật: {{ now()->format('H:i:s') }}</span>
    </div>
    <div class="card-body" id="resultContainer">
        @if($ketQua->count())
        @php $maxPhieu = $ketQua->max('so_phieu') ?: 1; @endphp
        @foreach($ketQua as $ucv)
        @php $pct = $soVoted > 0 ? round($ucv->so_phieu / $soVoted * 100, 1) : 0; @endphp
        <div style="margin-bottom:16px;" data-ucv-id="{{ $ucv->ma_ung_cu_vien }}">
            <div class="d-flex justify-content-between align-items-center" style="margin-bottom:6px;">
                <span style="font-weight:600;">{{ $ucv->ho_ten }} <span class="text-muted text-sm">({{ $ucv->lop }})</span></span>
                <span class="text-sm result-info" style="color:var(--text-light);">{{ $ucv->so_phieu }} phiếu ({{ $pct }}%)</span>
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
        <p class="text-muted" style="text-align:center;padding:30px;">Chưa có dữ liệu.</p>
        @endif
    </div>
</div>

<p class="text-muted text-sm" style="text-align:center;margin-top:16px;">Tự động cập nhật mỗi 10 giây</p>
@endsection

@section('scripts')
<script>
setInterval(function() {
    fetch("{{ route('admin.bau-cu.ket-qua.api', $bauCu->ma_bau_cu) }}")
        .then(r => r.json())
        .then(data => {
            document.getElementById('lastUpdate').textContent = 'Cập nhật: ' + new Date().toLocaleTimeString('vi');
        })
        .catch(() => {});
}, 10000);
</script>
@endsection
