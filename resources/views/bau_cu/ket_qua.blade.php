@extends('layouts.app')
@section('title', 'Kết quả bầu cử')

@section('styles')
<style>
    .live-bar-wrap { background:#e2e8f0; border-radius:8px; height:28px; position:relative; overflow:hidden; }
    .live-bar-fill { height:100%; border-radius:8px; transition:width 0.6s; background:linear-gradient(90deg,#2563eb,#60a5fa); }
    .live-bar-text { position:absolute; left:50%; top:50%; transform:translate(-50%,-50%); font-size:12px; font-weight:700; color:#fff; text-shadow:0 1px 2px rgba(0,0,0,0.3); }
</style>
@endsection

@section('content')
<div style="max-width:700px;margin:0 auto;">
    <div style="margin-bottom:16px;">
        <a href="{{ route('bau-cu.show', $bauCu->ma_bau_cu) }}" style="color:#2563eb;text-decoration:none;font-size:13px;">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>
    </div>

    <div style="background:#fff;border-radius:14px;border:1px solid #e2e8f0;padding:24px;margin-bottom:20px;">
        <h1 style="font-size:22px;font-weight:700;color:#0f172a;margin-bottom:4px;">Kết quả bầu cử</h1>
        <p style="color:#64748b;font-size:14px;margin-bottom:16px;">{{ $bauCu->tieu_de }}</p>
        <div style="display:flex;gap:24px;">
            <div style="text-align:center;">
                <p style="font-size:28px;font-weight:800;color:#2563eb;font-family:'Montserrat',sans-serif;" id="totalVoted">{{ $soVoted }}</p>
                <p style="font-size:12px;color:#94a3b8;">Đã bỏ phiếu</p>
            </div>
            <div style="text-align:center;">
                <p style="font-size:28px;font-weight:800;color:#94a3b8;font-family:'Montserrat',sans-serif;">{{ $soCuTri }}</p>
                <p style="font-size:12px;color:#94a3b8;">Tổng cử tri</p>
            </div>
        </div>
    </div>

    <div style="background:#fff;border-radius:14px;border:1px solid #e2e8f0;padding:24px;" id="resultsBox">
        @if($ketQua->count())
        @php $maxPhieu = $ketQua->max('so_phieu') ?: 1; @endphp
        @foreach($ketQua as $ucv)
        @php $pct = $soVoted > 0 ? round($ucv->so_phieu / $soVoted * 100, 1) : 0; @endphp
        <div style="margin-bottom:14px;" data-ucv="{{ $ucv->ma_ung_cu_vien }}">
            <div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:4px;">
                <span style="font-weight:600;">{{ $ucv->ho_ten }} <span style="color:#94a3b8;">({{ $ucv->lop }})</span></span>
                <span style="color:#64748b;" class="vote-info">{{ $ucv->so_phieu }} phiếu ({{ $pct }}%)</span>
            </div>
            <div class="live-bar-wrap">
                <div class="live-bar-fill" style="width:{{ round($ucv->so_phieu / $maxPhieu * 100) }}%"></div>
                @if($ucv->so_phieu > 0)
                <span class="live-bar-text">{{ $ucv->so_phieu }}</span>
                @endif
            </div>
        </div>
        @endforeach
        @else
        <p style="color:#94a3b8;text-align:center;padding:30px;">Chưa có dữ liệu.</p>
        @endif

        <p style="font-size:12px;color:#94a3b8;text-align:center;margin-top:20px;">Tự động cập nhật mỗi 10 giây</p>
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
