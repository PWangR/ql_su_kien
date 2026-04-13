@extends('admin.layout')
@section('title', 'Tạo cuộc bầu cử')
@section('page-title', 'Tạo cuộc bầu cử mới')

@section('content')
<div style="max-width: 800px; margin: 0 auto; padding-bottom: var(--space-2xl);">
    
    <div style="margin-bottom: var(--space-xl);">
        <h2 style="font-family: var(--font-serif); font-size: 1.75rem; color: var(--text); margin-bottom: var(--space-xs);">
            Thiết lập Bầu cử
        </h2>
        <p class="text-muted">Cấu hình thời gian và quy tắc kiểm phiếu cho sự kiện bỏ phiếu mới.</p>
    </div>

    <form method="POST" action="{{ route('admin.bau-cu.store') }}">
        @csrf
        
        {{-- THÔNG TIN CHUNG --}}
        <div class="card" style="margin-bottom: var(--space-lg);">
            <div class="card-header" style="border-bottom: 1px solid var(--border-light); padding: var(--space-lg) var(--space-xl);">
                <div class="card-title" style="font-size: 1.1rem; display: flex; align-items: center;">
                    <span style="display: flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 8px; background: rgba(var(--primary-rgb, 59, 130, 246), 0.1); color: var(--primary); margin-right: 12px;">
                        <i class="bi bi-info-circle"></i>
                    </span>
                    Thông tin cơ bản
                </div>
            </div>
            <div class="card-body" style="padding: var(--space-xl);">
                <div class="form-group">
                    <label class="form-label">Tiêu đề *</label>
                    <input type="text" name="tieu_de" class="form-control" value="{{ old('tieu_de') }}" required placeholder="VD: Bầu ban chấp hành Đoàn khoa CNTT nhiệm kỳ 2024-2025" style="font-size: 1.05rem; padding: 12px 14px;">
                    @error('tieu_de') <div class="text-xs text-danger mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="form-group mb-0">
                    <label class="form-label">Mô tả chi tiết</label>
                    <textarea name="mo_ta" class="form-control" rows="4" placeholder="Mô tả về mục đích và các lưu ý của cuộc bầu cử này (cho phép cử tri nắm rõ quy tắc).">{{ old('mo_ta') }}</textarea>
                </div>
            </div>
        </div>

        {{-- THỜI GIAN --}}
        <div class="card" style="margin-bottom: var(--space-lg);">
            <div class="card-header" style="border-bottom: 1px solid var(--border-light); padding: var(--space-lg) var(--space-xl);">
                <div class="card-title" style="font-size: 1.1rem; display: flex; align-items: center;">
                    <span style="display: flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 8px; background: rgba(var(--accent-rgb, 139, 92, 246), 0.1); color: var(--accent); margin-right: 12px;">
                        <i class="bi bi-clock-history"></i>
                    </span>
                    Thiết lập thời gian
                </div>
            </div>
            <div class="card-body" style="padding: var(--space-xl);">
                <div class="input-grid form-group mb-0">
                    <div>
                        <label class="form-label">Thời gian bắt đầu *</label>
                        <input type="datetime-local" name="thoi_gian_bat_dau" class="form-control" value="{{ old('thoi_gian_bat_dau') }}" required>
                        @error('thoi_gian_bat_dau') <div class="text-xs text-danger mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label class="form-label">Thời gian kết thúc *</label>
                        <input type="datetime-local" name="thoi_gian_ket_thuc" class="form-control" value="{{ old('thoi_gian_ket_thuc') }}" required>
                        @error('thoi_gian_ket_thuc') <div class="text-xs text-danger mt-1">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="text-xs text-muted" style="margin-top: 14px; background: var(--bg-alt); padding: 10px 14px; border-radius: var(--border-radius-sm); border-left: 3px solid var(--border);">
                    <i class="bi bi-exclamation-triangle" style="margin-right:4px;"></i> Hệ thống chỉ mở cổng ghi nhận phiếu cho cử tri trong đúng khung thời gian thiết lập này.
                </div>
            </div>
        </div>

        {{-- QUY TẮC BỎ PHIẾU --}}
        <div class="card" style="margin-bottom: var(--space-xl);">
            <div class="card-header" style="border-bottom: 1px solid var(--border-light); padding: var(--space-lg) var(--space-xl);">
                <div class="card-title" style="font-size: 1.1rem; display: flex; align-items: center;">
                    <span style="display: flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 8px; background: rgba(var(--success-rgb, 16, 185, 129), 0.1); color: var(--success); margin-right: 12px;">
                        <i class="bi bi-ui-checks-grid"></i>
                    </span>
                    Quy định hòm phiếu
                </div>
            </div>
            <div class="card-body" style="padding: var(--space-xl);">
                <div class="input-grid form-group mb-0">
                    <div>
                        <label class="form-label">Số người bắt buộc phải chọn (Tối thiểu) *</label>
                        <input type="number" name="so_chon_toi_thieu" class="form-control" value="{{ old('so_chon_toi_thieu', 1) }}" min="1" required style="font-size:1.1rem; text-align:center;">
                    </div>
                    <div>
                        <label class="form-label">Số người có thể chọn (Tối đa) *</label>
                        <input type="number" name="so_chon_toi_da" class="form-control" value="{{ old('so_chon_toi_da', 1) }}" min="1" required style="font-size:1.1rem; text-align:center;">
                    </div>
                </div>
                <div class="text-xs text-muted" style="margin-top: 14px; background: var(--bg-alt); padding: 10px 14px; border-radius: var(--border-radius-sm); border-left: 3px solid var(--border);">
                    <i class="bi bi-check2-circle" style="margin-right:4px;"></i> <strong>Mẹo:</strong> Nếu bạn thiết lập Tối thiểu bằng với Tối đa (ví dụ 3 và 3), thì người bầu cử bắt buộc phải đánh dấu chính xác 3 người để tạo thành phiếu bầu hợp lệ.
                </div>
            </div>
        </div>

        {{-- ACTIONS --}}
        <div style="display:flex; justify-content:space-between; align-items:center; padding-top: var(--space-md);">
            <a href="{{ route('admin.bau-cu.index') }}" class="btn btn-secondary" style="padding: 10px 20px;">
                <i class="bi bi-arrow-left"></i> Trở về danh sách
            </a>
            <button type="submit" class="btn btn-primary" style="padding: 12px 28px; font-size: 1.05rem;">
                <i class="bi bi-save"></i> Hoàn tất tạo & Tiếp tục khai báo
            </button>
        </div>
    </form>
</div>
@endsection
