<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword as BaseResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends BaseResetPassword
{
    public function toMail($notifiable): MailMessage
    {
        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->email,
        ], false));

        $expire = (int) config('auth.passwords.' . config('auth.defaults.passwords') . '.expire');

        return (new MailMessage)
            ->subject(__('mail.reset.subject'))
            ->greeting(__('mail.common.greeting'))
            ->line(__('mail.reset.line_1'))
            ->action(__('mail.reset.action'), $url)
            ->line(__('mail.reset.line_2', ['count' => $expire]))
            ->line(__('mail.reset.line_3'))
            ->salutation(__('mail.common.salutation') . "\n\n" . __('mail.common.regard'));
    }
}
