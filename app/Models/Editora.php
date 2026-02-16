<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $nome
 * @property-read \Illuminate\Database\Eloquent\Collection|Livro[] $livro
 */
class Editora extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nome',
        'logotipo',
    ];

    // RELAÇÕES
    public function livro(): HasMany
    {
        return $this->hasMany(Livro::class);
    }
}
