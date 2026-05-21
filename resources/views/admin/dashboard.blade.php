@extends('admin.layout')

@section('title', 'Tổng quan hệ thống')
@section('page-title', 'Tổng quan hệ thống')

@section('styles')
<style>
    /* Premium Dashboard Styles */
    .hero-section {
        background: linear-gradient(135deg, var(--primary, #4361ee) 0%, #3a0ca3 100%);
        border-radius: 16px;
        padding: 40px;
        color: white;
        margin-bottom: 30px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(67, 97, 238, 0.2);
    }
    
    .hero-bg-shapes {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 1;
        opacity: 0.1;
        pointer-events: none;
    }
    
    .hero-content {
        position: relative;
        z-index: 2;
        max-width: 600px;
    }
    
    .hero-title {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 10px;
        font-family: var(--font-heading, inherit);
    }
    
    .hero-subtitle {
        font-size: 1.1rem;
        opacity: 0.9;
        margin-bottom: 25px;
        line-height: 1.6;
    }
    
    .hero-actions {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
    }
    
    .hero-btn {
        padding: 10px 24px;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    
    .hero-btn-primary {
        background: white;
        color: #4361ee;
    }
    
    .hero-btn-primary:hover {
        background: #f8f9fa;
        transform: translateY(-2px);
    }
    
    .hero-btn-outline {
        border: 2px solid rgba(255,255,255,0.3);
        color: white;
    }
    
    .hero-btn-outline:hover {
        background: rgba(255,255,255,0.1);
        border-color: white;
    }

    /* Modules Section */
    .section-header {
        display: flex;
        align-items: center;
        margin-bottom: 20px;
        font-weight: 700;
        font-size: 1.25rem;
        color: var(--text, #333);
    }
    
    .section-header i {
        margin-right: 10px;
        color: var(--primary, #4361ee);
    }

    .modules-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .module-card {
        background: var(--card, white);
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        border: 1px solid var(--border-light, #eee);
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        text-decoration: none;
        color: inherit;
        position: relative;
        overflow: hidden;
    }

    .module-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
        border-color: var(--primary, #4361ee);
    }

    .module-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        margin-bottom: 16px;
        transition: all 0.3s ease;
    }

    .module-card:hover .module-icon {
        transform: scale(1.1);
    }

    .module-title {
        font-weight: 600;
        font-size: 1.1rem;
        margin-bottom: 8px;
        color: var(--text, #333);
    }

    .module-desc {
        font-size: 0.875rem;
        color: var(--text-muted, #666);
        line-height: 1.5;
    }

    /* Modifiers for module cards */
    .mod-events .module-icon { background: rgba(67, 97, 238, 0.1); color: #4361ee; }
    .mod-users .module-icon { background: rgba(76, 201, 240, 0.1); color: #4cc9f0; }
    .mod-stats .module-icon { background: rgba(114, 9, 183, 0.1); color: #7209b7; }
    .mod-reports .module-icon { background: rgba(247, 37, 133, 0.1); color: #f72585; }
    .mod-scanner .module-icon { background: rgba(58, 12, 163, 0.1); color: #3a0ca3; }

    /* Animated entry */
    .fade-in-up {
        animation: fadeInUp 0.6s ease-out forwards;
        opacity: 0;
        transform: translateY(20px);
    }

    .delay-1 { animation-delay: 0.1s; }
    .delay-2 { animation-delay: 0.2s; }
    .delay-3 { animation-delay: 0.3s; }

    @keyframes fadeInUp {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @media (max-width: 768px) {
        .charts-row {
            grid-template-columns: 1fr !important;
        }
    }
</style>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
@endsection

@section('content')
@php
use App\Models\SuKien;
use App\Models\User;
use App\Models\DangKy;
use App\Models\LoaiSuKien;
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

// Recent events
$suKienGanDay = SuKien::with('loaiSuKien')->whereNull('deleted_at')->latest()->take(5)->get();

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
@endphp

<!-- Hero Banner -->
<div class="hero-section fade-in-up">
    <svg class="hero-bg-shapes" viewBox="0 0 100 100" preserveAspectRatio="none">
        <circle cx="80" cy="20" r="30" fill="white" />
        <circle cx="95" cy="80" r="40" fill="white" />
        <path d="M 0,100 L 100,100 L 100,60 Z" fill="white" opacity="0.5" />
    </svg>
    <div class="hero-content">
        <h1 class="hero-title">Xin chào, {{ auth()->user()->ho_ten ?? 'Admin' }}! 👋</h1>
        <p class="hero-subtitle">Chào mừng bạn đến với Hệ thống Quản lý Sự kiện. Tại đây, bạn có thể dễ dàng quản lý toàn bộ vòng đời của sự kiện, theo dõi đăng ký, điểm danh và phân tích dữ liệu hiệu quả.</p>
        <div class="hero-actions">
            <a href="{{ route('admin.su-kien.create') }}" class="hero-btn hero-btn-primary">
                <i class="bi bi-plus-circle-fill"></i> Tạo sự kiện mới
            </a>
            <a href="{{ route('home') }}" target="_blank" class="hero-btn hero-btn-outline">
                <i class="bi bi-box-arrow-up-right"></i> Xem trang chủ
            </a>
        </div>
    </div>
</div>

<!-- Quick Stats -->
<div class="section-header fade-in-up delay-1">
    <i class="bi bi-lightning-charge-fill"></i> Hoạt động tổng quan
</div>
<div class="stat-grid fade-in-up delay-1" style="margin-bottom: var(--space-xl, 30px);">
    <div class="stat-card" style="border-left: 4px solid #4361ee;">
        <div class="stat-icon" style="color:#4361ee; background:rgba(67,97,238,0.1);"><i class="bi bi-calendar-event"></i></div>
        <div>
            <div class="stat-value">{{ $tongSuKien }}</div>
            <div class="stat-label">Tổng sự kiện</div>
        </div>
    </div>
    <div class="stat-card" style="border-left: 4px solid #f72585;">
        <div class="stat-icon" style="color:#f72585; background:rgba(247,37,133,0.1);"><i class="bi bi-play-circle"></i></div>
        <div>
            <div class="stat-value">{{ $suKienDangDienRa }}</div>
            <div class="stat-label">Đang diễn ra</div>
        </div>
    </div>
    <div class="stat-card" style="border-left: 4px solid #4cc9f0;">
        <div class="stat-icon" style="color:#4cc9f0; background:rgba(76,201,240,0.1);"><i class="bi bi-people"></i></div>
        <div>
            <div class="stat-value">{{ $tongNguoiDung }}</div>
            <div class="stat-label">Thành viên</div>
        </div>
    </div>
    <div class="stat-card" style="border-left: 4px solid #7209b7;">
        <div class="stat-icon" style="color:#7209b7; background:rgba(114,9,183,0.1);"><i class="bi bi-clipboard-check"></i></div>
        <div>
            <div class="stat-value">{{ $tongDangKy }}</div>
            <div class="stat-label">Lượt đăng ký</div>
        </div>
    </div>
</div>

<!-- Module Shortcuts -->
<div class="section-header fade-in-up delay-2">
    <i class="bi bi-grid-fill"></i> Chức năng hệ thống
</div>
<div class="modules-grid fade-in-up delay-2">
    <a href="{{ route('admin.su-kien.index') }}" class="module-card mod-events">
        <div class="module-icon"><i class="bi bi-calendar3"></i></div>
        <div class="module-title">Quản lý sự kiện</div>
        <div class="module-desc">Theo dõi, chỉnh sửa và quản lý tất cả các sự kiện, trạng thái tổ chức.</div>
    </a>
    <a href="{{ route('admin.nguoi-dung.index') }}" class="module-card mod-users">
        <div class="module-icon"><i class="bi bi-person-lines-fill"></i></div>
        <div class="module-title">Quản lý người dùng</div>
        <div class="module-desc">Kiểm soát danh sách sinh viên, cán bộ, cấp quyền truy cập.</div>
    </a>
    <a href="{{ route('admin.thong-ke.index') }}" class="module-card mod-stats">
        <div class="module-icon"><i class="bi bi-pie-chart-fill"></i></div>
        <div class="module-title">Thống kê chi tiết</div>
        <div class="module-desc">Xem biểu đồ, phân tích dữ liệu đăng ký và điểm sinh viên.</div>
    </a>
    <a href="{{ route('admin.diem-danh.scanner') }}" class="module-card mod-scanner">
        <div class="module-icon"><i class="bi bi-qr-code-scan"></i></div>
        <div class="module-title">Điểm danh QR</div>
        <div class="module-desc">Sử dụng máy ảnh quét mã QR của sinh viên để điểm danh nhanh chóng.</div>
    </a>
</div>

<!-- Charts & Data Row -->
<div class="charts-row fade-in-up delay-3" style="display:grid;grid-template-columns:2fr 1fr;gap:var(--space-lg, 24px);margin-bottom:var(--space-lg, 24px);">
    <!-- Line Chart: Registration trend -->
    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="bi bi-graph-up"></i> Tăng trưởng đăng ký</div>
        </div>
        <div class="card-body">
            <div style="position:relative; height:300px;">
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
            <div style="position:relative; height:300px;">
                <canvas id="categoryChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Recent Events Table -->
<div class="card fade-in-up delay-3" style="margin-bottom:var(--space-lg, 24px);">
    <div class="card-header">
        <div class="card-title"><i class="bi bi-clock-history"></i> Sự kiện mới nhất</div>
        <a href="{{ route('admin.su-kien.index') }}" class="btn btn-outline btn-sm">
            <i class="bi bi-list-ul"></i> Xem tất cả
        </a>
    </div>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Tên sự kiện</th>
                    <th>Thời gian</th>
                    <th>Đăng ký</th>
                    <th>Trạng thái</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($suKienGanDay as $sk)
                <tr>
                    <td>
                        <strong>{{ $sk->ten_su_kien }}</strong>
                        <div class="text-xs text-muted" style="font-size: 0.8rem; margin-top: 4px;">
                            {{ $sk->loaiSuKien->ten_loai ?? 'Không phân loại' }}
                        </div>
                    </td>
                    <td class="text-muted" style="font-size: 0.9rem;">
                        {{ $sk->thoi_gian_bat_dau ? $sk->thoi_gian_bat_dau->format('d/m/Y H:i') : '—' }}
                    </td>
                    <td>
                        <span class="badge" style="background: var(--light, #f1f5f9); color: var(--text, #333);">
                            {{ $sk->so_luong_hien_tai }}/{{ $sk->so_luong_toi_da ?: '∞' }}
                        </span>
                    </td>
                    <td>
                        @php
                        $statusMap = [
                        'sap_to_chuc' => ['class' => 'badge-primary', 'label' => 'Sắp tổ chức', 'bg' => '#4361ee'],
                        'dang_dien_ra' => ['class' => 'badge-success', 'label' => 'Đang diễn ra', 'bg' => '#10b981'],
                        'da_ket_thuc' => ['class' => 'badge-secondary', 'label' => 'Đã kết thúc', 'bg' => '#6b7280'],
                        'huy' => ['class' => 'badge-danger', 'label' => 'Đã hủy', 'bg' => '#ef4444'],
                        ];
                        $c = $statusMap[$sk->trang_thai_thuc_te] ?? $statusMap['da_ket_thuc'];
                        @endphp
                        <span class="badge" style="background-color: {{ $c['bg'] }}; color: white; border: none;">
                            {{ $c['label'] }}
                        </span>
                    </td>
                    <td>
                        <div class="btn-group">
                            <a href="{{ route('admin.su-kien.edit', $sk->ma_su_kien) }}" class="btn btn-sm" style="background: #f1f5f9; border-color: #e2e8f0; color: #475569;" title="Sửa">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="{{ route('admin.su-kien.show', $sk->ma_su_kien) }}" class="btn btn-sm" style="background: #f1f5f9; border-color: #e2e8f0; color: #475569;" title="Xem chi tiết">
                                <i class="bi bi-eye"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align:center;color:var(--text-muted);padding:30px;">Chưa có sự kiện nào trong hệ thống</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Theme colors
    const primary = '#4361ee';
    const accent = '#f72585';
    const info = '#4cc9f0';
    const purple = '#7209b7';
    const secondary = '#3f37c9';
    const success = '#4ade80';

    // Chart defaults
    Chart.defaults.font.family = "'Inter', 'Segoe UI', sans-serif";
    Chart.defaults.font.size = 12;
    Chart.defaults.color = '#6b7280';

    // 1. Line Chart — Registrations by month
    const regChartCanvas = document.getElementById('registrationChart');
    if(regChartCanvas) {
        const regCtx = regChartCanvas.getContext('2d');
        
        // Gradient for line chart
        const gradient = regCtx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(67, 97, 238, 0.4)');
        gradient.addColorStop(1, 'rgba(67, 97, 238, 0.0)');

        new Chart(regCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($dangKyTheoThang->pluck('thang')->map(fn($t) => \Carbon\Carbon::createFromFormat('Y-m', $t)->format('m/Y'))) !!},
                datasets: [{
                    label: 'Lượt đăng ký',
                    data: {!! json_encode($dangKyTheoThang->pluck('so_luong')) !!},
                    borderColor: primary,
                    backgroundColor: gradient,
                    borderWidth: 3,
                    pointBackgroundColor: 'white',
                    pointBorderColor: primary,
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    tension: 0.4,
                    fill: true,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(17, 24, 39, 0.9)',
                        titleFont: { size: 13, family: 'Inter' },
                        bodyFont: { size: 14, weight: 'bold', family: 'Inter' },
                        padding: 12,
                        cornerRadius: 8,
                        displayColors: false
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        border: { display: false }
                    },
                    y: {
                        grid: { color: 'rgba(0,0,0,0.05)', borderDash: [5, 5] },
                        border: { display: false },
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
    }

    // 2. Doughnut Chart — Event categories
    const catChartCanvas = document.getElementById('categoryChart');
    if(catChartCanvas) {
        const catCtx = catChartCanvas.getContext('2d');
        const catColors = [primary, accent, info, purple, secondary, success];
        
        const catLabels = {!! json_encode($suKienTheoLoai->map(fn($s) => $s->loaiSuKien->ten_loai ?? 'Khác')) !!};
        const catData = {!! json_encode($suKienTheoLoai->pluck('so_luong')) !!};
        
        if(catData.length > 0) {
            new Chart(catCtx, {
                type: 'doughnut',
                data: {
                    labels: catLabels,
                    datasets: [{
                        data: catData,
                        backgroundColor: catColors.slice(0, catData.length),
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right',
                            labels: {
                                padding: 20,
                                usePointStyle: true,
                                pointStyle: 'circle',
                                font: { size: 12 }
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(17, 24, 39, 0.9)',
                            padding: 12,
                            cornerRadius: 8
                        }
                    },
                    cutout: '70%',
                }
            });
        } else {
            // Hiển thị text trống nếu không có dữ liệu
            catCtx.font = "14px Inter";
            catCtx.textAlign = "center";
            catCtx.fillStyle = "#9ca3af";
            catCtx.fillText("Chưa có dữ liệu", catChartCanvas.width/2, catChartCanvas.height/2);
        }
    }
</script>
@endsection