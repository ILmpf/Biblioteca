<?php

declare(strict_types=1);

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class CustomVerifyEmail extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
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
            ->subject('Verifica o teu endereço de email')
            ->greeting('Olá '.$notifiable->name.'!')
            ->line('Bem-vindo à Biblioteca digital! Estamos muito felizes por te teres juntado a nós.')
            ->line('Para começares a requisitar livros e aceder a todos os recursos, precisamos que confirmes o teu endereço de email.')
            ->line('Por favor, clica no botão abaixo para verificar a tua conta:')
            ->action('Verificar Conta', $verificationUrl)
            ->line('Este link de verificação irá expirar em 60 minutos.')
            ->line('Após verificares o email, poderás:')
            ->line('• Requisitar livros do nosso vasto catálogo')
            ->line('• Gerir as tuas requisições')
            ->line('Se não criaste uma conta na nossa plataforma, podes ignorar este email. Apenas certifica-te de que o teu email não foi comprometido.');
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
