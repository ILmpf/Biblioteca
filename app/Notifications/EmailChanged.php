<?php

declare(strict_types=1);

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EmailChanged extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(protected string $oldEmail, protected string $newEmail)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Email Alterado')
            ->greeting('Olá '.$notifiable->name.'!')
            ->line('O teu endereço de email foi alterado com sucesso.')
            ->line('Email antigo: '.$this->oldEmail)
            ->line('Email novo: '.$this->newEmail)
            ->line('Se não fizeste esta alteração, por favor contacta-nos imediatamente.')
            ->action('Aceder ao Perfil', route('profile.show'))
            ->line('Esta é uma notificação de segurança.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
