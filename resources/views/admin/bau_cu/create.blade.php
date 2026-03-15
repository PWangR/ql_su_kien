@extends('admin.layout')
@section('title', 'Tạo cuộc bầu cử')
@section('page-title', 'Tạo cuộc bầu cử mới')

@section('content')
<div class="card" style="max-width:700px;">
    <div class="card-header">
        <h3 class="card-title"><i class="bi bi-plus-circle"></i> Thông tin cuộc bầu cử</h3>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.bau-cu.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Tiêu đề <span style="color:var(--danger)">*</span></label>
                <input type="text" name="tieu_de" class="form-control @error('tieu_de') is-invalid @enderror"
                       value="{{ old('tieu_de') }}" required placeholder="VD: Bầu ban chấp hành Đoàn khoa CNTT">
                @error('tieu_de') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Mô tả</label>
                <textarea name="mo_ta" class="form-control" rows="3" placeholder="Mô tả chi tiết (tùy chọn)">{{ old('mo_ta') }}</textarea>
            </div>

            <div class="input-grid mb-3">
                <div>
                    <label class="form-label">Thời gian bắt đầu <span style="color:var(--danger)">*</span></label>
                    <input type="datetime-local" name="thoi_gian_bat_dau" class="form-control @error('thoi_gian_bat_dau') is-invalid @enderror"
                           value="{{ old('thoi_gian_bat_dau') }}" required>
                    @error('thoi_gian_bat_dau') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label class="form-label">Thời gian kết thúc <span style="color:var(--danger)">*</span></label>
                    <input type="datetime-local" name="thoi_gian_ket_thuc" class="form-control @error('thoi_gian_ket_thuc') is-invalid @enderror"
                           value="{{ old('thoi_gian_ket_thuc') }}" required>
                    @error('thoi_gian_ket_thuc') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="input-grid mb-3">
                <div>
                    <label class="form-label">Số chọn tối thiểu <span style="color:var(--danger)">*</span></label>
                    <input type="number" name="so_chon_toi_thieu" class="form-control" value="{{ old('so_chon_toi_thieu', 1) }}" min="1" required>
                </div>
                <div>
                    <label class="form-label">Số chọn tối đa <span style="color:var(--danger)">*</span></label>
                    <input type="number" name="so_chon_toi_da" class="form-control" value="{{ old('so_chon_toi_da', 1) }}" min="1" required>
                </div>
            </div>

            <div class="d-flex gap-2 mt-3">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Tạo cuộc bầu cử</button>
                <a href="{{ route('admin.bau-cu.index') }}" class="btn btn-secondary">Hủy</a>
            </div>
        </form>
    </div>
</div>
@endsection
