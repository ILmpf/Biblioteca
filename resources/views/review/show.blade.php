<x-layout>
    <!-- Página de Detalhes da Review -->
    <div class="py-8 max-w-5xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('review.index') }}" class="flex items-center gap-2 text-sm hover:text-primary transition-colors group">
                <x-fas-arrow-left class="h-4 w-4 group-hover:-translate-x-1 transition-transform" />
                <span class="font-medium">Voltar às Reviews</span>
            </a>
        </div>

        <div class="card bg-base-100 shadow-xl">
            <div class="card-body">
                <div class="flex items-start justify-between mb-6">
                    <div>
                        <h1 class="text-3xl font-bold">Detalhes da Review</h1>
                        <p class="text-gray-600 mt-1">Submetida em {{ $review->created_at->format('d/m/Y \à\s H:i') }}</p>
                    </div>
                    <span class="badge badge-{{ $review->estado->color() }} badge-lg">
                        {{ $review->estado->label() }}
                    </span>
                </div>

                <div class="divider"></div>

                <!-- Informação do Utilizador -->
                <div class="bg-base-200 rounded-lg p-6 mb-6">
                    <h2 class="text-xl font-semibold mb-4 flex items-center gap-2">
                        <x-fas-user class="w-5 h-5" />
                        Informações do Cidadão
                    </h2>
                    <div class="flex items-center gap-4">
                        <div class="avatar">
                            <div class="w-20 h-20 rounded-full ring ring-primary ring-offset-base-100 ring-offset-2">
                                <img src="{{ asset($review->user->image_path) }}" alt="{{ $review->user->name }}" />
                            </div>
                        </div>
                        <div class="space-y-1">
                            <p class="text-lg font-bold">{{ $review->user->name }}</p>
                            <p class="text-sm text-gray-600">{{ $review->user->email }}</p>
                            <p class="text-xs text-gray-500">
                                Requisição: 
                                <a href="{{ route('requisicao.show', $review->requisicao) }}" class="link link-primary">
                                    {{ $review->requisicao->numero }}
                                </a>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Informação do Livro -->
                <div class="bg-base-200 rounded-lg p-6 mb-6">
                    <h2 class="text-xl font-semibold mb-4 flex items-center gap-2">
                        <x-fas-book class="w-5 h-5" />
                        Livro Avaliado
                    </h2>
                    <div class="flex items-start gap-6">
                        <img 
                            src="{{ asset($review->livro->imagem) }}" 
                            alt="{{ $review->livro->nome }}"
                            class="w-32 h-44 object-cover rounded-lg shadow-md"
                        />
                        <div class="flex-1">
                            <h3 class="text-2xl font-bold mb-2">{{ $review->livro->nome }}</h3>
                            <p class="text-gray-600 mb-1">
                                <strong>Autor(es):</strong> {{ $review->livro->autor->pluck('nome')->join(', ') }}
                            </p>
                            <p class="text-gray-600 mb-1">
                                <strong>Editora:</strong> {{ $review->livro->editora->nome }}
                            </p>
                            <p class="text-gray-600 mb-3">
                                <strong>ISBN:</strong> {{ $review->livro->isbn }}
                            </p>
                            <a href="{{ route('livro.show', $review->livro) }}" class="btn btn-sm btn-outline gap-2">
                                <x-fas-external-link-alt class="w-3 h-3" />
                                Ver Livro
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Conteúdo da Review -->
                <div class="bg-base-200 rounded-lg p-6 mb-6">
                    <h2 class="text-xl font-semibold mb-4 flex items-center gap-2">
                        <x-fas-star class="w-5 h-5" />
                        Conteúdo da Review
                    </h2>
                    
                    <div class="mb-4">
                        <label class="text-sm font-semibold text-gray-600 mb-2 block">Avaliação</label>
                        <div class="flex items-center gap-2">
                            <div class="text-yellow-500 text-3xl">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $review->rating)
                                        ★
                                    @else
                                        ☆
                                    @endif
                                @endfor
                            </div>
                            <span class="text-2xl font-bold text-gray-700">{{ $review->rating }}/5</span>
                        </div>
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-gray-600 mb-2 block">Comentário</label>
                        <div class="bg-white rounded-lg p-4 border">
                            <p class="text-gray-800 leading-relaxed">{{ $review->comentario }}</p>
                        </div>
                    </div>
                </div>

                @if($review->estado === \App\ReviewEstado::REJECTED && $review->justificacao)
                    <div class="alert alert-error mb-6">
                        <x-fas-exclamation-triangle class="w-6 h-6" />
                        <div>
                            <h3 class="font-bold">Justificação</h3>
                            <div class="text-sm">{{ $review->justificacao }}</div>
                        </div>
                    </div>
                @endif

                <!-- Formulário de Moderação -->
                @if($review->estado === \App\ReviewEstado::IN_APPROVAL)
                    <div class="divider"></div>

                    <div class="bg-warning/5 border-2 border-warning/20 rounded-lg p-6">
                        <h2 class="text-2xl font-semibold mb-6 flex items-center gap-2 text-yellow-600">
                            <x-fas-tasks class="w-6 h-6" />
                            Ação de Moderação
                        </h2>

                        <form action="{{ route('review.update', $review) }}" method="POST" class="space-y-6" x-data="{ estado: '' }">
                            @csrf
                            @method('PATCH')

                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text font-bold text-lg">Decisão *</span>
                                </label>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <label class="cursor-pointer border-2 rounded-lg p-6 flex items-center justify-between hover:border-success hover:bg-success/10 has-checked:border-success has-checked:bg-success/20 has-checked:shadow-lg transition-all">
                                        <span class="flex items-center gap-3">
                                            <x-fas-check-circle class="w-6 h-6 text-success" />
                                            <span class="font-bold text-lg">Aprovar Review</span>
                                        </span>
                                        <input 
                                            type="radio" 
                                            name="estado" 
                                            value="approved" 
                                            class="radio radio-success radio-lg"
                                            x-model="estado"
                                            required
                                        />
                                    </label>

                                    <label class="cursor-pointer border-2 rounded-lg p-6 flex items-center justify-between hover:border-error hover:bg-error/10 has-checked:border-error has-checked:bg-error/20 has-checked:shadow-lg transition-all">
                                        <span class="flex items-center gap-3">
                                            <x-fas-times-circle class="w-6 h-6 text-error" />
                                            <span class="font-bold text-lg">Rejeitar Review</span>
                                        </span>
                                        <input 
                                            type="radio" 
                                            name="estado" 
                                            value="rejected" 
                                            class="radio radio-error radio-lg"
                                            x-model="estado"
                                            required
                                        />
                                    </label>
                                </div>
                                @error('estado')
                                    <label class="label">
                                        <span class="label-text-alt text-error">{{ $message }}</span>
                                    </label>
                                @enderror
                            </div>

                            <div class="form-control" x-show="estado === 'rejected'" x-transition>
                                <label class="label">
                                    <span class="label-text font-bold text-lg">Justificação</span>
                                </label>
                                <textarea 
                                    name="justificacao" 
                                    class="textarea textarea-bordered textarea-lg h-32 w-full" 
                                    placeholder="Explica a justificação para rejeitar esta review. Esta informação será visível para o cidadão que a submeteu."
                                    :required="estado === 'rejected'"
                                    minlength="10"
                                    maxlength="500"
                                >{{ old('justificacao') }}</textarea>
                                <label class="label">
                                    <span class="label-text-alt">Mínimo 10 caracteres, máximo 500</span>
                                </label>
                                @error('justificacao')
                                    <label class="label">
                                        <span class="label-text-alt text-error font-semibold">{{ $message }}</span>
                                    </label>
                                @enderror
                            </div>

                            <div class="flex gap-4 justify-end pt-6 border-t">
                                <a href="{{ route('review.index') }}" class="btn btn-ghost btn-lg">
                                    <x-fas-times class="w-4 h-4" />
                                    Cancelar
                                </a>
                                <button type="submit" class="btn btn-primary btn-lg gap-2" :disabled="!estado">
                                    <x-fas-paper-plane class="w-5 h-5" />
                                    Submeter Decisão
                                </button>
                            </div>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layout>