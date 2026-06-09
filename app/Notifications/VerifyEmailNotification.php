<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail as BaseVerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class VerifyEmailNotification extends BaseVerifyEmail
{
    public function toMail($notifiable): MailMessage
    {
        $url = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject(__('mail.verify.subject'))
            ->greeting(__('mail.common.greeting'))
            ->line(__('mail.verify.line_1'))
            ->action(__('mail.verify.action'), $url)
            ->line(__('mail.verify.line_2'))
            ->salutation(__('mail.common.salutation') . "\n\n" . __('mail.common.regard'));
    }
}
