@extends('admin.layout')

@section('title', 'Danh sách sự kiện')
@section('page-title', 'Quản lý sự kiện')

@section('styles')
    <style>
        .event-thumb {
            width: 72px;
            height: 48px;
            object-fit: cover;
            border: 1px solid var(--border);
            flex-shrink: 0;
        }

        .event-thumb-placeholder {
            width: 72px;
            height: 48px;
            background: var(--bg-alt);
            border: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--border);
            font-size: 18px;
            flex-shrink: 0;
        }

    </style>
@endsection

@section('content')
    <!-- Header -->
    <div
        style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:var(--space-md);margin-bottom:var(--space-lg);">
        <div>
            <p class="text-muted text-sm">Quản lý, tra cứu và khai thác dữ liệu sự kiện.</p>
        </div>
        <div class="btn-group">

            <a href="{{ route('admin.su-kien.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> Thêm sự kiện mới
            </a>
        </div>
    </div>

    <!-- Filter -->
    <div class="card" style="margin-bottom:var(--space-lg);">
        <div class="card-body" style="padding:14px 20px;">
            <form method="GET" style="display:flex;gap:10px;flex-wrap:wrap;align-items:flex-end;">
                <!-- Giữ lại các input hidden về sắp xếp để Lọc không làm mất Sort -->
                @if(request('sort_col')) <input type="hidden" name="sort_col" value="{{ request('sort_col') }}"> @endif
                @if(request('sort_dir')) <input type="hidden" name="sort_dir" value="{{ request('sort_dir') }}"> @endif

                <div style="flex:1;min-width:160px;">
                    <label class="form-label">Tìm kiếm</label>
                    <input type="text" name="search" class="form-control" placeholder="Tên sự kiện..."
                        value="{{ request('search') }}">
                </div>
                <div style="min-width:160px;">
                    <label class="form-label">Loại sự kiện</label>
                    <select name="ma_loai_su_kien" class="form-control">
                        <option value="">Tất cả thể loại</option>
                        @foreach($loaiSuKien as $loai)
                            <option value="{{ $loai->ma_loai_su_kien }}" {{ request('ma_loai_su_kien') == $loai->ma_loai_su_kien ? 'selected' : '' }}>{{ $loai->ten_loai }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="min-width:150px;">
                    <label class="form-label">Trạng thái</label>
                    <select name="trang_thai" class="form-control">
                        <option value="">Tất cả trạng thái</option>
                        <option value="sap_to_chuc" {{ request('trang_thai') == 'sap_to_chuc' ? 'selected' : '' }}>Sắp tổ chức</option>
                        <option value="dang_dien_ra" {{ request('trang_thai') == 'dang_dien_ra' ? 'selected' : '' }}>Đang diễn ra</option>
                        <option value="da_ket_thuc" {{ request('trang_thai') == 'da_ket_thuc' ? 'selected' : '' }}>Đã kết thúc</option>
                        <option value="huy" {{ request('trang_thai') == 'huy' ? 'selected' : '' }}>Đã hủy</option>
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
            <style>
                .sortable-link { color: inherit; text-decoration: none; display: flex; align-items: center; justify-content: space-between; transition: color 0.2s; }
                .sortable-link:hover { color: var(--primary); }
                .sort-icon { font-size:0.8rem; margin-left: 4px; }
            </style>
            @php
                function sortUrl($col) {
                    $curCol = request('sort_col');
                    $curDir = request('sort_dir', 'desc');
                    $newDir = ($curCol === $col && $curDir === 'desc') ? 'asc' : 'desc';
                    return request()->fullUrlWithQuery(['sort_col' => $col, 'sort_dir' => $newDir]);
                }
                function sortIcon($col) {
                    if (request('sort_col') !== $col) {
                        return '<i class="bi bi-arrow-down-up text-muted sort-icon" style="opacity: 0.4;"></i>';
                    }
                    return request('sort_dir') === 'asc' 
                        ? '<i class="bi bi-sort-up text-primary sort-icon"></i>' 
                        : '<i class="bi bi-sort-down text-primary sort-icon"></i>';
                }
            @endphp
            <table style="width: 100%;">
                <thead>
                    <tr>
                        <th style="width: 60px;">#</th>
                        <th style="width: 90px;">Ảnh</th>
                        <th><a href="{{ sortUrl('ten_su_kien') }}" class="sortable-link" style="justify-content: flex-start;">Thông tin sự kiện {!! sortIcon('ten_su_kien') !!}</a></th>
                        <th>Thời gian & Địa điểm</th>
                        <th style="width: 110px; text-align: center;">Đăng ký</th>
                        <th style="width: 130px;">Trạng thái</th>
                        <th style="text-align:right; width: 120px;">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($suKien as $sk)
                        <tr>
                            <td class="text-muted text-sm">#{{ $sk->ma_su_kien }}</td>
                            <td>
                                @if($sk->anh_su_kien)
                                    <img src="{{ asset('storage/' . $sk->anh_su_kien) }}" class="event-thumb">
                                @else
                                    <div class="event-thumb-placeholder"><i class="bi bi-image"></i></div>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.su-kien.show', $sk->ma_su_kien) }}"
                                    style="font-weight:600;color:var(--text);text-decoration:none;">{{ $sk->ten_su_kien }}</a>
                                <div style="margin-top:4px;display:flex;align-items:center;gap:6px;">
                                    <span class="badge badge-primary"
                                        style="font-size:0.625rem;">{{ $sk->loaiSuKien->ten_loai ?? 'Chưa phân loại' }}</span>
                                    @if($sk->diem_cong > 0)<span class="text-sm font-semibold" style="color:var(--warning);"><i
                                    class="bi bi-star"></i> +{{ $sk->diem_cong }}</span>@endif
                                </div>
                            </td>
                            <td>
                                <div class="text-sm text-muted"><i class="bi bi-calendar-event"></i>
                                    {{ $sk->thoi_gian_bat_dau?->format('d/m/Y H:i') }}</div>
                                <div class="text-sm text-muted"><i class="bi bi-geo-alt"></i>
                                    {{ Str::limit($sk->dia_diem ?: 'Chưa cập nhật', 30) }}</div>
                            </td>
                            <td style="text-align: center;">
                                <strong>{{ $sk->so_luong_hien_tai }} / {{ $sk->so_luong_toi_da ?: '∞' }}</strong>
                            </td>
                            <td>
                                @php
                                    $statusMap = [
                                        'sap_to_chuc' => 'badge-primary',
                                        'dang_dien_ra' => 'badge-success',
                                        'da_ket_thuc' => 'badge-secondary',
                                        'huy' => 'badge-danger',
                                    ];
                                @endphp
                                <span
                                    class="badge {{ $statusMap[$sk->trang_thai_thuc_te] ?? 'badge-secondary' }}">{{ $sk->trang_thai_label }}</span>
                            </td>
                            <td style="text-align:right;">
                                <div class="btn-group">
                                    <a href="{{ route('admin.su-kien.show', $sk->ma_su_kien) }}"
                                        class="btn btn-secondary btn-sm" title="Chi tiết"><i class="bi bi-eye"></i></a>
                                    <a href="{{ route('admin.su-kien.edit', $sk->ma_su_kien) }}"
                                        class="btn btn-secondary btn-sm" title="Sửa"><i class="bi bi-pencil"></i></a>
                                    <form method="POST" action="{{ route('admin.su-kien.destroy', $sk->ma_su_kien) }}"
                                        style="display:inline;" onsubmit="return confirm('Xác nhận xóa sự kiện này?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Xóa"><i
                                                class="bi bi-trash"></i></button>
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

@endsection