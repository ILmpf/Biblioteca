<?php

declare(strict_types=1);

namespace App\Models;

use App\ReviewEstado;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property int $livro_id
 * @property int $requisicao_id
 * @property int $rating
 * @property string $comentario
 * @property ReviewEstado $estado
 * @property string|null $justificacao
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read User $user
 * @property-read Livro $livro
 * @property-read Requisicao $requisicao
 */
class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'livro_id',
        'requisicao_id',
        'rating',
        'comentario',
        'estado',
        'justificacao',
    ];

    protected function casts(): array
    {
        return [
            'estado' => ReviewEstado::class,
            'rating' => 'integer',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    protected $attributes = [
        'estado' => ReviewEstado::IN_APPROVAL,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function livro(): BelongsTo
    {
        return $this->belongsTo(Livro::class);
    }

    public function requisicao(): BelongsTo
    {
        return $this->belongsTo(Requisicao::class);
    }

    #[\Illuminate\Database\Eloquent\Attributes\Scope]
    protected function aprovada($query)
    {
        return $query->where('estado', ReviewEstado::APPROVED->value);
    }

    #[\Illuminate\Database\Eloquent\Attributes\Scope]
    protected function emAprovacao($query)
    {
        return $query->where('estado', ReviewEstado::IN_APPROVAL->value);
    }

    #[\Illuminate\Database\Eloquent\Attributes\Scope]
    protected function recusada($query)
    {
        return $query->where('estado', ReviewEstado::REJECTED->value);
    }
}
