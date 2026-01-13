<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Livro extends Model
{
    /** @use HasFactory<\Database\Factories\EditoraFactory> */
    use HasFactory;

    protected $fillable = ['isbn', 'nome', 'autores', 'bibliografia', 'capa', 'preco'];

    public function editora()
    {
        return $this->belongsTo(Editora::class);
    }

    public function autores()
    {
        return $this->belongsToMany(Autor::class);
    }
}
