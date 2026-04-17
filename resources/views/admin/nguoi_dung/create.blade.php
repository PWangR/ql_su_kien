@extends('admin.layout')

@section('title', 'Tạo người dùng mới')
@section('page-title', 'Tạo người dùng mới')

@section('styles')
<style>
    .form-section {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--border-radius-md);
        padding: 24px;
        margin-bottom: 24px;
    }

    .form-section h3 {
        font-size: 0.95rem;
        font-weight: 600;
        margin-bottom: 16px;
        padding-bottom: 12px;
        border-bottom: 1px solid var(--border-light);
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 16px;
        margin-bottom: 16px;
    }

    .avatar-upload {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 16px;
        border: 2px dashed var(--border);
        border-radius: var(--border-radius);
        background: var(--bg-alt);
        margin-bottom: 20px;
    }

    .avatar-preview {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        border: 2px solid var(--border);
        background: var(--card);
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        flex-shrink: 0;
        font-size: 1.6rem;
        font-weight: 700;
        color: var(--accent);
    }

    .avatar-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .note-box {
        padding: 12px 16px;
        background: #fffbeb;
        border: 1px solid #fbbf24;
        border-radius: var(--border-radius);
        color: #92400e;
        font-size: 0.875rem;
        margin-bottom: 20px;
    }

    .btn-group-submit {
        display: flex;
        gap: 12px;
        justify-content: flex-end;
        padding-top: 20px;
        border-top: 1px solid var(--border-light);
    }

    .alert {
        display: flex;
        gap: 12px;
        align-items: flex-start;
        padding: 16px;
        border-radius: var(--border-radius);
        margin-bottom: 20px;
        animation: slideIn 0.3s ease-out;
    }

    .alert i {
        font-size: 1.2rem;
        flex-shrink: 0;
        margin-top: 2px;
    }

    .alert-success {
        background: #ecfdf5;
        border: 1px solid #10b981;
        color: #065f46;
    }

    .alert-success i {
        color: #10b981;
    }

    .alert-error {
        background: #fee;
        border: 1px solid #fca5a5;
        color: #7f1d1d;
    }

    .alert-error i {
        color: #dc2626;
    }

    .alert-warning {
        background: #fef3c7;
        border: 1px solid #fbbf24;
        color: #92400e;
    }

    .alert-warning i {
        color: #f59e0b;
    }

    .alert strong {
        display: block;
        margin-bottom: 6px;
    }

    .alert ul {
        margin: 8px 0 0;
        padding-left: 20px;
    }

    .alert ul li {
        margin: 4px 0;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @media (max-width: 768px) {
        .form-grid {
            grid-template-columns: 1fr;
        }

        .avatar-upload {
            flex-direction: column;
            text-align: center;
        }

        .avatar-preview {
            margin: 0 auto;
        }
    }
</style>
@endsection

@section('content')
<div style="max-width: 800px; margin: 0 auto;">
    @if (session('success'))
    <div class="alert alert-success">
        <i class="bi bi-check-circle-fill"></i>
        <div>
            <strong>Thành công!</strong>
            <p style="margin: 0; font-size: 0.9rem;">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    @if (session('error'))
    <div class="alert alert-error">
        <i class="bi bi-exclamation-circle-fill"></i>
        <div>
            <strong>Lỗi!</strong>
            <p style="margin: 0; font-size: 0.9rem;">{{ session('error') }}</p>
        </div>
    </div>
    @endif

    @if ($errors->any())
    <div class="alert alert-warning">
        <i class="bi bi-exclamation-triangle-fill"></i>
        <div>
            <strong>Kiểm tra lại thông tin:</strong>
            <ul style="margin: 0;">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    <form method="POST" action="{{ route('admin.nguoi-dung.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="note-box">
            <i class="bi bi-info-circle"></i>
            Tài khoản mới sẽ được gửi email xác thực. Vui lòng đảm bảo SMTP đã được cấu hình và kích hoạt để tài khoản có thể gửi email xác thực.
        </div>

        <!-- Avatar Section -->
        <div class="form-section">
            <h3>Ảnh đại diện</h3>
            <div class="avatar-upload">
                <div class="avatar-preview" id="avatarPreview">👤</div>
                <div style="flex: 1; text-align: left;">
                    <label class="form-label">Tải lên ảnh đại diện</label>
                    <input type="file" name="duong_dan_anh" id="avatarInput" class="form-control" accept="image/*">
                    <div class="text-xs text-muted" style="margin-top: 6px;">
                        Hỗ trợ định dạng: JPG, PNG, GIF, WEBP. Dung lượng tối đa: 2MB.
                    </div>
                </div>
            </div>
        </div>

        <!-- Personal Information Section -->
        <div class="form-section">
            <h3>Thông tin cá nhân</h3>
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Họ tên <span style="color: var(--danger);">*</span></label>
                    <input type="text" name="ho_ten" class="form-control @error('ho_ten') is-invalid @enderror"
                        value="{{ old('ho_ten') }}" placeholder="Nguyễn Văn A" required>
                    @error('ho_ten') <small class="form-text text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Mã sinh viên <span style="color: var(--danger);">*</span></label>
                    <input type="text" name="ma_sinh_vien" class="form-control @error('ma_sinh_vien') is-invalid @enderror"
                        value="{{ old('ma_sinh_vien') }}" placeholder="12345678" pattern="[0-9]{8}" maxlength="8" required>
                    @error('ma_sinh_vien') <small class="form-text text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Lớp <span style="color: var(--danger);">*</span></label>
                    <input type="text" name="lop" class="form-control @error('lop') is-invalid @enderror"
                        value="{{ old('lop') }}" placeholder="64.CNTT-1" required>
                    @error('lop') <small class="form-text text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Số điện thoại</label>
                    <input type="text" name="so_dien_thoai" class="form-control @error('so_dien_thoai') is-invalid @enderror"
                        value="{{ old('so_dien_thoai') }}" placeholder="0912345678">
                    @error('so_dien_thoai') <small class="form-text text-danger">{{ $message }}</small> @enderror
                </div>
            </div>
        </div>

        <!-- Account Information Section -->
        <div class="form-section">
            <h3>Thông tin tài khoản</h3>
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Email <span style="color: var(--danger);">*</span></label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                        value="{{ old('email') }}" placeholder="user@example.com" required>
                    @error('email') <small class="form-text text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Vai trò <span style="color: var(--danger);">*</span></label>
                    <select name="vai_tro" class="form-control @error('vai_tro') is-invalid @enderror" required>
                        <option value="">-- Chọn vai trò --</option>
                        <option value="sinh_vien" {{ old('vai_tro') === 'sinh_vien' ? 'selected' : '' }}>Sinh viên</option>
                        <option value="admin" {{ old('vai_tro') === 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                    @error('vai_tro') <small class="form-text text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Mật khẩu <span style="color: var(--danger);">*</span></label>
                    <input type="password" name="mat_khau" class="form-control @error('mat_khau') is-invalid @enderror"
                        placeholder="Tối thiểu 8 ký tự" minlength="8" required>
                    @error('mat_khau') <small class="form-text text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Xác nhận mật khẩu <span style="color: var(--danger);">*</span></label>
                    <input type="password" name="mat_khau_confirmation" class="form-control @error('mat_khau_confirmation') is-invalid @enderror"
                        placeholder="Nhập lại mật khẩu" minlength="8" required>
                    @error('mat_khau_confirmation') <small class="form-text text-danger">{{ $message }}</small> @enderror
                </div>
            </div>
        </div>

        <div class="btn-group-submit">
            <a href="{{ route('admin.nguoi-dung.index') }}" class="btn btn-secondary" id="btnBack">
                <i class="bi bi-arrow-left"></i> Quay lại
            </a>
            <button type="submit" class="btn btn-primary" id="btnSubmit">
                <i class="bi bi-check-circle"></i> Tạo tài khoản
            </button>
        </div>
    </form>
</div>

@section('scripts')
<script>
    const avatarInput = document.getElementById('avatarInput');
    const avatarPreview = document.getElementById('avatarPreview');
    const form = document.querySelector('form');
    const btnSubmit = document.getElementById('btnSubmit');
    const btnBack = document.getElementById('btnBack');

    // Avatar preview
    avatarInput?.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(evt) {
                avatarPreview.innerHTML = `<img src="${evt.target.result}" alt="Preview">`;
            };
            reader.readAsDataURL(file);
        }
    });

    // Auto-dismiss success messages
    const successAlert = document.querySelector('.alert-success');
    if (successAlert) {
        setTimeout(() => {
            successAlert.style.opacity = '0';
            successAlert.style.transform = 'translateY(-10px)';
            successAlert.style.transition = 'all 0.3s ease-out';
            setTimeout(() => {
                successAlert.style.display = 'none';
            }, 300);
        }, 5000);
    }

    // Form submission loading state
    form?.addEventListener('submit', function(e) {
        const isValid = form.checkValidity();
        if (!isValid) {
            e.preventDefault();
            e.stopPropagation();
            return;
        }

        // Show loading state
        btnSubmit.disabled = true;
        btnSubmit.innerHTML = '<i class="bi bi-hourglass-split" style="animation: spin 1s linear infinite;"></i> Đang xử lý...';
        btnBack.disabled = true;

        // Prevent accidental double submissions
        setTimeout(() => {
            if (btnSubmit.disabled) {
                btnSubmit.innerHTML = '<i class="bi bi-check-circle"></i> Tạo tài khoản';
                btnSubmit.disabled = false;
                btnBack.disabled = false;
            }
        }, 30000); // Reset after 30 seconds if server doesn't respond
    });

    // Add spinner animation
    const style = document.createElement('style');
    style.textContent = `
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
    `;
    document.head.appendChild(style);

    // Log when page loads to verify script is running
    console.log('Create user form loaded successfully');
</script>
@endsection

@endsection