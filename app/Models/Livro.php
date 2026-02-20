<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $nome
 * @property string $isbn
 * @property string $bibliografia
 * @property int $editora_id
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|Autor[] $autor
 * @property-read Editora $editora
 * @property-read \Illuminate\Database\Eloquent\Collection|Requisicao[] $requisicao
 * @property-read \Illuminate\Database\Eloquent\Collection|Review[] $reviews
 */
class Livro extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nome',
        'isbn',
        'bibliografia',
        'editora_id',
        'imagem',
        'preco',
    ];

    protected function casts(): array
    {
        return [
            'preco' => 'decimal:2',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    // RELAÇÕES
    public function editora(): BelongsTo
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

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function availabilityAlerts(): HasMany
    {
        return $this->hasMany(AlertaDisponibilidadeLivro::class);
    }

    // HELPERS
    public function isAvailable(): bool
    {
        return ! $this->requisicao()
            ->wherePivot('entregue', false)
            ->exists();
    }

    public function hasAlert(User $user): bool
    {
        return $this->availabilityAlerts()
            ->where('user_id', $user->id)
            ->where('notificado', false)
            ->exists();
    }

    #[\Illuminate\Database\Eloquent\Attributes\Scope]
    protected function nome($query, string $nome)
    {
        return $query->where('nome', 'like', "%{$nome}%");
    }

    public function scopeAutor($query, string $autor)
    {
        return $query->whereHas('autor', fn ($q) => $q->where('nome', 'like', "%{$autor}%"));
    }

    public function scopeEditora($query, string $editora)
    {
        return $query->whereHas('editora', fn ($q) => $q->where('nome', 'like', "%{$editora}%"));
    }

    #[\Illuminate\Database\Eloquent\Attributes\Scope]
    protected function disponivel($query)
    {
        return $query->whereDoesntHave('requisicao', fn ($q) => $q->where('requisicao_livro.entregue', false));
    }

    #[\Illuminate\Database\Eloquent\Attributes\Scope]
    protected function indisponivel($query)
    {
        return $query->whereHas('requisicao', fn ($q) => $q->where('requisicao_livro.entregue', false));
    }

    #[\Illuminate\Database\Eloquent\Attributes\Scope]
    protected function available($query)
    {
        return $this->disponivel($query);
    }
}
