@extends('admin.layout')
@section('title', $bauCu->tieu_de)
@section('page-title', 'Chi tiết bầu cử')

@section('styles')
<style>
.tabs { display:flex; gap:10px; border-bottom:1px solid var(--border); margin-bottom:var(--space-lg); }
.tab-btn { padding:10px 16px; border:none; background:none; cursor:pointer; font-size:0.875rem; font-weight:600; color:var(--text-muted); border-bottom:2px solid transparent; margin-bottom:-1px; transition:color 0.2s, border-color 0.2s; font-family:var(--font-sans); }
.tab-btn.active { color:var(--accent); border-bottom-color:var(--accent); }
.tab-btn:hover { color:var(--accent); }
.tab-content { display:none; }
.tab-content.active { display:block; }
.info-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(200px,1fr)); gap:16px; }
.info-item label { font-size:0.75rem; color:var(--text-muted); font-weight:600; text-transform:uppercase; letter-spacing:0.05em; }
.info-item p { font-size:0.9375rem; font-weight:600; color:var(--text); margin-top:4px; }
.modal-layer { display:none; position:fixed; inset:0; background:rgba(0,0,0,0.4); z-index:1000; align-items:center; justify-content:center; padding:18px; }
.modal-layer.show { display:flex; }
.modal-box { background:var(--card); border:1px solid var(--border); border-radius:var(--border-radius-md); max-width:500px; width:100%; padding:24px; box-shadow:0 8px 30px rgba(0,0,0,0.1); max-height:80vh; overflow-y:auto; }
.progress-bar-wrap { background:var(--bg-alt); border:1px solid var(--border-light); height:24px; overflow:hidden; position:relative; }
.progress-bar-fill { height:100%; transition:width 0.5s; background:var(--accent); }
.progress-bar-text { position:absolute; left:50%; top:50%; transform:translate(-50%,-50%); font-size:0.75rem; font-weight:700; color:#fff; }
</style>
@endsection

@section('content')
{{-- Header --}}
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:var(--space-md);flex-wrap:wrap;gap:10px;">
    <div>
        <a href="{{ route('admin.bau-cu.index') }}" style="color:var(--accent);text-decoration:none;font-size:0.8125rem;">
            <i class="bi bi-arrow-left"></i> Danh sách bầu cử
        </a>
    </div>
    <div class="btn-group">
        <form method="POST" action="{{ route('admin.bau-cu.toggle-visibility', $bauCu->ma_bau_cu) }}" style="display:inline;">
            @csrf
            <button class="btn btn-sm {{ $bauCu->hien_thi ? 'btn-success' : 'btn-secondary' }}">
                <i class="bi {{ $bauCu->hien_thi ? 'bi-eye' : 'bi-eye-slash' }}"></i>
                {{ $bauCu->hien_thi ? 'Đang hiển thị' : 'Ẩn' }}
            </button>
        </form>
        <form method="POST" action="{{ route('admin.bau-cu.toggle-result', $bauCu->ma_bau_cu) }}" style="display:inline;">
            @csrf
            <button class="btn btn-sm {{ $bauCu->hien_thi_ket_qua ? 'btn-primary' : 'btn-secondary' }}">
                <i class="bi bi-bar-chart"></i>
                {{ $bauCu->hien_thi_ket_qua ? 'KQ hiển thị' : 'KQ ẩn' }}
            </button>
        </form>
        <a href="{{ route('admin.bau-cu.edit', $bauCu->ma_bau_cu) }}" class="btn btn-sm btn-outline">
            <i class="bi bi-pencil"></i> Sửa
        </a>
    </div>
</div>

{{-- Info --}}
<div class="card mb-3">
    <div class="card-body">
        <h2 style="font-size:1.25rem;font-weight:700;margin-bottom:8px;">{{ $bauCu->tieu_de }}</h2>
        @if($bauCu->mo_ta)
        <p class="text-muted text-sm" style="margin-bottom:var(--space-md);">{!! nl2br(e($bauCu->mo_ta)) !!}</p>
        @endif
        <div class="info-grid mt-4 pt-4 border-t" style="border-top:1px solid var(--border-light);">
            <div class="info-item">
                <label>Trạng thái</label>
                <p><span class="badge badge-{{ $bauCu->trang_thai_color }}">{{ $bauCu->trang_thai_label }}</span></p>
            </div>
            <div class="info-item">
                <label>Thời gian</label>
                <p class="text-sm"><i class="bi bi-calendar"></i> {{ $bauCu->thoi_gian_bat_dau->format('d/m/Y H:i') }} <br><span class="text-xs text-muted">→ {{ $bauCu->thoi_gian_ket_thuc->format('d/m/Y H:i') }}</span></p>
            </div>
            <div class="info-item">
                <label>Số chọn</label>
                <p>{{ $bauCu->so_chon_toi_thieu }} – {{ $bauCu->so_chon_toi_da }}</p>
            </div>
            <div class="info-item">
                <label>Tỉ lệ bỏ phiếu</label>
                <p><strong style="color:var(--accent);">{{ $soVoted }}</strong>/{{ $soCuTri }} <span class="text-muted text-sm">({{ $soCuTri > 0 ? round($soVoted/$soCuTri*100,1) : 0 }}%)</span></p>
            </div>
        </div>
    </div>
</div>

{{-- Tabs --}}
<div class="tabs">
    <button class="tab-btn active" onclick="switchTab('ucv')"><i class="bi bi-people"></i> Ứng cử viên ({{ $ungCuViens->count() }})</button>
    <button class="tab-btn" onclick="switchTab('cutri')"><i class="bi bi-person-check"></i> Cử tri ({{ $cuTris->count() }})</button>
    <button class="tab-btn" onclick="switchTab('ketqua')"><i class="bi bi-bar-chart"></i> Kết quả</button>
</div>

{{-- Tab: Ứng kết viên --}}
<div class="tab-content active" id="tab-ucv">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:var(--space-sm);">
        <span class="text-sm text-muted">{{ $ungCuViens->count() }} ứng cử viên</span>
        <div class="btn-group">
            <button class="btn btn-sm btn-outline" onclick="document.getElementById('modalImportUCV').classList.add('show')">
                <i class="bi bi-file-earmark-excel"></i> Nhập Excel
            </button>
            <button class="btn btn-sm btn-primary" onclick="document.getElementById('modalUCV').classList.add('show')">
                <i class="bi bi-plus-lg"></i> Thêm
            </button>
            @if($ungCuViens->count())
            <form method="POST" action="{{ route('admin.ung-cu-vien.destroy-all', $bauCu->ma_bau_cu) }}" onsubmit="return confirm('Xóa tất cả ứng cử viên?')">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i> Xóa tất cả</button>
            </form>
            @endif
        </div>
    </div>
    <div class="card">
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Họ tên</th>
                        <th>Lớp</th>
                        <th>MSSV</th>
                        <th>ĐTB</th>
                        <th>ĐRL</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ungCuViens as $i => $ucv)
                    <tr>
                        <td class="text-muted text-sm">{{ $i + 1 }}</td>
                        <td><strong>{{ $ucv->ho_ten }}</strong></td>
                        <td class="text-sm">{{ $ucv->lop }}</td>
                        <td class="text-sm">{{ $ucv->ma_sinh_vien }}</td>
                        <td class="text-sm">{{ $ucv->diem_trung_binh ? number_format($ucv->diem_trung_binh, 2) : '–' }}</td>
                        <td class="text-sm">{{ $ucv->diem_ren_luyen ? number_format($ucv->diem_ren_luyen, 1) : '–' }}</td>
                        <td style="text-align:right;">
                            <form method="POST" action="{{ route('admin.ung-cu-vien.destroy', $ucv->ma_ung_cu_vien) }}" onsubmit="return confirm('Xóa?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Tab: Cử tri --}}
<div class="tab-content" id="tab-cutri">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:var(--space-sm);">
        <span class="text-sm text-muted">{{ $cuTris->count() }} cử tri</span>
        <div class="btn-group">
            <button class="btn btn-sm btn-outline" onclick="document.getElementById('modalImportCuTri').classList.add('show')">
                <i class="bi bi-file-earmark-excel"></i> Nhập Excel
            </button>
            <form method="POST" action="{{ route('admin.cu-tri.add-all', $bauCu->ma_bau_cu) }}">
                @csrf
                <button class="btn btn-sm btn-primary"><i class="bi bi-people"></i> Thêm tất cả SV</button>
            </form>
            @if($cuTris->count())
            <form method="POST" action="{{ route('admin.cu-tri.destroy-all', $bauCu->ma_bau_cu) }}" onsubmit="return confirm('Xóa tất cả cử tri?')">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i> Xóa tất cả</button>
            </form>
            @endif
        </div>
    </div>
    <div class="card">
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Họ tên</th>
                        <th>MSSV</th>
                        <th class="hide-sm">Email</th>
                        <th>Đã bỏ phiếu</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cuTris as $i => $ct)
                    <tr>
                        <td class="text-muted text-sm">{{ $i + 1 }}</td>
                        <td>{{ $ct->nguoiDung->ho_ten ?? '–' }}</td>
                        <td class="text-sm">{{ $ct->nguoiDung->ma_sinh_vien ?? '–' }}</td>
                        <td class="hide-sm text-sm text-light">{{ $ct->nguoiDung->email ?? '–' }}</td>
                        <td>
                            @if($ct->da_bo_phieu)
                            <span class="badge badge-success"><i class="bi bi-check"></i> Đã bầu</span>
                            @else
                            <span class="badge badge-secondary">Chưa</span>
                            @endif
                        </td>
                        <td style="text-align:right;">
                            @if(!$ct->da_bo_phieu)
                            <form method="POST" action="{{ route('admin.cu-tri.destroy', $ct->ma_cu_tri) }}" onsubmit="return confirm('Xóa?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Tab: Kết quả --}}
<div class="tab-content" id="tab-ketqua">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="bi bi-bar-chart"></i> Kết quả bỏ phiếu</h3>
            <span class="text-muted text-sm">{{ $soVoted }}/{{ $soCuTri }} phiếu</span>
        </div>
        <div class="card-body">
            @if($ketQua->count())
            @php $maxPhieu = $ketQua->max('so_phieu') ?: 1; @endphp
            @foreach($ketQua as $ucv)
            @php $pct = $soVoted > 0 ? round($ucv->so_phieu / $soVoted * 100, 1) : 0; @endphp
            <div style="margin-bottom:14px;">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px;">
                    <span style="font-weight:600;font-size:0.875rem;">{{ $ucv->ho_ten }} <span class="text-muted text-xs">({{ $ucv->lop }})</span></span>
                    <span class="text-xs text-muted">{{ $ucv->so_phieu }} phiếu ({{ $pct }}%)</span>
                </div>
                <div class="progress-bar-wrap">
                    <div class="progress-bar-fill" style="width:{{ $maxPhieu > 0 ? round($ucv->so_phieu / $maxPhieu * 100) : 0 }}%"></div>
                    @if($ucv->so_phieu > 0)
                    <span class="progress-bar-text">{{ $ucv->so_phieu }}</span>
                    @endif
                </div>
            </div>
            @endforeach
            @else
            <p class="text-muted" style="text-align:center;padding:30px;">Chưa có dữ liệu.</p>
            @endif
        </div>
    </div>
</div>

{{-- Modal Add UCV --}}
<div class="modal-layer" id="modalUCV">
    <div class="modal-box">
        <h3 style="font-size:1rem;font-weight:700;margin-bottom:var(--space-md);"><i class="bi bi-person-plus"></i> Thêm ứng cử viên</h3>
        <form method="POST" action="{{ route('admin.ung-cu-vien.store', $bauCu->ma_bau_cu) }}">
            @csrf
            <div class="form-group">
                <label class="form-label">Họ tên *</label>
                <input type="text" name="ho_ten" class="form-control" required>
            </div>
            <div class="input-grid form-group">
                <div><label class="form-label">Lớp *</label><input type="text" name="lop" class="form-control" required></div>
                <div><label class="form-label">MSSV *</label><input type="text" name="ma_sinh_vien" class="form-control" required></div>
            </div>
            <div class="input-grid form-group">
                <div><label class="form-label">ĐTB tích lũy</label><input type="number" name="diem_trung_binh" class="form-control" step="0.01" min="0" max="10"></div>
                <div><label class="form-label">Điểm rèn luyện</label><input type="number" name="diem_ren_luyen" class="form-control" step="0.1" min="0" max="100"></div>
            </div>
            <div class="form-group">
                <label class="form-label">Giới thiệu</label>
                <textarea name="gioi_thieu" class="form-control" rows="2"></textarea>
            </div>
            <div class="btn-group">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check"></i> Thêm</button>
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('modalUCV').classList.remove('show')">Hủy</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Import UCV --}}
<div class="modal-layer" id="modalImportUCV">
    <div class="modal-box">
        <h3 style="font-size:1rem;font-weight:700;margin-bottom:var(--space-sm);"><i class="bi bi-file-earmark-excel"></i> Nhập Ứng cử viên từ Excel</h3>
        <p class="text-sm text-muted mb-3">File Excel phải có dòng tiêu đề tiếng Anh tương ứng với các cột: <code>ho_ten</code>, <code>lop</code>, <code>ma_sinh_vien</code>, <code>diem_trung_binh</code>, <code>diem_ren_luyen</code>, <code>gioi_thieu</code>.</p>
        <form method="POST" action="{{ route('admin.ung-cu-vien.import', $bauCu->ma_bau_cu) }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="form-label">Chọn file Excel (.xlsx, .xls, .csv)</label>
                <input type="file" name="file" class="form-control" accept=".xlsx,.xls,.csv" required>
            </div>
            <div class="btn-group">
                <button type="submit" class="btn btn-primary"><i class="bi bi-upload"></i> Nhập file</button>
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('modalImportUCV').classList.remove('show')">Hủy</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Import Cử tri --}}
<div class="modal-layer" id="modalImportCuTri">
    <div class="modal-box">
        <h3 style="font-size:1rem;font-weight:700;margin-bottom:var(--space-sm);"><i class="bi bi-file-earmark-excel"></i> Nhập Cử tri từ Excel</h3>
        <p class="text-sm text-muted mb-3">File Excel phải có dòng tiêu đề với một trong hai cột: <code>ma_sinh_vien</code> hoặc <code>email</code> để hệ thống tự động tìm danh sách tài khoản hợp lệ.</p>
        <form method="POST" action="{{ route('admin.cu-tri.import', $bauCu->ma_bau_cu) }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="form-label">Chọn file Excel (.xlsx, .xls, .csv)</label>
                <input type="file" name="file" class="form-control" accept=".xlsx,.xls,.csv" required>
            </div>
            <div class="btn-group">
                <button type="submit" class="btn btn-primary"><i class="bi bi-upload"></i> Nhập file</button>
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('modalImportCuTri').classList.remove('show')">Hủy</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
function switchTab(tab) {
    document.querySelectorAll('.tab-content').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    document.getElementById('tab-' + tab).classList.add('active');
    event.currentTarget.classList.add('active');
}
document.querySelectorAll('.modal-layer').forEach(m => {
    m.addEventListener('click', function(e) {
        if (e.target === this) this.classList.remove('show');
    });
});
</script>
@endsection
