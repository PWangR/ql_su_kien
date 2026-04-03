@extends('admin.layout')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
@php
use App\Models\SuKien;
use App\Models\User;
use App\Models\DangKy;
use App\Models\LoaiSuKien;
use App\Models\LichSuDiem;
use Illuminate\Support\Facades\DB;

// Overall stats
$tongSuKien = SuKien::whereNull('deleted_at')->count();
$tongNguoiDung = User::whereNull('deleted_at')->count();
$tongDangKy = DangKy::whereNull('deleted_at')->count();

// Real-time status
$allEvents = SuKien::whereNull('deleted_at')->get();
$suKienSapDienRa = $allEvents->filter(fn($e) => $e->trang_thai_thuc_te === 'sap_to_chuc')->count();
$suKienDangDienRa = $allEvents->filter(fn($e) => $e->trang_thai_thuc_te === 'dang_dien_ra')->count();
$suKienDaKetThuc = $allEvents->filter(fn($e) => $e->trang_thai_thuc_te === 'da_ket_thuc')->count();

// Recent data
$suKienGanDay = SuKien::with('loaiSuKien')->whereNull('deleted_at')->latest()->take(5)->get();
$dangKyGanDay = DangKy::with(['nguoiDung', 'suKien'])->whereNull('deleted_at')->orderBy('thoi_gian_dang_ky', 'desc')->take(6)->get();

// Chart data: Registrations by month (last 6 months)
$dangKyTheoThang = DangKy::whereNull('deleted_at')
    ->where('thoi_gian_dang_ky', '>=', now()->subMonths(6))
    ->selectRaw("DATE_FORMAT(thoi_gian_dang_ky, '%Y-%m') as thang, COUNT(*) as so_luong")
    ->groupBy('thang')
    ->orderBy('thang')
    ->get();

// Chart data: Event categories
$suKienTheoLoai = SuKien::whereNull('deleted_at')
    ->select('ma_loai_su_kien', DB::raw('COUNT(*) as so_luong'))
    ->groupBy('ma_loai_su_kien')
    ->with('loaiSuKien')
    ->get();

// Chart data: Top students by points
$topSinhVien = LichSuDiem::select('ma_sinh_vien', DB::raw('SUM(diem) as tong_diem'))
    ->groupBy('ma_sinh_vien')
    ->orderByDesc('tong_diem')
    ->take(5)
    ->with('nguoiDung')
    ->get();
@endphp

<!-- Stat Cards -->
<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-icon"><i class="bi bi-calendar3"></i></div>
        <div>
            <div class="stat-value">{{ $tongSuKien }}</div>
            <div class="stat-label">Tổng sự kiện</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon"><i class="bi bi-hourglass-split"></i></div>
        <div>
            <div class="stat-value">{{ $suKienSapDienRa }}</div>
            <div class="stat-label">Sắp diễn ra</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon"><i class="bi bi-play-circle"></i></div>
        <div>
            <div class="stat-value">{{ $suKienDangDienRa }}</div>
            <div class="stat-label">Đang diễn ra</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon"><i class="bi bi-check-circle"></i></div>
        <div>
            <div class="stat-value">{{ $suKienDaKetThuc }}</div>
            <div class="stat-label">Đã kết thúc</div>
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
</div>

<!-- Charts Row -->
<div style="display:grid;grid-template-columns:2fr 1fr;gap:var(--space-lg);margin-bottom:var(--space-lg);">
    <!-- Line Chart: Registration trend -->
    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="bi bi-graph-up"></i> Xu hướng đăng ký theo tháng</div>
        </div>
        <div class="card-body">
            <div class="chart-container">
                <canvas id="registrationChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Doughnut Chart: Event categories -->
    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="bi bi-pie-chart"></i> Phân loại sự kiện</div>
        </div>
        <div class="card-body">
            <div class="chart-container">
                <canvas id="categoryChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Bar Chart: Top students -->
<div class="card" style="margin-bottom:var(--space-lg);">
    <div class="card-header">
        <div class="card-title"><i class="bi bi-bar-chart"></i> Top 5 sinh viên điểm cao nhất</div>
    </div>
    <div class="card-body">
        <div class="chart-container">
            <canvas id="topStudentsChart"></canvas>
        </div>
    </div>
</div>

<!-- Recent Events Table -->
<div class="card" style="margin-bottom:var(--space-lg);">
    <div class="card-header">
        <div class="card-title"><i class="bi bi-calendar3"></i> Sự kiện gần đây</div>
        <a href="{{ route('admin.su-kien.index') }}" class="btn btn-outline btn-sm">
            <i class="bi bi-grid"></i> Xem tất cả
        </a>
    </div>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Tên sự kiện</th>
                    <th>Loại</th>
                    <th>Thời gian</th>
                    <th>Đăng ký</th>
                    <th>Trạng thái</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($suKienGanDay as $sk)
                <tr>
                    <td><strong>{{ $sk->ten_su_kien }}</strong></td>
                    <td>{{ $sk->loaiSuKien->ten_loai ?? '—' }}</td>
                    <td class="text-muted text-sm">
                        {{ $sk->thoi_gian_bat_dau ? $sk->thoi_gian_bat_dau->format('d/m/Y H:i') : '—' }}
                    </td>
                    <td>{{ $sk->so_luong_hien_tai }}/{{ $sk->so_luong_toi_da ?: '∞' }}</td>
                    <td>
                        @php
                        $statusMap = [
                            'sap_to_chuc'  => ['class' => 'badge-primary', 'label' => 'Sắp tổ chức'],
                            'dang_dien_ra' => ['class' => 'badge-success', 'label' => 'Đang diễn ra'],
                            'da_ket_thuc'  => ['class' => 'badge-secondary', 'label' => 'Đã kết thúc'],
                            'huy'          => ['class' => 'badge-danger', 'label' => 'Đã hủy'],
                        ];
                        $c = $statusMap[$sk->trang_thai_thuc_te] ?? $statusMap['da_ket_thuc'];
                        @endphp
                        <span class="badge {{ $c['class'] }}">{{ $c['label'] }}</span>
                    </td>
                    <td>
                        <div class="btn-group">
                            <a href="{{ route('admin.su-kien.edit', $sk->ma_su_kien) }}" class="btn btn-secondary btn-sm">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="{{ route('admin.su-kien.show', $sk->ma_su_kien) }}" class="btn btn-secondary btn-sm">
                                <i class="bi bi-eye"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center;color:var(--text-muted);padding:30px;">Chưa có sự kiện nào</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Recent Registrations -->
<div class="card" style="margin-bottom:var(--space-lg);">
    <div class="card-header">
        <div class="card-title"><i class="bi bi-clipboard-check"></i> Đăng ký gần đây</div>
    </div>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Người dùng</th>
                    <th>Sự kiện</th>
                    <th>Thời gian đăng ký</th>
                    <th>Trạng thái</th>
                </tr>
            </thead>
            <tbody>
                @forelse($dangKyGanDay as $dk)
                <tr>
                    <td>
                        <strong>{{ $dk->nguoiDung->ho_ten ?? '—' }}</strong>
                        <br><small class="text-muted">{{ $dk->nguoiDung->ma_sinh_vien ?? '—' }}</small>
                    </td>
                    <td>{{ Str::limit($dk->suKien->ten_su_kien ?? '—', 40) }}</td>
                    <td class="text-muted text-sm">{{ \Carbon\Carbon::parse($dk->thoi_gian_dang_ky)->format('d/m/Y H:i') }}</td>
                    <td>
                        @php
                        $regStatus = [
                            'da_dang_ky'  => ['class' => 'badge-warning', 'label' => 'Đã đăng ký'],
                            'da_tham_gia' => ['class' => 'badge-success', 'label' => 'Đã tham gia'],
                            'vang_mat'    => ['class' => 'badge-danger',  'label' => 'Vắng mặt'],
                            'huy'         => ['class' => 'badge-secondary', 'label' => 'Đã hủy'],
                        ];
                        $rs = $regStatus[$dk->trang_thai_tham_gia] ?? ['class' => 'badge-secondary', 'label' => 'Không rõ'];
                        @endphp
                        <span class="badge {{ $rs['class'] }}">{{ $rs['label'] }}</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align:center;color:var(--text-muted);padding:30px;">Chưa có đăng ký nào</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Quick Links -->
<div class="btn-group">
    <a href="{{ route('admin.su-kien.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Tạo sự kiện mới
    </a>
    <a href="{{ route('admin.nguoi-dung.index') }}" class="btn btn-outline">
        <i class="bi bi-person-plus"></i> Quản lý người dùng
    </a>
    <a href="{{ route('admin.thong-ke.index') }}" class="btn btn-outline">
        <i class="bi bi-bar-chart"></i> Thống kê
    </a>
    <a href="{{ route('admin.media.index') }}" class="btn btn-outline">
        <i class="bi bi-images"></i> Thư viện media
    </a>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
// Monochrome palette
const navy     = '#002060';
const navyL    = '#003399';
const steel    = '#4A5568';
const slate    = '#6B7B8D';
const silver   = '#A0AEC0';
const mist     = '#CBD5E0';
const cream    = '#E8E4DC';

// Chart defaults
Chart.defaults.font.family = "'Source Sans 3', sans-serif";
Chart.defaults.font.size = 12;
Chart.defaults.color = '#6B6B6B';

// 1. Line Chart — Registrations by month
const regCtx = document.getElementById('registrationChart').getContext('2d');
new Chart(regCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode($dangKyTheoThang->pluck('thang')->map(fn($t) => \Carbon\Carbon::createFromFormat('Y-m', $t)->format('m/Y'))) !!},
        datasets: [{
            label: 'Lượt đăng ký',
            data: {!! json_encode($dangKyTheoThang->pluck('so_luong')) !!},
            borderColor: navy,
            backgroundColor: 'rgba(0,32,96,0.08)',
            borderWidth: 2,
            pointBackgroundColor: navy,
            pointRadius: 4,
            tension: 0.3,
            fill: true,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
        },
        scales: {
            x: { grid: { display: false }, border: { color: '#D5D0C8' } },
            y: { grid: { color: '#E8E4DC' }, border: { display: false }, beginAtZero: true }
        }
    }
});

// 2. Doughnut Chart — Event categories
const catCtx = document.getElementById('categoryChart').getContext('2d');
const catColors = [navy, steel, silver, mist, cream, navyL];
new Chart(catCtx, {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($suKienTheoLoai->map(fn($s) => $s->loaiSuKien->ten_loai ?? 'Khác')) !!},
        datasets: [{
            data: {!! json_encode($suKienTheoLoai->pluck('so_luong')) !!},
            backgroundColor: catColors.slice(0, {{ $suKienTheoLoai->count() }}),
            borderColor: '#FFFFFF',
            borderWidth: 2,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: { padding: 16, usePointStyle: true, pointStyle: 'rect' }
            }
        },
        cutout: '60%',
    }
});

// 3. Bar Chart — Top students
const topCtx = document.getElementById('topStudentsChart').getContext('2d');
new Chart(topCtx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($topSinhVien->map(fn($s) => $s->nguoiDung->ho_ten ?? 'N/A')) !!},
        datasets: [{
            label: 'Tổng điểm',
            data: {!! json_encode($topSinhVien->pluck('tong_diem')) !!},
            backgroundColor: navy,
            borderColor: navy,
            borderWidth: 1,
            borderRadius: 2,
            barThickness: 36,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        indexAxis: 'y',
        plugins: {
            legend: { display: false },
        },
        scales: {
            x: { grid: { color: '#E8E4DC' }, border: { display: false }, beginAtZero: true },
            y: { grid: { display: false }, border: { color: '#D5D0C8' } }
        }
    }
});
</script>
@endsection
