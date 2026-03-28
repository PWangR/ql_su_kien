@extends('admin.layout')

@section('title', 'Thống kê')
@section('page-title', 'Thống kê')

@section('content')
<!-- Stats -->
<div class="stat-grid" style="margin-bottom:var(--space-xl);">
    <div class="stat-card">
        <div class="stat-icon"><i class="bi bi-calendar3"></i></div>
        <div>
            <div class="stat-value">{{ $tongSuKien }}</div>
            <div class="stat-label">Tổng sự kiện</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon"><i class="bi bi-people"></i></div>
        <div>
            <div class="stat-value">{{ $tongNguoiDung }}</div>
            <div class="stat-label">Người dùng</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon"><i class="bi bi-clipboard-check"></i></div>
        <div>
            <div class="stat-value">{{ $tongDangKy }}</div>
            <div class="stat-label">Lượt đăng ký</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon"><i class="bi bi-clock"></i></div>
        <div>
            <div class="stat-value">{{ $suKienSapDienRa }}</div>
            <div class="stat-label">Sắp diễn ra</div>
        </div>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:var(--space-lg);margin-bottom:var(--space-lg);">

<!-- Biểu đồ theo tháng -->
<div class="card">
    <div class="card-header">
        <div class="card-title"><i class="bi bi-bar-chart"></i> Sự kiện theo tháng ({{ date('Y') }})</div>
    </div>
    <div class="card-body">
        @php
            $thangData = [];
            for($i = 1; $i <= 12; $i++) $thangData[$i] = 0;
            foreach($suKienTheoThang as $item) $thangData[$item->thang] = $item->so_luong;
            $maxVal = max($thangData) ?: 1;
        @endphp
        <div style="display:flex;align-items:flex-end;gap:6px;height:180px;border-bottom:1px solid var(--border);padding-bottom:4px;">
            @foreach($thangData as $thang => $soLuong)
            <div style="flex:1;display:flex;flex-direction:column;align-items:center;gap:4px;" title="Tháng {{ $thang }}: {{ $soLuong }} sự kiện">
                <div class="text-xs text-muted">{{ $soLuong ?: '' }}</div>
                <div style="width:100%;background:var(--accent);height:{{ max(4, ($soLuong/$maxVal)*150) }}px;transition:height 0.6s ease;"></div>
            </div>
            @endforeach
        </div>
        <div style="display:flex;gap:6px;margin-top:8px;">
            @foreach(range(1,12) as $i)
            <div style="flex:1;text-align:center;font-size:0.6875rem;color:var(--text-muted);">T{{ $i }}</div>
            @endforeach
        </div>
    </div>
</div>

<!-- Top sự kiện -->
<div class="card">
    <div class="card-header">
        <div class="card-title"><i class="bi bi-trophy"></i> Top sự kiện đăng ký nhiều</div>
    </div>
    <div class="card-body" style="padding:0;">
        @forelse($topSuKien as $i => $sk)
        <div style="padding:12px 20px;display:flex;align-items:center;gap:14px;{{ $i > 0 ? 'border-top:1px solid var(--border-light)' : '' }}">
            <div style="width:28px;height:28px;border:1px solid var(--border);border-radius:50%;display:flex;align-items:center;justify-content:center;font-family:var(--font-serif);font-weight:700;font-size:0.8125rem;color:{{ $i==0 ? 'var(--warning)' : 'var(--text-muted)' }};flex-shrink:0;">{{ $i+1 }}</div>
            <div style="flex:1;overflow:hidden;">
                <div style="font-size:0.875rem;font-weight:600;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $sk->ten_su_kien }}</div>
                <div class="text-xs text-muted" style="margin-top:2px;">{{ $sk->dang_ky_count }} người đăng ký</div>
            </div>
        </div>
        @empty
        <div style="text-align:center;padding:var(--space-3xl);color:var(--text-muted);">Chưa có dữ liệu</div>
        @endforelse
    </div>
</div>

</div>

<!-- Điểm sinh viên -->
<div class="card">
    <div class="card-header">
        <div class="card-title"><i class="bi bi-star"></i> Bảng xếp hạng điểm</div>
        <a href="{{ route('admin.thong-ke.diem') }}" class="btn btn-outline btn-sm">Xem đầy đủ</a>
    </div>
    <div class="table-responsive">
        <table>
            <thead><tr><th>#</th><th>Họ tên</th><th>MSSV</th><th>Tổng điểm</th></tr></thead>
            <tbody>
                @php
                    $topDiem = \Illuminate\Support\Facades\DB::table('lich_su_diem')
                        ->join('nguoi_dung','lich_su_diem.ma_nguoi_dung','=','nguoi_dung.ma_nguoi_dung')
                        ->select('nguoi_dung.ho_ten','nguoi_dung.ma_sinh_vien',\Illuminate\Support\Facades\DB::raw('SUM(lich_su_diem.diem) as tong_diem'))
                        ->groupBy('lich_su_diem.ma_nguoi_dung','nguoi_dung.ho_ten','nguoi_dung.ma_sinh_vien')
                        ->orderByDesc('tong_diem')->take(5)->get();
                @endphp
                @forelse($topDiem as $i => $row)
                <tr>
                    <td style="font-weight:700;color:{{ $i<3?'var(--warning)':'var(--text-muted)' }};font-family:var(--font-serif);">{{ $i+1 }}</td>
                    <td><strong>{{ $row->ho_ten }}</strong></td>
                    <td class="text-sm text-light">{{ $row->ma_sinh_vien }}</td>
                    <td><span style="font-family:var(--font-serif);font-size:1.125rem;font-weight:700;color:var(--accent);">{{ $row->tong_diem }}</span></td>
                </tr>
                @empty
                <tr><td colspan="4" style="text-align:center;padding:var(--space-2xl);color:var(--text-muted);">Chưa có dữ liệu điểm</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
