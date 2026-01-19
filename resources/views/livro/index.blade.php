<x-layout>
    <div>
        <header class="py-8 md:py-12">
            <h1 class="text-3xl font-bold">Livros</h1>
            <p class="text-muted-foreground text-sm mt-2">Requisita um livro!</p>
        </header>

        <div>
            <a href="/livros" class="btn {{request()->has('estado') ? 'btn-outline' : ''}}">Todos
            </a>

            <a href="/livros?estado=disponivel" class="btn {{request('estado') === 'disponivel' ? '' : 'btn-outline'}}">
                Disponível <span class="text-xs pl-3">{{$availableCount}}</span>
            </a>

            <a href="/livros?estado=indisponivel" class="btn {{request('estado') === 'indisponivel' ? '' : 'btn-outline'}}">
                Indisponível<span class="text-xs pl-3">{{$unavailableCount}}</span>
            </a>
        </div>

        <div class="mt-10 text-muted">
            <div class="grid md:grid-cols-2 gap-10">
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
                    <span>Autores:</span>
                    <ul>
                        @foreach ($livro->autor as $autor)
                            <li>{{ $autor->nome }}</li>
                        @endforeach
                    </ul>


                    <x-slot:actions>
                    </x-slot:actions>
                </x-card>
            @empty
                <p>Não existem livros de momento.</p>
            @endforelse
            </div>
        </div>
    </div>
</x-layout>
