@extends('admin.layout')

@section('title', 'Danh sách sự kiện')
@section('page-title', 'Quản lý sự kiện')

@section('styles')
<style>
.event-thumb { width:72px; height:48px; object-fit:cover; border:1px solid var(--border); flex-shrink:0; }
.event-thumb-placeholder { width:72px; height:48px; background:var(--bg-alt); border:1px solid var(--border); display:flex; align-items:center; justify-content:center; color:var(--border); font-size:18px; flex-shrink:0; }
.modal-layer { position:fixed; inset:0; display:none; align-items:center; justify-content:center; background:rgba(0,0,0,0.4); z-index:999; padding:18px; }
.modal-layer.show { display:flex; }
.modal-card { background:var(--card); border:1px solid var(--border); border-radius:var(--border-radius-md); width:100%; max-width:520px; overflow:hidden; }
.modal-card header { padding:16px 20px; border-bottom:1px solid var(--border); display:flex; justify-content:space-between; align-items:center; }
.modal-card .body { padding:20px; }
.modal-card footer { padding:14px 20px; border-top:1px solid var(--border); display:flex; justify-content:flex-end; gap:10px; }
</style>
@endsection

@section('content')
<!-- Header -->
<div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:var(--space-md);margin-bottom:var(--space-lg);">
    <div>
        <p class="text-muted text-sm">Quản lý, tra cứu và khai thác dữ liệu sự kiện.</p>
    </div>
    <div class="btn-group">
        <button type="button" class="btn btn-secondary" onclick="openImportModal()">
            <i class="bi bi-file-earmark-spreadsheet"></i> Nhập Excel
        </button>
        <a href="{{ route('admin.su-kien.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Thêm sự kiện mới
        </a>
    </div>
</div>

<!-- Filter -->
<div class="card" style="margin-bottom:var(--space-lg);">
    <div class="card-body" style="padding:14px 20px;">
        <form method="GET" style="display:flex;gap:10px;flex-wrap:wrap;align-items:flex-end;">
            <div style="flex:1;min-width:200px;">
                <label class="form-label">Tìm kiếm</label>
                <input type="text" name="search" class="form-control" placeholder="Tên sự kiện..." value="{{ request('search') }}">
            </div>
            <div style="min-width:180px;">
                <label class="form-label">Trạng thái</label>
                <select name="trang_thai" class="form-control">
                    <option value="">Tất cả trạng thái</option>
                    <option value="sap_to_chuc"  {{ request('trang_thai')=='sap_to_chuc'  ? 'selected' : '' }}>Sắp tổ chức</option>
                    <option value="dang_dien_ra" {{ request('trang_thai')=='dang_dien_ra' ? 'selected' : '' }}>Đang diễn ra</option>
                    <option value="da_ket_thuc"  {{ request('trang_thai')=='da_ket_thuc'  ? 'selected' : '' }}>Đã kết thúc</option>
                    <option value="huy"          {{ request('trang_thai')=='huy'          ? 'selected' : '' }}>Đã hủy</option>
                </select>
            </div>
            <div class="btn-group">
                <button type="submit" class="btn btn-primary"><i class="bi bi-funnel"></i> Lọc</button>
                <a href="{{ route('admin.su-kien.index') }}" class="btn btn-secondary">Đặt lại</a>
            </div>
        </form>
    </div>
</div>

<!-- Table -->
<div class="card">
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Ảnh</th>
                    <th>Thông tin sự kiện</th>
                    <th>Thời gian & Địa điểm</th>
                    <th>Đăng ký</th>
                    <th>Trạng thái</th>
                    <th style="text-align:right;">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($suKien as $sk)
                <tr>
                    <td class="text-muted text-sm">#{{ $sk->ma_su_kien }}</td>
                    <td>
                        @if($sk->anh_su_kien)
                        <img src="{{ asset('storage/'.$sk->anh_su_kien) }}" class="event-thumb">
                        @else
                        <div class="event-thumb-placeholder"><i class="bi bi-image"></i></div>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.su-kien.show', $sk->ma_su_kien) }}" style="font-weight:600;color:var(--text);text-decoration:none;">{{ $sk->ten_su_kien }}</a>
                        <div style="margin-top:4px;display:flex;align-items:center;gap:6px;">
                            <span class="badge badge-primary" style="font-size:0.625rem;">{{ $sk->loaiSuKien->ten_loai ?? 'Chưa phân loại' }}</span>
                            @if($sk->qr_code_path)<span class="badge badge-secondary"><i class="bi bi-qr-code"></i></span>@endif
                            @if($sk->diem_cong > 0)<span class="text-sm font-semibold" style="color:var(--warning);"><i class="bi bi-star"></i> +{{ $sk->diem_cong }}</span>@endif
                        </div>
                    </td>
                    <td>
                        <div class="text-sm text-muted"><i class="bi bi-calendar-event"></i> {{ $sk->thoi_gian_bat_dau?->format('d/m/Y H:i') }}</div>
                        <div class="text-sm text-muted"><i class="bi bi-geo-alt"></i> {{ Str::limit($sk->dia_diem ?: 'Chưa cập nhật', 30) }}</div>
                    </td>
                    <td>
                        <strong>{{ $sk->so_luong_hien_tai }} / {{ $sk->so_luong_toi_da ?: '∞' }}</strong>
                    </td>
                    <td>
                        @php
                        $statusMap = [
                            'sap_to_chuc'  => 'badge-primary',
                            'dang_dien_ra' => 'badge-success',
                            'da_ket_thuc'  => 'badge-secondary',
                            'huy'          => 'badge-danger',
                        ];
                        @endphp
                        <span class="badge {{ $statusMap[$sk->trang_thai_thuc_te] ?? 'badge-secondary' }}">{{ $sk->trang_thai_label }}</span>
                    </td>
                    <td style="text-align:right;">
                        <div class="btn-group">
                            <a href="{{ route('admin.su-kien.show', $sk->ma_su_kien) }}" class="btn btn-secondary btn-sm" title="Chi tiết"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('admin.su-kien.edit', $sk->ma_su_kien) }}" class="btn btn-secondary btn-sm" title="Sửa"><i class="bi bi-pencil"></i></a>
                            <form method="POST" action="{{ route('admin.su-kien.destroy', $sk->ma_su_kien) }}" style="display:inline;" onsubmit="return confirm('Xác nhận xóa sự kiện này?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" title="Xóa"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center;padding:var(--space-2xl);color:var(--text-muted);">
                        <i class="bi bi-inbox" style="font-size:32px;display:block;margin-bottom:6px;opacity:0.3;"></i>
                        Không tìm thấy sự kiện nào.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($suKien->hasPages())
    <div style="padding:var(--space-md) 20px;border-top:1px solid var(--border-light);">{{ $suKien->links() }}</div>
    @endif
</div>

<!-- Import Modal -->
<div class="modal-layer" id="importModal">
    <div class="modal-card">
        <header>
            <div style="font-weight:600;"><i class="bi bi-file-earmark-spreadsheet"></i> Nhập sự kiện từ Excel</div>
            <button class="btn btn-secondary btn-sm" onclick="closeImportModal()"><i class="bi bi-x"></i></button>
        </header>
        <form method="POST" action="{{ route('admin.su-kien.import') }}" enctype="multipart/form-data">
            @csrf
            <div class="body">
                <p class="text-sm text-muted" style="margin-bottom:12px;">Cột tối thiểu: <code>ten_su_kien</code>, <code>ma_loai_su_kien</code>, <code>thoi_gian_bat_dau</code>, <code>thoi_gian_ket_thuc</code>.</p>
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
