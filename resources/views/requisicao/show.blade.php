<x-layout>
    <div class="py-8 max-w-6xl mx-auto">
        <div class="flex justify-between items-center">
            <a href="{{ route('requisicao.index') }}" class="flex items-center gap-x-2 text-xl link-hover">
                <x-icons.arrow-back/>
                Voltar às requisições
            </a>

            @can('isAdmin')
                <button
                    x-data
                    @click="$dispatch('toggle-edit')"
                    class="btn btn-outline flex items-center gap-x-2">
                    <x-icons.edit/>
                    Editar Requisição
                </button>
            @endcan
        </div>

        <div
            class="mt-6 p-6 border rounded-xl space-y-6 relative"
            x-data="{ editing: false }"
            x-on:toggle-edit.window="editing = !editing"
        >
            {{-- FORM (editable requisicao data only) --}}
            <form action="" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <h1 class="font-bold text-4xl">Detalhes da Requisição</h1>

                <div class="flex items-center gap-x-2">
                    <label class="fieldset-label text-xl">Estado:</label>

                    <template x-if="!editing">
                        <x-requisicao.status-label status="{{ $requisicao->estado }}" class="!text-xl">
                            {{ $requisicao->estado->label() }}
                        </x-requisicao.status-label>
                    </template>

                    <template x-if="editing">
                        <select name="estado" class="border rounded px-3 py-2">
                            @foreach (\App\RequisicaoEstado::cases() as $estadoOption)
                                <option
                                    value="{{ $estadoOption->value }}"
                                    @selected($requisicao->estado === $estadoOption)
                                >
                                    {{ $estadoOption->label() }}
                                </option>
                            @endforeach
                        </select>
                    </template>

                    <label class="fieldset-label text-xl ml-6">Entrega Prevista:</label>
                    <span class="font-bold text-xl">
                @if($requisicao->estado === \App\RequisicaoEstado::CANCELLED)
                            N/A
                        @else
                            {{ $requisicao->data_entrega_prevista->format('d/m/Y') }}
                        @endif
            </span>
                </div>

                <div class="flex items-center gap-x-2">
                    <label class="fieldset-label text-xl">Data da Requisição:</label>
                    <span class="text-xl">
                {{ $requisicao->data_requisicao->format('d/m/Y') }}
            </span>

                    <label class="fieldset-label text-xl ml-6">Data de Entrega:</label>

                    <template x-if="!editing">
                <span class="font-bold text-xl">
                    @if($requisicao->estado === \App\RequisicaoEstado::CANCELLED)
                        N/A
                    @else
                        {{ $requisicao->data_entrega?->format('d/m/Y') ?? 'Não entregue.' }}
                    @endif
                </span>
                    </template>

                    <template x-if="editing">
                        <input
                            type="date"
                            name="data_entrega"
                            value="{{ $requisicao->data_entrega?->format('Y-m-d') }}"
                            class="border rounded px-3 py-2"
                        >
                    </template>
                </div>

                <div class="flex justify-end pt-6" x-show="editing">
                    <button type="submit" class="btn btn-primary flex items-center gap-x-2">
                        <x-fas-save class="w-5 h-5"/>
                        Guardar
                    </button>
                </div>
            </form>

            <hr class="border-gray-200">

            <div class="space-y-3">
                <h2 class="font-bold text-2xl">Dados do Cidadão</h2>

                <div class="flex items-center gap-x-4">
                    <img
                        src="{{ asset($requisicao->user->image_path) }}"
                        alt="{{ $requisicao->user->name }}"
                        class="w-30 h-30 aspect-square object-cover border"
                    >

                    <div class="space-y-1">
                        <div class="flex gap-x-2">
                            <span class="font-bold">Nome:</span>
                            <span>{{ $requisicao->user->name }}</span>
                        </div>

                        <div class="flex gap-x-2">
                            <span class="font-bold">Email:</span>
                            <span>{{ $requisicao->user->email }}</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>


        <div class="mt-6 space-y-6 p-6 shadow-md">
            <h2 class="font-bold text-4xl">Livros Requisitados</h2>

            <hr class="border-gray-200">

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                @foreach ($requisicao->livro as $livro)
                    <x-card href="{{ route('livro.show', $livro) }}">
                        <x-slot:image>
                            <img
                                src="{{ asset($livro->imagem) }}"
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

                        <x-slot:actions>
                        </x-slot:actions>
                    </x-card>
                @endforeach
            </div>
        </div>

    </div>
</x-layout>
