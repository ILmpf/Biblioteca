<x-layout>
    <!-- Página de Gestão de Requisições -->
    <!-- Secção de Header -->
    <x-layout.header
        title="As Minhas Requisições"
        description="Gere e acompanha as tuas requisições de livros"
        :centered="true"
    >
        <x-slot:actions>
            <a href="{{ route('requisicao.create') }}" class="btn btn-primary gap-2 shadow-lg hover:shadow-xl transition-all">
                <x-fas-plus class="h-5 w-5" />
                Nova Requisição
            </a>
        </x-slot:actions>
    </x-layout.header>

    <!-- Secção de Filtros -->
    <div class="bg-base-100 p-6 rounded-2xl shadow-xl border border-base-200 mt-6">
        <div>
            <label class="text-sm font-semibold text-base-content/70 mb-3 flex items-center gap-2">
                <x-fas-filter class="h-4 w-4" />
                Filtrar Requisições
            </label>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('requisicao.index') }}"
                   class="btn {{ request()->has('filtro') ? 'btn-outline' : 'btn-primary' }} gap-2">
                    <x-fas-th class="h-4 w-4" />
                    Todas as Requisições
                </a>

                <a href="{{ route('requisicao.index', ['filtro' => 'ativas']) }}"
                   class="btn {{ request('filtro') === 'ativas' ? 'btn-warning' : 'btn-outline' }} gap-2">
                    <x-fas-clock class="h-4 w-4" />
                    Ativas
                    <span class="badge {{ request('filtro') === 'ativas' ? 'badge-warning badge-outline' : '' }}">
                        {{ $ativas }}
                    </span>
                </a>

                <a href="{{ route('requisicao.index', ['filtro' => 'ultimos30Dias']) }}"
                   class="btn {{ request('filtro') === 'ultimos30Dias' ? 'btn-info' : 'btn-outline' }} gap-2">
                    <x-fas-calendar class="h-4 w-4" />
                    Últimos 30 Dias
                    <span class="badge {{ request('filtro') === 'ultimos30Dias' ? 'badge-info badge-outline' : '' }}">
                        {{ $ultimos30Dias }}
                    </span>
                </a>

                <a href="{{ route('requisicao.index', ['filtro' => 'entreguesHoje']) }}"
                   class="btn {{ request('filtro') === 'entreguesHoje' ? 'btn-success' : 'btn-outline' }} gap-2">
                    <x-fas-check-circle class="h-4 w-4" />
                    Entregues Hoje
                    <span class="badge {{ request('filtro') === 'entreguesHoje' ? 'badge-success badge-outline' : '' }}">
                        {{ $entreguesHoje }}
                    </span>
                </a>
            </div>
        </div>
    </div>

    <!-- Conteúdo Principal -->
    <div class="mt-8">
        @forelse($requisicoes as $requisicao)
            <!-- Vista Mobile -->
            <div class="card bg-base-100 shadow-lg hover:shadow-xl transition-all border border-base-200 mb-4 lg:hidden">
                <div class="card-body">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <div class="badge badge-ghost mb-2">Nº {{ $requisicao->numero }}</div>
                            <x-requisicao.status-label status="{{ $requisicao->estado }}">
                                @if($requisicao->estado === \App\RequisicaoEstado::ACTIVE)
                                    <x-fas-clock class="h-3 w-3 inline" />
                                @elseif($requisicao->estado === \App\RequisicaoEstado::COMPLETED)
                                    <x-fas-check-circle class="h-3 w-3 inline" />
                                @elseif($requisicao->estado === \App\RequisicaoEstado::CANCELLED)
                                    <x-fas-times-circle class="h-3 w-3 inline" />
                                @endif
                                {{ $requisicao->estado->label() }}
                            </x-requisicao.status-label>
                        </div>
                        <a href="{{ route('requisicao.show', $requisicao) }}" class="btn btn-sm btn-primary gap-2">
                            <x-fas-eye class="h-4 w-4" />
                            Ver
                        </a>
                    </div>

                    <div class="flex items-center gap-3 mb-3">
                        <div class="avatar">
                            <div class="mask mask-squircle h-12 w-12">
                                <img src="{{ $requisicao->user->image_path }}" alt="{{ $requisicao->user->name }}">
                            </div>
                        </div>
                        <div>
                            <div class="font-bold">{{ $requisicao->user->name }}</div>
                            <div class="text-sm text-base-content/60">{{ $requisicao->user->email }}</div>
                        </div>
                    </div>

                    <div class="flex justify-between text-sm">
                        <div>
                            <span class="text-base-content/60">Requisição:</span>
                            <span class="font-medium">{{ $requisicao->data_requisicao->format('d/m/Y') }}</span>
                        </div>
                        @if($requisicao->data_entrega)
                            <div>
                                <span class="text-base-content/60">Entrega:</span>
                                <span class="font-medium">{{ $requisicao->data_entrega->format('d/m/Y') }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="lg:hidden">
                @if(request('filtro') === 'entreguesHoje')
                    <x-layout.empty-state>
                        <x-slot:icon>
                            <x-fas-calendar-times class="h-10 w-10" />
                        </x-slot:icon>
                        <x-slot:title>Não foram entregues livros hoje</x-slot:title>
                    </x-layout.empty-state>
                @else
                    <x-layout.empty-state>
                        <x-slot:icon>
                            <x-fas-inbox class="h-10 w-10" />
                        </x-slot:icon>
                        <x-slot:title>Nenhuma requisição encontrada</x-slot:title>
                        <x-slot:description>Ainda não fizeste nenhuma requisição</x-slot:description>
                        <x-slot:action>
                            <a href="{{ route('requisicao.create') }}" class="btn btn-primary gap-2">
                                <x-fas-plus class="h-4 w-4" />
                                Criar Primeira Requisição
                            </a>
                        </x-slot:action>
                    </x-layout.empty-state>
                @endif
            </div>
        @endforelse

        <!-- Vista Total -->
        <div class="hidden lg:block overflow-x-auto bg-base-100 rounded-2xl shadow-xl border border-base-200">
            <table class="table table-zebra">
                <thead>
                    <tr>
                        <th class="bg-base-200">
                            <div class="flex items-center gap-2">
                                <x-fas-hashtag class="h-4 w-4" />
                                Nº Requisição
                            </div>
                        </th>
                        <th class="bg-base-200">
                            <div class="flex items-center gap-2">
                                <x-fas-user class="h-4 w-4" />
                                Utilizador
                            </div>
                        </th>
                        <th class="bg-base-200">
                            <div class="flex items-center gap-2">
                                <x-fas-info-circle class="h-4 w-4" />
                                Estado
                            </div>
                        </th>
                        <th class="bg-base-200">
                            <div class="flex items-center gap-2">
                                <x-fas-calendar class="h-4 w-4" />
                                Data Requisição
                            </div>
                        </th>
                        <th class="bg-base-200">
                            <div class="flex items-center gap-2">
                                <x-fas-calendar-check class="h-4 w-4" />
                                Data Entrega
                            </div>
                        </th>
                        <th class="bg-base-200">
                            <div class="flex items-center gap-2">
                                <x-fas-cog class="h-4 w-4" />
                                Ações
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody>
                @forelse($requisicoes as $requisicao)
                    <tr class="hover">
                        <td>
                            <span class="badge badge-ghost badge-lg">{{ $requisicao->numero }}</span>
                        </td>

                        <td>
                            <div class="flex items-center gap-3">
                                <div class="avatar">
                                    <div class="mask mask-squircle h-12 w-12">
                                        <img src="{{ $requisicao->user->image_path }}" alt="{{ $requisicao->user->name }}">
                                    </div>
                                </div>
                                <div>
                                    <div class="font-bold">{{ $requisicao->user->name }}</div>
                                    <div class="text-sm text-base-content/60">{{ $requisicao->user->email }}</div>
                                </div>
                            </div>
                        </td>

                        <td>
                            <x-requisicao.status-label status="{{ $requisicao->estado }}">
                                @if($requisicao->estado === \App\RequisicaoEstado::ACTIVE)
                                    <x-fas-clock class="h-3 w-3 inline" />
                                @elseif($requisicao->estado === \App\RequisicaoEstado::COMPLETED)
                                    <x-fas-check-circle class="h-3 w-3 inline" />
                                @elseif($requisicao->estado === \App\RequisicaoEstado::CANCELLED)
                                    <x-fas-times-circle class="h-3 w-3 inline" />
                                @endif
                                {{ $requisicao->estado->label() }}
                            </x-requisicao.status-label>
                        </td>

                        <td>
                            <div class="flex items-center gap-2">
                                <x-fas-calendar-alt class="h-4 w-4 text-base-content/50" />
                                <span class="font-medium">{{ $requisicao->data_requisicao->format('d/m/Y') }}</span>
                            </div>
                        </td>

                        <td>
                            @if($requisicao->data_entrega !== null)
                                <div class="flex items-center gap-2">
                                    <x-fas-check class="h-4 w-4 text-success" />
                                    <span class="font-medium">{{ $requisicao->data_entrega->format('d/m/Y') }}</span>
                                </div>
                            @else
                                <span class="text-base-content/50">—</span>
                            @endif
                        </td>

                        <td>
                            <a href="{{ route('requisicao.show', $requisicao) }}" 
                               class="btn btn-sm btn-primary gap-2">
                                <x-fas-eye class="h-4 w-4" />
                                Ver Detalhes
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-0">
                            @if(request('filtro') === 'entreguesHoje')
                                <x-layout.empty-state>
                                    <x-slot:icon>
                                        <x-fas-calendar-times class="h-10 w-10" />
                                    </x-slot:icon>
                                    <x-slot:title>Não foram entregues livros hoje</x-slot:title>
                                </x-layout.empty-state>
                            @else
                                <x-layout.empty-state>
                                    <x-slot:icon>
                                        <x-fas-inbox class="h-10 w-10" />
                                    </x-slot:icon>
                                    <x-slot:title>Nenhuma requisição encontrada</x-slot:title>
                                    <x-slot:description>Ainda não fizeste nenhuma requisição</x-slot:description>
                                    <x-slot:action>
                                        <a href="{{ route('requisicao.create') }}" class="btn btn-primary gap-2">
                                            <x-fas-plus class="h-4 w-4" />
                                            Criar Primeira Requisição
                                        </a>
                                    </x-slot:action>
                                </x-layout.empty-state>
                            @endif
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginação -->
        @if($requisicoes->hasPages())
            <div class="mt-6">
                {{ $requisicoes->links('components.form.pagination') }}
            </div>
        @endif
    </div>

</x-layout>
