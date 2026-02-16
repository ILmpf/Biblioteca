<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property string $nome
 * @property-read \Illuminate\Database\Eloquent\Collection|Livro[] $livro
 */
class Autor extends Model
{
    use HasFactory;

    protected $table = 'autores';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nome',
        'foto',
    ];

    /**
     * Livro relationship.
     */
    public function livro(): BelongsToMany
    {
        return $this->belongsToMany(Livro::class);
    }
}
