<x-layout>
    <div class="py-8 max-w-4xl mx-auto">

        <form
            method="POST"
            action="{{ route('requisicao.store') }}"
            class="p-6 border rounded-xl space-y-6"
            x-data="requisicaoForm(@json($livroSelecionado?->id))"
        >
            @csrf

            <h1 class="font-bold text-4xl">Nova Requisição</h1>

            <div class="border rounded-lg p-4 bg-base-100">
                <h2 class="font-bold text-xl mb-3">Cidadão</h2>

                <div class="flex items-center gap-4">
                    <img
                        src="{{ asset(auth()->user()->image_path) }}"
                        class="w-30 h-30 aspect-square object-cover"
                    >

                    <div>
                        <label class="font-semibold">Nome: <span
                                class="font-medium">{{auth()->user()->name}}</span></label> <br/>
                        <label class="font-semibold">Email: <span
                                class="font-medium">{{auth()->user()->email}}</span></label>
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                <h2 class="font-bold text-xl">Livros a requisitar</h2>

                @if ($errors->any())
                    <div class="alert alert-error">
                        <ul class="list-disc pl-4">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <template x-for="(livroId, index) in livros" :key="index">
                    <div class="flex flex-col gap-2 p-2 border rounded-lg bg-base-100">

                        <div class="flex items-center gap-3">
                            <select
                                name="livros[]"
                                class="select select-bordered w-auto"
                                x-model="livros[index]"
                            >
                                <option value="">Seleciona um livro</option>

                                @foreach($livrosDisponiveis as $livro)
                                    <option value="{{ $livro->id }}" :selected="livros[index] == {{ $livro->id }}">
                                        {{ $livro->nome }}
                                    </option>
                                @endforeach
                            </select>

                            <button
                                type="button"
                                class="btn btn-ghost btn-sm"
                                @click="removeLivro(index)"
                                x-show="livros.length > 1"
                            >
                                <x-fas-window-close class="w-5 h-5"/>
                            </button>
                        </div>

                        <template x-if="livros[index]">
                            <div class="flex gap-4 text-sm text-muted-foreground">
                                <span>
                                    <strong>Autor:</strong>
                                    @foreach($livrosDisponiveis as $livro)
                                        <span x-show="livros[index] == {{ $livro->id }}">
                                            {{ $livro->autor->pluck('nome')->join(', ') }}
                                        </span>
                                    @endforeach
                                </span>


                                <span>
                                    <strong>Editora:</strong>
                                    @foreach($livrosDisponiveis as $livro)
                                        <span x-show="livros[index] == {{ $livro->id }}">
                                            {{ $livro->editora->nome }}
                                        </span>
                                    @endforeach
                                </span>
                            </div>

                        </template>

                    </div>
                </template>

                <button
                    type="button"
                    class="btn btn-outline btn-sm"
                    @click="addLivro()"
                    x-show="livros.length < 3"
                >
                    + Adicionar outro livro
                </button>
            </div>

            <div class="flex justify-end pt-6">
                <button type="submit" class="btn btn-primary">
                    Criar Requisição
                </button>
            </div>

        </form>
    </div>
</x-layout>

<script>
    function requisicaoForm(livroSelecionado = null) {
        return {
            livros: livroSelecionado ? [livroSelecionado] : [''],

            addLivro() {
                if (this.livros.length < 3) this.livros.push('');
            },

            removeLivro(index) {
                if (this.livros.length > 1) {
                    this.livros.splice(index, 1);
                }
            }
        }
    }
</script>
