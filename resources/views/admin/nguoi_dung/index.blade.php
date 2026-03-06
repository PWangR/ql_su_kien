@extends('admin.layout')

@section('title', 'Quản lý người dùng')
@section('page-title', 'Quản lý người dùng')

@section('content')
<div class="card mb-3">
    <div class="card-header">
        <div class="card-title"><i class="bi bi-people-fill" style="color:var(--primary)"></i> Danh sách người dùng</div>
        <button class="btn btn-primary" onclick="document.getElementById('addModal').style.display='flex'">
            <i class="bi bi-person-plus-fill"></i> Thêm mới
        </button>
    </div>

    <!-- Filter -->
    <div style="padding:14px 20px;border-bottom:1px solid var(--border);background:#fafafa;">
        <form method="GET" style="display:flex;gap:10px;flex-wrap:wrap;">
            <input type="text" name="search" class="form-control" placeholder="Tìm kiếm tên, email, MSSV..." value="{{ request('search') }}" style="max-width:280px;">
            <select name="vai_tro" class="form-control" style="max-width:180px;">
                <option value="">-- Vai trò --</option>
                @foreach($vaiTro as $vt)
                <option value="{{ $vt->ma_vai_tro }}" {{ request('vai_tro') == $vt->ma_vai_tro ? 'selected' : '' }}>{{ $vt->ten_vai_tro }}</option>
                @endforeach
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
                        <span class="badge {{ $nd->vaiTro->ten_vai_tro === 'admin' ? 'badge-primary' : 'badge-secondary' }}">
                            {{ $nd->vaiTro->ten_vai_tro ?? '—' }}
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
<div id="addModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:999;align-items:center;justify-content:center;padding:20px;">
    <div style="background:#fff;border-radius:16px;width:100%;max-width:500px;max-height:90vh;overflow-y:auto;">
        <div style="padding:20px 24px;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;">
            <h3 style="font-size:16px;font-weight:700;">Thêm người dùng mới</h3>
            <button onclick="document.getElementById('addModal').style.display='none'" style="background:none;border:none;font-size:20px;cursor:pointer;color:var(--text-light);">&times;</button>
        </div>
        <form method="POST" action="{{ route('admin.nguoi-dung.store') }}" style="padding:24px;">
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
                <select name="ma_vai_tro" class="form-control" required>
                    @foreach($vaiTro as $vt)
                    <option value="{{ $vt->ma_vai_tro }}">{{ $vt->ten_vai_tro }}</option>
                    @endforeach
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
            <div style="display:flex;gap:10px;">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check"></i> Thêm</button>
                <button type="button" onclick="document.getElementById('addModal').style.display='none'" class="btn btn-secondary">Hủy</button>
            </div>
        </form>
    </div>
</div>
@endsection
