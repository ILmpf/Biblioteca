<?php

declare(strict_types=1);

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class AdminAccountCreated extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(protected string $password)
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
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject('Conta de Administrador Criada')
            ->greeting('Olá '.$notifiable->name.'!')
            ->line('Foi criada uma conta de administrador para ti.')
            ->line('Detalhes da conta:')
            ->line('Email: '.$notifiable->email)
            ->line('Password: '.$this->password)
            ->line('Por favor, confirma o teu endereço de email clicando no botão abaixo:')
            ->action('Verificar Email', $verificationUrl)
            ->line('Este link de verificação irá expirar em 60 minutos.')
            ->line('Após verificares o email, podes fazer login com as credenciais acima.')
            ->line('Recomendamos que alteres a password após o primeiro login.');
    }

    /**
     * Get the verification URL for the given notifiable.
     */
    protected function verificationUrl(object $notifiable): string
    {
        return URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );
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
