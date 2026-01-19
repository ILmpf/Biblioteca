<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreLivroRequest;
use App\Http\Requests\UpdateLivroRequest;
use App\Models\Livro;
use App\RequisicaoEstado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LivroController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
       $query = Livro::query();


       if ($request->filled('nome')) {
           $query->where('nome', 'like', '%' . $request->nome . '%');
       }

       if ($request->filled('autor')) {
            $query->whereHas('autor', function ($q) use ($request) {
                $q->where('nome', 'like', '%' . $request->autor . '%');
            });
       }

       if ($request->filled('editora')) {
            $query->whereHas('editora', function ($q) use ($request) {
                $q->where('nome', 'like', '%' . $request->editora . '%');
            });
       }

       $query->when($request->filled('estado'), function ($q) use ($request) {
            match ($request->estado) {
                'indisponivel' => $q->whereHas('requisicao', fn ($qr) =>
                $qr->where('requisicao_livro.entregue', 0)
                ),

                'disponivel' => $q->whereDoesntHave('requisicao', fn ($qr) =>
                $qr->where('requisicao_livro.entregue', 0)
                ),

                default => null,
            };
       });

        $livros = $query
            ->with(['autor', 'editora'])
            ->get();

        return view('livro.index', [
            'livros' => $livros
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLivroRequest $request): void
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Livro $livro): void
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Livro $livro): void
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLivroRequest $request, Livro $livro): void
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Livro $livro): void
    {
        //
    }
}
