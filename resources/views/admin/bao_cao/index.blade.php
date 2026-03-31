@extends('admin.layout')
@section('title', 'Xuất Báo Cáo Lớp')
@section('content')
<div style="max-width:900px;margin:0 auto;">

    <div class="card">
        <!-- Header -->
        <div style="background:var(--accent);padding:var(--space-xl);color:#fff;border-radius:var(--border-radius-md) var(--border-radius-md) 0 0;">
            <h1 style="font-family:var(--font-serif);font-size:1.5rem;font-weight:700;margin:0;">
                <i class="bi bi-file-earmark-excel" style="margin-right:8px;"></i>Xuất Báo Cáo Điểm Theo Lớp
            </h1>
            <p style="margin:8px 0 0 0;opacity:0.9;font-size:0.9375rem;">Tạo báo cáo Excel chi tiết về điểm tích luỹ của sinh viên</p>
        </div>

        <!-- Content -->
        <div class="card-body" style="padding:var(--space-xl);">

            <!-- Error Messages -->
            @if($errors->any())
            <div class="alert alert-error" style="margin-bottom:var(--space-xl);">
                <i class="bi bi-exclamation-triangle"></i>
                <div>
                    @foreach($errors->all() as $error)
                    <div style="margin:4px 0;">• {{ $error }}</div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Form -->
            <form method="POST" action="{{ route('admin.bao-cao.export') }}" data-skip-loading="true" style="background:var(--bg-alt);padding:var(--space-xl);border-radius:var(--border-radius-md);margin-bottom:var(--space-xl);">
                @csrf

                <!-- Lớp (required) -->
                <div class="form-group">
                    <label class="form-label">Chọn Lớp <span style="color:#8B0000;">*</span></label>
                    <select name="lop" class="form-control @error('lop') is-invalid @enderror" required>
                        <option value="">-- Chọn lớp --</option>
                        @foreach($danhSachLop as $l)
                        <option value="{{ $l }}" @selected(old('lop')===$l)>{{ $l }}</option>
                        @endforeach
                    </select>
                    @error('lop')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Date Range -->
                <div class="input-grid" style="gap:var(--space-lg);">
                    <div class="form-group">
                        <label class="form-label">Từ Ngày (tùy chọn)</label>
                        <input type="date" name="from_date" class="form-control @error('from_date') is-invalid @enderror"
                            value="{{ old('from_date') }}" placeholder="YYYY-MM-DD">
                        <small style="color:var(--text-muted);display:block;margin-top:4px;">
                            Để trống = không lọc theo ngày
                        </small>
                        @error('from_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Đến Ngày (tùy chọn)</label>
                        <input type="date" name="to_date" class="form-control @error('to_date') is-invalid @enderror"
                            value="{{ old('to_date') }}" placeholder="YYYY-MM-DD">
                        <small style="color:var(--text-muted);display:block;margin-top:4px;">
                            Để trống = không lọc theo ngày
                        </small>
                        @error('to_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                @error('date_range')
                <div class="alert alert-warning" style="margin:var(--space-md) 0;">
                    <i class="bi bi-exclamation-circle"></i> {{ $message }}
                </div>
                @enderror

                <!-- Submit Button -->
                <div style="display:flex;gap:var(--space-md);margin-top:var(--space-xl);">
                    <button type="submit" class="btn btn-primary" style="flex:1;display:flex;align-items:center;justify-content:center;gap:8px;">
                        <i class="bi bi-download"></i>Xuất Excel
                    </button>
                    <a href="{{ route('home') }}" class="btn btn-secondary" style="flex:1;display:flex;align-items:center;justify-content:center;gap:8px;text-decoration:none;">
                        <i class="bi bi-arrow-left"></i>Quay Lại
                    </a>
                </div>
            </form>

            <!-- Instructions -->
            <div style="background:#E8F0E8;border-left:4px solid var(--success);padding:var(--space-lg);border-radius:var(--border-radius-md);">
                <h3 style="margin-top:0;color:var(--success);font-size:1rem;">
                    <i class="bi bi-info-circle"></i>Hướng dẫn sử dụng
                </h3>
                <ul style="margin:var(--space-md) 0;padding-left:20px;line-height:1.8;">
                    <li><strong>Bước 1:</strong> Chọn lớp từ dropdown</li>
                    <li><strong>Bước 2:</strong> (Tùy chọn) Chọn khoảng ngày để lọc điểm</li>
                    <li><strong>Bước 3:</strong> Click nút "Xuất Excel" để tải file báo cáo</li>
                    <li><strong>Lưu ý:</strong> Nếu không chọn ngày, báo cáo sẽ hiển thị tất cả điểm của sinh viên</li>
                </ul>

                <h4 style="margin-top:var(--space-lg);margin-bottom:var(--space-sm);color:var(--success);font-size:0.9375rem;">
                    Nội dung file Excel:
                </h4>
                <ul style="margin:var(--space-md) 0;padding-left:20px;line-height:1.8;font-size:0.9375rem;">
                    <li>Mã sinh viên (ID)</li>
                    <li>Tên sinh viên (Full name)</li>
                    <li>Lớp (Class)</li>
                    <li>Tổng điểm (Total points)</li>
                    <li>Khoảng ngày lọc</li>
                    <li>Thời gian xuất báo cáo</li>
                </ul>
            </div>

        </div>
    </div>

</div>
@endsection

@section('styles')
<style>
    .input-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
    }

    @media (max-width: 768px) {
        .input-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('scripts')
<script>
    // Hide loading overlay khi form submit hoàn tất
    // (Form submit → traditional POST → browser download file → hide loading)
    document.querySelector('form').addEventListener('submit', function() {
        // Loading đã auto-kích hoạt từ form submit listener ở loading.js
        // File download xong sau ~100ms → force-hide
        setTimeout(() => {
            if (window.LoadingStore) {
                window.LoadingStore.forceHide();
            }
        }, 100); // 100ms - đủ thời gian form submit + file download kịp
    });
</script>
@endsection