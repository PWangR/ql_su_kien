<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

/**
 * Model SmtpSetting — Quản lý cấu hình SMTP từ database
 * Chỉ dùng 1 record duy nhất (singleton pattern)
 */
class SmtpSetting extends Model
{
    protected $table = 'smtp_settings';

    protected $fillable = [
        'mail_host',
        'mail_port',
        'mail_username',
        'mail_password',
        'mail_encryption',
        'mail_from_address',
        'mail_from_name',
        'is_active',
    ];

    protected $casts = [
        'mail_port' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Encrypt password trước khi lưu vào DB
     */
    public function setMailPasswordAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['mail_password'] = Crypt::encryptString($value);
        }
    }

    /**
     * Decrypt password khi đọc từ DB
     */
    public function getMailPasswordAttribute($value)
    {
        if (!empty($value)) {
            try {
                return Crypt::decryptString($value);
            } catch (\Exception $e) {
                return $value; // Trả về raw nếu không decrypt được
            }
        }
        return $value;
    }

    /**
     * Lấy raw password (encrypted) — dùng khi cần check có tồn tại không
     */
    public function getRawPassword()
    {
        return $this->attributes['mail_password'] ?? null;
    }

    /**
     * Lấy cấu hình SMTP hiện tại (singleton)
     */
    public static function getConfig(): ?self
    {
        return static::first();
    }

    /**
     * Lấy hoặc tạo mới cấu hình SMTP
     */
    public static function getOrCreate(): self
    {
        $config = static::first();

        if (!$config) {
            $config = static::create([
                'mail_host' => config('mail.mailers.smtp.host', 'smtp.gmail.com'),
                'mail_port' => config('mail.mailers.smtp.port', 587),
                'mail_username' => config('mail.mailers.smtp.username', ''),
                'mail_encryption' => config('mail.mailers.smtp.encryption', 'tls'),
                'mail_from_address' => config('mail.from.address', ''),
                'mail_from_name' => config('mail.from.name', 'Quản Lý Sự Kiện'),
                'is_active' => false,
            ]);
        }

        return $config;
    }
}
