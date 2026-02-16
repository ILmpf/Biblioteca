<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Livro\CreateLivroAction;
use App\Actions\Livro\UpdateLivroAction;
use App\Http\Requests\StoreLivroRequest;
use App\Http\Requests\UpdateLivroRequest;
use App\Models\Autor;
use App\Models\Editora;
use App\Models\Livro;
use App\Services\Google;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class LivroController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $allowedSorts = ['nome', 'created_at', 'updated_at'];
        $allowedDirections = ['asc', 'desc'];

        $sort = $request->input('sort', 'nome');
        $direction = $request->input('direction', 'asc');

        if (! in_array($sort, $allowedSorts, true)) {
            $sort = 'nome';
        }
        if (! in_array($direction, $allowedDirections, true)) {
            $direction = 'asc';
        }

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
            ->orderBy($sort, $direction)
            ->paginate(6)
            ->withQueryString();

        return view('livro.index', [
            'livros' => $livros,
            'editoras' => Editora::orderBy('nome')->get(),
            'autores' => Autor::orderBy('nome')->get(),
            'availableCount' => Livro::disponivel()->count(),
            'unavailableCount' => Livro::indisponivel()->count(),
            'sort' => $sort,
            'direction' => $direction,
        ]);
    }

    public function importGoogle(Request $request, Google $google): RedirectResponse
    {
        $query = $request->input('q', 'Laravel');
        $google->searchAndSave($query);

        return redirect()->back()->with('success', 'Livros importados com sucesso!');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        Gate::authorize('create', Livro::class);

        return view('livro.create', [
            'editoras' => Editora::orderBy('nome')->get(),
            'autores' => Autor::orderBy('nome')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLivroRequest $request, CreateLivroAction $action): RedirectResponse
    {
        Gate::authorize('create', Livro::class);
        $livro = $action->handle($request->validated());

        return redirect()->route('livro.show', $livro)->with('success', 'Livro criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Livro $livro): \Illuminate\View\View
    {
        return view('livro.show', [
            'livro' => $livro,
            'editoras' => Editora::orderBy('nome')->get(),
            'autores' => Autor::orderBy('nome')->get(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Livro $livro): \Illuminate\View\View
    {
        Gate::authorize('update', $livro);

        return view('livro.edit', [
            'livro' => $livro,
            'editoras' => Editora::orderBy('nome')->get(),
            'autores' => Autor::orderBy('nome')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLivroRequest $request, Livro $livro, UpdateLivroAction $action): \Illuminate\Http\RedirectResponse
    {
        Gate::authorize('update', $livro);
        $action->handle($livro, $request->validated());

        return redirect()->route('livro.show', $livro)->with('success', 'Livro atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Livro $livro): \Illuminate\Http\RedirectResponse
    {
        if (! Gate::allows('delete', $livro)) {
            return back()->with('error', 'Não é possível eliminar este livro porque está numa requisição ativa.');
        }

        $livro->delete();

        return to_route('livro.index')->with('success', 'Livro eliminado com sucesso!');
    }
}
