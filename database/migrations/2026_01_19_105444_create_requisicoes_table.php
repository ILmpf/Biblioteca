<?php

use App\Models\Livro;
use App\Models\Requisicao;
use App\Models\User;
use App\RequisicaoEstado;
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
        Schema::create('requisicoes', function (Blueprint $table) {
            $table->id();
            $table->string('numero');
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->string('estado');

            $table->date('data_requisicao');
            $table->date('data_entrega_prevista');
            $table->date('data_entrega')->nullable();

            $table->timestamps();
        });

        Schema::create('requisicao_livro', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Livro::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Requisicao::class)->constrained()->cascadeOnDelete();
            $table->boolean('entregue')->default(false);

            $table->timestamps();

            $table->unique(['livro_id', 'requisicao_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requisicoes');
        Schema::dropIfExists('requisicao_livro');
    }
};
