<?php

use App\Models\Livro;
use App\Models\Requisicao;
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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Livro::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Requisicao::class)->constrained('requisicoes')->cascadeOnDelete();
            $table->unsignedTinyInteger('rating');
            $table->text('comentario');
            $table->string('estado')->default('in_approval');
            $table->text('justificacao')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'livro_id', 'requisicao_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
