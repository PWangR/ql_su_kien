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

            <hr class="section-rule" style="margin: var(--space-lg) 0;">

            {{-- ============ PHẦN CẤU HÌNH NỘI DUNG EMAIL XÁC THỰC ============ --}}
            <div style="margin-top: var(--space-xl); margin-bottom: var(--space-xl);">
                <h3 style="font-size: 1.125rem; font-weight: 600; margin-bottom: var(--space-md); color: var(--text);">
                    <i class="bi bi-file-text"></i> Cấu hình nội dung Email Xác Thực Tài Khoản
                </h3>
                <p style="color: var(--text-muted); font-size: 0.875rem; margin-bottom: var(--space-lg);">
                    Tùy chỉnh header, footer, signature cho email xác thực tài khoản người dùng.
                </p>

                {{-- Email Header --}}
                <div class="form-group">
                    <label class="form-label" for="mail_header">Header Email (Phần đầu)</label>
                    <textarea class="form-control @error('mail_header') is-invalid @enderror"
                        id="mail_header" name="mail_header" rows="3"
                        placeholder="VD: &lt;h1 style=&quot;color: #007bff;&quot;&gt;Quản Lý Sự Kiện NTU&lt;/h1&gt;">{{ old('mail_header', $smtp->mail_header) }}</textarea>
                    @error('mail_header')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-hint">Có thể sử dụng HTML. Hiển thị ở đầu email xác thực.</div>
                </div>

                {{-- Email Body Template --}}
                <div class="form-group">
                    <label class="form-label" for="mail_body_template">Nội dung bổ sung (Body Template) - <span style="color: var(--text-muted); font-size: 0.875rem;">Tùy chọn</span></label>
                    <textarea class="form-control @error('mail_body_template') is-invalid @enderror"
                        id="mail_body_template" name="mail_body_template" rows="4"
                        placeholder="VD: Sau khi xác thực, bạn có thể:&#10;- Tham gia các sự kiện&#10;- Xem điểm&#10;- Quản lý hồ sơ">{{ old('mail_body_template', $smtp->mail_body_template) }}</textarea>
                    @error('mail_body_template')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-hint">Nội dung bổ sung sẽ hiển thị sau nội dung xác thực chính (tùy chọn).</div>
                </div>

                {{-- Email Footer --}}
                <div class="form-group">
                    <label class="form-label" for="mail_footer">Footer Email (Phần chân)</label>
                    <textarea class="form-control @error('mail_footer') is-invalid @enderror"
                        id="mail_footer" name="mail_footer" rows="3"
                        placeholder="VD: © 2026 Khoa CNTT - Đại học Nha Trang. Tất cả quyền được bảo lưu.">{{ old('mail_footer', $smtp->mail_footer) }}</textarea>
                    @error('mail_footer')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-hint">Hiển thị ở cuối email. Có thể sử dụng HTML.</div>
                </div>

                {{-- Email Signature --}}
                <div class="form-group">
                    <label class="form-label" for="mail_signature">Chữ ký Email (Signature)</label>
                    <textarea class="form-control @error('mail_signature') is-invalid @enderror"
                        id="mail_signature" name="mail_signature" rows="4"
                        placeholder="VD: Trân trọng,&#10;Đội ngũ Quản Lý Sự Kiện&#10;Khoa Công Nghệ Thông Tin&#10;Đại học Nha Trang">{{ old('mail_signature', $smtp->mail_signature) }}</textarea>
                    @error('mail_signature')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Welcome Subject --}}
                <div class="form-group">
                    <label class="form-label" for="subject_welcome">Subject (Tiêu đề) Email</label>
                    <input type="text" class="form-control @error('subject_welcome') is-invalid @enderror"
                        id="subject_welcome" name="subject_welcome"
                        value="{{ old('subject_welcome', $smtp->subject_welcome ?? 'Xác thực Email - Quản lý Sự kiện') }}"
                        placeholder="Xác thực Email - Quản lý Sự kiện">
                    @error('subject_welcome')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="btn-group">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg"></i> Lưu cấu hình
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Preview Email Section --}}
<div class="card mb-lg">
    <div class="card-header">
        <div class="card-title">
            <i class="bi bi-eye"></i> Preview Email Xác Thực
        </div>
    </div>
    <div class="card-body">
        <p style="color: var(--text-muted); margin-bottom: var(--space-md);">
            Xem trước cách email xác thực sẽ hiển thị với cấu hình hiện tại:
        </p>

        <div style="background: var(--bg-alt); border: 1px solid var(--border); border-radius: var(--border-radius); padding: var(--space-lg); font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; max-width: 600px;">
            {{-- Email Header --}}
            @if($smtp->mail_header)
            <div style="margin-bottom: var(--space-md); border-bottom: 1px solid var(--border); padding-bottom: var(--space-md);">
                {!! $smtp->mail_header !!}
            </div>
            @endif

            {{-- Email Body --}}
            <div style="margin-bottom: var(--space-md); line-height: 1.6; color: var(--text);">
                <p>Xin chào <strong>Tên Người Dùng</strong>,</p>

                <p>Cảm ơn bạn đã đăng ký tài khoản. Vui lòng nhấp vào nút bên dưới để xác thực email của bạn.</p>

                <div style="text-align: center; margin: 20px 0;">
                    <a href="#" style="display: inline-block; padding: 12px 30px; background-color: #007bff; color: white; text-decoration: none; border-radius: 4px; font-weight: bold;">
                        Xác thực Email
                    </a>
                </div>

                <p style="color: #666; font-size: 13px;">
                    <strong>Lưu ý:</strong> Liên kết này sẽ hết hạn sau 60 phút. Nếu bạn không tạo tài khoản này, hãy bỏ qua email này.
                </p>

                @if($smtp->mail_body_template)
                <hr style="border: none; border-top: 1px solid var(--border); margin: 20px 0;">
                <div style="background-color: #f9f9f9; padding: 15px; border-radius: 4px;">
                    {!! nl2br(e($smtp->mail_body_template)) !!}
                </div>
                @endif
            </div>

            {{-- Email Signature --}}
            @if($smtp->mail_signature)
            <div style="margin-bottom: var(--space-md); border-top: 1px solid var(--border); padding-top: var(--space-md); white-space: pre-wrap; color: var(--text-light); font-size: 0.9rem;">
                {{ $smtp->mail_signature }}
            </div>
            @endif

            {{-- Email Footer --}}
            @if($smtp->mail_footer)
            <div style="border-top: 1px solid var(--border); padding-top: var(--space-md); font-size: 0.8125rem; color: var(--text-muted); text-align: center;">
                {!! $smtp->mail_footer !!}
            </div>
            @endif
        </div>

        {{-- Sender Info --}}
        <div style="margin-top: var(--space-lg); padding: var(--space-md); background: var(--card); border-radius: var(--border-radius); border-left: 3px solid var(--accent);">
            <strong>📩 Thông tin người gửi:</strong>
            <div style="margin-top: 8px; font-size: 0.875rem;">
                <div><strong>From:</strong> {{ $smtp->mail_from_name }} &lt;{{ $smtp->mail_from_address ?? 'chưa_cấu_hình@example.com' }}&gt;</div>
                <div style="margin-top: 4px;"><strong>Subject:</strong> {{ $smtp->subject_welcome ?? 'Xác thực Email - Quản lý Sự kiện' }}</div>
            </div>
        </div>
    </div>
</div>

{{-- Hướng dẫn sử dụng biến Template --}}
<div class="card mb-lg">
    <div class="card-header">
        <div class="card-title">
            <i class="bi bi-info-circle"></i> Thông tin hữu ích
        </div>
    </div>
    <div class="card-body">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: var(--space-lg);">
            {{-- Biến Template --}}
            <div style="background: var(--bg-alt); padding: var(--space-md); border-radius: var(--border-radius); border-left: 3px solid var(--accent);">
                <strong style="display: block; margin-bottom: 10px;">📧 Biến có sẵn trong Body Template (Tùy chọn):</strong>
                <div style="font-family: monospace; font-size: 0.8125rem; line-height: 1.8; color: var(--text-muted);">
                    <div><code>{name}</code> - Tên người dùng</div>
                    <div><code>{email}</code> - Email người dùng</div>
                </div>
                <p style="font-size: 0.8125rem; color: var(--text-muted); margin-top: 10px; margin-bottom: 0;">
                    <em>Biến này sẽ tự động được thay thế khi gửi email.</em>
                </p>
            </div>

            {{-- Mẹo HTML --}}
            <div style="background: var(--bg-alt); padding: var(--space-md); border-radius: var(--border-radius); border-left: 3px solid #17a2b8;">
                <strong style="display: block; margin-bottom: 10px;">💡 Mẹo sử dụng HTML:</strong>
                <div style="font-family: monospace; font-size: 0.8125rem; line-height: 1.6; color: var(--text-muted);">
                    <div>&lt;strong&gt;Text đậm&lt;/strong&gt;</div>
                    <div>&lt;em&gt;Text nghiêng&lt;/em&gt;</div>
                    <div>&lt;a href="url"&gt;Link&lt;/a&gt;</div>
                </div>
            </div>
        </div>

        {{-- Ví dụ --}}
        <div style="margin-top: var(--space-lg); padding: var(--space-md); background: var(--card); border-radius: var(--border-radius); border-left: 3px solid #28a745;">
            <strong style="display: block; margin-bottom: 8px;">✨ Ví dụ cấu hình hoàn chỉnh:</strong>

            <div style="margin-top: 12px;">
                <p style="color: var(--text-muted); font-size: 0.875rem; margin: 0 0 8px 0;"><strong>Header:</strong></p>
                <div style="font-family: monospace; font-size: 0.8125rem; background: var(--bg-alt); padding: 10px; border-radius: 4px; margin-bottom: 12px;">
                    &lt;h2 style="color: #007bff;"&gt;Quản Lý Sự Kiện&lt;/h2&gt;
                </div>

                <p style="color: var(--text-muted); font-size: 0.875rem; margin: 0 0 8px 0;"><strong>Body Template:</strong></p>
                <div style="font-family: monospace; font-size: 0.8125rem; background: var(--bg-alt); padding: 10px; border-radius: 4px; margin-bottom: 12px; white-space: pre-wrap;">
                    Sau khi xác thực email, bạn sẽ có thể:
                    - Tham gia các sự kiện
                    - Xem điểm tích lũy
                    - Quản lý hồ sơ cá nhân
                </div>

                <p style="color: var(--text-muted); font-size: 0.875rem; margin: 0 0 8px 0;"><strong>Signature:</strong></p>
                <div style="font-family: monospace; font-size: 0.8125rem; background: var(--bg-alt); padding: 10px; border-radius: 4px; margin-bottom: 12px; white-space: pre-wrap;">
                    Trân trọng,
                    Đội ngũ Quản Lý Sự Kiện
                    Khoa Công Nghệ Thông Tin - NTU
                </div>

                <p style="color: var(--text-muted); font-size: 0.875rem; margin: 0 0 8px 0;"><strong>Footer:</strong></p>
                <div style="font-family: monospace; font-size: 0.8125rem; background: var(--bg-alt); padding: 10px; border-radius: 4px;">
                    © 2026 Khoa CNTT - Đại học Nha Trang
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Hướng dẫn cấu hình SMTP--}}
<div class="card mb-lg">
    <div class="card-header">
        <div class="card-title">
            <i class="bi bi-question-circle"></i> Hướng dẫn cấu hình SMTP cho Gmail
        </div>
    </div>
    <div class="card-body">
        <!-- Gmail Instructions -->
        <div class="smtp-tab-content active" data-provider="gmail">

            <div class="" style="margin-bottom:var(--space-md);">
                <strong><i class="bi bi-info-circle"></i> Lưu ý:</strong> Gmail yêu cầu <strong>App Password</strong> thay vì mật khẩu tài khoản thường.
            </div>

            <div style="background:var(--bg-alt);padding:var(--space-lg);border-radius:var(--border-radius-sm);margin-bottom:var(--space-md);">
                <div style="margin-bottom:var(--space-md);">
                    <strong style="color:var(--text);">Bước 1: Tạo App Password</strong>
                    <ol style="margin:var(--space-sm) 0;padding-left:20px;">
                        <li>Truy cập <a href="https://myaccount.google.com/security" target="_blank" rel="noopener">myaccount.google.com/security</a></li>
                        <li>Bật <strong>2-Step Verification (Xác thực 2 bước)</strong> nếu chưa bật</li>
                        <li>Quay lại Security, tìm <strong>"App passwords"</strong></li>
                        <li>Chọn <strong>Mail → Windows Computer</strong></li>
                        <li>Copy password được tạo (16 ký tự, không có dấu cách)</li>
                    </ol>
                </div>

                <div style="border-top:1px solid var(--border);padding-top:var(--space-md);">
                    <strong style="color:var(--text);">Bước 2: Nhập cấu hình vào hệ thống</strong>
                    <div style="margin:var(--space-sm) 0;font-family:monospace;background:var(--card);padding:12px;border-radius:var(--border-radius-sm);border-left:3px solid var(--accent);overflow-x:auto;">
                        <div><strong style="color:var(--accent);">SMTP Host:</strong> <code>smtp.gmail.com</code></div>
                        <div><strong style="color:var(--accent);">Port:</strong> <code>587</code></div>
                        <div><strong style="color:var(--accent);">Encryption:</strong> <code>TLS</code></div>
                        <div><strong style="color:var(--accent);">Username:</strong> <code>your-email@gmail.com</code></div>
                        <div><strong style="color:var(--accent);">Password:</strong> <code>16-character app password</code></div>
                        <div><strong style="color:var(--accent);">From Address:</strong> <code>your-email@gmail.com</code></div>
                        <div><strong style="color:var(--accent);">From Name:</strong> <code>Quản Lý Sự Kiện</code></div>
                    </div>
                </div>
            </div>
        </div>
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
    // Switch SMTP tab
    function switchSmtpTab(btn) {
        const provider = btn.dataset.provider;

        // Update active button
        document.querySelectorAll('.smtp-tab-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        // Update active content
        document.querySelectorAll('.smtp-tab-content').forEach(c => c.classList.remove('active'));
        document.querySelector(`[data-provider="${provider}"]`).classList.add('active');
    }

    // Hide/show active tab
    document.addEventListener('DOMContentLoaded', () => {
        const style = document.createElement('style');
        style.textContent = `
        .smtp-tab-btn {
            padding: 10px 16px;
            border: none;
            background: none;
            cursor: pointer;
            color: var(--text-muted);
            border-bottom: 2px solid transparent;
            margin-bottom: -1px;
            transition: color 0.2s, border-color 0.2s;
            white-space: nowrap;
            font-weight: 500;
        }
        .smtp-tab-btn:hover {
            color: var(--text);
        }
        .smtp-tab-btn.active {
            color: var(--accent);
            border-bottom-color: var(--accent);
        }
        .smtp-tab-content {
            display: none;
        }
        .smtp-tab-content.active {
            display: block;
        }
    `;
        document.head.appendChild(style);
    });

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
                body: JSON.stringify({
                    test_email: email
                }),
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