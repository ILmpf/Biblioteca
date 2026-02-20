<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreReviewRequest;
use App\Http\Requests\UpdateReviewRequest;
use App\Models\Requisicao;
use App\Models\Review;
use App\Models\User;
use App\Notifications\ReviewCreated;
use App\Notifications\ReviewStatusChanged;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ReviewController extends Controller
{
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', Review::class);

        $filtro = $request->query('filtro', 'em_aprovacao');

        $query = Review::with(['user', 'livro', 'requisicao']);

        match ($filtro) {
            'aprovada' => $query->aprovada(),
            'recusada' => $query->recusada(),
            default => $query->emAprovacao(),
        };

        $reviews = $query->latest()->paginate(15)->withQueryString();

        $stats = [
            'em_aprovacao' => Review::emAprovacao()->count(),
            'aprovada' => Review::aprovada()->count(),
            'recusada' => Review::recusada()->count(),
        ];

        return view('review.index', [
            'reviews' => $reviews,
            'stats' => $stats,
            'filtro' => $filtro,
        ]);
    }

    public function show(Review $review): View
    {
        Gate::authorize('view', $review);

        $review->load(['user', 'livro', 'requisicao']);

        return view('review.show', ['review' => $review]);
    }

    public function store(StoreReviewRequest $request, Requisicao $requisicao): RedirectResponse
    {
        $review = Review::create([
            'user_id' => $request->user()->id,
            'livro_id' => $request->livro_id,
            'requisicao_id' => $requisicao->id,
            'rating' => $request->rating,
            'comentario' => $request->comentario,
        ]);

        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new ReviewCreated($review));
        }

        return redirect()
            ->route('requisicao.show', $requisicao)
            ->with('success', 'Review submetida com sucesso! Aguarda aprovação.');
    }

    public function update(UpdateReviewRequest $request, Review $review): RedirectResponse
    {
        Gate::authorize('update', $review);

        $review->update([
            'estado' => $request->estado,
            'justificacao' => $request->justificacao,
        ]);

        $review->user->notify(new ReviewStatusChanged($review));

        return redirect()
            ->route('review.show', $review)
            ->with('success', 'Review atualizada com sucesso!');
    }
}
