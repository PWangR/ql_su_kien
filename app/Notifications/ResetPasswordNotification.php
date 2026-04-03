<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    public string $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $resetUrl = route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ]);

        $expireMinutes = config('auth.passwords.' . config('auth.defaults.passwords') . '.expire');

        return (new MailMessage)
            ->subject('Đặt lại mật khẩu - Quản lý Sự kiện')
            ->greeting('Chào ' . $notifiable->ho_ten)
            ->line('Chúng tôi đã nhận được yêu cầu đặt lại mật khẩu cho tài khoản của bạn.')
            ->line('Liên kết này được gửi qua cùng hạ tầng SMTP đang dùng cho email xác thực đăng ký.')
            ->action('Đặt lại mật khẩu', $resetUrl)
            ->line('Liên kết sẽ hết hạn sau ' . $expireMinutes . ' phút.')
            ->line('Nếu bạn không yêu cầu thay đổi mật khẩu, vui lòng bỏ qua email này.')
            ->salutation('Trân trọng, Ban quản trị');
    }
}
