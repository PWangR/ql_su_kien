@extends('admin.layout')
@section('title', 'Sửa cuộc bầu cử')
@section('page-title', 'Sửa cuộc bầu cử')

@section('content')
<div class="card" style="max-width:700px;">
    <div class="card-header">
        <h3 class="card-title"><i class="bi bi-pencil-square"></i> Chỉnh sửa: {{ $bauCu->tieu_de }}</h3>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.bau-cu.update', $bauCu->ma_bau_cu) }}">
            @csrf @method('PUT')
            <div class="form-group">
                <label class="form-label">Tiêu đề *</label>
                <input type="text" name="tieu_de" class="form-control" value="{{ old('tieu_de', $bauCu->tieu_de) }}" required>
                @error('tieu_de') <div class="text-xs text-danger mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Mô tả</label>
                <textarea name="mo_ta" class="form-control" rows="3">{{ old('mo_ta', $bauCu->mo_ta) }}</textarea>
            </div>

            <div class="input-grid form-group">
                <div>
                    <label class="form-label">Thời gian bắt đầu *</label>
                    <input type="datetime-local" name="thoi_gian_bat_dau" class="form-control"
                           value="{{ old('thoi_gian_bat_dau', $bauCu->thoi_gian_bat_dau->format('Y-m-d\TH:i')) }}" required>
                </div>
                <div>
                    <label class="form-label">Thời gian kết thúc *</label>
                    <input type="datetime-local" name="thoi_gian_ket_thuc" class="form-control"
                           value="{{ old('thoi_gian_ket_thuc', $bauCu->thoi_gian_ket_thuc->format('Y-m-d\TH:i')) }}" required>
                </div>
            </div>

            <div class="input-grid form-group">
                <div>
                    <label class="form-label">Số chọn tối thiểu</label>
                    <input type="number" name="so_chon_toi_thieu" class="form-control"
                           value="{{ old('so_chon_toi_thieu', $bauCu->so_chon_toi_thieu) }}" min="1" required>
                </div>
                <div>
                    <label class="form-label">Số chọn tối đa</label>
                    <input type="number" name="so_chon_toi_da" class="form-control"
                           value="{{ old('so_chon_toi_da', $bauCu->so_chon_toi_da) }}" min="1" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Trạng thái</label>
                <select name="trang_thai" class="form-control">
                    <option value="nhap" {{ $bauCu->trang_thai === 'nhap' ? 'selected' : '' }}>Nháp</option>
                    <option value="dang_dien_ra" {{ $bauCu->trang_thai === 'dang_dien_ra' ? 'selected' : '' }}>Đang diễn ra</option>
                    <option value="hoan_thanh" {{ $bauCu->trang_thai === 'hoan_thanh' ? 'selected' : '' }}>Hoàn thành</option>
                    <option value="huy" {{ $bauCu->trang_thai === 'huy' ? 'selected' : '' }}>Hủy</option>
                </select>
            </div>

            <div class="btn-group mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Cập nhật</button>
                <a href="{{ route('admin.bau-cu.show', $bauCu->ma_bau_cu) }}" class="btn btn-secondary">Hủy</a>
            </div>
        </form>
    </div>
</div>
@endsection
