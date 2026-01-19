<?php

namespace App\Models;

use App\RequisicaoEstado;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Requisicao extends Model
{
    /** @use HasFactory<\Database\Factories\RequisicaoFactory> */
    use HasFactory;

    protected $table = 'requisicoes';

    protected $casts = [
        'estado' => RequisicaoEstado::class,
    ];

    protected $attributes = [
        'estado' => RequisicaoEstado::ACTIVE,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function livro(): BelongsToMany
    {
        return $this->belongsToMany(Livro::class, 'requisicao_livro');
    }
}
