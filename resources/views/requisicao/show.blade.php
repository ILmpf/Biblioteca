<x-layout>
    <!-- Página de Detalhes da Requisição -->
    <div 
        class="container mx-auto px-4 py-6 max-w-7xl" 
        x-data="{
            editing: false,
            saving: false,
            selectedState: '{{ $requisicao->estado->value }}',
            deliveryDate: '{{ old('data_entrega', $requisicao->data_entrega?->format('Y-m-d')) }}',
            totalBooks: {{ $requisicao->livros->count() }},
            deliveredBooks: [],
            toggleBook(bookId, checked) {
                if (checked && !this.deliveredBooks.includes(bookId)) {
                    this.deliveredBooks.push(bookId);
                } else if (!checked) {
                    this.deliveredBooks = this.deliveredBooks.filter(id => id !== bookId);
                }
            },
            get allBooksDelivered() {
                return this.deliveredBooks.length === this.totalBooks;
            }
        }"
        x-init="
            const checkboxes = document.querySelectorAll('input[name=\'livros_entregue[]\']');
            checkboxes.forEach(cb => {
                if (cb.checked) deliveredBooks.push(cb.value);
            });
        "
    >
        <!-- Navegação -->
        <x-layout.breadcrumb url="{{ route('requisicao.index') }}" text="Voltar às Requisições" />


        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mt-6 mb-8">
            <div>
                <h1 class="text-3xl font-bold">{{ $requisicao->numero }}</h1>
            </div>

            <!-- Ações -->
            <div class="flex gap-2">
                @can('update', $requisicao)
                    <button
                        @click="editing = !editing"
                        class="btn"
                        :class="editing ? 'btn-primary' : 'btn-outline'"
                    >
                        <x-fas-edit class="h-4 w-4" />
                        <span x-text="editing ? 'Modo Edição' : 'Editar'"></span>
                    </button>
                @endcan
            </div>
        </div>

        <!-- Conteúdo Principal -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Detalhes da Requisição -->
            <div class="lg:col-span-2">
                <div class="card bg-base-100 shadow-xl">
                    <div class="card-body">
                        <h2 class="card-title text-2xl mb-6">Detalhes da Requisição</h2>
                        
                        <!-- Editar Requisição -->
                        <form 
                            action="{{ route('requisicao.update', $requisicao) }}" 
                            method="POST"
                            id="update-requisicao-form"
                            @submit="saving = true"
                        >
                            @csrf
                            @method('PUT')

                            <div class="space-y-6">
                                <!-- Estado -->
                                <div class="form-control">
                                    <label class="label">
                                        <span class="label-text text-base font-semibold flex items-center gap-2">
                                            <x-fas-info-circle class="h-5 w-5 text-primary" />
                                            Estado
                                        </span>
                                    </label>
                                    <div x-show="!editing" x-cloak>
                                        <x-requisicao.status-label status="{{ $requisicao->estado }}" class="badge-lg text-base">
                                            {{ $requisicao->estado->label() }}
                                        </x-requisicao.status-label>
                                    </div>
                                    <select 
                                        x-show="editing"
                                        x-cloak
                                        x-model="selectedState"
                                        name="estado" 
                                        class="select select-bordered select-lg w-full"
                                        required
                                        @change="if (selectedState === 'active') { deliveryDate = '' }"
                                    >
                                        @foreach (\App\RequisicaoEstado::cases() as $stateOption)
                                            <option 
                                                value="{{ $stateOption->value }}" 
                                                @selected($requisicao->estado === $stateOption)
                                                :disabled="('{{ $stateOption->value }}' === 'completed' && !allBooksDelivered) || ('{{ $stateOption->value }}' === 'cancelled' && '{{ $requisicao->estado->value }}' === 'completed')"
                                            >
                                                {{ $stateOption->label() }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div x-show="editing && selectedState === 'completed' && !allBooksDelivered" x-cloak class="mt-2">
                                        <div class="alert alert-warning text-sm">
                                            <x-fas-exclamation-triangle class="h-4 w-4" />
                                            <span>Todos os livros devem estar marcados como entregues para concluir a requisição.</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Data da Requisição -->
                                <div class="form-control">
                                    <label class="label">
                                        <span class="label-text text-base font-semibold flex items-center gap-2">
                                            <x-fas-calendar class="h-5 w-5 text-primary" />
                                            Data da Requisição
                                        </span>
                                    </label>
                                    <p class="text-lg font-medium py-2">{{ $requisicao->data_requisicao->format('d/m/Y') }}</p>
                                </div>

                                <!-- Data Entrega Prevista -->
                                <div class="form-control">
                                    <label class="label">
                                        <span class="label-text text-base font-semibold flex items-center gap-2">
                                            <x-fas-calendar-alt class="h-5 w-5 text-primary" />
                                            Entrega Prevista
                                        </span>
                                    </label>
                                    <p class="text-lg font-medium py-2">
                                        @if($requisicao->estado === \App\RequisicaoEstado::CANCELLED)
                                            N/A
                                        @else
                                            {{ $requisicao->data_entrega_prevista->format('d/m/Y') }}
                                        @endif
                                    </p>
                                </div>

                                <!-- Data de Entrega -->
                                <div class="form-control">
                                    <label class="label">
                                        <span class="label-text text-base font-semibold flex items-center gap-2">
                                            <x-fas-calendar-check class="h-5 w-5 text-primary" />
                                            Data de Entrega
                                        </span>
                                    </label>
                                    <div x-show="!editing" x-cloak>
                                        <p class="text-lg font-medium py-2">
                                            @if($requisicao->estado === \App\RequisicaoEstado::CANCELLED)
                                                N/A
                                            @else
                                                {{ $requisicao->data_entrega?->format('d/m/Y') ?? 'Não entregue' }}
                                            @endif
                                        </p>
                                    </div>
                                    <input
                                        x-show="editing"
                                        x-cloak
                                        x-model="deliveryDate"
                                        type="date"
                                        name="data_entrega"
                                        class="input input-bordered input-lg w-full"
                                    >
                                </div>
                            </div>

                            <!-- Ações -->
                            <div x-show="editing" x-cloak class="card-actions justify-end mt-8">
                                <button
                                    type="button"
                                    @click="editing = false; saving = false"
                                    class="btn btn-lg"
                                    :disabled="saving"
                                >
                                    Cancelar
                                </button>
                                <button 
                                    type="submit" 
                                    class="btn btn-primary btn-lg"
                                    :disabled="saving"
                                >
                                    <span x-show="!saving">Guardar</span>
                                    <span x-show="saving">
                                        <span class="loading loading-spinner loading-sm"></span>
                                        A guardar...
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Informação do Cidadão -->
            <div class="lg:col-span-1">
                <div class="card bg-base-100 shadow-xl">
                    <div class="card-body">
                        <h2 class="card-title text-2xl mb-6">Cidadão</h2>
                        
                        <div class="flex flex-col items-center text-center space-y-6 py-4">
                            <div class="avatar">
                                <div class="w-32 rounded-full ring ring-primary ring-offset-base-100 ring-offset-2">
                                    <img
                                        src="{{ asset($requisicao->user->image_path) }}"
                                        alt="{{ $requisicao->user->name }}"
                                    >
                                </div>
                            </div>
                            
                            <div>
                                <h3 class="text-xl font-bold mb-2">{{ $requisicao->user->name }}</h3>
                                <p class="text-base text-base-content/70">{{ $requisicao->user->email }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Secção de Livros -->
        <div class="mt-6">
            <div class="card bg-base-100 shadow-xl">
                <div class="card-body">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="card-title text-2xl">Livros Requisitados ({{ $requisicao->livros->count() }})</h2>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Livro</th>
                                    <th>Autor</th>
                                    <th>Editora</th>
                                    <th>Estado</th>
                                    @can('update', $requisicao)
                                        <th x-show="editing" x-cloak>Entregue</th>
                                    @endcan
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($requisicao->livros as $book)
                                    <tr>
                                        <td>
                                            <div class="flex items-center gap-3">
                                                <div class="avatar">
                                                    <div class="mask mask-squircle w-12 h-12">
                                                        <img src="{{ asset($book->imagem) }}" alt="{{ $book->nome }}" />
                                                    </div>
                                                </div>
                                                <div>
                                                    <a href="{{ route('livro.show', $book) }}" class="font-bold hover:link">{{ $book->nome }}</a>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $book->autor->pluck('nome')->join(', ') }}</td>
                                        <td>{{ $book->editora->nome }}</td>
                                        <td>
                                            <span class="badge {{ $book->pivot->entregue ? 'badge-success' : 'badge-warning' }}">
                                                {{ $book->pivot->entregue ? 'Entregue' : 'Pendente' }}
                                            </span>
                                        </td>
                                        @can('update', $requisicao)
                                            <td x-show="editing" x-cloak>
                                                <input
                                                    type="checkbox"
                                                    name="livros_entregue[]"
                                                    value="{{ $book->id }}"
                                                    form="update-requisicao-form"
                                                    class="checkbox checkbox-primary"
                                                    @checked(old('livros_entregue') ? in_array($book->id, old('livros_entregue', [])) : $book->pivot->entregue)
                                                    @change="toggleBook('{{ $book->id }}', $event.target.checked)"
                                                >
                                            </td>
                                        @endcan
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Secção de Reviews -->
        @if($requisicao->estado === \App\RequisicaoEstado::COMPLETED && auth()->user()->role === 'cidadão')
            <div class="mt-6">
                <div class="card bg-base-100 shadow-xl">
                    <div class="card-body">
                        <h2 class="card-title text-2xl mb-6">Reviews dos Livros</h2>
                        
                        <div class="space-y-4">
                            @foreach ($requisicao->livros->where('pivot.entregue', true) as $book)
                                @php
                                    $existingReview = $requisicao->reviews->where('livro_id', $book->id)->first();
                                @endphp
                                
                                <div class="border rounded-lg p-4">
                                    <div class="flex items-start gap-4">
                                        <img 
                                            src="{{ asset($book->imagem) }}" 
                                            alt="{{ $book->nome }}"
                                            class="w-20 h-28 object-cover rounded"
                                        />
                                        
                                        <div class="flex-1">
                                            <h3 class="font-bold text-lg">{{ $book->nome }}</h3>
                                            <p class="text-sm text-base-content/60">{{ $book->autor->pluck('nome')->join(', ') }}</p>
                                            
                                            @if($existingReview)
                                                <div class="mt-3 p-3 bg-base-200 rounded">
                                                    <div class="flex items-center gap-2 mb-2">
                                                        <span class="badge badge-{{ $existingReview->estado->color() }}">
                                                            {{ $existingReview->estado->label() }}
                                                        </span>
                                                        <span class="text-yellow-400">
                                                            @for($i = 1; $i <= 5; $i++)
                                                                @if($i <= $existingReview->rating)
                                                                    ★
                                                                @else
                                                                    ☆
                                                                @endif
                                                            @endfor
                                                        </span>
                                                    </div>
                                                    <p class="text-sm">{{ $existingReview->comentario }}</p>
                                                    
                                                    @if($existingReview->estado === \App\ReviewEstado::REJECTED && $existingReview->justificacao)
                                                        <div class="mt-2 p-2 bg-error/10 rounded border border-error/20">
                                                            <p class="text-xs font-semibold text-error">Motivo da recusa:</p>
                                                            <p class="text-xs text-error/80">{{ $existingReview->justificacao }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            @else
                                                <button 
                                                    class="btn btn-sm btn-primary mt-3"
                                                    onclick="document.getElementById('review_modal_{{ $book->id }}').showModal()"
                                                >
                                                    Deixar Review
                                                </button>
                                                
                                                <!-- Modal de Review -->
                                                <dialog id="review_modal_{{ $book->id }}" class="modal">
                                                    <div class="modal-box max-w-2xl">
                                                        <form method="dialog">
                                                            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                                                        </form>
                                                        
                                                        <h3 class="font-bold text-lg mb-4">Deixar Review - {{ $book->nome }}</h3>
                                                        
                                                        <form action="{{ route('review.store', $requisicao) }}" method="POST" class="space-y-4">
                                                            @csrf
                                                            <input type="hidden" name="livro_id" value="{{ $book->id }}">
                                                            
                                                            <div class="form-control">
                                                                <label class="label">
                                                                    <span class="label-text font-semibold">Avaliação *</span>
                                                                </label>
                                                                <div class="rating rating-lg">
                                                                    @for($i = 1; $i <= 5; $i++)
                                                                        <input 
                                                                            type="radio" 
                                                                            name="rating" 
                                                                            value="{{ $i }}" 
                                                                            class="mask mask-star-2 bg-yellow-400"
                                                                            required
                                                                        />
                                                                    @endfor
                                                                </div>
                                                                @error('rating')
                                                                    <label class="label">
                                                                        <span class="label-text-alt text-error">{{ $message }}</span>
                                                                    </label>
                                                                @enderror
                                                            </div>
                                                            
                                                            <div class="form-control">
                                                                <label class="label">
                                                                    <span class="label-text font-semibold">Comentário *</span>
                                                                </label>
                                                                <textarea 
                                                                    name="comentario" 
                                                                    class="textarea textarea-bordered h-32" 
                                                                    placeholder="Partilha a tua opinião sobre este livro..."
                                                                    required
                                                                    minlength="10"
                                                                    maxlength="1000"
                                                                >{{ old('comentario') }}</textarea>
                                                                <label class="label">
                                                                    <span class="label-text-alt">Mínimo 10 caracteres</span>
                                                                    <span class="label-text-alt">Máximo 1000 caracteres</span>
                                                                </label>
                                                                @error('comentario')
                                                                    <label class="label">
                                                                        <span class="label-text-alt text-error">{{ $message }}</span>
                                                                    </label>
                                                                @enderror
                                                            </div>
                                                            
                                                            <div class="modal-action">
                                                                <button type="button" onclick="this.closest('dialog').close()" class="btn">
                                                                    Cancelar
                                                                </button>
                                                                <button type="submit" class="btn btn-primary">
                                                                    Submeter Review
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <form method="dialog" class="modal-backdrop">
                                                        <button>close</button>
                                                    </form>
                                                </dialog>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-layout>
