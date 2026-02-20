<?php

declare(strict_types=1);

namespace App\Actions\Requisicao;

use App\Models\AlertaDisponibilidadeLivro;
use App\Models\Requisicao;
use App\Notifications\LivroAvailable;
use App\RequisicaoEstado;
use Illuminate\Support\Facades\DB;

class UpdateRequisicaoAction
{
    /**
     * Handle the update of a requisicao.
     */
    public function handle(Requisicao $requisicao, array $data): Requisicao
    {
        return DB::transaction(function () use ($requisicao, $data) {
            $previousState = $requisicao->estado;
            $newState = RequisicaoEstado::from($data['estado']);

            $updateData = [
                'estado' => $newState,
                'data_entrega_prevista' => $data['data_entrega_prevista'] ?? $requisicao->data_entrega_prevista,
            ];

            if ($newState === RequisicaoEstado::COMPLETED) {
                $updateData['data_entrega'] = $data['data_entrega'] ?? now();
            } 
            elseif ($newState === RequisicaoEstado::CANCELLED) {
                $updateData['data_entrega'] = null;

                $data['livros_entregue'] = $requisicao->livros->pluck('id')->toArray();
            } 
            elseif ($newState === RequisicaoEstado::ACTIVE) {
                if (isset($data['data_entrega']) && ! empty($data['data_entrega'])) {
                    $updateData['data_entrega'] = $data['data_entrega'];
                } 
                elseif ($previousState !== RequisicaoEstado::ACTIVE) {
                    $updateData['data_entrega'] = null;
                } 
                else {
                    $updateData['data_entrega'] = $requisicao->data_entrega;
                }
            }

            $requisicao->update($updateData);

            $deliveredBooks = collect($data['livros_entregue'] ?? []);

            $bookIds = $requisicao->livros->pluck('id');

            if ($deliveredBooks->isNotEmpty()) {
                $requisicao->livros()->syncWithPivotValues(
                    $deliveredBooks->toArray(),
                    ['entregue' => true],
                    false
                );
            }

            $undeliveredBooks = $bookIds->diff($deliveredBooks);
            if ($undeliveredBooks->isNotEmpty()) {
                $requisicao->livros()->syncWithPivotValues(
                    $undeliveredBooks->toArray(),
                    ['entregue' => false],
                    false
                );
            }

            if ($requisicao->estado === RequisicaoEstado::ACTIVE) {
                $allBooksDelivered = $requisicao->livros()
                    ->wherePivot('entregue', true)
                    ->count() === $requisicao->livros()->count();

                if ($allBooksDelivered && $requisicao->livros()->count() > 0) {
                    $requisicao->update([
                        'estado' => RequisicaoEstado::COMPLETED,
                        'data_entrega' => now(),
                    ]);
                }
            }

            if ($deliveredBooks->isNotEmpty()) {
                foreach ($deliveredBooks as $bookId) {
                    $book = $requisicao->livros()->find($bookId);

                    if ($book && $book->isAvailable()) {
                        $alerts = AlertaDisponibilidadeLivro::where('livro_id', $book->id)
                            ->where('notificado', false)
                            ->with('user')
                            ->get();

                        foreach ($alerts as $alert) {
                            try {
                                $alert->user->notify(new LivroAvailable($book));
                                $alert->update(['notificado' => true]);
                            } catch (\Exception $e) {

                                logger()->error('Failed to send book availability notification', [
                                    'livro_id' => $book->id,
                                    'user_id' => $alert->user_id,
                                    'error' => $e->getMessage(),
                                ]);
                            }
                        }
                    }
                }
            }

            return $requisicao->fresh(['livros', 'user']);
        });
    }
}
