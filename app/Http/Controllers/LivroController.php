<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreLivroRequest;
use App\Http\Requests\UpdateLivroRequest;
use App\Models\Autor;
use App\Models\Editora;
use App\Models\Livro;
use App\Services\Google;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class LivroController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $livros = Livro::query()
            ->when($request->nome, fn ($q, $v) => $q->nome($v))
            ->when($request->autor, fn ($q, $v) => $q->autor($v))
            ->when($request->editora, fn ($q, $v) => $q->editora($v))
            ->when($request->estado, function ($q, $estado) {
                match ($estado) {
                    'disponivel' => $q->disponivel(),
                    'indisponivel' => $q->indisponivel(),
                };
            })
            ->with(['autor', 'editora'])
            ->paginate(6)
            ->withQueryString();

        return view('livro.index', [
            'livros' => $livros,
            'editoras' => Editora::orderBy('nome')->get(),
            'autores' => Autor::orderBy('nome')->get(),
            'availableCount' => Livro::disponivel()->count(),
            'unavailableCount' => Livro::indisponivel()->count(),
        ]);
    }

    public function importGoogle(Request $request)
    {
        $query = $request->input('q', 'Laravel');
        $service = new Google();
        $service->searcAndSave($query);

        return redirect()->back()->with('success', 'Livros importados com sucesso!');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): void
    {
        Gate::authorize('create', Livro::class);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLivroRequest $request): never
    {

        dd('persiste o livro');
    }

    /**
     * Display the specified resource.
     */
    public function show(Livro $livro)
    {
        return view('livro.show', [
            'livro' => $livro,
        ]);
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
        Gate::authorize('update', $livro);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Livro $livro)
    {
        Gate::authorize('delete', $livro);

        $livro->delete();

        return to_route('livro.index');
    }
}
