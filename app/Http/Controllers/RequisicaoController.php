<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Requisicao\CreateRequisicaoAction;
use App\Actions\Requisicao\UpdateRequisicaoAction;
use App\Http\Requests\StoreRequisicaoRequest;
use App\Http\Requests\UpdateRequisicaoRequest;
use App\Models\Livro;
use App\Models\Requisicao;
use App\RequisicaoEstado;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RequisicaoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $user = $request->user();
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

        return view('requisicao.index', [
            'requisicoes' => $requisicoes,
            'ativas' => $ativas,
            'ultimos30Dias' => $ultimos30Dias,
            'entreguesHoje' => $entreguesHoje,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $livroSelecionado = $request->filled('livro_id')
            ? Livro::available()->findOrFail($request->livro_id)
            : null;

        $livrosDisponiveis = Livro::available()->get();

        return view('requisicao.create', [
            'livroSelecionado' => $livroSelecionado,
            'livrosDisponiveis' => $livrosDisponiveis,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequisicaoRequest $request, CreateRequisicaoAction $action): RedirectResponse
    {
        try {
            $action->handle($request->validated(), $request->user());
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }

        return redirect()
            ->route('requisicao.index')
            ->with('success', 'Requisição criada com sucesso! Receberás um mail com os detalhes.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Requisicao $requisicao): View
    {
        Gate::authorize('view', $requisicao);

        $requisicao->load(['livros', 'user', 'reviews.livro']);

        return view('requisicao.show', [
            'requisicao' => $requisicao,
        ]);
    }

    public function cancel(Requisicao $requisicao): RedirectResponse
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
     * Update the specified resource in storage.
     */
    public function update(
        UpdateRequisicaoRequest $request,
        Requisicao $requisicao,
        UpdateRequisicaoAction $action
    ): RedirectResponse {
        $action->handle($requisicao, $request->validated());

        return redirect()
            ->route('requisicao.show', $requisicao)
            ->with('success', 'Requisição atualizada com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Requisicao $requisicao): void
    {
        //
    }
}
