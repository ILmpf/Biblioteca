<?php

use App\Models\Autor;
use App\Models\Editora;
use App\Models\Livro;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('livros', function (Blueprint $table) {
            $table->id();
            $table->string('isbn', 13)->unique();
            $table->string('nome', 100);
            $table->foreignIdFor(Editora::class);
            $table->text('bibliografia');
            $table->string('capa');
            $table->decimal('preco', 8, 2);
            $table->timestamps();
        });

        Schema::create('autor_livro', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Autor::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Livro::class)->constrained()->cascadeOnDelete();
            $table->timestamps();
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
