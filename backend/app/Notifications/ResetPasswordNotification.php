<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    public string $token;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Build the custom reset URL.
     */
    protected function resetUrl($notifiable)
    {
        $frontend = env('FRONTEND_URL', 'https://erp.gkpawiligar.org');

        return "{$frontend}/reset-password?token={$this->token}&email={$notifiable->email}";
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $frontend = env('FRONTEND_URL', 'https://gkpawiligar.org');

        $url = "{$frontend}/reset-password?token={$this->token}&email={$notifiable->email}";

        return (new MailMessage)
            ->subject('Reset Password')
            ->line('Klik tombol di bawah untuk reset password Anda.')
            ->action('Reset Password', $url)
            ->line('Token berlaku selama 60 menit.');
    }
}
