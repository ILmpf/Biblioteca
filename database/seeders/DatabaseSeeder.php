<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Autor;
use App\Models\Livro;
use App\Models\Requisicao;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Leonardo',
            'email' => 'leonardo@example.com',
            'role' => 'admin',
            'password' => 'password123!',
        ]);

        Autor::factory(10)->create();
        Livro::factory(30)->create();
        Requisicao::factory(10)->create();
    }
}
