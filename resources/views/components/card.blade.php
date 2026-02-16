<div {{ $attributes->merge([
    'class' => 'group relative card bg-base-100 shadow-lg hover:shadow-2xl 
               transition-all duration-300 overflow-hidden border border-base-200
               hover:border-primary/30 hover:-translate-y-1'
]) }}>
    {{-- CLICKABLE CARD CONTENT --}}
    <a href="{{ $attributes->get('href') }}" class="flex flex-col h-full">
        <!-- Capa do Livro com Sobreposição -->
        <figure class="relative h-64 overflow-hidden bg-gradient-to-br from-base-200 to-base-300">
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            {{ $image }}
            
            <!-- Emblema de Visualização Rápida -->
            <div class="absolute top-4 left-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                <div class="badge badge-primary gap-1 shadow-lg">
                    <x-fas-eye class="h-3 w-3" />
                    Ver Detalhes
                </div>
            </div>
        </figure>

        <div class="card-body flex-1 flex flex-col p-5">
            <!-- Título -->
            <h2 class="card-title text-lg line-clamp-2 group-hover:text-primary transition-colors">
                {{ $title }}
            </h2>

            <!-- Informação da Editora -->
            @isset($editora)
                <div class="flex items-center gap-2 text-sm text-base-content/60 mt-1">
                    <x-fas-building class="h-3 w-3" />
                    <span>{{ $editora }}</span>
                </div>
            @endisset

            <!-- Conteúdo Adicional -->
            <div class="flex-1 mt-3">
                {{ $slot }}
            </div>
        </div>
    </a>

    {{-- ACTIONS (NOT inside the anchor) --}}
    @isset($actions)
        <div class="absolute bottom-5 right-5 z-10">
            {{ $actions }}
        </div>
    @endisset
</div>
