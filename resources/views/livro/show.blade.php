<x-layout>
    <div class="py-8 max-w-7xl mx-auto">
        <!-- Breadcrumb & Ações -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
            <a href="{{ route('livro.index') }}" class="flex items-center gap-2 text-sm hover:text-primary transition-colors group">
                <x-fas-arrow-left class="h-4 w-4 group-hover:-translate-x-1 transition-transform" />
                <span class="font-medium">Voltar ao Catálogo</span>
            </a>

            @can('isAdmin')
                <div class="flex gap-3">
                    <button 
                        class="btn btn-outline btn-sm gap-2"
                        x-data
                        @click="$dispatch('open-modal', 'edit-livro')"
                        type="button">
                        <x-fas-edit class="h-4 w-4" />
                        Editar Livro
                    </button>
                    @can('delete', $livro)
                        <form method="POST" action="{{ route('livro.destroy', $livro) }}">
                            @csrf
                            @method('DELETE')
                            <button 
                                type="submit" 
                                class="btn btn-error btn-sm gap-2"
                                onclick="return confirm('Tens a certeza que desejas apagar este livro?')"
                            >
                                <x-fas-trash class="h-4 w-4" />
                                Apagar
                            </button>
                        </form>
                    @else
                        <button 
                            type="button" 
                            class="btn btn-outline btn-error btn-sm btn-disabled gap-2"
                            disabled
                            title="Não é possível eliminar um livro que está numa requisição ativa"
                        >
                            <x-fas-trash class="h-4 w-4" />
                            Apagar
                        </button>
                    @endcan
                </div>
            @endcan
        </div>

        <!-- Conteúdo Principal -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-1">
                <div class="sticky top-8 space-y-6">
                    <!-- Capa do Livro -->
                    <div class="card bg-base-100 shadow-2xl overflow-hidden">
                        <figure class="relative aspect-2/3 bg-linear-to-br from-base-200 to-base-300 cursor-pointer group"
                                onclick="document.getElementById('image_modal').showModal()">
                            <img 
                                src="{{ asset($livro->imagem) }}" 
                                alt="{{ $livro->nome }}"
                                class="object-cover w-full h-full transition-transform group-hover:scale-105"
                            />
                            
                            <!-- Ampliar -->
                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors flex items-center justify-center">
                                <div class="opacity-0 group-hover:opacity-100 transition-opacity bg-white/90 rounded-full p-3">
                                    <x-fas-search-plus class="h-6 w-6 text-gray-800" />
                                </div>
                            </div>
                            
                            <!-- Estado -->
                            <div class="absolute top-4 right-4">
                                @if($livro->isAvailable())
                                    <div class="badge badge-success badge-lg gap-2 shadow-lg">
                                        <x-fas-check-circle class="h-4 w-4" />
                                        Disponível
                                    </div>
                                @else
                                    <div class="badge badge-error badge-lg gap-2 shadow-lg">
                                        <x-fas-times-circle class="h-4 w-4" />
                                        Indisponível
                                    </div>
                                @endif
                            </div>
                        </figure>
                    </div>

                    <!-- Botão de Ação -->
                    @if($livro->isAvailable())
                        <a 
                            href="{{ route('requisicao.create', ['livro_id' => $livro->id]) }}"
                            class="btn btn-primary btn-lg w-full gap-2 shadow-lg hover:shadow-xl transition-all"
                        >
                            <x-fas-bookmark class="h-5 w-5" />
                            Requisitar Este Livro
                        </a>
                    @else
                        <button 
                            class="btn btn-disabled btn-lg w-full gap-2"
                            disabled
                        >
                            <x-fas-times class="h-5 w-5" />
                            Indisponível de momento
                        </button>
                    @endif

                    <!-- Informações -->
                    <div class="card bg-base-100 shadow-lg">
                        <div class="card-body p-4 space-y-3">
                            <h3 class="font-semibold text-sm text-base-content/70 uppercase tracking-wide">
                                Informações
                            </h3>
                            
                            <div class="space-y-2 text-sm">
                                <div class="flex items-start gap-2">
                                    <x-fas-building class="h-4 w-4 mt-0.5 text-primary shrink-0" />
                                    <div>
                                        <div class="text-base-content/60">Editora</div>
                                        <div class="font-medium">{{ $livro->editora->nome }}</div>
                                    </div>
                                </div>

                                <div class="divider my-1"></div>

                                <div class="flex items-start gap-2">
                                    <x-fas-user class="h-4 w-4 mt-0.5 text-secondary shrink-0" />
                                    <div>
                                        <div class="text-base-content/60">
                                            {{ $livro->autor->count() > 1 ? 'Autores' : 'Autor' }}
                                        </div>
                                        <div class="font-medium">
                                            {{ $livro->autor->pluck('nome')->join(', ') }}
                                        </div>
                                    </div>
                                </div>

                                @if($livro->isbn)
                                    <div class="divider my-1"></div>
                                    <div class="flex items-start gap-2">
                                        <x-fas-barcode class="h-4 w-4 mt-0.5 text-accent shrink-0" />
                                        <div>
                                            <div class="text-base-content/60">ISBN</div>
                                            <div class="font-mono text-xs">{{ $livro->isbn }}</div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2 space-y-6">
                <!-- Título e Descrição -->
                <div>
                    <h1 class="text-4xl md:text-5xl font-bold mb-4">{{ $livro->nome }}</h1>
                    
                    <div class="flex flex-wrap gap-2 mb-6">
                        <span class="badge badge-primary badge-lg">{{ $livro->editora->nome }}</span>
                        @foreach($livro->autor as $autor)
                            <span class="badge badge-outline badge-lg">{{ $autor->nome }}</span>
                        @endforeach
                    </div>
                </div>

                <!-- Bibliografia -->
                <div class="card bg-base-100 shadow-lg">
                    <div class="card-body">
                        <h2 class="card-title text-2xl mb-4 flex items-center gap-2">
                            <x-fas-book-open class="h-6 w-6 text-primary" />
                            Sinopse
                        </h2>
                        <p class="text-base-content/80 leading-relaxed text-lg">
                            {{ $livro->bibliografia }}
                        </p>
                    </div>
                </div>

                <!-- Histórico de Requisições -->
                <div class="card bg-base-100 shadow-lg">
                    <div class="card-body">
                        <h2 class="card-title text-2xl mb-4 flex items-center gap-2">
                            <x-fas-history class="h-6 w-6 text-secondary" />
                            Histórico de Requisições
                        </h2>

                        @if($livro->requisicao->count())
                            <div class="overflow-x-auto">
                                <table class="table table-zebra">
                                    <thead>
                                        <tr>
                                            <th>Nº Requisição</th>
                                            <th>Utilizador</th>
                                            <th>Data Requisição</th>
                                            <th>Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($livro->requisicao as $requisicao)
                                            <tr class="hover">
                                                <td>
                                                    <span class="badge badge-ghost">{{ $requisicao->numero }}</span>
                                                </td>
                                                <td>
                                                    <div class="flex items-center gap-3">
                                                        <div class="avatar">
                                                            <div class="mask mask-squircle h-10 w-10">
                                                                <img
                                                                    src="{{ asset($requisicao->user->image_path) }}"
                                                                    alt="{{ $requisicao->user->name }}"
                                                                />
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <div class="font-medium">{{ $requisicao->user->name }}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $requisicao->data_requisicao->format('d/m/Y') }}</td>
                                                <td>
                                                    <x-requisicao.status-label :status="$requisicao->estado">
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
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-12">
                                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-base-200 mb-4">
                                    <x-fas-inbox class="h-8 w-8 text-base-content/30" />
                                </div>
                                <p class="text-base-content/70">
                                    Este livro ainda não teve nenhuma requisição.
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Imagem -->
    <dialog id="image_modal" class="modal">
        <div class="modal-box max-w-4xl p-0 bg-transparent shadow-none">
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2 bg-white/90 hover:bg-white z-10">
                    <x-fas-times class="h-4 w-4" />
                </button>
            </form>
            <img 
                src="{{ asset($livro->imagem) }}" 
                alt="{{ $livro->nome }}"
                class="w-full h-auto rounded-lg"
            />
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>Fechar</button>
        </form>
    </dialog>

    <!-- Modal de Edição -->
    <x-modal name="edit-livro" title="Editar Livro">
        <form method="POST" action="{{ route('livro.update', $livro) }}" class="w-full" enctype="multipart/form-data"
            x-data="{
                submitting: false,
                form: {
                    nome: '{{ old('nome', $livro->nome) }}',
                    isbn: '{{ old('isbn', $livro->isbn) }}',
                    editora_id: '{{ old('editora_id', $livro->editora_id) }}',
                    autores: {{ json_encode(old('autores', $livro->autor->pluck('id')->toArray())) }},
                    bibliografia: '{{ old('bibliografia', $livro->bibliografia) }}',
                    preco: '{{ old('preco', $livro->preco) }}'
                },
                errors: {
                    nome: '',
                    isbn: '',
                    editora_id: '',
                    autores: '',
                    bibliografia: '',
                    preco: ''
                },
                imagemError: '',
                previewImage: null,
                
                validateNome() {
                    if (!this.form.nome || this.form.nome.trim() === '') {
                        this.errors.nome = 'O título é obrigatório';
                        return false;
                    }
                    if (this.form.nome.length > 255) {
                        this.errors.nome = 'O título não pode ter mais de 255 caracteres';
                        return false;
                    }
                    this.errors.nome = '';
                    return true;
                },
                
                validateIsbn() {
                    if (!this.form.isbn || this.form.isbn.trim() === '') {
                        this.errors.isbn = 'O ISBN é obrigatório';
                        return false;
                    }
                    if (this.form.isbn.length > 20) {
                        this.errors.isbn = 'O ISBN não pode ter mais de 20 caracteres';
                        return false;
                    }
                    this.errors.isbn = '';
                    return true;
                },
                
                validateEditora() {
                    if (!this.form.editora_id) {
                        this.errors.editora_id = 'Seleciona uma editora';
                        return false;
                    }
                    this.errors.editora_id = '';
                    return true;
                },
                
                validateAutores() {
                    if (this.form.autores.length === 0) {
                        this.errors.autores = 'Seleciona pelo menos um autor';
                        return false;
                    }
                    this.errors.autores = '';
                    return true;
                },
                
                validateBibliografia() {
                    if (!this.form.bibliografia || this.form.bibliografia.trim() === '') {
                        this.errors.bibliografia = 'A bibliografia é obrigatória';
                        return false;
                    }
                    this.errors.bibliografia = '';
                    return true;
                },
                
                validatePreco() {
                    if (!this.form.preco || this.form.preco === '') {
                        this.errors.preco = 'O preço é obrigatório';
                        return false;
                    }
                    if (this.form.preco < 0) {
                        this.errors.preco = 'O preço deve ser positivo';
                        return false;
                    }
                    this.errors.preco = '';
                    return true;
                },
                
                handleImageChange(event) {
                    const file = event.target.files[0];
                    if (file) {
                        if (file.size > 2048 * 1024) {
                            this.imagemError = 'A imagem não pode exceder 2MB';
                            this.previewImage = null;
                            return;
                        }
                        if (!file.type.startsWith('image/')) {
                            this.imagemError = 'O ficheiro deve ser uma imagem';
                            this.previewImage = null;
                            return;
                        }
                        this.imagemError = '';
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            this.previewImage = e.target.result;
                        };
                        reader.readAsDataURL(file);
                    }
                },
                
                handleSubmit(event) {
                    let isValid = true;
                    isValid = this.validateNome() && isValid;
                    isValid = this.validateIsbn() && isValid;
                    isValid = this.validateEditora() && isValid;
                    isValid = this.validateAutores() && isValid;
                    isValid = this.validateBibliografia() && isValid;
                    isValid = this.validatePreco() && isValid;
                    
                    if (!isValid) {
                        event.preventDefault();
                        return false;
                    }
                    this.submitting = true;
                }
            }"
            @submit="handleSubmit($event)">
            @csrf
            @method('PUT')
            <div class="space-y-6">
                <div class="form-control">
                    <label for="edit_nome" class="label">
                        <span class="label-text font-semibold">Título do Livro <span class="text-error">*</span></span>
                    </label>
                    <input
                        type="text"
                        id="edit_nome"
                        name="nome"
                        x-model="form.nome"
                        class="input input-bordered w-full"
                        :class="{
                            'input-error': errors.nome,
                            'input-success': form.nome && !errors.nome
                        }"
                        @input="validateNome"
                        @blur="validateNome"
                        placeholder="Introduz o título do livro"
                        required
                    />
                    <x-form.validation-feedback field="nome" success-message="Título válido" />
                    <x-form.error name="nome" />
                </div>

                <div class="form-control">
                    <label for="edit_isbn" class="label">
                        <span class="label-text font-semibold">ISBN <span class="text-error">*</span></span>
                    </label>
                    <input
                        type="text"
                        id="edit_isbn"
                        name="isbn"
                        x-model="form.isbn"
                        class="input input-bordered w-full"
                        :class="{
                            'input-error': errors.isbn,
                            'input-success': form.isbn && !errors.isbn
                        }"
                        @input="validateIsbn"
                        @blur="validateIsbn"
                        placeholder="ISBN do livro"
                        maxlength="20"
                        required
                    />
                    <x-form.validation-feedback field="isbn" success-message="ISBN válido" />
                    <x-form.error name="isbn" />
                </div>

                <div class="space-y-2">
                    <label class="label">
                        <span class="label-text font-semibold">Editora <span class="text-error">*</span></span>
                    </label>
                    <select 
                        name="editora_id" 
                        x-model="form.editora_id" 
                        @change="validateEditora()"
                        class="select select-bordered w-full" 
                        :class="{ 'select-error': errors.editora_id, 'select-success': form.editora_id && !errors.editora_id }"
                        required>
                        <option value="">Seleciona uma editora</option>
                        @foreach($editoras as $editora)
                            <option value="{{ $editora->id }}">{{ $editora->nome }}</option>
                        @endforeach
                    </select>
                    <template x-if="errors.editora_id">
                        <p x-text="errors.editora_id" class="text-sm text-error" x-transition></p>
                    </template>
                    <x-form.error name="editora_id" />
                </div>

                <div class="space-y-2">
                    <label class="label">
                        <span class="label-text font-semibold">Autores <span class="text-error">*</span></span>
                    </label>
                    <select 
                        name="autores[]" 
                        x-model="form.autores"
                        @change="validateAutores()"
                        class="select select-bordered w-full" 
                        :class="{ 'select-error': errors.autores, 'select-success': form.autores.length > 0 && !errors.autores }"
                        multiple 
                        required 
                        size="5">
                        @foreach($autores as $autor)
                            <option value="{{ $autor->id }}">{{ $autor->nome }}</option>
                        @endforeach
                    </select>
                    <p class="text-xs text-base-content/60">Mantém pressionado Ctrl (Windows) ou Cmd (Mac) para selecionar vários autores</p>
                    <template x-if="errors.autores">
                        <p x-text="errors.autores" class="text-sm text-error" x-transition></p>
                    </template>
                    <x-form.error name="autores" />
                </div>

                <div class="form-control">
                    <label for="edit_bibliografia" class="label">
                        <span class="label-text font-semibold">Bibliografia <span class="text-error">*</span></span>
                    </label>
                    <textarea
                        id="edit_bibliografia"
                        name="bibliografia"
                        x-model="form.bibliografia"
                        class="textarea textarea-bordered w-full"
                        :class="{
                            'textarea-error': errors.bibliografia,
                            'textarea-success': form.bibliografia && !errors.bibliografia
                        }"
                        @input="validateBibliografia"
                        @blur="validateBibliografia"
                        placeholder="Bibliografia..."
                        rows="4"
                        required
                    ></textarea>
                    <x-form.validation-feedback field="bibliografia" success-message="Bibliografia válida" />
                    <x-form.error name="bibliografia" />
                </div>

                <div class="form-control">
                    <label for="edit_preco" class="label">
                        <span class="label-text font-semibold">Preço <span class="text-error">*</span></span>
                    </label>
                    <input
                        type="number"
                        id="edit_preco"
                        name="preco"
                        x-model="form.preco"
                        class="input input-bordered w-full"
                        :class="{
                            'input-error': errors.preco,
                            'input-success': form.preco && !errors.preco
                        }"
                        @input="validatePreco"
                        @blur="validatePreco"
                        step="0.01"
                        min="0"
                        placeholder="Preço do livro"
                        required
                    />
                    <x-form.validation-feedback field="preco" success-message="Preço válido" />
                    <x-form.error name="preco" />
                </div>

                <div class="space-y-2">
                    <label class="font-medium">Imagem da Capa</label>
                    @if($livro->imagem)
                        <div class="mb-2">
                            <p class="text-sm text-base-content/60 mb-2">Imagem atual:</p>
                            <img src="{{ asset($livro->imagem) }}" alt="Capa atual" class="h-32 w-auto rounded-lg shadow-md" />
                        </div>
                    @endif
                    <input 
                        type="file" 
                        name="imagem" 
                        accept="image/*" 
                        class="file-input file-input-bordered w-full"
                        :class="{ 'file-input-error': imagemError, 'file-input-success': previewImage && !imagemError }"
                        @change="handleImageChange($event)" />
                    <p class="text-xs text-base-content/60">Tamanho máximo: 2MB (deixar em branco para manter a imagem atual)</p>
                    <p x-show="imagemError" x-text="imagemError" class="text-sm text-error" x-transition></p>
                    <div x-show="previewImage" class="mt-2" x-transition>
                        <p class="text-sm text-base-content/60 mb-2">Nova imagem:</p>
                        <img :src="previewImage" alt="Preview" class="h-40 w-auto rounded-lg shadow-md" />
                    </div>
                </div>

                <div class="flex justify-end gap-x-5 pt-4 border-t">
                    <button 
                        type="button" 
                        class="btn btn-ghost" 
                        @click="$dispatch('close-modal')"
                        :disabled="submitting">Cancelar</button>
                    <button 
                        type="submit" 
                        class="btn btn-primary gap-2"
                        :class="{ 'loading': submitting }"
                        :disabled="submitting">
                        <span x-show="!submitting">Atualizar Livro</span>
                        <span x-show="submitting">A atualizar...</span>
                    </button>
                </div>
            </div>
        </form>
    </x-modal>
</x-layout>
