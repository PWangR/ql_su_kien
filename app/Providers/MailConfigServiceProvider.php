<?php

namespace App\Providers;

use App\Models\SmtpSetting;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

/**
 * MailConfigServiceProvider — Override cấu hình mail từ database
 * Khi SmtpSetting.is_active = true, sẽ dùng config từ DB thay vì .env
 */
class MailConfigServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Chỉ override nếu bảng đã tồn tại (tránh lỗi khi migrate lần đầu)
        try {
            if (!Schema::hasTable('smtp_settings')) {
                return;
            }

            $smtp = SmtpSetting::where('is_active', true)->first();

            if ($smtp) {
                Config::set('mail.default', 'smtp');
                Config::set('mail.mailers.smtp.host', $smtp->mail_host);
                Config::set('mail.mailers.smtp.port', $smtp->mail_port);
                Config::set('mail.mailers.smtp.username', $smtp->mail_username);
                Config::set('mail.mailers.smtp.password', $smtp->mail_password);
                Config::set('mail.mailers.smtp.encryption', $smtp->mail_encryption);

                if ($smtp->mail_from_address) {
                    Config::set('mail.from.address', $smtp->mail_from_address);
                }
                if ($smtp->mail_from_name) {
                    Config::set('mail.from.name', $smtp->mail_from_name);
                }
            }
        } catch (\Exception $e) {
            // Fail silently — sử dụng .env config mặc định
        }
    }
}
