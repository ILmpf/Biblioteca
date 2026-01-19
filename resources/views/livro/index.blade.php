<x-layout>
    <div>
        <header class="py-8 md:py-12">
            <h1 class="text-3xl font-bold">Livros</h1>
            <p class="text-muted-foreground text-sm mt-2">Requisita um livro!</p>
        </header>

        <div>
            <a href="/livros" class="btn {{request()->has('estado') ? 'btn-outline' : ''}}">Todos</a>
            <a href="/livros?estado=disponivel" class="btn {{request('estado') === 'disponivel' ? '' : 'btn-outline'}}">Disponível</a>
            <a href="/livros?estado=indisponivel" class="btn {{request('estado') === 'indisponivel' ? '' : 'btn-outline'}}">Indisponível</a>
        </div>

        <div class="mt-10 text-muted">
            <div class="grid md:grid-cols-3 gap-10">
            @forelse($livros as $livro)
                <x-card href="{{route('livro.show', $livro)}}">
                    <x-slot:image>
                        <img src="{{$livro->imagem}}" alt="Imagem Livro">
                    </x-slot:image>

                    <x-slot:title>
                        {{$livro->nome}}
                    </x-slot:title>
                    <div>
                        <x-livro.status-label :status="$livro->isAvailable()">
                            {{$livro->isAvailable() ? "Disponível" : "Indisponível para requisitar"}}
                        </x-livro.status-label>
                    </div>
                    <p>Clica para consultar detalhes!</p>

                    <x-slot:actions>
                    </x-slot:actions>
                </x-card>
            @empty
                <p>Não existem livros de momento na BD.</p>
            @endforelse
            </div>
        </div>
    </div>
</x-layout>
