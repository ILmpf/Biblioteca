<?php

declare(strict_types=1);

namespace App\Actions\Livro;

use App\Models\Livro;
use Illuminate\Http\UploadedFile;

class CreateLivroAction
{
    public function handle(array $data): Livro
    {
        $autores = $data['autores'] ?? null;
        unset($data['autores']);

        if (isset($data['imagem']) && $data['imagem'] instanceof UploadedFile) {
            $data['imagem'] = $data['imagem']->store('livros', 'public');
        }

        $livro = Livro::create($data);

        if ($autores) {
            $livro->autor()->sync($autores);
        }

        return $livro;
    }
}
