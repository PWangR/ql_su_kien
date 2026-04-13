@extends('admin.layout')

@section('title', 'Cấu hình SMTP')
@section('page-title', 'Cấu hình SMTP')

@section('content')
<div class="card mb-lg">
    <div class="card-header">
        <div class="card-title">
            <i class="bi bi-envelope-gear"></i> Cấu hình máy chủ Email (SMTP)
        </div>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.smtp.update') }}" method="POST" id="smtpForm">
            @csrf

            {{-- Trạng thái kích hoạt --}}
            <div class="form-group">
                <label class="form-label" style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                    <input type="checkbox" name="is_active" value="1"
                           {{ $smtp->is_active ? 'checked' : '' }}
                           style="width: 18px; height: 18px; cursor: pointer;">
                    <span style="text-transform: none; font-size: 0.9375rem; color: var(--text);">
                        Kích hoạt cấu hình SMTP từ database
                        <small style="display: block; color: var(--text-muted); text-transform: none; letter-spacing: 0;">
                            Khi bật, hệ thống sẽ sử dụng cấu hình bên dưới thay vì file .env
                        </small>
                    </span>
                </label>
            </div>

            <hr class="section-rule">

            <div class="input-grid">
                {{-- SMTP Host --}}
                <div class="form-group">
                    <label class="form-label" for="mail_host">SMTP Host <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('mail_host') is-invalid @enderror"
                           id="mail_host" name="mail_host"
                           value="{{ old('mail_host', $smtp->mail_host) }}"
                           placeholder="smtp.gmail.com" required>
                    @error('mail_host')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Port --}}
                <div class="form-group">
                    <label class="form-label" for="mail_port">Cổng (Port) <span class="text-danger">*</span></label>
                    <input type="number" class="form-control @error('mail_port') is-invalid @enderror"
                           id="mail_port" name="mail_port"
                           value="{{ old('mail_port', $smtp->mail_port) }}"
                           placeholder="587" required min="1" max="65535">
                    @error('mail_port')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-hint">Thường dùng: 587 (TLS), 465 (SSL), 25 (không mã hóa)</div>
                </div>
            </div>

            <div class="input-grid">
                {{-- Username --}}
                <div class="form-group">
                    <label class="form-label" for="mail_username">Tài khoản (Username)</label>
                    <input type="text" class="form-control @error('mail_username') is-invalid @enderror"
                           id="mail_username" name="mail_username"
                           value="{{ old('mail_username', $smtp->mail_username) }}"
                           placeholder="your-email@gmail.com">
                    @error('mail_username')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="form-group">
                    <label class="form-label" for="mail_password">Mật khẩu (Password)</label>
                    <div style="position: relative;">
                        <input type="password" class="form-control"
                               id="mail_password" name="mail_password"
                               placeholder="{{ $smtp->getRawPassword() ? '••••••••••• (đã lưu)' : 'Nhập mật khẩu SMTP' }}"
                               style="padding-right: 44px;">
                        <button type="button" onclick="togglePasswordVisibility()" title="Hiện/ẩn mật khẩu"
                                style="position:absolute; right:8px; top:50%; transform:translateY(-50%); background:none; border:none; cursor:pointer; color:var(--text-muted); font-size:16px;">
                            <i class="bi bi-eye" id="togglePwdIcon"></i>
                        </button>
                    </div>
                    <div class="form-hint">Để trống nếu không muốn thay đổi mật khẩu hiện tại</div>
                </div>
            </div>

            <div class="input-grid">
                {{-- Encryption --}}
                <div class="form-group">
                    <label class="form-label" for="mail_encryption">Mã hóa (Encryption)</label>
                    <select class="form-control" id="mail_encryption" name="mail_encryption">
                        <option value="tls" {{ old('mail_encryption', $smtp->mail_encryption) === 'tls' ? 'selected' : '' }}>TLS</option>
                        <option value="ssl" {{ old('mail_encryption', $smtp->mail_encryption) === 'ssl' ? 'selected' : '' }}>SSL</option>
                        <option value="null" {{ old('mail_encryption', $smtp->mail_encryption) === null ? 'selected' : '' }}>Không mã hóa</option>
                    </select>
                </div>

                {{-- From Address --}}
                <div class="form-group">
                    <label class="form-label" for="mail_from_address">Địa chỉ gửi (From Address)</label>
                    <input type="email" class="form-control @error('mail_from_address') is-invalid @enderror"
                           id="mail_from_address" name="mail_from_address"
                           value="{{ old('mail_from_address', $smtp->mail_from_address) }}"
                           placeholder="noreply@example.com">
                    @error('mail_from_address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- From Name --}}
            <div class="form-group">
                <label class="form-label" for="mail_from_name">Tên người gửi (From Name)</label>
                <input type="text" class="form-control @error('mail_from_name') is-invalid @enderror"
                       id="mail_from_name" name="mail_from_name"
                       value="{{ old('mail_from_name', $smtp->mail_from_name) }}"
                       placeholder="Quản Lý Sự Kiện">
                @error('mail_from_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="btn-group">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg"></i> Lưu cấu hình
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Test Email Section --}}
<div class="card">
    <div class="card-header">
        <div class="card-title">
            <i class="bi bi-send-check"></i> Kiểm tra gửi email
        </div>
    </div>
    <div class="card-body">
        <p style="margin-bottom: var(--space-md); color: var(--text-light);">
            Gửi email test để kiểm tra cấu hình SMTP hoạt động đúng. Vui lòng <strong>lưu cấu hình</strong> trước khi test.
        </p>

        <div style="display: flex; gap: var(--space-sm); flex-wrap: wrap; align-items: flex-end;">
            <div class="form-group" style="flex: 1; min-width: 250px; margin-bottom: 0;">
                <label class="form-label" for="test_email">Email nhận test</label>
                <input type="email" class="form-control" id="test_email"
                       placeholder="test@example.com"
                       value="{{ auth()->user()->email }}">
            </div>
            <div style="margin-bottom: 0;">
                <button type="button" class="btn btn-outline" id="btnTestEmail" onclick="testSmtpEmail()">
                    <i class="bi bi-send"></i> Gửi email test
                </button>
            </div>
        </div>

        {{-- Kết quả test --}}
        <div id="testResult" style="margin-top: var(--space-md); display: none;"></div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Toggle password visibility
function togglePasswordVisibility() {
    const input = document.getElementById('mail_password');
    const icon = document.getElementById('togglePwdIcon');
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'bi bi-eye-slash';
    } else {
        input.type = 'password';
        icon.className = 'bi bi-eye';
    }
}

// Test gửi email via AJAX
function testSmtpEmail() {
    const email = document.getElementById('test_email').value;
    const btn = document.getElementById('btnTestEmail');
    const result = document.getElementById('testResult');

    if (!email) {
        result.style.display = 'block';
        result.innerHTML = '<div class="alert alert-error"><i class="bi bi-exclamation-circle"></i> Vui lòng nhập email nhận test.</div>';
        return;
    }

    // Disable button + loading
    btn.disabled = true;
    btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Đang gửi...';
    result.style.display = 'block';
    result.innerHTML = '<div class="alert alert-info"><i class="bi bi-clock"></i> Đang gửi email test, vui lòng chờ...</div>';

    fetch('{{ route("admin.smtp.test") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        },
        body: JSON.stringify({ test_email: email }),
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            result.innerHTML = '<div class="alert alert-success"><i class="bi bi-check-circle"></i> ' + data.message + '</div>';
        } else {
            result.innerHTML = '<div class="alert alert-error"><i class="bi bi-exclamation-circle"></i> ' + data.message + '</div>';
        }
    })
    .catch(err => {
        result.innerHTML = '<div class="alert alert-error"><i class="bi bi-exclamation-circle"></i> Lỗi kết nối: ' + err.message + '</div>';
    })
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-send"></i> Gửi email test';
    });
}
</script>
@endsection
