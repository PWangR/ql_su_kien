@extends('admin.layout')

@section('title', 'Quản lý người dùng')
@section('page-title', 'Quản lý người dùng')

@section('styles')
<style>
    .hero-bar{
        background: linear-gradient(120deg,#111827,#0f172a 40%,#1d4ed8 100%);
        border-radius: 16px;
        padding: 20px 24px;
        color:#e2e8f0;
        box-shadow:0 16px 40px rgba(0,0,0,0.25);
    }
    .hero-bar h4{color:#fff;margin-bottom:6px;font-weight:800;}
    .hero-actions{display:flex;gap:10px;flex-wrap:wrap;}
    .modal-layer{position:fixed;inset:0;display:none;align-items:center;justify-content:center;background:rgba(15,23,42,0.5);z-index:999;padding:18px;}
    .modal-layer.show{display:flex;}
    .modal-card{background:#fff;border-radius:16px;width:100%;max-width:540px;box-shadow:0 18px 46px rgba(0,0,0,0.18);overflow:hidden;}
    .modal-card header{padding:16px 20px;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;}
    .modal-card .body{padding:20px;}
    .modal-card footer{padding:14px 20px;border-top:1px solid var(--border);display:flex;justify-content:flex-end;gap:10px;}
</style>
@endsection

@section('content')
<div class="hero-bar mb-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
        <div class="text-uppercase small text-muted">Access Control</div>
        <h4 class="mb-1">Danh sách người dùng</h4>
        <div class="text-sm text-muted">Quản lý vai trò, trạng thái và tài khoản đăng nhập.</div>
    </div>
    <div class="hero-actions">
        <button class="btn btn-secondary" type="button" onclick="openImportModal()">
            <i class="bi bi-upload"></i> Nhập Excel
        </button>
        <button class="btn btn-primary" type="button" onclick="openAddModal()">
            <i class="bi bi-person-plus-fill"></i> Thêm mới
        </button>
    </div>
</div>

<div class="card mb-3">
    <div class="card-header">
        <div class="card-title"><i class="bi bi-people-fill" style="color:var(--primary)"></i> Danh sách người dùng</div>
    </div>

    <!-- Filter -->
    <div style="padding:14px 20px;border-bottom:1px solid var(--border);background:#fafafa;">
        <form method="GET" style="display:flex;gap:10px;flex-wrap:wrap;">
            <input type="text" name="search" class="form-control" placeholder="Tìm kiếm tên, email, MSSV..." value="{{ request('search') }}" style="max-width:280px;">
            <select name="vai_tro" class="form-control" style="max-width:180px;">
                <option value="">-- Vai trò --</option>
                <option value="admin" {{ request('vai_tro') == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="sinh_vien" {{ request('vai_tro') == 'sinh_vien' ? 'selected' : '' }}>Sinh viên</option>
            </select>
            <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> Tìm</button>
            <a href="{{ route('admin.nguoi-dung.index') }}" class="btn btn-secondary">Đặt lại</a>
        </form>
    </div>

    <div class="table-resp">
        <table>
            <thead>
                <tr>
                    <th>Người dùng</th>
                    <th>MSSV</th>
                    <th>Email</th>
                    <th>Vai trò</th>
                    <th>Trạng thái</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($nguoiDung as $nd)
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px;">
                            <div style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#2563eb,#60a5fa);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:13px;flex-shrink:0;">
                                {{ mb_substr($nd->ho_ten, 0, 1) }}
                            </div>
                            <strong>{{ $nd->ho_ten }}</strong>
                        </div>
                    </td>
                    <td>{{ $nd->ma_sinh_vien }}</td>
                    <td style="font-size:13px;">{{ $nd->email }}</td>
                    <td>
                        <span class="badge {{ $nd->vai_tro === 'admin' ? 'badge-primary' : 'badge-secondary' }}">
                            {{ $nd->vai_tro === 'admin' ? 'Admin' : 'Sinh viên' }}
                        </span>
                    </td>
                    <td>
                        @php
                            $s = ['hoat_dong'=>['bg'=>'#dcfce7','t'=>'#15803d','l'=>'Hoạt động'],'khong_hoat_dong'=>['bg'=>'#f1f5f9','t'=>'#475569','l'=>'Không HĐ'],'bi_khoa'=>['bg'=>'#fee2e2','t'=>'#b91c1c','l'=>'Bị khóa']];
                            $st = $s[$nd->trang_thai] ?? $s['khong_hoat_dong'];
                        @endphp
                        <span class="badge" style="background:{{ $st['bg'] }};color:{{ $st['t'] }};">{{ $st['l'] }}</span>
                    </td>
                    <td>
                        <div style="display:flex;gap:4px;">
                            <button class="btn btn-secondary btn-sm" title="Sửa" onclick="openEditModal({{ $nd->ma_nguoi_dung }}, '{{ $nd->ho_ten }}', '{{ $nd->ma_sinh_vien }}', '{{ $nd->email }}', '{{ $nd->vai_tro }}', '{{ $nd->so_dien_thoai }}')">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <form method="POST" action="{{ route('admin.nguoi-dung.toggle-status', $nd->ma_nguoi_dung) }}">
                                @csrf
                                <button class="btn btn-secondary btn-sm" title="{{ $nd->trang_thai==='bi_khoa' ? 'Mở khóa' : 'Khóa' }}">
                                    <i class="bi bi-{{ $nd->trang_thai==='bi_khoa' ? 'unlock' : 'lock' }}"></i>
                                </button>
                            </form>
                            @if($nd->ma_nguoi_dung !== auth()->id())
                            <form method="POST" action="{{ route('admin.nguoi-dung.destroy', $nd->ma_nguoi_dung) }}" onsubmit="return confirm('Xóa người dùng này?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" style="text-align:center;padding:30px;color:var(--text-light);">
                    <i class="bi bi-people" style="font-size:28px;display:block;margin-bottom:6px;"></i>Chưa có người dùng
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($nguoiDung->hasPages())
    <div style="padding:16px 20px;border-top:1px solid var(--border);">{{ $nguoiDung->links() }}</div>
    @endif
</div>

<!-- Add Modal -->
<div id="addModal" class="modal-layer">
    <div class="modal-card">
        <header>
            <h3 style="font-size:16px;font-weight:700;">Thêm người dùng mới</h3>
            <button onclick="closeAddModal()" style="background:none;border:none;font-size:20px;cursor:pointer;color:var(--text-light);">&times;</button>
        </header>
        <form method="POST" action="{{ route('admin.nguoi-dung.store') }}" class="body">
            @csrf
            <div class="mb-3">
                <label class="form-label">Họ tên *</label>
                <input type="text" name="ho_ten" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Mã sinh viên *</label>
                <input type="text" name="ma_sinh_vien" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email *</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Vai trò *</label>
                <select name="vai_tro" class="form-control" required>
                    <option value="sinh_vien">Sinh viên</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Mật khẩu *</label>
                <input type="password" name="mat_khau" class="form-control" required minlength="8">
            </div>
            <div class="mb-3">
                <label class="form-label">Số điện thoại</label>
                <input type="text" name="so_dien_thoai" class="form-control">
            </div>
            <footer style="display:flex;gap:10px;justify-content:flex-end;padding-top:8px;">
                <button type="button" onclick="closeAddModal()" class="btn btn-secondary">Hủy</button>
                <button type="submit" class="btn btn-primary"><i class="bi bi-check"></i> Thêm</button>
            </footer>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="modal-layer">
    <div class="modal-card">
        <header>
            <h3 style="font-size:16px;font-weight:700;">Cập nhật người dùng</h3>
            <button onclick="closeEditModal()" style="background:none;border:none;font-size:20px;cursor:pointer;color:var(--text-light);">&times;</button>
        </header>
        <form method="POST" id="editForm" class="body">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label">Họ tên *</label>
                <input type="text" name="ho_ten" id="edit_ho_ten" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Mã sinh viên *</label>
                <input type="text" name="ma_sinh_vien" id="edit_ma_sinh_vien" class="form-control" readonly>
            </div>
            <div class="mb-3">
                <label class="form-label">Email *</label>
                <input type="email" name="email" id="edit_email" class="form-control" readonly>
            </div>
            <div class="mb-3">
                <label class="form-label">Vai trò *</label>
                <select name="vai_tro" id="edit_vai_tro" class="form-control" required>
                    <option value="sinh_vien">Sinh viên</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Mật khẩu mới (bỏ trống nếu giữ nguyên)</label>
                <input type="password" name="mat_khau" class="form-control" minlength="8">
            </div>
            <div class="mb-3">
                <label class="form-label">Số điện thoại</label>
                <input type="text" name="so_dien_thoai" id="edit_so_dien_thoai" class="form-control">
            </div>
            <footer style="display:flex;gap:10px;justify-content:flex-end;padding-top:8px;">
                <button type="button" onclick="closeEditModal()" class="btn btn-secondary">Hủy</button>
                <button type="submit" class="btn btn-primary"><i class="bi bi-check2"></i> Lưu</button>
            </footer>
        </form>
    </div>
</div>

<!-- Import Modal -->
<div id="importModal" class="modal-layer">
    <div class="modal-card">
        <header>
            <h3 style="font-size:16px;font-weight:700;">Nhập người dùng từ Excel</h3>
            <button onclick="closeImportModal()" style="background:none;border:none;font-size:20px;cursor:pointer;color:var(--text-light);">&times;</button>
        </header>
        <form method="POST" action="{{ route('admin.nguoi-dung.import') }}" enctype="multipart/form-data" class="body">
            @csrf
            <p class="text-muted small">Cột tối thiểu: <code>ho_ten</code>, <code>ma_sinh_vien</code>, <code>email</code>. Tùy chọn: <code>vai_tro</code>, <code>mat_khau</code>, <code>so_dien_thoai</code>.</p>
            <input type="file" name="file" accept=".xls,.xlsx,.csv" class="form-control" required>
            <footer style="display:flex;gap:10px;justify-content:flex-end;padding-top:12px;">
                <button type="button" onclick="closeImportModal()" class="btn btn-secondary">Hủy</button>
                <button type="submit" class="btn btn-primary"><i class="bi bi-upload"></i> Tải lên</button>
            </footer>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const addModal = document.getElementById('addModal');
    const editModal = document.getElementById('editModal');
    const importModal = document.getElementById('importModal');

    function openAddModal(){ addModal.classList.add('show'); }
    function closeAddModal(){ addModal.classList.remove('show'); }
    function openEditModal(id, hoTen, mssv, email, vaiTro, sdt){
        const form = document.getElementById('editForm');
        form.action = `/admin/nguoi-dung/${id}`;
        document.getElementById('edit_ho_ten').value = hoTen;
        document.getElementById('edit_ma_sinh_vien').value = mssv;
        document.getElementById('edit_email').value = email;
        document.getElementById('edit_vai_tro').value = vaiTro;
        document.getElementById('edit_so_dien_thoai').value = sdt || '';
        editModal.classList.add('show');
    }
    function closeEditModal(){ editModal.classList.remove('show'); }
    function openImportModal(){ importModal.classList.add('show'); }
    function closeImportModal(){ importModal.classList.remove('show'); }
</script>
@endsection
