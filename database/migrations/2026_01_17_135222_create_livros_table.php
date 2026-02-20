<?php

use App\Models\Autor;
use App\Models\Editora;
use App\Models\Livro;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('livros', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Editora::class)->constrained()->cascadeOnDelete();
            $table->string('isbn')->unique();
            $table->string('nome');
            $table->text('bibliografia');
            $table->string('imagem')->nullable();
            $table->decimal('preco', 8, 2);

            $table->timestamps();
        });

        Schema::create('autor_livro', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Livro::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Autor::class)->constrained()->cascadeOnDelete();

            $table->unique(['livro_id', 'autor_id']);
        });

        Schema::create('alertas_disponibilidade_livros', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Livro::class)->constrained()->cascadeOnDelete();
            $table->boolean('notificado')->default(false);
            $table->timestamps();

            $table->unique(['user_id', 'livro_id']);
            $table->index(['livro_id', 'notificado']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('livros');
        Schema::dropIfExists('autor_livro');
        Schema::dropIfExists('alertas_disponibilidade_livros');
    }
};
