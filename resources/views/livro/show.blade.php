<x-layout>
    <div class="py-8 max-w-4xl mx-auto">
        <div class="flex justify-between items-center">
            <a href="{{route('livro.index')}}" class="flex items-center gap-x-2 text-sm font-medium">
                <x-icons.arrow-back />
                Voltar à galeria
            </a>

            @can('isAdmin')
                <div class="gap-x-3 flex items-center">
                    <button class="btn btn-outline">
                        <x-icons.edit />
                        Editar Livro
                    </button>
                    <form method="POST" action="{{route('livro.destroy', $livro)}}">
                        @csrf
                        @method('DELETE')

                        <button class="btn btn-outline text-red-500">Apagar</button>
                    </form>
                </div>
            @endcan
        </div>

        <div class="mt-8 space-y-6">
            <h1 class="font-bold text-4xl">{{$livro->nome}}</h1>

            <div class="mt-2 flex gap-x-3 items-center">
                <x-livro.status-label :status="$livro->isAvailable()">{{$livro->isAvailable() ? "Disponível" : "Indisponível para requisitar"}}</x-livro.status-label>
            </div>

            <div class="card bg-base-100 w-full border shadow-sm mt-8">
                <div class="card-body">
                    <h2 class="card-title">Bibliografia</h2>
                    <p>{{$livro->bibliografia}}</p>
                    <div class="card-actions justify-end"></div>
                </div>
            </div>

            <div>
                <h3 class="font-bold text-xl mt-6">Histórico de Requisições</h3>

                @if($livro->requisicao->count())
                    <div class="overflow-x-auto">
                        <table class="table">
                            <!-- head -->
                            <thead>
                            <tr>
                                <th>Nº Requisição</th>
                                <th>Cidadão</th>
                                <th>Data Requisição</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($livro->requisicao as $requisicao)
                            <!-- row 1 -->
                            <tr>
                                <td>
                                    <span class="badge badge-ghost badge-sm">{{$requisicao->numero}}</span>
                                </td>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="avatar">
                                            <div class="mask mask-squircle h-12 w-12">
                                                <img
                                                    src="https://img.daisyui.com/images/profile/demo/2@94.webp"
                                                    alt="Avatar Tailwind CSS Component" />
                                            </div>
                                        </div>
                                        <div>
                                            <div class="font-bold">Hart Hagerty</div>
                                            <div class="text-sm opacity-50">United States</div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{$requisicao->data_requisicao}}</td>
                                <th>
                                    <button class="btn btn-ghost btn-xs">detalhes</button>
                                </th>
                            </tr>
                            @endforeach
                            </tbody>
                            <!-- foot -->
                            <tfoot>
                            <tr>
                                <th>Nº Requisição</th>
                                <th>Cidadão</th>
                                <th>Data Requisição</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                @else
                    <p>Este livro ainda não teve nenhuma requisição.</p>
                @endif

            </div>
        </div>
    </div>
</x-layout>
