@extends('admin.layout')

@section('title', 'Quản lý người dùng')
@section('page-title', 'Quản lý người dùng')

@section('styles')
<style>
    .modal-layer {
        position: fixed;
        inset: 0;
        display: none;
        align-items: center;
        justify-content: center;
        background: rgba(0, 0, 0, 0.4);
        z-index: 999;
        padding: 18px;
    }

    .modal-layer.show {
        display: flex;
    }

    .modal-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--border-radius-md);
        width: 100%;
        max-width: 720px;
        overflow: hidden;
    }

    .modal-card header {
        padding: 16px 20px;
        border-bottom: 1px solid var(--border);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-card .body {
        padding: 20px;
        max-height: 80vh;
        overflow-y: auto;
    }

    .user-summary-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 16px;
        margin-bottom: var(--space-lg);
    }

    .user-summary-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--border-radius-md);
        padding: 16px;
    }

    .user-summary-label {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: var(--text-muted);
    }

    .user-summary-value {
        margin-top: 8px;
        font-size: 1.6rem;
        font-weight: 700;
    }

    .filter-grid {
        display: grid;
        grid-template-columns: minmax(220px, 1.2fr) minmax(160px, 0.8fr) minmax(160px, 0.8fr) minmax(180px, 0.9fr) auto;
        gap: 10px;
        align-items: end;
    }

    .user-cell {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 240px;
    }

    .user-avatar {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: var(--bg-alt);
        border: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        font-weight: 700;
        color: var(--accent);
        flex-shrink: 0;
    }

    .user-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .user-meta {
        display: flex;
        flex-direction: column;
        gap: 4px;
        min-width: 0;
    }

    .user-meta strong {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .verification-chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        border-radius: 999px;
        padding: 4px 10px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .verification-chip.verified {
        background: #ecfdf5;
        color: #065f46;
        border: 1px solid #10b981;
    }

    .verification-chip.pending {
        background: #fff7ed;
        color: #9a3412;
        border: 1px solid #fb923c;
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;
    }

    .avatar-upload-panel {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 14px;
        border: 1px dashed var(--border);
        border-radius: var(--border-radius);
        margin-bottom: 16px;
        background: var(--bg-alt);
    }

    .avatar-preview {
        width: 72px;
        height: 72px;
        border-radius: 50%;
        border: 1px solid var(--border);
        background: var(--card);
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        flex-shrink: 0;
        font-size: 1.4rem;
        font-weight: 700;
        color: var(--accent);
    }

    .avatar-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .modal-note {
        margin-bottom: 16px;
        padding: 12px 14px;
        border: 1px solid #f59e0b;
        background: #fffbeb;
        color: #92400e;
        border-radius: var(--border-radius);
        font-size: 0.875rem;
    }

    .table-actions {
        display: flex;
        justify-content: flex-end;
    }

    @media (max-width: 992px) {
        .user-summary-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .filter-grid {
            grid-template-columns: 1fr 1fr;
        }
    }

    @media (max-width: 768px) {

        .form-grid,
        .user-summary-grid,
        .filter-grid {
            grid-template-columns: 1fr;
        }

        .modal-card {
            max-width: 96vw;
        }
    }
</style>
@endsection

@section('content')
@php
$tongNguoiDung = $nguoiDung->total();
$tongAdmin = $nguoiDung->getCollection()->where('vai_tro', 'admin')->count();
$tongSinhVien = $nguoiDung->getCollection()->where('vai_tro', 'sinh_vien')->count();
$tongChuaXacThuc = $nguoiDung->getCollection()->whereNull('email_verified_at')->count();
@endphp

<div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:var(--space-md);margin-bottom:var(--space-lg);">
    <div>
        <p class="text-muted text-sm" style="margin:0 0 6px;">Quản lý tài khoản truy cập, ảnh đại diện và trạng thái xác thực email.</p>
        <div class="text-xs text-muted">Tài khoản tạo mới sẽ gửi email xác thực ngay sau khi lưu, nên SMTP phải đang hoạt động.</div>
    </div>
    <button class="btn btn-primary" type="button" onclick="window.location.href='{{ route('admin.nguoi-dung.create') }}'"><i class="bi bi-person-plus"></i> Thêm mới</button>
</div>

<div class="user-summary-grid">
    <div class="user-summary-card">
        <div class="user-summary-label">Tổng kết quả lọc</div>
        <div class="user-summary-value">{{ $tongNguoiDung }}</div>
    </div>
    <div class="user-summary-card">
        <div class="user-summary-label">Admin</div>
        <div class="user-summary-value">{{ $tongAdmin }}</div>
    </div>
    <div class="user-summary-card">
        <div class="user-summary-label">Sinh viên</div>
        <div class="user-summary-value">{{ $tongSinhVien }}</div>
    </div>
    <div class="user-summary-card">
        <div class="user-summary-label">Chưa xác thực email</div>
        <div class="user-summary-value">{{ $tongChuaXacThuc }}</div>
    </div>
</div>

<div class="card">
    <div style="padding:14px 20px;border-bottom:1px solid var(--border);background:var(--bg-alt);">
        <form method="GET" class="filter-grid">
            <div>
                <label class="form-label">Tìm nhanh</label>
                <input type="text" name="search" class="form-control" placeholder="Tên, lớp, MSSV, email..." value="{{ request('search') }}">
            </div>
            <div>
                <label class="form-label">Lớp</label>
                <input type="text" name="lop" class="form-control" placeholder="VD: 64.CNTT-1" value="{{ request('lop') }}">
            </div>
            <div>
                <label class="form-label">Vai trò</label>
                <select name="vai_tro" class="form-control">
                    <option value="">-- Tất cả --</option>
                    <option value="admin" {{ request('vai_tro') === 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="sinh_vien" {{ request('vai_tro') === 'sinh_vien' ? 'selected' : '' }}>Sinh viên</option>
                </select>
            </div>
            <div>
                <label class="form-label">Xác thực email</label>
                <select name="xac_thuc_email" class="form-control">
                    <option value="">-- Tất cả --</option>
                    <option value="da_xac_thuc" {{ request('xac_thuc_email') === 'da_xac_thuc' ? 'selected' : '' }}>Đã xác thực</option>
                    <option value="chua_xac_thuc" {{ request('xac_thuc_email') === 'chua_xac_thuc' ? 'selected' : '' }}>Chưa xác thực</option>
                </select>
            </div>
            <div class="btn-group">
                <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> Lọc</button>
                <a href="{{ route('admin.nguoi-dung.index') }}" class="btn btn-secondary">Đặt lại</a>
            </div>
        </form>
    </div>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th style="min-width:260px;">Người dùng</th>
                    <th>MSSV</th>
                    <th>Lớp</th>
                    <th>Email</th>
                    <th>Xác thực</th>
                    <th>Vai trò</th>
                    <th>Trạng thái</th>
                    <th style="text-align:right;">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($nguoiDung as $nd)
                <tr>
                    <td>
                        <div class="user-cell">
                            <div class="user-avatar">
                                @if($nd->duong_dan_anh)
                                <img src="{{ asset('storage/'.$nd->duong_dan_anh) }}" alt="{{ $nd->ho_ten }}">
                                @else
                                {{ mb_substr($nd->ho_ten, 0, 1) }}
                                @endif
                            </div>
                            <div class="user-meta">
                                <strong>{{ $nd->ho_ten }}</strong>
                                <span class="text-xs text-muted">{{ $nd->so_dien_thoai ?: 'Chưa có số điện thoại' }}</span>
                            </div>
                        </div>
                    </td>
                    <td><span class="mono">{{ $nd->ma_sinh_vien }}</span></td>
                    <td>{{ $nd->lop }}</td>
                    <td style="min-width:240px;">
                        <div>{{ $nd->email }}</div>
                        <div class="text-xs text-muted">Tạo lúc {{ optional($nd->created_at)->format('d/m/Y H:i') }}</div>
                    </td>
                    <td>
                        @if($nd->email_verified_at)
                        <span class="verification-chip verified"><i class="bi bi-patch-check"></i> Đã xác thực</span>
                        @else
                        <span class="verification-chip pending"><i class="bi bi-hourglass-split"></i> Chờ xác thực</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge {{ $nd->vai_tro === 'admin' ? 'badge-primary' : 'badge-secondary' }}">
                            {{ $nd->vai_tro === 'admin' ? 'Admin' : 'Sinh viên' }}
                        </span>
                    </td>
                    <td>
                        @php
                        $statusMap = [
                        'hoat_dong' => 'badge-success',
                        'khong_hoat_dong' => 'badge-secondary',
                        'bi_khoa' => 'badge-danger',
                        ];
                        $statusLabel = [
                        'hoat_dong' => 'Hoạt động',
                        'khong_hoat_dong' => 'Không hoạt động',
                        'bi_khoa' => 'Bị khóa',
                        ];
                        @endphp
                        <span class="badge {{ $statusMap[$nd->trang_thai] ?? 'badge-secondary' }}">{{ $statusLabel[$nd->trang_thai] ?? 'Không rõ' }}</span>
                    </td>
                    <td>
                        <div class="btn-group table-actions">
                            <button class="btn btn-secondary btn-sm"
                                title="Sửa"
                                data-id="{{ $nd->ma_sinh_vien }}"
                                data-hoten="{{ $nd->ho_ten }}"
                                data-email="{{ $nd->email }}"
                                data-vaitro="{{ $nd->vai_tro }}"
                                data-sdt="{{ $nd->so_dien_thoai }}"
                                data-lop="{{ $nd->lop }}"
                                data-trangthai="{{ $nd->trang_thai }}"
                                data-avatar="{{ $nd->duong_dan_anh ? asset('storage/'.$nd->duong_dan_anh) : '' }}"
                                onclick="openEditModal(this)">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <form method="POST" action="{{ route('admin.nguoi-dung.toggle-status', $nd->ma_sinh_vien) }}" style="display:inline;">
                                @csrf
                                <button class="btn btn-secondary btn-sm" title="{{ $nd->trang_thai === 'bi_khoa' ? 'Mở khóa' : 'Khóa' }}">
                                    <i class="bi bi-{{ $nd->trang_thai === 'bi_khoa' ? 'unlock' : 'lock' }}"></i>
                                </button>
                            </form>
                            @if($nd->ma_sinh_vien !== auth()->id())
                            <form method="POST" action="{{ route('admin.nguoi-dung.destroy', $nd->ma_sinh_vien) }}" style="display:inline;" onsubmit="return confirm('Xóa người dùng này?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align:center;padding:var(--space-2xl);color:var(--text-muted);">
                        <i class="bi bi-people" style="font-size:28px;display:block;margin-bottom:6px;opacity:0.3;"></i>Chưa có người dùng phù hợp bộ lọc
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($nguoiDung->hasPages())
    <div style="padding:var(--space-md) 20px;border-top:1px solid var(--border-light);display:flex;justify-content:center;">{{ $nguoiDung->links() }}</div>
    @endif
</div>

<div id="addModal" class="modal-layer">
    <div class="modal-card">
        <header>
            <h3 style="font-size:1rem;font-weight:600;">Thêm người dùng mới</h3>
            <button type="button" onclick="closeAddModal()" style="background:none;border:none;font-size:18px;cursor:pointer;color:var(--text-light);">&times;</button>
        </header>
        <form method="POST" action="{{ route('admin.nguoi-dung.store') }}" enctype="multipart/form-data" class="body">
            @csrf
            <div class="modal-note">
                Tài khoản mới sẽ được gửi email xác thực giống luồng đăng ký công khai. Nếu SMTP chưa hoạt động, hệ thống sẽ không cho tạo tài khoản.
            </div>

            <div class="avatar-upload-panel">
                <div class="avatar-preview" id="addAvatarPreview">{{ mb_substr(auth()->user()->ho_ten, 0, 1) }}</div>
                <div style="flex:1;">
                    <label class="form-label">Ảnh đại diện</label>
                    <input type="file" name="duong_dan_anh" id="addAvatarInput" class="form-control" accept="image/*">
                    <div class="text-xs text-muted" style="margin-top:6px;">Hỗ trợ JPG, PNG, GIF, WEBP tối đa 2MB.</div>
                </div>
            </div>

            <div class="form-grid">
                <div class="form-group"><label class="form-label">Họ tên *</label><input type="text" name="ho_ten" class="form-control" required></div>
                <div class="form-group"><label class="form-label">Mã sinh viên *</label><input type="text" name="ma_sinh_vien" class="form-control" required inputmode="numeric" pattern="[0-9]{8}" maxlength="8"></div>
                <div class="form-group"><label class="form-label">Lớp *</label><input type="text" name="lop" class="form-control" placeholder="VD: 64.CNTT-1" required></div>
                <div class="form-group"><label class="form-label">Email *</label><input type="email" name="email" class="form-control" required></div>
                <div class="form-group"><label class="form-label">Vai trò *</label>
                    <select name="vai_tro" class="form-control" required>
                        <option value="sinh_vien">Sinh viên</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div class="form-group"><label class="form-label">Số điện thoại</label><input type="text" name="so_dien_thoai" class="form-control" placeholder="VD: 0912345678"></div>
                <div class="form-group"><label class="form-label">Mật khẩu *</label><input type="password" name="mat_khau" class="form-control" required minlength="8"></div>
                <div class="form-group"><label class="form-label">Xác nhận mật khẩu *</label><input type="password" name="mat_khau_confirmation" class="form-control" required minlength="8"></div>
            </div>

            <div style="display:flex;gap:10px;justify-content:flex-end;padding-top:8px;">
                <button type="button" onclick="closeAddModal()" class="btn btn-secondary">Hủy</button>
                <button type="submit" class="btn btn-primary"><i class="bi bi-check"></i> Tạo tài khoản</button>
            </div>
        </form>
    </div>
</div>

<div id="editModal" class="modal-layer">
    <div class="modal-card">
        <header>
            <h3 style="font-size:1rem;font-weight:600;">Cập nhật người dùng</h3>
            <button type="button" onclick="closeEditModal()" style="background:none;border:none;font-size:18px;cursor:pointer;color:var(--text-light);">&times;</button>
        </header>
        <form method="POST" id="editForm" enctype="multipart/form-data" class="body">
            @csrf
            @method('PUT')

            <div class="avatar-upload-panel">
                <div class="avatar-preview" id="editAvatarPreview">U</div>
                <div style="flex:1;">
                    <label class="form-label">Ảnh đại diện</label>
                    <input type="file" name="duong_dan_anh" id="editAvatarInput" class="form-control" accept="image/*">
                    <label style="display:flex;align-items:center;gap:8px;margin-top:8px;font-size:0.875rem;cursor:pointer;">
                        <input type="checkbox" name="xoa_anh_dai_dien" value="1"> Xóa ảnh đại diện hiện tại
                    </label>
                </div>
            </div>

            <div class="form-grid">
                <div class="form-group"><label class="form-label">Họ tên *</label><input type="text" name="ho_ten" id="edit_ho_ten" class="form-control" required></div>
                <div class="form-group"><label class="form-label">Mã sinh viên</label><input type="text" name="ma_sinh_vien" id="edit_ma_sinh_vien" class="form-control" readonly style="opacity:0.6;"></div>
                <div class="form-group"><label class="form-label">Email</label><input type="email" name="email" id="edit_email" class="form-control" readonly style="opacity:0.6;"></div>
                <div class="form-group"><label class="form-label">Vai trò *</label>
                    <select name="vai_tro" id="edit_vai_tro" class="form-control" required>
                        <option value="sinh_vien">Sinh viên</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div class="form-group"><label class="form-label">Lớp *</label><input type="text" name="lop" id="edit_lop" class="form-control" placeholder="VD: 64.CNTT-1" required></div>
                <div class="form-group"><label class="form-label">Số điện thoại</label><input type="text" name="so_dien_thoai" id="edit_so_dien_thoai" class="form-control"></div>
                <div class="form-group"><label class="form-label">Trạng thái</label>
                    <select name="trang_thai" id="edit_trang_thai" class="form-control">
                        <option value="hoat_dong">Hoạt động</option>
                        <option value="khong_hoat_dong">Không hoạt động</option>
                        <option value="bi_khoa">Bị khóa</option>
                    </select>
                </div>
                <div class="form-group"><label class="form-label">Mật khẩu mới</label><input type="password" name="mat_khau" class="form-control" minlength="8" placeholder="Bỏ trống nếu giữ nguyên"></div>
                <div class="form-group"><label class="form-label">Xác nhận mật khẩu mới</label><input type="password" name="mat_khau_confirmation" class="form-control" minlength="8" placeholder="Nhập lại mật khẩu mới"></div>
            </div>

            <div style="display:flex;gap:10px;justify-content:flex-end;padding-top:8px;">
                <button type="button" onclick="closeEditModal()" class="btn btn-secondary">Hủy</button>
                <button type="submit" class="btn btn-primary"><i class="bi bi-check2"></i> Lưu</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const addModal = document.getElementById('addModal');
    const editModal = document.getElementById('editModal');

    function setAvatarPreview(containerId, inputId, fallbackText = 'U', imageUrl = '') {
        const container = document.getElementById(containerId);
        const input = document.getElementById(inputId);

        const render = (src) => {
            container.innerHTML = src ?
                `<img src="${src}" alt="Avatar preview">` :
                fallbackText;
        };

        render(imageUrl);

        if (input) {
            input.onchange = (event) => {
                const [file] = event.target.files || [];
                if (!file) {
                    render(imageUrl);
                    return;
                }

                const reader = new FileReader();
                reader.onload = (e) => render(e.target.result);
                reader.readAsDataURL(file);
            };
        }
    }

    function openAddModal() {
        addModal.classList.add('show');
    }

    function closeAddModal() {
        addModal.classList.remove('show');
    }

    function openEditModal(btn) {
        const id = btn.getAttribute('data-id');
        const hoTen = btn.getAttribute('data-hoten');
        const email = btn.getAttribute('data-email');
        const vaiTro = btn.getAttribute('data-vaitro');
        const sdt = btn.getAttribute('data-sdt');
        const lop = btn.getAttribute('data-lop');
        const trangThai = btn.getAttribute('data-trangthai');
        const avatar = btn.getAttribute('data-avatar');

        const form = document.getElementById('editForm');
        form.action = `/admin/nguoi-dung/${id}`;
        document.getElementById('edit_ho_ten').value = hoTen;
        document.getElementById('edit_ma_sinh_vien').value = id;
        document.getElementById('edit_email').value = email;
        document.getElementById('edit_vai_tro').value = vaiTro;
        document.getElementById('edit_so_dien_thoai').value = sdt || '';
        document.getElementById('edit_lop').value = lop || '';
        document.getElementById('edit_trang_thai').value = trangThai || 'hoat_dong';
        document.querySelector('#editForm input[name="xoa_anh_dai_dien"]').checked = false;
        document.getElementById('editAvatarInput').value = '';
        setAvatarPreview('editAvatarPreview', 'editAvatarInput', hoTen ? hoTen.trim().charAt(0).toUpperCase() : 'U', avatar || '');
        editModal.classList.add('show');
    }

    function closeEditModal() {
        editModal.classList.remove('show');
    }

    document.querySelectorAll('.modal-layer').forEach((modal) => {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.remove('show');
            }
        });
    });

    setAvatarPreview('addAvatarPreview', 'addAvatarInput', '{{ mb_substr(auth()->user()->ho_ten, 0, 1) }}');
</script>
@endsection