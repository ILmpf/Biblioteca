<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property int $livro_id
 * @property bool $notificado
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read User $user
 * @property-read Livro $livro
 */
class AlertaDisponibilidadeLivro extends Model
{
    protected $table = 'alertas_disponibilidade_livros';

    protected $fillable = [
        'user_id',
        'livro_id',
        'notificado',
    ];

    protected function casts(): array
    {
        return [
            'notificado' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function livro(): BelongsTo
    {
        return $this->belongsTo(Livro::class);
    }

    #[\Illuminate\Database\Eloquent\Attributes\Scope]
    protected function pending($query)
    {
        return $query->where('notificado', false);
    }
}
