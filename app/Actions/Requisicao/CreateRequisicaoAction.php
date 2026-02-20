<?php

declare(strict_types=1);

namespace App\Actions\Requisicao;

use App\Models\Livro;
use App\Models\Requisicao;
use App\Notifications\RequisicaoCreated;
use App\RequisicaoEstado;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;

class CreateRequisicaoAction
{
    /**
     * Handle the creation of a new requisicao.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function handle(array $data, $user): Requisicao
    {
        $bookIds = collect($data['livros'] ?? [])->filter()->unique();

        if (! Gate::allows('create', [Requisicao::class, $bookIds->count()])) {
            throw ValidationException::withMessages([
                'livros' => 'Não pode requisitar mais de 3 livros em simultâneo.',
            ]);
        }

        $books = Livro::find($bookIds->toArray());

        foreach ($books as $book) {
            if (! $book->isAvailable()) {
                throw ValidationException::withMessages([
                    'livros' => "O livro '{$book->nome}' não está disponível.",
                ]);
            }
        }

        return DB::transaction(function () use ($bookIds, $user) {
            $requisicao = Requisicao::create([
                'user_id' => $user->id,
                'estado' => RequisicaoEstado::ACTIVE,
                'data_requisicao' => now(),
                'data_entrega_prevista' => now()->addDays(5),
            ]);

            $requisicao->livros()->attach(
                $bookIds->mapWithKeys(fn ($id) => [
                    $id => ['entregue' => false],
                ])
            );

            $user->notify((new RequisicaoCreated($requisicao))->afterCommit());

            return $requisicao;
        });
    }
}
