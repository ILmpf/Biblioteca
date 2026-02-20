<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Autor;
use App\Models\Editora;
use App\Models\Livro;
use Illuminate\Support\Facades\Http;

class Google
{
    protected string $baseUrl;

    protected string $apiKey;

    public function __construct()
    {
        $this->baseUrl = config('services.google_books.base_url');
        $this->apiKey = config('services.google_books.key');
    }

    public function search(string $query, int $maxResults = 10): array
    {
        $response = Http::get($this->baseUrl.'/volumes', [
            'q' => $query,
            'maxResults' => $maxResults,
            'key' => $this->apiKey,
        ]);

        return $response->json()['items'] ?? [];
    }

    public function saveBooks(array $results): void
    {
        foreach ($results as $result) {
            $info = $result['volumeInfo'] ?? [];

            $isbn = null;
            foreach ($info['industryIdentifiers'] ?? [] as $identifier) {
                if ($identifier['type'] === 'ISBN_13') {
                    $isbn = $identifier['identifier'];
                    break;
                }
            }
            if (! $isbn) {
                foreach ($info['industryIdentifiers'] ?? [] as $identifier) {
                    if ($identifier['type'] === 'ISBN_10') {
                        $isbn = $identifier['identifier'];
                        break;
                    }
                }
            }
            if (! $isbn) {
                continue;
            }

            $editora = Editora::firstOrCreate(['nome' => $info['publisher'] ?? 'Desconhecida']);

            $livro = Livro::updateOrCreate(
                ['isbn' => $isbn],
                [
                    'nome' => $info['title'],
                    'bibliografia' => $info['description'] ?? 'Sem descrição',
                    'imagem' => $info['imageLinks']['thumbnail'] ?? null,
                    'preco' => $info['price'] ?? 0,
                    'editora_id' => $editora->id,
                ]
            );

            foreach ($info['authors'] ?? [] as $authorName) {
                $autor = Autor::firstOrCreate(['nome' => $authorName]);
                $livro->autor()->syncWithoutDetaching([$autor->id]);
            }
        }
    }

    public function searchAndSave(string $query, int $maxResults = 10): void
    {
        $results = $this->search($query, $maxResults);
        $this->saveBooks($results);
    }
}
