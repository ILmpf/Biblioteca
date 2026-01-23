<?php

declare(strict_types=1);

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
            'editora_id' => Editora::factory(),
            'isbn' => fake()->isbn13(),
            'nome' => fake()->sentence(3),
            'bibliografia' => fake()->text(200),
            'imagem' => 'images/book-icon.png',
            'preco' => fake()->randomFloat(),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function ($livro) {
            $autores = Autor::inRandomOrder()
                ->take(random_int(1, 3))
                ->pluck('id');

            $livro->autor()->attach($autores);
        });
    }
}
