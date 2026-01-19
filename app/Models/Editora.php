<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Editora extends Model
{
    /** @use HasFactory<\Database\Factories\EditoraFactory> */
    use HasFactory;

    //RELAÇÕES
    public function livro(): HasMany
    {
        return $this->hasMany(Livro::class);
    }
}
