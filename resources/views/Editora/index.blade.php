<x-layout>
    <header class="py-8">
        <h1 class="text-3xl font-bold">Editoras</h1>
        <p class="text-sm text-muted-foreground mt-2">
            Lista de editoras disponíveis
        </p>
    </header>

    <div class="flex items-center gap-4">
        {{ $editoras->links('components.form.pagination') }}

        <div class="text-sm text-muted-foreground whitespace-nowrap">
            A mostrar
            <strong>{{ $editoras->firstItem() ?? 0 }}</strong>
            –
            <strong>{{ $editoras->lastItem() ?? 0 }}</strong>
            de
            <strong>{{ $editoras->total() }}</strong>
            editoras
        </div>
    </div>

    <div class="mt-6">
        <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($editoras as $editora)
                <div class="card bg-base-100 shadow-sm">
                    <figure>
                        <img
                            src="{{ $editora->logo ?? '/images/image-placeholder.png' }}"
                            alt="Logo {{ $editora->nome }}"
                            class="h-35 object-contain"
                        />
                    </figure>

                    <div class="card-body">
                        <h2 class="card-title justify-center">
                            {{ $editora->nome }}
                        </h2>

                        <p class="text-center text-sm text-muted-foreground">
                            {{ $count = $editora->livros_count ?? $editora->livro->count() }}
                            {{ Str::plural('livro', $count) }}
                        </p>

                        <div class="card-actions justify-center mt-4">
                            <a href="#"
                               class="btn btn-outline btn-sm">
                                Ver livros
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <p class="col-span-full text-center text-muted-foreground">
                    Nenhuma editora encontrada.
                </p>
            @endforelse
        </section>
    </div>
</x-layout>
