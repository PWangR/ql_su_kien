@extends('admin.layout')
@section('title', $bauCu->tieu_de)
@section('page-title', 'Chi tiết bầu cử')

@section('styles')
<style>
    .tabs {
        display: flex;
        gap: 10px;
        border-bottom: 1px solid var(--border);
        margin-bottom: var(--space-lg);
    }

    .tab-btn {
        padding: 10px 16px;
        border: none;
        background: none;
        cursor: pointer;
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--text-muted);
        border-bottom: 2px solid transparent;
        margin-bottom: -1px;
        transition: color 0.2s, border-color 0.2s;
        font-family: var(--font-sans);
    }

    .tab-btn.active {
        color: var(--accent);
        border-bottom-color: var(--accent);
    }

    .tab-btn:hover {
        color: var(--accent);
    }

    .tab-content {
        display: none;
    }

    .tab-content.active {
        display: block;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
    }

    .info-item label {
        font-size: 0.75rem;
        color: var(--text-muted);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .info-item p {
        font-size: 0.9375rem;
        font-weight: 600;
        color: var(--text);
        margin-top: 4px;
    }

    .modal-layer {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.4);
        z-index: 1000;
        align-items: center;
        justify-content: center;
        padding: 18px;
    }

    .modal-layer.show {
        display: flex;
    }

    .modal-box {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--border-radius-md);
        max-width: 500px;
        width: 100%;
        padding: 24px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
        max-height: 80vh;
        overflow-y: auto;
    }

    .progress-bar-wrap {
        background: var(--bg-alt);
        border: 1px solid var(--border-light);
        height: 24px;
        overflow: hidden;
        position: relative;
    }

    .progress-bar-fill {
        height: 100%;
        transition: width 0.5s;
        background: var(--accent);
    }

    .progress-bar-text {
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
        font-size: 0.75rem;
        font-weight: 700;
        color: #fff;
    }
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
            <button class="btn btn-sm btn-outline" onclick="document.getElementById('modalSelectStudents').classList.add('show')">
                <i class="bi bi-search"></i> Chọn sinh viên
            </button>
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

{{-- Modal Chọn Sinh viên --}}
<div class="modal-layer" id="modalSelectStudents" style="z-index:1001;">
    <div class="modal-box" style="max-width:700px;">
        <h3 style="font-size:1rem;font-weight:700;margin-bottom:var(--space-md);"><i class="bi bi-search"></i> Chọn Sinh viên từ danh sách</h3>

        <div class="form-group">
            <input type="text" id="searchStudent" placeholder="Tìm kiếm: Họ tên, MSSV, email..." class="form-control" style="margin-bottom:var(--space-sm);">
            <select id="filterClass" class="form-control" style="margin-bottom:var(--space-sm);">
                <option value="">Tất cả lớp</option>
            </select>
        </div>

        <div style="border:1px solid var(--border);border-radius:var(--border-radius-sm);max-height:300px;overflow-y:auto;margin-bottom:var(--space-md);">
            <div id="studentList" style="padding:10px;">
                <p class="text-muted text-sm text-center" style="padding:20px;">Đang tải...</p>
            </div>
        </div>

        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:var(--space-sm);padding-bottom:var(--space-sm);border-bottom:1px solid var(--border-light);">
            <span class="text-sm" id="selectedCount">0 đã chọn</span>
            <div>
                <button type="button" class="btn btn-sm btn-outline" onclick="document.querySelectorAll('#studentList input[type=checkbox]').forEach(c => c.checked = false); updateSelectedCount();" style="margin-right:var(--space-xs);">Bỏ chọn tất cả</button>
                <button type="button" class="btn btn-sm btn-outline" onclick="document.querySelectorAll('#studentList input[type=checkbox]').forEach(c => c.checked = true); updateSelectedCount();">Chọn tất cả trang</button>
            </div>
        </div>

        <div class="btn-group">
            <button type="button" class="btn btn-primary" onclick="addSelectedStudents()" id="addBtn">
                <i class="bi bi-check"></i> Thêm cử tri
            </button>
            <button type="button" class="btn btn-secondary" onclick="document.getElementById('modalSelectStudents').classList.remove('show')">Hủy</button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const BAUCUCID = '{{ $bauCu->ma_bau_cu }}';
    // FIX: Truyền ID thẳng vào route function, không dùng placeholder
    const API_URL = '{{ route("admin.cu-tri.api.list", $bauCu->ma_bau_cu) }}';
    const ADD_URL = '{{ route("admin.cu-tri.add-selected", $bauCu->ma_bau_cu) }}';
    let allClasses = new Set();

    // Khi modal được mở
    document.getElementById('modalSelectStudents')?.addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.remove('show');
        }
    });

    // FIX: Load danh sách sinh viên khi modal được mở (bằng cách observe class 'show')
    const modalElement = document.getElementById('modalSelectStudents');
    if (modalElement) {
        // Observer để detect khi modal được mở
        const observer = new MutationObserver(function(mutations) {
            if (modalElement.classList.contains('show') && document.getElementById('studentList').innerHTML.trim() === '<p class="text-muted text-sm text-center" style="padding:20px;">Đang tải...</p>') {
                loadStudents();
            }
        });
        observer.observe(modalElement, {
            attributes: true,
            attributeFilter: ['class']
        });
    }

    document.querySelector('#searchStudent')?.addEventListener('input', debounce(loadStudents, 300));
    document.querySelector('#filterClass')?.addEventListener('change', loadStudents);

    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    async function loadStudents() {
        const search = document.getElementById('searchStudent').value;
        const classFilter = document.getElementById('filterClass').value;

        try {
            const resp = await fetch(`${API_URL}?search=${encodeURIComponent(search)}&class=${encodeURIComponent(classFilter)}&limit=100`);
            const students = await resp.json();

            // Collect classes
            students.forEach(s => {
                if (s.lop) allClasses.add(s.lop);
            });

            // Update class filter
            const classSelect = document.getElementById('filterClass');
            const currentValue = classSelect.value;
            const currentOptions = Array.from(classSelect.options).map(o => o.value);

            allClasses.forEach(cls => {
                if (!currentOptions.includes(cls)) {
                    const opt = document.createElement('option');
                    opt.value = cls;
                    opt.text = cls;
                    classSelect.appendChild(opt);
                }
            });

            // Render students
            let html = '';
            if (students.length === 0) {
                html = '<p class="text-muted text-sm text-center" style="padding:20px;">Không tìm thấy sinh viên nào.</p>';
            } else {
                students.forEach(s => {
                    html += `
                    <label style="display:block;padding:8px;border-bottom:1px solid var(--border-light);cursor:pointer;transition:background 0.15s;">
                        <input type="checkbox" data-id="${s.id}" data-name="${s.ho_ten}" value="${s.id}" onchange="updateSelectedCount()" style="margin-right:8px;">
                        <span style="font-weight:600;">${s.ho_ten}</span>
                        <span class="text-xs text-muted">${s.ma_sinh_vien}</span>
                        <span class="text-xs text-light">${s.email}</span>
                    </label>
                `;
                });
            }
            document.getElementById('studentList').innerHTML = html;
            updateSelectedCount();
        } catch (error) {
            console.error('Lỗi tải danh sách:', error);
            document.getElementById('studentList').innerHTML = '<p class="text-danger text-sm text-center" style="padding:20px;">Lỗi tải dữ liệu</p>';
        }
    }

    function updateSelectedCount() {
        const checked = document.querySelectorAll('#studentList input[type=checkbox]:checked');
        const count = checked.length;
        document.getElementById('selectedCount').textContent = `${count} đã chọn`;
        document.getElementById('addBtn').disabled = count === 0;
        document.getElementById('addBtn').style.opacity = count === 0 ? '0.5' : '1';
    }

    async function addSelectedStudents() {
        const checked = Array.from(document.querySelectorAll('#studentList input[type=checkbox]:checked'));
        if (checked.length === 0) {
            alert('Vui lòng chọn ít nhất một sinh viên.');
            return;
        }

        const studentIds = checked.map(c => c.value);
        const btn = document.getElementById('addBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="bi bi-hourglass"></i> Đang thêm...';

        try {
            const resp = await fetch(ADD_URL, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                },
                body: JSON.stringify({
                    student_ids: studentIds
                }),
            });

            let data;
            try {
                data = await resp.json();
            } catch (parseError) {
                console.error('JSON parse error:', parseError);
                const text = await resp.text();
                console.error('Response text:', text);
                alert('Lỗi: Server phản hồi không hợp lệ (HTTP ' + resp.status + ')\nResponse: ' + text.substring(0, 200));
                return;
            }

            if (data.success) {
                alert(data.message || 'Đã thêm cử tri thành công');
                document.getElementById('modalSelectStudents').classList.remove('show');
                location.reload();
            } else if (data.error) {
                alert('Lỗi: ' + data.error);
            } else {
                alert('Có lỗi xảy ra (HTTP ' + resp.status + ')');
            }
        } catch (error) {
            console.error('Lỗi thêm cử tri:', error);
            alert('Lỗi thêm cử tri: ' + error.message);
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-check"></i> Thêm cử tri';
        }
    }

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