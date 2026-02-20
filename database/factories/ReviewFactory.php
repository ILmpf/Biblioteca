<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Livro;
use App\Models\Requisicao;
use App\Models\User;
use App\RequisicaoEstado;
use App\ReviewEstado;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'livro_id' => Livro::factory(),
            'requisicao_id' => Requisicao::factory()->state([
                'estado' => RequisicaoEstado::COMPLETED,
                'data_entrega' => now(),
            ]),
            'rating' => fake()->numberBetween(1, 5),
            'comentario' => fake()->paragraph(3),
            'estado' => ReviewEstado::IN_APPROVAL,
        ];
    }

    public function ativo(): static
    {
        return $this->state(fn (array $attributes) => [
            'estado' => ReviewEstado::APPROVED,
        ]);
    }

    public function recusado(): static
    {
        return $this->state(fn (array $attributes) => [
            'estado' => ReviewEstado::REJECTED,
            'justificacao' => fake()->sentence(),
        ]);
    }
}
