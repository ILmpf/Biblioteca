<x-layout>
    <header class="py-10 md:py-14 border-b border-base-200">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div>
                <h1 class="text-4xl font-bold tracking-tight">
                    Requisições
                </h1>

                <p class="text-muted-foreground mt-2">
                    Vê aqui as tuas requisições!
                </p>
            </div>
        </div>
    </header>

    <div class="mt-6 bg-base-100 p-6 rounded-xl shadow-sm space-y-6">
        <div class="flex flex-wrap gap-2">

            <a href="{{ route('requisicao.index') }}"
               class="btn {{ request()->has('filtro') ? 'btn-outline' : '' }}">
                Todas
            </a>

            <a href="{{ route('requisicao.index', ['filtro' => 'ativas']) }}"
               class="btn {{ request('filtro') === 'ativas' ? '' : 'btn-outline' }}">
                Requisições Ativas
                <span class="badge badge-neutral ml-2">{{ $ativas }}</span>
            </a>

            <a href="{{ route('requisicao.index', ['filtro' => 'ultimos30Dias']) }}"
               class="btn {{ request('filtro') === 'ultimos30Dias' ? '' : 'btn-outline' }}">
                Últimos 30 dias
                <span class="badge badge-info ml-2">{{ $ultimos30Dias }}</span>
            </a>

            <a href="{{ route('requisicao.index', ['filtro' => 'entreguesHoje']) }}"
               class="btn {{ request('filtro') === 'entreguesHoje' ? '' : 'btn-outline' }}">
                Entregues hoje
                <span class="badge badge-success ml-2">{{ $entreguesHoje }}</span>
            </a>

        </div>
    </div>

    <div class="overflow-x-auto mt-6">
        <table class="table table-zebra w-full">
            <thead>
            <tr>
                <th>Nº Requisição</th>
                <th>Cidadão</th>
                <th>Estado</th>
                <th>Data Requisição</th>
                <th>Data Entrega</th>
                <th>Ações</th>
            </tr>
            </thead>
            <tbody>
            @forelse($requisicoes as $requisicao)
                <tr>
                    <td>
                        <span class="badge badge-ghost badge-sm">{{ $requisicao->numero }}</span>
                    </td>

                    <td>
                        <div class="flex items-center gap-3">
                            <div class="avatar">
                                <div class="mask mask-squircle h-12 w-12">
                                    <img src="{{ $requisicao->user->image_path }}" alt="Avatar">
                                </div>
                            </div>
                            <div>
                                <div class="font-bold">{{ $requisicao->user->name }}</div>
                            </div>
                        </div>
                    </td>

                    <td>
                        <x-requisicao.status-label status="{{$requisicao->estado}}">
                            {{$requisicao->estado->label()}}
                        </x-requisicao.status-label>
                    </td>

                    <td>{{ $requisicao->data_requisicao->format('d/m/Y') }}</td>

                    <td>
                        @if($requisicao->data_entrega !== null)
                            {{$requisicao->data_entrega->format('d/m/Y')}}
                        @endif
                    </td>

                    <td>
                        <a href="{{route('requisicao.show', $requisicao)}}" class="btn btn-outline btn-xs">
                            Detalhes
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center text-muted">Não existem requisições.</td>
                </tr>
            @endforelse
            </tbody>

            <tfoot>
            <tr>
                <th>Nº Requisição</th>
                <th>Cidadão</th>
                <th>Estado</th>
                <th>Data Requisição</th>
                <th>Data Entrega</th>
                <th>Ações</th>
            </tr>
            </tfoot>
        </table>

        {{-- Pagination --}}
        <div class="mt-4">
            {{ $requisicoes->links('components.form.pagination') }}
        </div>
    </div>

</x-layout>
