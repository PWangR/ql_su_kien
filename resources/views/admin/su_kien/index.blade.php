@extends('admin.layout')

@section('title', 'Danh sách sự kiện')
@section('page-title', 'Quản lý sự kiện')

@section('styles')
<style>
    .hero-bar{
        background: radial-gradient(circle at 10% 20%, rgba(37,99,235,.12), transparent 30%), radial-gradient(circle at 80% 0%, rgba(14,165,233,.12), transparent 25%), #0f172a;
        border-radius: 16px;
        padding: 20px 24px;
        color:#e2e8f0;
        box-shadow:0 15px 40px rgba(15,23,42,0.25);
    }
    .hero-bar h4{color:#fff;margin-bottom:6px;font-weight:800;}
    .hero-pill{display:inline-flex;align-items:center;gap:8px;padding:6px 12px;border-radius:999px;background:rgba(255,255,255,0.1);color:#cbd5e1;font-size:12px;}
    .hero-actions{display:flex;gap:10px;flex-wrap:wrap;}
    .btn-ghost{
        background:rgba(255,255,255,0.08);
        border:1px solid rgba(255,255,255,0.18);
        color:#fff;
    }
    .event-thumbnail {
        width: 80px;
        height: 50px;
        object-fit: cover;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        border: 1px solid #f1f5f9;
        transition: transform 0.2s;
    }
    .event-thumbnail:hover { transform: scale(1.08); }
    .table-container { background: #fff; border-radius: 12px; box-shadow: 0 8px 30px rgba(0,0,0,0.06); overflow: hidden; }
    .table thead th {
        background: #f8fafc;
        text-transform: uppercase;
        font-size: 11px;
        letter-spacing: 0.5px;
        font-weight: 700;
        color: #64748b;
        border-bottom: 2px solid #f1f5f9;
        padding: 15px 20px;
    }
    .table tbody td { padding: 15px 20px; vertical-align: middle; border-bottom: 1px solid #f1f5f9; }
    .table tbody tr:hover { background-color: #f8fafc; }
    .badge-status { padding: 6px 12px; border-radius: 20px; font-size: 11px; font-weight: 700; }
    .filter-card {
        background: #fff;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    .event-title { font-weight: 700; color: #1e293b; text-decoration: none; font-size: 14px; transition: color 0.2s; }
    .event-title:hover { color: var(--primary); }
    .text-date { font-size: 12px; color: #64748b; display: block; }
    .badge-type { background: #eff6ff; color: #2563eb; font-size: 11px; padding: 3px 8px; border-radius: 6px; font-weight: 600; }
    .modal-layer{
        position:fixed;inset:0;display:none;align-items:center;justify-content:center;
        background:rgba(15,23,42,0.5);z-index:999;
        padding:18px;
    }
    .modal-layer.show{display:flex;}
    .modal-card{
        background:#fff;border-radius:16px;box-shadow:0 20px 50px rgba(0,0,0,0.15);
        width:100%;max-width:520px;overflow:hidden;
    }
    .modal-card header{padding:16px 20px;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;}
    .modal-card .body{padding:20px;}
    .modal-card footer{padding:14px 20px;border-top:1px solid var(--border);display:flex;justify-content:flex-end;gap:10px;}
</style>
@endsection

@section('content')
<div class="hero-bar mb-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
        <div class="hero-pill mb-2"><i class="bi bi-magic"></i> Admin workspace</div>
        <h4 class="mb-1">Danh sách sự kiện</h4>
        <div class="text-sm text-muted">Quản lý, tra cứu và khai thác dữ liệu nhanh.</div>
    </div>
    <div class="hero-actions">
        <button type="button" class="btn btn-ghost" onclick="openImportModal()">
            <i class="bi bi-file-earmark-spreadsheet"></i> Nhập Excel
        </button>
        <a href="{{ route('admin.su-kien.create') }}" class="btn btn-primary px-4 shadow-sm">
            <i class="bi bi-plus-lg me-2"></i>Thêm sự kiện mới
        </a>
    </div>
</div>

<!-- Filter Section -->
<div class="filter-card">
    <form method="GET" class="row g-3 align-items-end">
        <div class="col-md-4">
            <label class="form-label small fw-bold text-uppercase">Tìm kiếm</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                <input type="text" name="search" class="form-control border-start-0 bg-light"
                    placeholder="Tên sự kiện..." value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-md-3">
            <label class="form-label small fw-bold text-uppercase">Trạng thái</label>
            <select name="trang_thai" class="form-select bg-light">
                <option value="">Tất cả trạng thái</option>
                <option value="sap_to_chuc"  {{ request('trang_thai')=='sap_to_chuc'  ? 'selected' : '' }}>Sắp tổ chức</option>
                <option value="dang_dien_ra" {{ request('trang_thai')=='dang_dien_ra' ? 'selected' : '' }}>Đang diễn ra</option>
                <option value="da_ket_thuc"  {{ request('trang_thai')=='da_ket_thuc'  ? 'selected' : '' }}>Đã kết thúc</option>
                <option value="huy"          {{ request('trang_thai')=='huy'          ? 'selected' : '' }}>Đã hủy</option>
            </select>
        </div>
        <div class="col-md-5 d-flex gap-2">
            <button type="submit" class="btn btn-secondary px-4"><i class="bi bi-funnel me-2"></i>Lọc</button>
            <a href="{{ route('admin.su-kien.index') }}" class="btn btn-light border px-4">Đặt lại</a>
        </div>
    </form>
</div>

<!-- Table Section -->
<div class="table-container">
    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th width="50">#</th>
                    <th width="100">Ảnh</th>
                    <th>Thông tin sự kiện</th>
                    <th>Thời gian & Địa điểm</th>
                    <th class="text-center">Người tham gia</th>
                    <th>Trạng thái</th>
                    <th class="text-end">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($suKien as $sk)
                <tr>
                    <td class="text-muted small">#{{ $sk->ma_su_kien }}</td>
                    <td>
                        @if($sk->anh_su_kien)
                        <img src="{{ asset('storage/'.$sk->anh_su_kien) }}" class="event-thumbnail">
                        @else
                        <div class="event-thumbnail d-flex align-items-center justify-content-center bg-light text-muted small">No Img</div>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.su-kien.show', $sk->ma_su_kien) }}" class="event-title">{{ $sk->ten_su_kien }}</a>
                        <div class="mt-1 d-flex align-items-center gap-2">
                            <span class="badge-type">{{ $sk->loaiSuKien->ten_loai ?? 'Chưa phân loại' }}</span>
                            @if($sk->qr_code_path)
                                <span class="badge badge-secondary"><i class="bi bi-qr-code"></i> QR</span>
                            @endif
                            @if($sk->diem_cong > 0)
                            <span class="text-warning small fw-bold"><i class="bi bi-star-fill me-1"></i>+{{ $sk->diem_cong }} Đ</span>
                            @endif
                        </div>
                    </td>
                    <td>
                        <span class="text-date"><i class="bi bi-calendar-event me-2"></i>{{ $sk->thoi_gian_bat_dau?->format('d/m/Y H:i') }}</span>
                        <span class="text-date"><i class="bi bi-geo-alt me-2"></i>{{ Str::limit($sk->dia_diem ?: 'Chưa cập nhật', 30) }}</span>
                    </td>
                    <td class="text-center">
                        <div class="fw-bold">{{ $sk->so_luong_hien_tai }} / {{ $sk->so_luong_toi_da ?: '∞' }}</div>
                        <div class="progress mt-1" style="height: 4px; border-radius: 10px;">
                            @php
                                $percent = $sk->so_luong_toi_da ? min(100, ($sk->so_luong_hien_tai / $sk->so_luong_toi_da) * 100) : 0;
                            @endphp
                            <div class="progress-bar bg-primary" style="width: {{ $percent }}%"></div>
                        </div>
                    </td>
                    <td>
                        <span class="badge badge-status bg-{{ $sk->trang_thai_color }} text-white">{{ $sk->trang_thai_label }}</span>
                    </td>
                    <td class="text-end">
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.su-kien.show', $sk->ma_su_kien) }}" class="btn btn-sm btn-outline-info border-0" title="Chi tiết"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('admin.su-kien.edit', $sk->ma_su_kien) }}" class="btn btn-sm btn-outline-warning border-0" title="Sửa"><i class="bi bi-pencil-square"></i></a>
                            <form method="POST" action="{{ route('admin.su-kien.destroy', $sk->ma_su_kien) }}" class="d-inline" onsubmit="return confirm('Xác nhận xóa sự kiện này?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger border-0" title="Xóa"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5 text-muted">
                        <i class="bi bi-inbox fs-1 d-block mb-3 opacity-25"></i>
                        Không tìm thấy sự kiện nào phù hợp.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($suKien->hasPages())
    <div class="px-4 py-3 border-top bg-light">
        {{ $suKien->links() }}
    </div>
    @endif
</div>

<!-- Import modal -->
<div class="modal-layer" id="importModal">
    <div class="modal-card">
        <header>
            <div class="fw-bold"><i class="bi bi-file-earmark-spreadsheet"></i> Nhập sự kiện từ Excel</div>
            <button class="btn btn-secondary btn-sm" onclick="closeImportModal()">Đóng</button>
        </header>
        <form method="POST" action="{{ route('admin.su-kien.import') }}" enctype="multipart/form-data">
            @csrf
            <div class="body">
                <p class="text-muted small mb-3">Định dạng cột tối thiểu: <code>ten_su_kien</code>, <code>ma_loai_su_kien</code>, <code>thoi_gian_bat_dau</code>, <code>thoi_gian_ket_thuc</code>. Các cột khác (địa điểm, điểm cộng, số lượng) tùy chọn.</p>
                <input type="file" name="file" accept=".xls,.xlsx,.csv" class="form-control" required>
            </div>
            <footer>
                <button type="button" class="btn btn-secondary" onclick="closeImportModal()">Hủy</button>
                <button type="submit" class="btn btn-primary"><i class="bi bi-upload"></i> Tải lên</button>
            </footer>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const importModal = document.getElementById('importModal');
    function openImportModal(){ importModal.classList.add('show'); }
    function closeImportModal(){ importModal.classList.remove('show'); }
</script>
@endsection
