<?php

namespace App\Services;

use App\Models\SmtpSetting;
use Illuminate\Support\Facades\Config;

/**
 * Service để load SMTP config từ database
 * Thay thế mail config từ .env hoặc config file
 */
class SmtpConfigService
{
    /**
     * Load cấu hình SMTP từ database
     * Được gọi trong bootstrap của ứng dụng
     */
    public static function loadSmtpConfig(): void
    {
        // Kiểm tra xem SmtpSetting có enabled không
        $smtp = SmtpSetting::first();

        // Nếu SMTP từ database được active, sử dụng nó
        if ($smtp && $smtp->is_active) {
            // Thiết lập mail config từ database
            Config::set('mail.default', 'smtp');
            Config::set('mail.mailers.smtp', [
                'transport' => 'smtp',
                'host' => $smtp->mail_host,
                'port' => $smtp->mail_port,
                'encryption' => $smtp->mail_encryption,
                'username' => $smtp->mail_username,
                'password' => $smtp->mail_password,
                'timeout' => null,
                'local_domain' => env('MAIL_EHLO_DOMAIN'),
            ]);

            // Thiết lập "from" address
            Config::set('mail.from', [
                'address' => $smtp->mail_from_address,
                'name' => $smtp->mail_from_name,
            ]);
        }
        // Nếu không active, giữ nguyên config từ .env / config file
    }
}
