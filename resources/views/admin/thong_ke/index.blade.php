@extends('admin.layout')

@section('title', 'Thống kê')
@section('page-title', 'Thống kê')

@section('content')
<!-- Stats -->
<div class="stat-grid mb-4">
    <div class="stat-card">
        <div class="stat-icon" style="background:#dbeafe;color:#2563eb;"><i class="bi bi-calendar3"></i></div>
        <div class="stat-info">
            <div class="stat-value">{{ $tongSuKien }}</div>
            <div class="stat-label">Tổng sự kiện</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#dcfce7;color:#16a34a;"><i class="bi bi-people-fill"></i></div>
        <div class="stat-info">
            <div class="stat-value">{{ $tongNguoiDung }}</div>
            <div class="stat-label">Người dùng</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#fef3c7;color:#d97706;"><i class="bi bi-clipboard-check-fill"></i></div>
        <div class="stat-info">
            <div class="stat-value">{{ $tongDangKy }}</div>
            <div class="stat-label">Lượt đăng ký</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#fce7f3;color:#db2777;"><i class="bi bi-clock-fill"></i></div>
        <div class="stat-info">
            <div class="stat-value">{{ $suKienSapDienRa }}</div>
            <div class="stat-label">Sắp diễn ra</div>
        </div>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">

<!-- Biểu đồ theo tháng -->
<div class="card">
    <div class="card-header">
        <div class="card-title"><i class="bi bi-bar-chart-fill" style="color:var(--primary)"></i> Sự kiện theo tháng ({{ date('Y') }})</div>
    </div>
    <div class="card-body">
        @php
            $thangData = [];
            for($i = 1; $i <= 12; $i++) $thangData[$i] = 0;
            foreach($suKienTheoThang as $item) $thangData[$item->thang] = $item->so_luong;
            $maxVal = max($thangData) ?: 1;
        @endphp
        <div style="display:flex;align-items:flex-end;gap:6px;height:160px;border-bottom:2px solid var(--border);padding-bottom:4px;">
            @foreach($thangData as $thang => $soLuong)
            <div style="flex:1;display:flex;flex-direction:column;align-items:center;gap:4px;" title="Tháng {{ $thang }}: {{ $soLuong }} sự kiện">
                <div style="font-size:10px;color:var(--text-light);">{{ $soLuong ?: '' }}</div>
                <div style="width:100%;background:linear-gradient(to top,#2563eb,#60a5fa);border-radius:3px 3px 0 0;height:{{ max(4, ($soLuong/$maxVal)*130) }}px;transition:height 0.6s ease;"></div>
            </div>
            @endforeach
        </div>
        <div style="display:flex;gap:6px;margin-top:8px;">
            @foreach(range(1,12) as $i)
            <div style="flex:1;text-align:center;font-size:10px;color:var(--text-light);">T{{ $i }}</div>
            @endforeach
        </div>
    </div>
</div>

<!-- Top sự kiện -->
<div class="card">
    <div class="card-header">
        <div class="card-title"><i class="bi bi-trophy-fill" style="color:#f59e0b"></i> Top sự kiện đăng ký nhiều</div>
    </div>
    <div class="card-body" style="padding:0;">
        @forelse($topSuKien as $i => $sk)
        <div style="padding:12px 20px;display:flex;align-items:center;gap:14px;{{ $i > 0 ? 'border-top:1px solid var(--border)' : '' }}">
            <div style="width:28px;height:28px;border-radius:50%;background:{{ $i==0 ? '#fbbf24':($i==1?'#94a3b8':($i==2?'#cd7c2f':'#e2e8f0')) }};display:flex;align-items:center;justify-content:center;font-weight:800;font-size:12px;color:{{ $i<3?'#fff':'var(--text-light)' }};flex-shrink:0;">{{ $i+1 }}</div>
            <div style="flex:1;overflow:hidden;">
                <div style="font-size:13px;font-weight:600;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $sk->ten_su_kien }}</div>
                <div style="font-size:11px;color:var(--text-light);">{{ $sk->dang_ky_count }} người đăng ký</div>
            </div>
        </div>
        @empty
        <div style="text-align:center;padding:30px;color:var(--text-light);">Chưa có dữ liệu</div>
        @endforelse
    </div>
</div>

</div>

<!-- Điểm sinh viên -->
<div class="card mt-4">
    <div class="card-header">
        <div class="card-title"><i class="bi bi-star-fill" style="color:#f59e0b"></i> Bảng xếp hạng điểm</div>
        <a href="{{ route('admin.thong-ke.diem') }}" class="btn btn-secondary btn-sm">Xem đầy đủ</a>
    </div>
    <div class="table-resp">
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
                    <td style="font-weight:700;color:{{ $i<3?'#f59e0b':'var(--text-light)' }};">{{ $i+1 }}</td>
                    <td><strong>{{ $row->ho_ten }}</strong></td>
                    <td style="color:var(--text-light);font-size:13px;">{{ $row->ma_sinh_vien }}</td>
                    <td><span class="badge badge-warning">{{ $row->tong_diem }} điểm</span></td>
                </tr>
                @empty
                <tr><td colspan="4" style="text-align:center;padding:20px;color:var(--text-light);">Chưa có dữ liệu điểm</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
