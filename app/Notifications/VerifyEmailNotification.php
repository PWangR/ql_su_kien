<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Mail\VerifyEmailMail;

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

    /**
     * Gửi email xác thực bằng Mailable custom
     */
    public function toMail(object $notifiable)
    {
        return new VerifyEmailMail($notifiable, $this->signedUrl);
    }

    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
