<x-layout>
    <div>
        <header class="py-10 md:py-14 border-b border-base-200">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div>
                    <h1 class="text-4xl font-bold tracking-tight">
                        Catálogo de Livros
                    </h1>
                    <p class="text-muted-foreground mt-2">
                        Requisita um livro!
                    </p>
                </div>

                @can('isAdmin')
                    <button
                        class="btn btn-outline gap-2"
                        x-data
                        @click="$dispatch('open-modal', 'create-livro')"
                        type="button"
                    >
                        <x-icons.create/>
                        Criar novo livro
                    </button>
                @endcan
            </div>
        </header>


        <div class="mt-6 bg-base-100 p-6 rounded-xl shadow-sm space-y-6">
            <div class="flex flex-wrap gap-2">
                <a href="/livros" class="btn {{ request()->has('estado') ? 'btn-outline' : '' }}">
                    Todos
                </a>

                <a href="/livros?estado=disponivel"
                   class="btn {{ request('estado') === 'disponivel' ? '' : 'btn-outline' }}">
                    Disponível
                    <span class="badge badge-success ml-2">{{ $availableCount }}</span>
                </a>

                <a href="/livros?estado=indisponivel"
                   class="btn {{ request('estado') === 'indisponivel' ? '' : 'btn-outline' }}">
                    Indisponível
                    <span class="badge badge-error ml-2">{{ $unavailableCount }}</span>
                </a>
            </div>
        </div>

        <div class="mt-6">
            <hr class="border-base-200"/>
            <form method="GET"
                  action="{{ route('livro.index') }}"
                  class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">

                @if(request('estado'))
                    <input type="hidden" name="estado" value="{{ request('estado') }}">
                @endif

                <x-form.field
                    label="Título"
                    name="nome"
                />

                <x-form.field
                    label="Autor"
                    name="autor"
                />

                <x-form.field
                    label="Editora"
                    name="editora"
                />

                <div class="flex gap-2">
                    <button type="submit" class="btn btn-secondary">
                        Filtrar
                    </button>

                    <a href="{{ route('livro.index') }}"
                       class="btn btn-outline">
                        Limpar
                    </a>
                </div>
            </form>
        </div>

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mt-8">
            {{ $livros->links('components.form.pagination') }}

            <div class="text-sm text-muted-foreground">
                A mostrar
                <strong>{{ $livros->firstItem() ?? 0 }}</strong>
                –
                <strong>{{ $livros->lastItem() ?? 0 }}</strong>
                de
                <strong>{{ $livros->total() }}</strong>
                livros
            </div>
        </div>

        <div class="mt-6 text-muted">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                @forelse($livros as $livro)
                    <x-card href="{{ route('livro.show', $livro) }}">
                        <x-slot:image>
                            <img
                                src="{{ $livro->imagem }}"
                                alt="Imagem Livro"
                                class="object-cover w-full h-full rounded-md"
                            >
                        </x-slot:image>

                        <x-slot:title>
                            {{ $livro->nome }}
                        </x-slot:title>

                        <div class="flex flex-col gap-1 text-sm text-muted-foreground">
                            <span>
                                <strong>
                                    {{ $livro->autor->count() > 1 ? 'Autores' : 'Autor' }}:
                                </strong>
                                {{ $livro->autor->pluck('nome')->join(', ') }}
                            </span>

                            <span>
                                <strong>Editora:</strong>
                                {{ $livro->editora->nome }}
                            </span>
                        </div>

                        <div class="mt-2">
                            <x-livro.status-label :status="$livro->isAvailable()">
                                {{$livro->isAvailable() ? "Disponível" : "Indisponível para requisitar"}}
                            </x-livro.status-label>
                        </div>

                        <x-slot:actions>
                            <button
                                type="button"
                                x-data
                                @click="window.location='{{ route('requisicao.create', ['livro_id' => $livro->id]) }}'"
                                class="btn btn-sm
                                {{ $livro->isAvailable()
                                    ? 'bg-green-400 hover:bg-green-500'
                                    : 'btn-ghost'
                                }}"
                                {{ !$livro->isAvailable() ? 'disabled' : '' }}
                            >
                                {{ $livro->isAvailable() ? 'Requisitar' : '' }}
                            </button>
                        </x-slot:actions>
                    </x-card>

                @empty
                    <p>Não existem livros de momento.</p>
                @endforelse
            </div>
        </div>

        <x-modal name="create-livro" title="Novo Livro">
            <form method="POST" action="{{route('livro.store')}}" class="w-full">
                @csrf

                <div class="space-y-6">
                    <x-form.field
                        label="Título do Livro"
                        name="nome"
                        placeholder="Introduz o título do livro"
                        autofocus
                        required
                    />

                    <div x-data="{ editora: '' }" class="space-y-2">
                        <label class="font-medium">Editora</label>

                        <select
                            name="editora_id"
                            x-model="editora"
                            class="select select-md w-full"
                            required
                        >
                            <option value="new">Seleciona uma editora</option>
                            <option value="new">Nova Editora</option>

                            @foreach($editoras as $editora)
                                <option value="{{ $editora->id }}">
                                    {{ $editora->nome }}
                                </option>
                            @endforeach
                        </select>

                        {{-- Appears only if "new" --}}
                        <div x-show="editora === 'new'" x-transition>
                            <x-form.field
                                label="Nova Editora"
                                name="editora_nome"
                                placeholder="Nome da editora"
                            />
                        </div>
                    </div>

                    <x-form.field
                        label="Editora"
                        name="editora"
                        placeholder="Introduz a Editora do livro"
                    />

                    <x-form.field
                        label="Bibliografia"
                        name="bibliogragia"
                        type="textarea"
                        placeholder="Bibliografia..."
                    />

                    <div class="flex justify-end gap-x-5">
                        <button type="button" class="btn bg-red-400 hover:bg-red-500" @click="$dispatch('close-modal')">Cancelar</button>
                        <button type="submit" class="btn bg-green-400 hover:bg-green-500">Criar</button>
                    </div>

                </div>
            </form>
        </x-modal>
    </div>
</x-layout>
