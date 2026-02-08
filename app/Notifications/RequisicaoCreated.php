<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Requisicao;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class RequisicaoCreated extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(protected Requisicao $requisicao)
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
        $url = route('requisicao.show', $this->requisicao);

        $mail = (new MailMessage)
            ->subject('Nova requisição efetuada')
            ->greeting('Olá '.$notifiable->name.'!')
            ->line('A tua requisição foi criada com sucesso.')
            ->line('Número: '.$this->requisicao->numero)
            ->line('Data da requisição: '.$this->requisicao->data_requisicao->format('d/m/Y'))
            ->line('Entrega prevista: '.$this->requisicao->data_entrega_prevista->format('d/m/Y'))
            ->line('Livros requisitados:');

        foreach ($this->requisicao->livros as $livro) {
            $mail->line(new HtmlString(
                '<strong>'.e($livro->nome).'</strong><br/>'.
                '<img src="'.asset($livro->imagem).'" width="120"><br><br>'
            ));
        }

        return $mail
            ->action('Visualizar', $url)
            ->line('Obrigado pela confiança.');
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
