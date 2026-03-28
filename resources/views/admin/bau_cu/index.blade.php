@extends('admin.layout')

@section('title', 'Quản lý Bầu cử')
@section('page-title', 'Quản lý Bầu cử')

@section('content')
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:var(--space-lg);">
    <div>
        <span class="text-muted text-sm">Tổng: {{ $bauCus->count() }} cuộc bầu cử</span>
    </div>
    <a href="{{ route('admin.bau-cu.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Tạo cuộc bầu cử
    </a>
</div>

<div class="card">
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Tiêu đề</th>
                    <th>Thời gian</th>
                    <th>Ứng cử viên</th>
                    <th>Cử tri</th>
                    <th>Đã bỏ phiếu</th>
                    <th>Trạng thái</th>
                    <th>Hiển thị</th>
                    <th style="text-align:right;">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bauCus as $bc)
                <tr>
                    <td class="text-xs text-muted">{{ $bc->ma_bau_cu }}</td>
                    <td>
                        <a href="{{ route('admin.bau-cu.show', $bc->ma_bau_cu) }}" style="color:var(--text);text-decoration:none;font-weight:600;">
                            {{ $bc->tieu_de }}
                        </a>
                    </td>
                    <td class="text-sm">
                        <i class="bi bi-calendar3"></i> {{ $bc->thoi_gian_bat_dau->format('d/m/Y H:i') }}<br>
                        <span class="text-muted text-xs">→ {{ $bc->thoi_gian_ket_thuc->format('d/m/Y H:i') }}</span>
                    </td>
                    <td>{{ $bc->so_ung_cu_vien }}</td>
                    <td>{{ $bc->so_cu_tri }}</td>
                    <td>
                        <strong style="color:var(--accent);">{{ $bc->so_da_bo_phieu }}</strong><span class="text-muted">/{{ $bc->so_cu_tri }}</span>
                    </td>
                    <td>
                        <span class="badge badge-{{ $bc->trang_thai_color }}">{{ $bc->trang_thai_label }}</span>
                    </td>
                    <td>
                        <form method="POST" action="{{ route('admin.bau-cu.toggle-visibility', $bc->ma_bau_cu) }}" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-sm {{ $bc->hien_thi ? 'btn-success' : 'btn-secondary' }}" title="{{ $bc->hien_thi ? 'Đang hiển thị' : 'Ẩn' }}">
                                <i class="bi {{ $bc->hien_thi ? 'bi-eye-fill' : 'bi-eye-slash' }}"></i>
                            </button>
                        </form>
                    </td>
                    <td style="text-align:right;">
                        <div class="btn-group">
                            <a href="{{ route('admin.bau-cu.edit', $bc->ma_bau_cu) }}" class="btn btn-sm btn-secondary">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.bau-cu.destroy', $bc->ma_bau_cu) }}" onsubmit="return confirm('Xóa cuộc bầu cử này?')" style="display:inline;">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" style="text-align:center;color:var(--text-muted);padding:var(--space-3xl);">
                        <i class="bi bi-inbox" style="font-size:32px;display:block;margin-bottom:8px;opacity:0.3;"></i>
                        Chưa có cuộc bầu cử nào
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
