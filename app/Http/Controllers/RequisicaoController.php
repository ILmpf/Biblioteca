<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Livro;
use App\Models\Requisicao;
use App\Notifications\RequisicaoCreated;
use App\RequisicaoEstado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class RequisicaoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        $baseQuery = Requisicao::query()
            ->when($user->role !== 'admin', fn ($q) => $q->where('user_id', $user->id));

        $ativas = (clone $baseQuery)->where('estado', RequisicaoEstado::ACTIVE)->count();
        $ultimos30Dias = (clone $baseQuery)->where('created_at', '>=', now()->subDays(30))->count();
        $entreguesHoje = (clone $baseQuery)->whereDate('data_entrega', today())->count();

        $filteredQuery = (clone $baseQuery);

        if ($request->filtro) {
            match ($request->filtro) {
                'ativas' => $filteredQuery->where('estado', RequisicaoEstado::ACTIVE),
                'ultimos30Dias' => $filteredQuery->where('created_at', '>=', now()->subDays(30)),
                'entreguesHoje' => $filteredQuery->whereDate('data_entrega', today()),
            };
        }

        $requisicoes = $filteredQuery
            ->with(['livros', 'user'])
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('requisicao.index', ['requisicoes' => $requisicoes, 'ativas' => $ativas, 'ultimos30Dias' => $ultimos30Dias, 'entreguesHoje' => $entreguesHoje]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $livroSelecionado = null;

        if ($request->filled('livro_id')) {
            $livroSelecionado = Livro::available()->findOrFail($request->livro_id);
        }

        $livrosDisponiveis = Livro::available()->get();

        return view('requisicao.create', [
            'livroSelecionado' => $livroSelecionado,
            'livrosDisponiveis' => $livrosDisponiveis,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'livros' => ['required', 'array', 'min:1', 'max:3'],
            'livros.*' => ['required', 'exists:livros,id'],
        ]);

        $livrosId = collect($request->livros)
            ->filter()
            ->unique();

        if (! Gate::allows('create', [Requisicao::class, $livrosId->count()])) {
            return back()
                ->withErrors([
                    'livros' => 'Não pode requisitar mais de 3 livros em simultâneo.',
                ])
                ->withInput();
        }

        $livros = Livro::whereIn('id', $livrosId)->get();

        foreach ($livros as $livro) {
            if (! $livro->isAvailable()) {
                return back()
                    ->withErrors([
                        'livros' => "O livro \"{$livro->nome}\" não está disponível.",
                    ])
                    ->withInput();
            }
        }

        DB::transaction(function () use ($livrosId) {
            $requisicao = Requisicao::create([
                'numero' => 'TEMP',
                'user_id' => auth()->id(),
                'estado' => RequisicaoEstado::ACTIVE,
                'data_requisicao' => now(),
                'data_entrega_prevista' => now()->addDays(5),
            ]);

            $requisicao->update([
                'numero' => 'REQ-'.$requisicao->id,
            ]);

            $requisicao->livros()->attach(
                $livrosId->mapWithKeys(fn ($id) => [
                    $id => ['entregue' => false],
                ])
            );

            Auth::user()->notify((new RequisicaoCreated($requisicao))->afterCommit());
        });

        return redirect()
            ->route('requisicao.index')
            ->with('success', 'Requisicao criada com sucesso! Reberás um mail com os detalhes.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Requisicao $requisicao)
    {
        return view('requisicao.show', [
            'requisicao' => $requisicao,
        ]);
    }

    public function cancel(Requisicao $requisicao)
    {
        Gate::authorize('cancel', $requisicao);

        if ($requisicao->estado === RequisicaoEstado::CANCELLED) {
            return back()->withErrors('A requisição já se encontra cancelada.');
        }

        DB::transaction(function () use ($requisicao) {
            $requisicao->update([
                'estado' => RequisicaoEstado::CANCELLED,
                'data_entrega' => null,
            ]);

            $requisicao->livros()->syncWithPivotValues(
                $requisicao->livros->modelKeys(),
                ['entregue' => true],
                false
            );
        });

        return back()->with('success', 'Requisição cancelada com sucesso.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Requisicao $requisicao): void
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Requisicao $requisicao): void
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Requisicao $requisicao): void
    {
        //
    }
}
