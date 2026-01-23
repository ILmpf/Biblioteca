<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Livro extends Model
{
    /** @use HasFactory<\Database\Factories\LivroFactory> */
    use HasFactory;

    protected $casts = [

    ];

    // RELAÇÕES
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

    // HELPERS
    public function isAvailable(): bool
    {
        return ! $this->requisicao()
            ->wherePivot('entregue', false)
            ->exists();
    }

    #[\Illuminate\Database\Eloquent\Attributes\Scope]
    protected function nome($query, $nome)
    {
        return $query->where('nome', 'like', "%{$nome}%");
    }

    public function scopeAutor($query, $autor)
    {
        return $query->whereHas('autor', fn ($q) => $q->where('nome', 'like', "%{$autor}%")
        );
    }

    public function scopeEditora($query, $editora)
    {
        return $query->whereHas('editora', fn ($q) => $q->where('nome', 'like', "%{$editora}%")
        );
    }

    #[\Illuminate\Database\Eloquent\Attributes\Scope]
    protected function disponivel($query)
    {
        return $query->whereDoesntHave('requisicao', fn ($q) => $q->where('requisicao_livro.entregue', false)
        );
    }

    #[\Illuminate\Database\Eloquent\Attributes\Scope]
    protected function indisponivel($query)
    {
        return $query->whereHas('requisicao', fn ($q) => $q->where('requisicao_livro.entregue', false)
        );
    }

    #[\Illuminate\Database\Eloquent\Attributes\Scope]
    protected function available($query)
    {
        return $query->whereDoesntHave('requisicao', function ($q) {
            $q->wherePivot('entregue', false);
        });
    }
}
