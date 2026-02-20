<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Review;
use App\ReviewEstado;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReviewStatusChanged extends Notification implements ShouldQueue
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
        $mail = (new MailMessage)
            ->subject('Estado da Tua Review Atualizado')
            ->greeting('Olá '.$notifiable->name.'!')
            ->line('O estado da tua review foi atualizado.')
            ->line('**Livro:** '.$this->review->livro->nome)
            ->line('**Estado:** '.$this->review->estado->label());

        if ($this->review->estado === ReviewEstado::APPROVED) {
            $mail->line('A tua review foi aprovada e está agora visível para outros utilizadores.')
                ->action('Ver Review', route('livro.show', $this->review->livro));
        } elseif ($this->review->estado === ReviewEstado::REJECTED) {
            $mail->line('Infelizmente, a tua review foi recusada.')
                ->line('**Justificação:** '.$this->review->justificacao);
        }

        return $mail->line('Obrigado pela tua participação.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'review_id' => $this->review->id,
            'estado' => $this->review->estado->value,
            'livro_nome' => $this->review->livro->nome,
        ];
    }
}
