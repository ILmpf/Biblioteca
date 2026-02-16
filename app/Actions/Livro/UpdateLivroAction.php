<?php

declare(strict_types=1);

namespace App\Actions\Livro;

use App\Models\Livro;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class UpdateLivroAction
{
    public function handle(Livro $livro, array $data): Livro
    {
        $autores = $data['autores'] ?? null;
        unset($data['autores']);

        if (isset($data['imagem']) && $data['imagem'] instanceof UploadedFile) {
            // Delete old image if exists
            if ($livro->imagem) {
                Storage::disk('public')->delete($livro->imagem);
            }
            $data['imagem'] = $data['imagem']->store('livros', 'public');
        } else {
            // Keep the old image if no new one is uploaded
            unset($data['imagem']);
        }

        $livro->update($data);

        if ($autores) {
            $livro->autor()->sync($autores);
        }

        return $livro;
    }
}
