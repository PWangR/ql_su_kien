@extends('admin.layout')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('styles')
<style>
.chart-bar {
    background: linear-gradient(to top, var(--primary), #60a5fa);
    border-radius: 4px 4px 0 0;
    transition: height 0.6s ease;
}

.recent-table td { font-size: 13px; }
.trang-thai-dot { display: inline-block; width: 8px; height: 8px; border-radius: 50%; margin-right: 4px; }
</style>
@endsection

@section('content')
@php
    use App\Models\SuKien;
    use App\Models\User;
    use App\Models\DangKy;
    use Illuminate\Support\Facades\DB;

    $tongSuKien      = SuKien::whereNull('deleted_at')->count();
    $tongNguoiDung   = User::whereNull('deleted_at')->count();
    $tongDangKy      = DangKy::whereNull('deleted_at')->count();
    $suKienSapDienRa = SuKien::where('trang_thai','sap_to_chuc')->whereNull('deleted_at')->count();
    $suKienGanDay    = SuKien::with('loaiSuKien')->whereNull('deleted_at')->latest()->take(5)->get();
@endphp

<!-- Stat cards -->
<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-icon" style="background:#dbeafe;color:#2563eb;">
            <i class="bi bi-calendar3"></i>
        </div>
        <div class="stat-info">
            <div class="stat-value">{{ $tongSuKien }}</div>
            <div class="stat-label">Tổng sự kiện</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#dcfce7;color:#16a34a;">
            <i class="bi bi-people-fill"></i>
        </div>
        <div class="stat-info">
            <div class="stat-value">{{ $tongNguoiDung }}</div>
            <div class="stat-label">Người dùng</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#fef3c7;color:#d97706;">
            <i class="bi bi-clipboard-check-fill"></i>
        </div>
        <div class="stat-info">
            <div class="stat-value">{{ $tongDangKy }}</div>
            <div class="stat-label">Lượt đăng ký</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#fce7f3;color:#db2777;">
            <i class="bi bi-clock-fill"></i>
        </div>
        <div class="stat-info">
            <div class="stat-value">{{ $suKienSapDienRa }}</div>
            <div class="stat-label">Sắp diễn ra</div>
        </div>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">

<!-- Sự kiện gần đây -->
<div class="card" style="grid-column:1/-1">
    <div class="card-header">
        <div class="card-title"><i class="bi bi-calendar3" style="color:var(--primary)"></i> Sự kiện gần đây</div>
        <a href="{{ route('admin.su-kien.index') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-grid"></i> Xem tất cả
        </a>
    </div>
    <div class="table-resp">
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
                    <td style="color:var(--text-light);font-size:13px;">
                        {{ $sk->thoi_gian_bat_dau ? $sk->thoi_gian_bat_dau->format('d/m/Y H:i') : '—' }}
                    </td>
                    <td>{{ $sk->so_luong_hien_tai }}/{{ $sk->so_luong_toi_da ?: '∞' }}</td>
                    <td>
                        @php
                            $colorMap = [
                                'sap_to_chuc'  => ['dot' => '#2563eb', 'bg' => '#dbeafe', 'text' => '#1d4ed8', 'label' => 'Sắp tổ chức'],
                                'dang_dien_ra' => ['dot' => '#16a34a', 'bg' => '#dcfce7', 'text' => '#15803d', 'label' => 'Đang diễn ra'],
                                'da_ket_thuc'  => ['dot' => '#64748b', 'bg' => '#f1f5f9', 'text' => '#475569', 'label' => 'Đã kết thúc'],
                                'huy'          => ['dot' => '#ef4444', 'bg' => '#fee2e2', 'text' => '#b91c1c', 'label' => 'Đã hủy'],
                            ];
                            $c = $colorMap[$sk->trang_thai] ?? $colorMap['da_ket_thuc'];
                        @endphp
                        <span class="badge" style="background:{{ $c['bg'] }};color:{{ $c['text'] }};">
                            <span class="trang-thai-dot" style="background:{{ $c['dot'] }}"></span>{{ $c['label'] }}
                        </span>
                    </td>
                    <td>
                        <div style="display:flex;gap:6px;">
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
                <tr><td colspan="6" style="text-align:center;color:var(--text-light);padding:30px;">Chưa có sự kiện nào</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

</div>

<!-- Quick Links -->
<div style="display:flex;flex-wrap:wrap;gap:12px;margin-top:20px;">
    <a href="{{ route('admin.su-kien.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle-fill"></i> Tạo sự kiện mới
    </a>
    <a href="{{ route('admin.nguoi-dung.index') }}" class="btn btn-secondary">
        <i class="bi bi-person-plus-fill"></i> Quản lý người dùng
    </a>
    <a href="{{ route('admin.thong-ke.index') }}" class="btn btn-secondary">
        <i class="bi bi-bar-chart-fill"></i> Thống kê
    </a>
    <a href="{{ route('admin.media.index') }}" class="btn btn-secondary">
        <i class="bi bi-images"></i> Thư viện media
    </a>
</div>
@endsection