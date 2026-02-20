<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Review;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReviewCreated extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(protected Review $review)
    {
        //
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = route('review.show', $this->review);

        return (new MailMessage)
            ->subject('Nova Review Submetida')
            ->greeting('Olá '.$notifiable->name.'!')
            ->line('Foi submetida uma nova review que requer a tua atenção.')
            ->line('**Cidadão:** '.$this->review->user->name)
            ->line('**Livro:** '.$this->review->livro->nome)
            ->line('**Avaliação:** '.$this->review->rating.'/5 estrelas')
            ->line('**Comentário:** '.substr($this->review->comentario, 0, 100).'...')
            ->action('Ver Review', $url)
            ->line('Por favor, analisa e aprova ou recusa esta review.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'review_id' => $this->review->id,
            'user_name' => $this->review->user->name,
            'livro_nome' => $this->review->livro->nome,
        ];
    }
}
