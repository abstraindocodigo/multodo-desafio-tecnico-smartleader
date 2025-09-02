<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification
{
    use Queueable;


    public $token;


    public function __construct($token)
    {
        $this->token = $token;
    }


    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $url = config('app.frontend_url') . '/reset-password?token=' . $this->token . '&email=' . urlencode($notifiable->email);

        return (new MailMessage)
            ->subject('Recuperação de Senha - MultiTodo')
            ->greeting('Olá!')
            ->line('Você está recebendo este email porque recebemos uma solicitação de recuperação de senha para sua conta.')
            ->action('Redefinir Senha', $url)
            ->line('Este link de recuperação de senha expirará em 60 minutos.')
            ->line('Se você não solicitou a recuperação de senha, nenhuma ação adicional é necessária.')
            ->salutation('Atenciosamente, Equipe MultiTodo');
    }


    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
