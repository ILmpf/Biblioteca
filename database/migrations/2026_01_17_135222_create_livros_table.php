<?php

use App\Models\Autor;
use App\Models\Editora;
use App\Models\Livro;
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
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('livros');
        Schema::dropIfExists('autor_livro');
    }
};
