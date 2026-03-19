<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class VerifyEmailNotification extends Notification
{
    use Queueable;

    public $signedUrl;

    public function __construct($signedUrl)
    {
        $this->signedUrl = $signedUrl;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Xác thực Email - Quản lý Sự kiện')
            ->greeting('Chào ' . $notifiable->ho_ten)
            ->line('Cảm ơn bạn đã đăng ký tài khoản. Vui lòng nhấp vào nút bên dưới để xác thực email của bạn.')
            ->action('Xác thực Email', $this->signedUrl)
            ->line('Liên kết này sẽ hết hạn sau 60 phút.')
            ->line('Nếu bạn không tạo tài khoản này, hãy bỏ qua email này.')
            ->salutation('Trân trọng, Ban quản trị');
    }

    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
