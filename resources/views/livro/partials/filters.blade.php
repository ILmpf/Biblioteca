<!-- Filtros -->
<div class="bg-base-100 p-6 rounded-2xl shadow-xl border border-base-200 space-y-6">
    <div>
        <label class="text-sm font-semibold text-base-content/70 mb-3 block">
            <x-fas-filter class="h-4 w-4 inline mr-1" />
            Filtrar por Estado
        </label>
        <div class="flex flex-wrap gap-2">
            <a href="/livros" 
               class="btn {{ request()->has('estado') ? 'btn-outline' : 'btn-primary' }} gap-2">
                <x-fas-th class="h-4 w-4" />
                Todos
            </a>

            <a href="/livros?estado=disponivel"
               class="btn {{ request('estado') === 'disponivel' ? 'btn-success' : 'btn-outline' }} gap-2">
                <x-fas-check-circle class="h-4 w-4" />
                Disponível
                <span class="badge {{ request('estado') === 'disponivel' ? 'badge-success badge-outline' : '' }}">
                    {{ $availableCount }}
                </span>
            </a>

            <a href="/livros?estado=indisponivel"
               class="btn {{ request('estado') === 'indisponivel' ? 'btn-error' : 'btn-outline' }} gap-2">
                <x-fas-times-circle class="h-4 w-4" />
                Indisponível
                <span class="badge {{ request('estado') === 'indisponivel' ? 'badge-error badge-outline' : '' }}">
                    {{ $unavailableCount }}
                </span>
            </a>
        </div>
    </div>

    <div class="divider my-2"></div>

    <!-- Filtros de pesquisa -->
    <form method="GET" action="{{ route('livro.index') }}" class="space-y-4">
        @if(request('estado'))
            <input type="hidden" name="estado" value="{{ request('estado') }}">
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            <div class="form-control">
                <label class="label">
                    <span class="label-text font-semibold flex items-center gap-2">
                        <x-fas-book class="h-4 w-4" />
                        Título
                    </span>
                </label>
                <input 
                    type="text" 
                    name="nome" 
                    value="{{ request('nome') }}"
                    placeholder="Pesquisar por título..."
                    class="input input-bordered focus:input-primary"
                />
            </div>

            <div class="form-control">
                <label class="label">
                    <span class="label-text font-semibold flex items-center gap-2">
                        <x-fas-user class="h-4 w-4" />
                        Autor
                    </span>
                </label>
                <input 
                    type="text" 
                    name="autor" 
                    value="{{ request('autor') }}"
                    placeholder="Pesquisar por autor..."
                    class="input input-bordered focus:input-primary"
                />
            </div>

            <div class="form-control">
                <label class="label">
                    <span class="label-text font-semibold flex items-center gap-2">
                        <x-fas-building class="h-4 w-4" />
                        Editora
                    </span>
                </label>
                <input 
                    type="text" 
                    name="editora" 
                    value="{{ request('editora') }}"
                    placeholder="Pesquisar por editora..."
                    class="input input-bordered focus:input-primary"
                />
            </div>

            <div class="form-control">
                <label class="label">
                    <span class="label-text font-semibold flex items-center gap-2">
                        <x-fas-sort class="h-4 w-4" />
                        Ordenar por
                    </span>
                </label>
                <select name="sort" class="select select-bordered focus:select-primary">
                    <option value="nome" {{ ($sort ?? request('sort')) === 'nome' ? 'selected' : '' }}>Título</option>
                    <option value="created_at" {{ ($sort ?? request('sort')) === 'created_at' ? 'selected' : '' }}>Adicionados recentemente</option>
                    <option value="updated_at" {{ ($sort ?? request('sort')) === 'updated_at' ? 'selected' : '' }}>Atualizados</option>
                </select>
            </div>

            <div class="form-control">
                <label class="label">
                    <span class="label-text font-semibold flex items-center gap-2">
                        <x-fas-arrow-up class="h-4 w-4" />
                        Direção
                    </span>
                </label>
                <select name="direction" class="select select-bordered focus:select-primary">
                    <option value="asc" {{ ($direction ?? request('direction')) === 'asc' ? 'selected' : '' }}>
                        ↑ Ascendente
                    </option>
                    <option value="desc" {{ ($direction ?? request('direction')) === 'desc' ? 'selected' : '' }}>
                        ↓ Descendente
                    </option>
                </select>
            </div>
        </div>

        <div class="flex gap-2 justify-end">
            <a href="{{ route('livro.index') }}" class="btn btn-ghost gap-2">
                <x-fas-undo class="h-4 w-4" />
                Limpar Filtros
            </a>
            <button type="submit" class="btn btn-primary gap-2">
                <x-fas-search class="h-4 w-4" />
                Aplicar Filtros
            </button>
        </div>
    </form>
</div>

<!-- Importação Google Books -->
@can('isAdmin')
    <div class="mt-6">
        <form method="POST" action="{{ route('livro.import-google') }}" class="flex gap-2">
            @csrf
            <input
                type="text"
                name="q"
                placeholder="Importar livros da Google Books API..."
                class="input input-bordered flex-1 focus:input-secondary"
            >
            <button type="submit" class="btn btn-secondary gap-2">
                <x-fas-download class="h-5 w-5" />
                Importar
            </button>
        </form>
    </div>
@endcan

<!-- Notificação -->
@if(session('success'))
    <div class="alert alert-success shadow-lg mt-6" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
        <x-fas-check-circle class="h-6 w-6" />
        <span>{{ session('success') }}</span>
    </div>
@endif