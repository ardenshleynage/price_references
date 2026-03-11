<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    public $token;

    public $username;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): \Illuminate\Notifications\Messages\MailMessage
    {
        $this->username = $notifiable->username;

        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->subject(Lang::get('Réinitialisation de votre mot de passe - Price References'))
            ->markdown('emails.password-reset', [
                'url' => $url,
                'username' => $this->username,
            ]);
    }

    public function toArray($notifiable): array
    {
        return [
            //
        ];
    }
}
