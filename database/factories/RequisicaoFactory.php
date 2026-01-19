<?php

namespace Database\Factories;

use App\Models\Livro;
use App\Models\Requisicao;
use App\Models\User;
use App\RequisicaoEstado;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Requisicao>
 */
class RequisicaoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $dataRequisicao = fake()->dateTimeBetween('-30 days', 'now');
        $dataEntregaPrevista = (clone $dataRequisicao)->modify('+5 days');
        $estado = fake()->randomElement(RequisicaoEstado::cases());

        return [
            'numero' => 'REQ-' . fake()->unique()->numberBetween(1, 100),
            'user_id' => User::factory(),
            'estado' => $estado->value,
            'data_requisicao' => $dataRequisicao->format('Y-m-d'),
            'data_entrega_prevista' => $dataEntregaPrevista->format('Y-m-d'),
            'data_entrega' => $estado === RequisicaoEstado::COMPLETED ? fake()->dateTimeBetween($dataRequisicao, 'now')->format('Y-m-d') : null,
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function ($requisicao) {
            $livros = Livro::available()
                ->inRandomOrder()
                ->take(rand(1, 3))
                ->get();

            foreach ($livros as $livro) {
                $livro->requisicao()
                    ->wherePivot('entregue', false)
                    ->updateExistingPivot(
                        $livro->requisicao->pluck('id'),
                        ['entregue' => true]
                    );

                $requisicao->livro()->attach($livro->id, [
                    'entregue' => $requisicao->estado === RequisicaoEstado::COMPLETED,
                ]);
            }
        });
    }
}
