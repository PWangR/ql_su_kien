@extends('admin.layout')
@section('title', 'Xuất Báo Cáo Lớp')
@section('content')
<div class="container mb-xl">

    <!-- Page Heading -->
    <div class="mb-xl">
        <div class="flex items-center gap-md mb-md">
            <i class="bi bi-file-earmark-excel" style="font-size: 2rem; color: var(--accent);"></i>
            <div>
                <h1 style="margin: 0; font-size: 1.95rem; color: var(--text);">Xuất Báo Cáo Điểm Theo Lớp</h1>
                <p style="margin: 4px 0 0 0; font-size: 0.9375rem; color: var(--text-muted);">Tạo báo cáo Excel chi tiết về điểm tích luỹ của sinh viên</p>
            </div>
        </div>
    </div>

    <!-- Main Form Card -->
    <div class="card mb-lg">
        <div class="card-header">
            <div class="card-title">
                <i class="bi bi-file-earmark-excel"></i>
                Xuất Báo Cáo Điểm Theo Lớp
            </div>
        </div>
        <div class="card-body">

            <!-- Error Messages -->
            @if($errors->any())
            <div class="alert alert-error mb-lg">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <div>
                    <strong>Có lỗi xảy ra:</strong>
                    @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Form -->
            <form method="POST" action="{{ route('admin.bao-cao.export') }}" id="baoCaoForm">
                @csrf

                <div class="grid grid-3 gap-lg mb-lg">
                    <!-- Lớp (required) -->
                    <div class="form-group" style="grid-column: span 1;">
                        <label class="form-label">
                            Chọn Lớp
                            <span class="text-danger">*</span>
                        </label>
                        <select name="lop" class="form-control @error('lop') is-invalid @enderror" required id="lopSelect">
                            <option value="">-- Chọn lớp --</option>
                            @foreach($danhSachLop as $l)
                            <option value="{{ $l }}" @selected(old('lop')===$l)>{{ $l }}</option>
                            @endforeach
                        </select>
                        @error('lop')
                        <div class="invalid-feedback"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>

                    <!-- From Date -->
                    <div class="form-group">
                        <label class="form-label">Từ Ngày</label>
                        <input type="date" name="from_date" class="form-control @error('from_date') is-invalid @enderror"
                            value="{{ old('from_date') }}">
                        <small class="form-hint">Tùy chọn</small>
                        @error('from_date')
                        <div class="invalid-feedback"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>

                    <!-- To Date -->
                    <div class="form-group">
                        <label class="form-label">Đến Ngày</label>
                        <input type="date" name="to_date" class="form-control @error('to_date') is-invalid @enderror"
                            value="{{ old('to_date') }}">
                        <small class="form-hint">Tùy chọn</small>
                        @error('to_date')
                        <div class="invalid-feedback"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>
                </div>

                @error('date_range')
                <div class="alert alert-warning mb-lg">
                    <i class="bi bi-exclamation-circle-fill"></i>
                    <span>{{ $message }}</span>
                </div>
                @enderror

                <!-- Submit Button -->
                <div class="btn-group">
                    <button type="submit" class="btn btn-primary" id="exportButton" style="flex: 2;">
                        <i class="bi bi-download"></i>
                        <span>Xuất Excel</span>
                    </button>
                    <a href="{{ route('home') }}" class="btn btn-secondary" style="flex: 1;">
                        <i class="bi bi-arrow-left"></i>
                        <span>Quay Lại</span>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Info Cards Grid -->
    <div class="grid grid-3 gap-lg">

        <!-- Quick Guide -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <i class="bi bi-question-circle"></i>
                    Hướng dẫn
                </div>
            </div>
            <div class="card-body" style="padding: 14px 20px;">
                <ol style="margin: 0; padding-left: 16px; line-height: 1.6; font-size: 0.875rem;">
                    <li><strong>Chọn lớp</strong></li>
                    <li><strong>Chọn ngày</strong> (tùy)</li>
                    <li><strong>Xuất Excel</strong></li>
                </ol>
            </div>
        </div>

        <!-- Excel Content Info -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <i class="bi bi-file-earmark-spreadsheet"></i>
                    Nội dung
                </div>
            </div>
            <div class="card-body" style="padding: 14px 20px;">
                <ul style="margin: 0; padding-left: 0; list-style: none; font-size: 0.8125rem; line-height: 1.6;">
                    <li><span class="text-accent">✓</span> ID sinh viên</li>
                    <li><span class="text-accent">✓</span> Tên sinh viên</li>
                    <li><span class="text-accent">✓</span> Lớp, Điểm</li>
                    <li><span class="text-accent">✓</span> Khoảng ngày, Thời gian</li>
                </ul>
            </div>
        </div>

        <!-- Tips -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <i class="bi bi-lightbulb"></i>
                    Lưu ý
                </div>
            </div>
            <div class="card-body" style="padding: 14px 20px;">
                <ul style="margin: 0; padding-left: 16px; list-style-type: none; font-size: 0.8125rem; line-height: 1.6;">
                    <li><strong>Lớp</strong> là bắt buộc</li>
                    <li>Không ngày = toàn bộ</li>
                    <li>File: <code style="background: var(--bg-alt); padding: 1px 4px; border-radius: 2px; font-size: 0.65rem;">bao_cao.xlsx</code></li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    @media (max-width: 1200px) {
        .grid.grid-3 {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .grid.grid-3 {
            grid-template-columns: 1fr;
        }
    }

    /* Tighten spacing for info cards */
    .card-body {
        padding: 20px;
    }

    .form-group {
        margin-bottom: 0;
    }

    .form-group .form-hint {
        margin-top: 4px;
        display: block;
    }
</style>
@endsection

@section('scripts')
<script>
    const baoCaoForm = document.getElementById('baoCaoForm');
    const exportButton = document.getElementById('exportButton');
    const lopSelect = document.getElementById('lopSelect');

    // Cập nhật trạng thái nút export
    function updateExportButtonState() {
        const hasSelectedClass = lopSelect.value !== '';
        exportButton.disabled = !hasSelectedClass;
        exportButton.style.opacity = hasSelectedClass ? '1' : '0.6';
        exportButton.style.cursor = hasSelectedClass ? 'pointer' : 'not-allowed';
        if (!hasSelectedClass) {
            exportButton.title = 'Vui lòng chọn lớp trước khi xuất báo cáo';
        } else {
            exportButton.title = '';
        }
    }

    // Gọi khi load trang
    updateExportButtonState();
    lopSelect.addEventListener('change', updateExportButtonState);

    // Xử lý submit form - dùng form submit truyền thống (tránh blob HTTPS warning)
    baoCaoForm.addEventListener('submit', function(e) {
        // Kiểm tra nếu chưa chọn lớp
        if (lopSelect.value === '') {
            e.preventDefault();
            alert('Vui lòng chọn lớp trước khi xuất báo cáo');
            return false;
        }

        // Tắt loading overlay sau khi form submit (download hoàn thành)
        // Download thường hoàn thành sau 1-2 giây
        setTimeout(() => {
            if (window.LoadingStore) {
                window.LoadingStore.hideLoading();
            }
        }, 2000);

        // Giữ nguyên button, không hiển thị loading
        // Form sẽ submit bình thường, browser xử lý download
    });
</script>
@endsection