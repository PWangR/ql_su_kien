<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SmtpSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;
use App\Models\ActivityLog;

/**
 * Controller quản lý cấu hình SMTP từ Admin Panel
 */
class SmtpSettingController extends Controller
{
    /**
     * Hiển thị form cấu hình SMTP
     */
    public function index()
    {
        $smtp = SmtpSetting::getOrCreate();

        return view('admin.smtp.index', compact('smtp'));
    }

    /**
     * Lưu cấu hình SMTP
     */
    public function update(Request $request)
    {
        $request->validate([
            'mail_host'         => 'required|string|max:255',
            'mail_port'         => 'required|integer|min:1|max:65535',
            'mail_username'     => 'nullable|string|max:255',
            'mail_encryption'   => 'nullable|string|in:tls,ssl,null',
            'mail_from_address' => 'nullable|email|max:255',
            'mail_from_name'    => 'nullable|string|max:255',
        ], [
            'mail_host.required'      => 'Vui lòng nhập SMTP Host.',
            'mail_port.required'      => 'Vui lòng nhập cổng SMTP.',
            'mail_port.integer'       => 'Cổng SMTP phải là số.',
            'mail_from_address.email' => 'Địa chỉ email gửi không hợp lệ.',
        ]);

        $smtp = SmtpSetting::getOrCreate();

        $data = $request->only([
            'mail_host', 'mail_port', 'mail_username',
            'mail_encryption', 'mail_from_address', 'mail_from_name',
        ]);

        // Chỉ cập nhật password nếu admin nhập mới
        if ($request->filled('mail_password')) {
            $data['mail_password'] = $request->mail_password;
        }

        $data['is_active'] = $request->has('is_active');

        // Xử lý encryption "null" -> null
        if ($data['mail_encryption'] === 'null') {
            $data['mail_encryption'] = null;
        }

        $smtp->fill($data);
        $smtp->save();

        // Ghi log hoạt động
        ActivityLog::log(
            'update',
            'Cập nhật cấu hình SMTP',
            SmtpSetting::class,
            $smtp->id
        );

        return redirect()
            ->route('admin.smtp.index')
            ->with('success', 'Cấu hình SMTP đã được lưu thành công!');
    }

    /**
     * Test gửi email qua SMTP config hiện tại
     */
    public function testEmail(Request $request)
    {
        $request->validate([
            'test_email' => 'required|email',
        ], [
            'test_email.required' => 'Vui lòng nhập email nhận test.',
            'test_email.email'    => 'Địa chỉ email không hợp lệ.',
        ]);

        try {
            $smtp = SmtpSetting::getConfig();

            if (!$smtp) {
                return response()->json([
                    'success' => false,
                    'message' => 'Chưa có cấu hình SMTP. Vui lòng lưu cấu hình trước.',
                ]);
            }

            // Override config tạm thời để test
            $this->applySmtpConfig($smtp);

            Mail::raw(
                'Đây là email test từ hệ thống Quản Lý Sự Kiện. Nếu bạn nhận được email này, cấu hình SMTP đã hoạt động đúng!',
                function ($message) use ($request, $smtp) {
                    $message->to($request->test_email)
                            ->subject('🔧 Test SMTP — Quản Lý Sự Kiện');

                    if ($smtp->mail_from_address) {
                        $message->from($smtp->mail_from_address, $smtp->mail_from_name ?? 'Quản Lý Sự Kiện');
                    }
                }
            );

            // Ghi log
            ActivityLog::log(
                'update',
                "Test gửi email SMTP tới {$request->test_email} — thành công",
                SmtpSetting::class,
                $smtp->id
            );

            return response()->json([
                'success' => true,
                'message' => "Email test đã được gửi thành công tới {$request->test_email}!",
            ]);

        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            $friendlyMessage = 'Gửi email thất bại: Vui lòng kiểm tra lại cấu hình SMTP.';

            if (str_contains($errorMessage, 'Application-specific password required') || str_contains($errorMessage, '534-5.7.9')) {
                $friendlyMessage = 'Đăng nhập thất bại: Gmail yêu cầu "Mật khẩu ứng dụng" (App Password) thay vì mật khẩu email thông thường. Vui lòng bật Xác minh 2 bước và tạo Mật khẩu ứng dụng trong cài đặt bảo mật Google của bạn.';
            } elseif (str_contains($errorMessage, 'Username and Password not accepted') || str_contains($errorMessage, '535-5.7.8')) {
                $friendlyMessage = 'Đăng nhập thất bại: Tài khoản (Username) hoặc Mật khẩu không chính xác.';
            } elseif (str_contains($errorMessage, 'Connection could not be established')) {
                $friendlyMessage = 'Lỗi kết nối: Không thể kết nối đến máy chủ SMTP. Vui lòng kiểm tra lại SMTP Host và Cổng (Port).';
            } elseif (str_contains($errorMessage, 'Unable to connect with TLS encryption')) {
                $friendlyMessage = 'Lỗi mã hóa: Máy chủ không hỗ trợ phương thức mã hóa TLS/SSL này hoặc cấu hình bị sai.';
            } else {
                $friendlyMessage = 'Gửi email thất bại quá trình xử lý: ' . $errorMessage;
            }

            return response()->json([
                'success' => false,
                'message' => $friendlyMessage,
            ]);
        }
    }

    /**
     * Apply SMTP config từ database vào Laravel config runtime
     */
    private function applySmtpConfig(SmtpSetting $smtp): void
    {
        Config::set('mail.default', 'smtp');
        Config::set('mail.mailers.smtp.host', $smtp->mail_host);
        Config::set('mail.mailers.smtp.port', $smtp->mail_port);
        Config::set('mail.mailers.smtp.username', $smtp->mail_username);
        Config::set('mail.mailers.smtp.password', $smtp->mail_password);
        Config::set('mail.mailers.smtp.encryption', $smtp->mail_encryption);
        Config::set('mail.from.address', $smtp->mail_from_address);
        Config::set('mail.from.name', $smtp->mail_from_name);

        // Purge cached mailer để dùng config mới
        app('mail.manager')->purge('smtp');
    }
}
