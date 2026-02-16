<?php

declare(strict_types=1);

namespace App\Models;

use App\RequisicaoEstado;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property int $user_id
 * @property RequisicaoEstado $estado
 * @property \Illuminate\Support\Carbon $data_requisicao
 * @property \Illuminate\Support\Carbon $data_entrega
 * @property \Illuminate\Support\Carbon $data_entrega_prevista
 * @property-read User $user
 * @property-read \Illuminate\Database\Eloquent\Collection|Livro[] $livros
 */
class Requisicao extends Model
{
    use HasFactory;

    protected $table = 'requisicoes';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'numero',
        'user_id',
        'estado',
        'data_requisicao',
        'data_entrega_prevista',
        'data_entrega',
    ];

    protected function casts(): array
    {
        return [
            'estado' => RequisicaoEstado::class,
            'data_requisicao' => 'date',
            'data_entrega' => 'date',
            'data_entrega_prevista' => 'date',
        ];
    }

    protected $attributes = [
        'estado' => RequisicaoEstado::ACTIVE,
    ];

    /**
     * User relationship.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Livros relationship.
     */
    public function livros(): BelongsToMany
    {
        return $this->belongsToMany(Livro::class, 'requisicao_livro')
            ->withPivot('entregue');
    }
}
