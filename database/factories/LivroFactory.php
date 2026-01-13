<?php

namespace Database\Factories;

use App\Models\Autor;
use App\Models\Editora;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Livro>
 */
class LivroFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'isbn' => fake()->isbn13(),
            'nome' => fake()->title(),
            'editora_id' => Editora::factory(),
            'bibliografia' => fake()->paragraphs(3, true),
            'capa' => fake()->imageUrl(),
            'preco' => fake()->randomFloat(2, 10, 50)
        ];
    }
}
