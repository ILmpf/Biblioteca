<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Livro;
use App\Models\Requisicao;
use App\RequisicaoEstado;
use Illuminate\Http\Request;

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
            ->with(['livro', 'user'])
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
            'livrosDisponiveis' => $livrosDisponiveis
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): void
    {
        //
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
