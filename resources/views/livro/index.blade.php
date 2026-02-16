<x-layout>
    <div>
        <!-- Header -->
        <header class="py-12 md:py-16 relative overflow-hidden">
            <div class="absolute inset-0 bg-linear-to-r from-primary/5 via-secondary/5 to-accent/5 -z-10"></div>
            <div class="flex flex-col items-center text-center gap-6">
                <div>
                    <h1 class="text-4xl md:text-5xl font-bold tracking-tight text-primary">
                        Catálogo de Livros
                    </h1>
                    <p class="text-base-content/70 mt-3 text-lg">
                        Explora a nossa coleção e encontra o teu próximo livro favorito!
                    </p>
                </div>

                @can('isAdmin')
                    <button
                        class="btn btn-primary gap-2 shadow-lg hover:shadow-xl transition-all"
                        x-data
                        @click="$dispatch('open-modal', 'create-livro')"
                        type="button"
                    >
                        <x-fas-plus class="h-5 w-5" />
                        Criar Novo Livro
                    </button>
                @endcan
            </div>
        </header>

        @include('livro.partials.filters')

        <!-- Informações de Resultados & Paginação -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mt-8">
            <div class="text-sm">
                <span class="text-base-content/70">A mostrar</span>
                <span class="font-bold text-primary">{{ $livros->firstItem() ?? 0 }}</span>
                <span class="text-base-content/70">–</span>
                <span class="font-bold text-primary">{{ $livros->lastItem() ?? 0 }}</span>
                <span class="text-base-content/70">de</span>
                <span class="font-bold text-secondary">{{ $livros->total() }}</span>
                <span class="text-base-content/70">livros</span>
            </div>
            {{ $livros->links('components.form.pagination') }}
        </div>

        <!-- Livros -->
        <div class="mt-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($livros as $livro)
                    <x-card href="{{ route('livro.show', $livro) }}">
                        <x-slot:image>
                            <img
                                src="{{ $livro->imagem }}"
                                alt="{{ $livro->nome }}"
                                class="object-cover w-full h-full group-hover:scale-110 transition-transform duration-500"
                            >
                        </x-slot:image>

                        <x-slot:title>
                            {{ $livro->nome }}
                        </x-slot:title>

                        <x-slot:editora>
                            {{ $livro->editora->nome }}
                        </x-slot:editora>

                        <!-- Detalhes do Livro -->
                        <div class="space-y-2">
                            <div class="flex items-start gap-2 text-sm text-base-content/70">
                                <x-fas-user class="h-4 w-4 mt-0.5 shrink-0" />
                                <span class="line-clamp-2">
                                    {{ $livro->autor->pluck('nome')->join(', ') }}
                                </span>
                            </div>

                            <!-- Estado -->
                            <div class="pt-2">
                                @if($livro->isAvailable())
                                    <div class="badge badge-success gap-1">
                                        <x-fas-check class="h-3 w-3" />
                                        Disponível
                                    </div>
                                @else
                                    <div class="badge badge-error gap-1">
                                        <x-fas-times class="h-3 w-3" />
                                        Indisponível
                                    </div>
                                @endif
                            </div>
                        </div>

                        <x-slot:actions>
                            @if($livro->isAvailable())
                                <button
                                    type="button"
                                    x-data
                                    @click.prevent="window.location='{{ route('requisicao.create', ['livro_id' => $livro->id]) }}'"
                                    class="btn btn-sm btn-success gap-1 shadow-lg hover:shadow-xl transition-all"
                                >
                                    <x-fas-bookmark class="h-4 w-4" />
                                    Requisitar
                                </button>
                            @endif
                        </x-slot:actions>
                    </x-card>

                @empty
                    <div class="col-span-full text-center py-16">
                        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-base-200 mb-4">
                            <x-fas-book class="h-10 w-10 text-base-content/30" />
                        </div>
                        <h3 class="text-xl font-semibold mb-2">Nenhum livro encontrado</h3>
                        <p class="text-base-content/70 mb-4">Tenta ajustar os teus filtros de pesquisa</p>
                        <a href="{{ route('livro.index') }}" class="btn btn-primary gap-2">
                            <x-fas-undo class="h-4 w-4" />
                            Limpar Filtros
                        </a>
                    </div>
                @endforelse
            </div>
        </div>

        @include('livro.partials.create-modal')
    </div>
</x-layout>
