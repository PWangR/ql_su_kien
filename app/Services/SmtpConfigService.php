<?php

namespace App\Services;

use App\Models\SmtpSetting;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Throwable;

class SmtpConfigService
{
    public static function loadSmtpConfig(): void
    {
        try {
            if (!Schema::hasTable('smtp_settings')) {
                return;
            }

            $smtp = SmtpSetting::first();
        } catch (Throwable $e) {
            return;
        }

        if ($smtp && $smtp->is_active) {
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

            Config::set('mail.from', [
                'address' => $smtp->mail_from_address,
                'name' => $smtp->mail_from_name,
            ]);
        }
    }
}
