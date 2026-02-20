<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Livro;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LivroAvailable extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(protected Livro $livro)
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
        $url = route('livro.show', $this->livro);

        return (new MailMessage)
            ->subject('Livro Disponível - '.$this->livro->nome)
            ->greeting('Olá '.$notifiable->name.'!')
            ->line('Boas notícias! O livro que estavas à espera já está disponível.')
            ->line('**'.$this->livro->nome.'**')
            ->line('Podes agora requisitar este livro antes que outra pessoa o faça!')
            ->action('Requisitar Livro', $url)
            ->line('Obrigado por usares a nossa biblioteca!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'livro_id' => $this->livro->id,
            'livro_nome' => $this->livro->nome,
        ];
    }
}
