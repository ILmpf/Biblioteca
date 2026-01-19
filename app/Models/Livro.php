<?php

declare(strict_types=1);

namespace App\Models;

use App\RequisicaoEstado;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Livro extends Model
{
    /** @use HasFactory<\Database\Factories\LivroFactory> */
    use HasFactory;

    protected $casts = [

    ];

    public function editora(): belongsTo
    {
        return $this->belongsTo(Editora::class);
    }

    public function autor(): BelongsToMany
    {
        return $this->belongsToMany(Autor::class);
    }

    public function requisicao(): BelongsToMany
    {
        return $this->belongsToMany(Requisicao::class, 'requisicao_livro')
            ->withPivot('entregue');
    }

    public function isAvailable(): bool
    {
        return ! $this->requisicao()
            ->wherePivot('entregue', false)
            ->exists();
    }
}
