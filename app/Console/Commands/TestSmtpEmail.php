<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SmtpSetting;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Message;

class TestSmtpEmail extends Command
{
    protected $signature = 'smtp:test {email?}';
    protected $description = 'Kiểm tra cấu hình SMTP bằng cách gửi email test';

    public function handle()
    {
        $email = $this->argument('email') ?? $this->ask('Email nhận test');

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error('❌ Email không hợp lệ!');
            return 1;
        }

        $this->info('🔍 Đang kiểm tra cấu hình SMTP...');

        try {
            // Lấy cấu hình SMTP
            $smtp = SmtpSetting::first();

            if (!$smtp) {
                $this->error('❌ Không tìm thấy cấu hình SMTP. Vui lòng cấu hình trước!');
                return 1;
            }

            // Hiển thị cấu hình hiện tại
            $this->info("\n📧 Cấu hình SMTP hiện tại:");
            $this->line("   Host: {$smtp->mail_host}");
            $this->line("   Port: {$smtp->mail_port}");
            $this->line("   Username: {$smtp->mail_username}");
            $this->line("   Encryption: " . ($smtp->mail_encryption ?? 'None'));
            $this->line("   From: {$smtp->mail_from_name} <{$smtp->mail_from_address}>");
            $this->line("   Status: " . ($smtp->is_active ? '✅ Active' : '❌ Inactive'));
            $this->line("");

            // Gửi email test
            $this->info("📤 Đang gửi email test tới: $email");

            Mail::send([], [], function (Message $message) use ($email, $smtp) {
                $message->to($email)
                    ->subject('Test Email - Quản Lý Sự Kiện')
                    ->html($this->getTestEmailHTML($smtp));
            });

            $this->info("\n✅ Email test đã được gửi thành công!");
            $this->line("   Vui lòng kiểm tra hộp thư: $email");

            return 0;
        } catch (\Exception $e) {
            $this->error("\n❌ Lỗi khi gửi email:");
            $this->error("   " . $e->getMessage());

            // Hiển thị gợi ý troubleshooting
            $this->line("\n💡 Gợi ý khắc phục:");
            $this->line("   1. Kiểm tra SMTP Host có đúng không");
            $this->line("   2. Kiểm tra Port có mở không");
            $this->line("   3. Kiểm tra Username/Password có chính xác không");
            $this->line("   4. Kiểm tra Encryption setting (TLS/SSL/None)");
            $this->line("   5. Nếu dùng Gmail, hãy dùng App Password chứ không phải mật khẩu thường");

            return 1;
        }
    }

    protected function getTestEmailHTML($smtp)
    {
        $header = $smtp->mail_header ?? '<h2>Quản Lý Sự Kiện</h2>';
        $signature = $smtp->mail_signature ?? 'Đội ngũ Quản Lý Sự Kiện';
        $footer = $smtp->mail_footer ?? '© 2026 Khoa Công Nghệ Thông Tin';

        return <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { border-bottom: 2px solid #007bff; padding-bottom: 20px; margin-bottom: 20px; }
        .content { margin: 20px 0; }
        .signature { border-top: 1px solid #ddd; padding-top: 20px; margin-top: 20px; }
        .footer { text-align: center; color: #666; font-size: 12px; margin-top: 20px; border-top: 1px solid #ddd; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            {$header}
        </div>

        <div class="content">
            <p>Xin chào,</p>
            <p>Đây là email kiểm tra cấu hình SMTP của hệ thống Quản Lý Sự Kiện.</p>
            <p><strong>Thông tin test:</strong></p>
            <ul>
                <li>SMTP Host: <code>{$smtp->mail_host}</code></li>
                <li>Port: <code>{$smtp->mail_port}</code></li>
                <li>From: <code>{$smtp->mail_from_name} &lt;{$smtp->mail_from_address}&gt;</code></li>
            </ul>
            <p>Nếu bạn nhận được email này có nghĩa là cấu hình SMTP đang hoạt động đúng! ✅</p>
        </div>

        <div class="signature">
            <p>Trân trọng,<br>{$signature}</p>
        </div>

        <div class="footer">
            {$footer}
        </div>
    </div>
</body>
</html>
HTML;
    }
}
