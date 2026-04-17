@extends('admin.layout')

@section('title', 'Thống kê')
@section('page-title', 'Thống kê')

@section('styles')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<style>
    .chart-container {
        position: relative;
        height: 300px;
        margin-bottom: var(--space-lg);
    }
</style>
@endsection

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

    <!-- Biểu đồ theo tháng (Chart.js) -->
    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="bi bi-bar-chart"></i> Sự kiện theo tháng ({{ date('Y') }})</div>
        </div>
        <div class="card-body">
            <div class="chart-container">
                <canvas id="eventMonthlyChart"></canvas>
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
            <thead>
                <tr>
                    <th>#</th>
                    <th>Họ tên</th>
                    <th>MSSV</th>
                    <th>Tổng điểm</th>
                </tr>
            </thead>
            <tbody>
                @php
                $topDiem = \Illuminate\Support\Facades\DB::table('lich_su_diem')
                ->join('nguoi_dung','lich_su_diem.ma_sinh_vien','=','nguoi_dung.ma_sinh_vien')
                ->select('nguoi_dung.ho_ten','nguoi_dung.ma_sinh_vien',\Illuminate\Support\Facades\DB::raw('SUM(lich_su_diem.diem) as tong_diem'))
                ->groupBy('lich_su_diem.ma_sinh_vien','nguoi_dung.ho_ten','nguoi_dung.ma_sinh_vien')
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
                <tr>
                    <td colspan="4" style="text-align:center;padding:var(--space-2xl);color:var(--text-muted);">Chưa có dữ liệu điểm</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Chuẩn bị dữ liệu cho biểu đồ sự kiện theo tháng
    const monthlyData = @json($suKienTheoThang);
    const monthData = {};

    // Khởi tạo tất cả tháng với 0
    for (let i = 1; i <= 12; i++) {
        monthData[i] = 0;
    }

    // Gán dữ liệu thực tế
    monthlyData.forEach(item => {
        monthData[item.thang] = item.so_luong;
    });

    // Tạo biểu đồ cột sự kiện theo tháng
    const chartElement = document.getElementById('eventMonthlyChart');
    if (chartElement && typeof Chart !== 'undefined') {
        const ctx = chartElement.getContext('2d');

        // Lấy CSS variables cho màu sắc
        const primary = getComputedStyle(document.documentElement).getPropertyValue('--primary') || '#3b82f6';
        const accentRGB = getComputedStyle(document.documentElement).getPropertyValue('--accent-rgb') || '139, 92, 246';

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['T1', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'T8', 'T9', 'T10', 'T11', 'T12'],
                datasets: [{
                    label: 'Số sự kiện',
                    data: [
                        monthData[1], monthData[2], monthData[3], monthData[4],
                        monthData[5], monthData[6], monthData[7], monthData[8],
                        monthData[9], monthData[10], monthData[11], monthData[12]
                    ],
                    backgroundColor: `rgba(${accentRGB}, 0.7)`,
                    borderColor: `rgba(${accentRGB}, 1)`,
                    borderWidth: 2,
                    borderRadius: 6,
                    hoverBackgroundColor: `rgba(${accentRGB}, 0.9)`,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        labels: {
                            color: getComputedStyle(document.documentElement).getPropertyValue('--text') || '#333',
                            font: {
                                size: 12,
                                weight: '500'
                            },
                            padding: 15
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            color: getComputedStyle(document.documentElement).getPropertyValue('--text-muted') || '#999',
                            font: {
                                size: 11
                            }
                        },
                        grid: {
                            color: getComputedStyle(document.documentElement).getPropertyValue('--border-light') || '#e5e7eb',
                            drawBorder: false
                        }
                    },
                    x: {
                        ticks: {
                            color: getComputedStyle(document.documentElement).getPropertyValue('--text-muted') || '#999',
                            font: {
                                size: 11
                            }
                        },
                        grid: {
                            display: false,
                            drawBorder: false
                        }
                    }
                }
            }
        });
    }
</script>
@endsection