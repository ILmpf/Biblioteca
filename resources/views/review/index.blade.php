<x-layout>
    <!-- Página de Gestão de Reviews -->
    <div class="py-8 max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="font-bold text-4xl">Gestão de Reviews</h1>
                <p class="text-gray-600 mt-2">Aprova ou recusa reviews submetidas pelos utilizadores.</p>
            </div>
        </div>

        <!-- Estado -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            <a 
                href="{{ route('review.index', ['filtro' => 'em_aprovacao']) }}" 
                class="stats shadow transition-all hover:shadow-lg {{ $filtro === 'em_aprovacao' ? 'ring-2 ring-yellow-500' : '' }}"
            >
                <div class="stat">
                    <div class="stat-figure text-yellow-500">
                        <x-fas-clock class="w-8 h-8" />
                    </div>
                    <div class="stat-title">Aguardam aprovação</div>
                    <div class="stat-value text-yellow-500">{{ $stats['em_aprovacao'] }}</div>
                </div>
            </a>

            <a 
                href="{{ route('review.index', ['filtro' => 'aprovada']) }}" 
                class="stats shadow transition-all hover:shadow-lg {{ $filtro === 'aprovada' ? 'ring-2 ring-green-500' : '' }}"
            >
                <div class="stat">
                    <div class="stat-figure text-green-500">
                        <x-fas-check-circle class="w-8 h-8" />
                    </div>
                    <div class="stat-title">Aprovadas</div>
                    <div class="stat-value text-green-500">{{ $stats['aprovada'] }}</div>
                </div>
            </a>

            <a 
                href="{{ route('review.index', ['filtro' => 'recusada']) }}" 
                class="stats shadow transition-all hover:shadow-lg {{ $filtro === 'recusada' ? 'ring-2 ring-red-500' : '' }}"
            >
                <div class="stat">
                    <div class="stat-figure text-red-500">
                        <x-fas-times-circle class="w-8 h-8" />
                    </div>
                    <div class="stat-title">Rejeitadas</div>
                    <div class="stat-value text-red-500">{{ $stats['recusada'] }}</div>
                </div>
            </a>
        </div>

        <!-- Reviews -->
        <div class="card bg-base-100 shadow-xl">
            <div class="card-body">
                <h2 class="card-title text-2xl mb-4">
                    <x-fas-list class="w-6 h-6" />
                    @if($filtro === 'aprovada')
                        Reviews Aprovadas
                    @elseif($filtro === 'recusada')
                        Reviews Rejeitadas
                    @else
                        Reviews Pendentes
                    @endif
                </h2>

                @if($reviews->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="table table-zebra">
                            <thead>
                                <tr>
                                    <th>Cidadão</th>
                                    <th>Livro</th>
                                    <th>Avaliação</th>
                                    <th>Data</th>
                                    <th>Estado</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($reviews as $review)
                                    <tr class="hover">
                                        <td>
                                            <div class="flex items-center gap-3">
                                                <div class="avatar">
                                                    <div class="w-12 h-12 rounded-full">
                                                        <img src="{{ asset($review->user->image_path) }}" alt="{{ $review->user->name }}" />
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="font-bold">{{ $review->user->name }}</div>
                                                    <div class="text-sm opacity-50">{{ $review->user->email }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="flex items-center gap-2">
                                                <img 
                                                    src="{{ asset($review->livro->imagem) }}" 
                                                    alt="{{ $review->livro->nome }}"
                                                    class="w-12 h-16 object-cover rounded"
                                                />
                                                <div>
                                                    <div class="font-semibold">{{ Str::limit($review->livro->nome, 40) }}</div>
                                                    <div class="text-xs text-gray-500">{{ $review->livro->autor->pluck('nome')->join(', ') }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="flex items-center gap-1 text-yellow-500 text-lg">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= $review->rating)
                                                        ★
                                                    @else
                                                        ☆
                                                    @endif
                                                @endfor
                                                <span class="text-sm text-gray-600 ml-1">({{ $review->rating }}/5)</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="text-sm">{{ $review->created_at->format('d/m/Y') }}</span>
                                            <br>
                                            <span class="text-xs text-gray-500">{{ $review->created_at->diffForHumans() }}</span>
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ $review->estado->color() }} whitespace-normal">
                                                {{ $review->estado->label() }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('review.show', $review) }}" class="btn btn-sm btn-outline gap-2">
                                                <x-fas-eye class="w-4 h-4" />
                                                Gerir
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6">
                        {{ $reviews->links() }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <x-fas-inbox class="w-16 h-16 mx-auto text-gray-300 mb-4" />
                        <p class="text-xl text-gray-500">
                            @if($filtro === 'aprovada')
                                Não há reviews aprovadas
                            @elseif($filtro === 'recusada')
                                Não há reviews rejeitadas
                            @else
                                Não há reviews pendentes
                            @endif
                        </p>
                        <p class="text-sm text-gray-400 mt-2">
                            @if($filtro === 'em_aprovacao')
                                Todas as reviews foram processadas
                            @else
                                Nenhuma review com este estado
                            @endif
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layout>