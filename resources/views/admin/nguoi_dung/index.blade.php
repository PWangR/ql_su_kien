@extends('admin.layout')

@section('title', 'Quan ly nguoi dung')
@section('page-title', 'Quan ly nguoi dung')

@section('styles')
<style>
.modal-layer { position:fixed; inset:0; display:none; align-items:center; justify-content:center; background:rgba(0,0,0,0.4); z-index:999; padding:18px; }
.modal-layer.show { display:flex; }
.modal-card { background:var(--card); border:1px solid var(--border); border-radius:var(--border-radius-md); width:100%; max-width:540px; overflow:hidden; }
.modal-card header { padding:16px 20px; border-bottom:1px solid var(--border); display:flex; justify-content:space-between; align-items:center; }
.modal-card .body { padding:20px; }
.modal-card footer { padding:14px 20px; border-top:1px solid var(--border); display:flex; justify-content:flex-end; gap:10px; }
</style>
@endsection

@section('content')
<div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:var(--space-md);margin-bottom:var(--space-lg);">
    <p class="text-muted text-sm">Quan ly vai tro, trang thai va tai khoan dang nhap.</p>
    <div class="btn-group">
        <button class="btn btn-secondary" type="button" onclick="openImportModal()"><i class="bi bi-upload"></i> Nhap Excel</button>
        <button class="btn btn-primary" type="button" onclick="openAddModal()"><i class="bi bi-person-plus"></i> Them moi</button>
    </div>
</div>

<div class="card">
    <div style="padding:14px 20px;border-bottom:1px solid var(--border);background:var(--bg-alt);">
        <form method="GET" style="display:flex;gap:10px;flex-wrap:wrap;">
            <input type="text" name="search" class="form-control" placeholder="Tim ten, email, MSSV..." value="{{ request('search') }}" style="max-width:260px;">
            <select name="vai_tro" class="form-control" style="max-width:160px;">
                <option value="">-- Vai tro --</option>
                <option value="admin" {{ request('vai_tro') == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="sinh_vien" {{ request('vai_tro') == 'sinh_vien' ? 'selected' : '' }}>Sinh vien</option>
            </select>
            <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> Tim</button>
            <a href="{{ route('admin.nguoi-dung.index') }}" class="btn btn-secondary">Dat lai</a>
        </form>
    </div>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Nguoi dung</th>
                    <th>MSSV</th>
                    <th>Email</th>
                    <th>Vai tro</th>
                    <th>Trang thai</th>
                    <th>Thao tac</th>
                </tr>
            </thead>
            <tbody>
                @forelse($nguoiDung as $nd)
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px;">
                            <div class="avatar">{{ mb_substr($nd->ho_ten, 0, 1) }}</div>
                            <strong>{{ $nd->ho_ten }}</strong>
                        </div>
                    </td>
                    <td>{{ $nd->ma_sinh_vien }}</td>
                    <td class="text-sm">{{ $nd->email }}</td>
                    <td>
                        <span class="badge {{ $nd->vai_tro === 'admin' ? 'badge-primary' : 'badge-secondary' }}">
                            {{ $nd->vai_tro === 'admin' ? 'Admin' : 'Sinh vien' }}
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
                                'hoat_dong' => 'Hoat dong',
                                'khong_hoat_dong' => 'Khong hoat dong',
                                'bi_khoa' => 'Bi khoa',
                            ];
                        @endphp
                        <span class="badge {{ $statusMap[$nd->trang_thai] ?? 'badge-secondary' }}">{{ $statusLabel[$nd->trang_thai] ?? 'Khong ro' }}</span>
                    </td>
                    <td>
                        <div class="btn-group">
                            <button class="btn btn-secondary btn-sm" title="Sua" onclick='openEditModal(@js($nd->ma_sinh_vien), @js($nd->ho_ten), @js($nd->ma_sinh_vien), @js($nd->email), @js($nd->vai_tro), @js($nd->so_dien_thoai))'>
                                <i class="bi bi-pencil"></i>
                            </button>
                            <form method="POST" action="{{ route('admin.nguoi-dung.toggle-status', $nd->ma_sinh_vien) }}" style="display:inline;">
                                @csrf
                                <button class="btn btn-secondary btn-sm" title="{{ $nd->trang_thai === 'bi_khoa' ? 'Mo khoa' : 'Khoa' }}">
                                    <i class="bi bi-{{ $nd->trang_thai === 'bi_khoa' ? 'unlock' : 'lock' }}"></i>
                                </button>
                            </form>
                            @if($nd->ma_sinh_vien !== auth()->id())
                            <form method="POST" action="{{ route('admin.nguoi-dung.destroy', $nd->ma_sinh_vien) }}" style="display:inline;" onsubmit="return confirm('Xoa nguoi dung nay?')">
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
                    <td colspan="6" style="text-align:center;padding:var(--space-2xl);color:var(--text-muted);">
                        <i class="bi bi-people" style="font-size:28px;display:block;margin-bottom:6px;opacity:0.3;"></i>Chua co nguoi dung
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($nguoiDung->hasPages())
    <div style="padding:var(--space-md) 20px;border-top:1px solid var(--border-light);">{{ $nguoiDung->links() }}</div>
    @endif
</div>

<div id="addModal" class="modal-layer">
    <div class="modal-card">
        <header><h3 style="font-size:1rem;font-weight:600;">Them nguoi dung moi</h3><button onclick="closeAddModal()" style="background:none;border:none;font-size:18px;cursor:pointer;color:var(--text-light);">&times;</button></header>
        <form method="POST" action="{{ route('admin.nguoi-dung.store') }}" class="body">
            @csrf
            <div class="form-group"><label class="form-label">Ho ten *</label><input type="text" name="ho_ten" class="form-control" required></div>
            <div class="form-group"><label class="form-label">Ma sinh vien *</label><input type="text" name="ma_sinh_vien" class="form-control" required inputmode="numeric" pattern="[0-9]{8}" maxlength="8"></div>
            <div class="form-group"><label class="form-label">Email *</label><input type="email" name="email" class="form-control" required></div>
            <div class="form-group"><label class="form-label">Vai tro *</label><select name="vai_tro" class="form-control" required><option value="sinh_vien">Sinh vien</option><option value="admin">Admin</option></select></div>
            <div class="form-group"><label class="form-label">Mat khau *</label><input type="password" name="mat_khau" class="form-control" required minlength="8"></div>
            <div class="form-group"><label class="form-label">So dien thoai</label><input type="text" name="so_dien_thoai" class="form-control"></div>
            <footer style="display:flex;gap:10px;justify-content:flex-end;padding-top:8px;">
                <button type="button" onclick="closeAddModal()" class="btn btn-secondary">Huy</button>
                <button type="submit" class="btn btn-primary"><i class="bi bi-check"></i> Them</button>
            </footer>
        </form>
    </div>
</div>

<div id="editModal" class="modal-layer">
    <div class="modal-card">
        <header><h3 style="font-size:1rem;font-weight:600;">Cap nhat nguoi dung</h3><button onclick="closeEditModal()" style="background:none;border:none;font-size:18px;cursor:pointer;color:var(--text-light);">&times;</button></header>
        <form method="POST" id="editForm" class="body">
            @csrf
            @method('PUT')
            <div class="form-group"><label class="form-label">Ho ten *</label><input type="text" name="ho_ten" id="edit_ho_ten" class="form-control" required></div>
            <div class="form-group"><label class="form-label">Ma sinh vien</label><input type="text" name="ma_sinh_vien" id="edit_ma_sinh_vien" class="form-control" readonly style="opacity:0.6;"></div>
            <div class="form-group"><label class="form-label">Email</label><input type="email" name="email" id="edit_email" class="form-control" readonly style="opacity:0.6;"></div>
            <div class="form-group"><label class="form-label">Vai tro *</label><select name="vai_tro" id="edit_vai_tro" class="form-control" required><option value="sinh_vien">Sinh vien</option><option value="admin">Admin</option></select></div>
            <div class="form-group"><label class="form-label">Mat khau moi (bo trong neu giu nguyen)</label><input type="password" name="mat_khau" class="form-control" minlength="8"></div>
            <div class="form-group"><label class="form-label">So dien thoai</label><input type="text" name="so_dien_thoai" id="edit_so_dien_thoai" class="form-control"></div>
            <footer style="display:flex;gap:10px;justify-content:flex-end;padding-top:8px;">
                <button type="button" onclick="closeEditModal()" class="btn btn-secondary">Huy</button>
                <button type="submit" class="btn btn-primary"><i class="bi bi-check2"></i> Luu</button>
            </footer>
        </form>
    </div>
</div>

<div id="importModal" class="modal-layer">
    <div class="modal-card">
        <header><h3 style="font-size:1rem;font-weight:600;">Nhap nguoi dung tu Excel</h3><button onclick="closeImportModal()" style="background:none;border:none;font-size:18px;cursor:pointer;color:var(--text-light);">&times;</button></header>
        <form method="POST" action="{{ route('admin.nguoi-dung.import') }}" enctype="multipart/form-data" class="body">
            @csrf
            <p class="text-sm text-muted" style="margin-bottom:12px;">Cot toi thieu: <code>ho_ten</code>, <code>ma_sinh_vien</code>, <code>email</code>.</p>
            <input type="file" name="file" accept=".xls,.xlsx,.csv" class="form-control" required>
            <footer style="display:flex;gap:10px;justify-content:flex-end;padding-top:12px;">
                <button type="button" onclick="closeImportModal()" class="btn btn-secondary">Huy</button>
                <button type="submit" class="btn btn-primary"><i class="bi bi-upload"></i> Tai len</button>
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

    function openAddModal() { addModal.classList.add('show'); }
    function closeAddModal() { addModal.classList.remove('show'); }
    function openEditModal(id, hoTen, mssv, email, vaiTro, sdt) {
        const form = document.getElementById('editForm');
        form.action = `/admin/nguoi-dung/${id}`;
        document.getElementById('edit_ho_ten').value = hoTen;
        document.getElementById('edit_ma_sinh_vien').value = mssv;
        document.getElementById('edit_email').value = email;
        document.getElementById('edit_vai_tro').value = vaiTro;
        document.getElementById('edit_so_dien_thoai').value = sdt || '';
        editModal.classList.add('show');
    }
    function closeEditModal() { editModal.classList.remove('show'); }
    function openImportModal() { importModal.classList.add('show'); }
    function closeImportModal() { importModal.classList.remove('show'); }
</script>
@endsection
