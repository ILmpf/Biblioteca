<x-modal name="create-livro" title="Novo Livro">
    <form method="POST" action="{{ route('livro.store') }}" class="w-full" enctype="multipart/form-data"
        x-data="{
            submitting: false,
            form: {
                nome: '',
                isbn: '',
                editora_id: '',
                editora_nome: '',
                autores: [],
                bibliografia: '',
                preco: ''
            },
            errors: {
                nome: '',
                isbn: '',
                editora_id: '',
                editora_nome: '',
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
            
            validateEditoraNome() {
                if (this.form.editora_id === 'new') {
                    if (!this.form.editora_nome || this.form.editora_nome.trim() === '') {
                        this.errors.editora_nome = 'O nome da editora é obrigatório';
                        return false;
                    }
                    if (this.form.editora_nome.length > 255) {
                        this.errors.editora_nome = 'O nome não pode ter mais de 255 caracteres';
                        return false;
                    }
                }
                this.errors.editora_nome = '';
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
                isValid = this.validateEditoraNome() && isValid;
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
        <div class="space-y-6">
            <div class="form-control">
                <label for="nome" class="label">
                    <span class="label-text font-semibold">Título do Livro <span class="text-error">*</span></span>
                </label>
                <input
                    type="text"
                    id="nome"
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
                    autofocus
                />
                <x-form.validation-feedback field="nome" success-message="Título válido" />
                <x-form.error name="nome" />
            </div>

            <div class="form-control">
                <label for="isbn" class="label">
                    <span class="label-text font-semibold">ISBN <span class="text-error">*</span></span>
                </label>
                <input
                    type="text"
                    id="isbn"
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
                    @change="validateEditora(); validateEditoraNome()"
                    class="select select-bordered w-full" 
                    :class="{ 'select-error': errors.editora_id, 'select-success': form.editora_id && !errors.editora_id && form.editora_id !== 'new' }"
                    required>
                    <option value="">Seleciona uma editora</option>
                    <option value="new">Nova Editora</option>
                    @foreach($editoras as $editora)
                        <option value="{{ $editora->id }}">{{ $editora->nome }}</option>
                    @endforeach
                </select>
                <template x-if="errors.editora_id">
                    <p x-text="errors.editora_id" class="text-sm text-error" x-transition></p>
                </template>
                <x-form.error name="editora_id" />
                
                <div x-show="form.editora_id === 'new'" x-transition x-cloak class="mt-3">
                    <div class="form-control">
                        <label for="editora_nome" class="label">
                            <span class="label-text font-semibold">Nova Editora <span class="text-error">*</span></span>
                        </label>
                        <input
                            type="text"
                            id="editora_nome"
                            name="editora_nome"
                            x-model="form.editora_nome"
                            class="input input-bordered w-full"
                            :class="{
                                'input-error': errors.editora_nome,
                                'input-success': form.editora_nome && !errors.editora_nome
                            }"
                            @input="validateEditoraNome"
                            @blur="validateEditoraNome"
                            placeholder="Nome da editora"
                        />
                        <x-form.validation-feedback field="editora_nome" success-message="Nome válido" />
                        <x-form.error name="editora_nome" />
                    </div>
                </div>
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
                        <option value="{{ $autor->id }}" @if(collect(old('autores', []))->contains($autor->id)) selected @endif>{{ $autor->nome }}</option>
                    @endforeach
                </select>
                <p class="text-xs text-base-content/60">Mantém pressionado Ctrl (Windows) ou Cmd (Mac) para selecionar vários autores</p>
                <template x-if="errors.autores">
                    <p x-text="errors.autores" class="text-sm text-error" x-transition></p>
                </template>
                <x-form.error name="autores" />
            </div>

            <div class="form-control">
                <label for="bibliografia" class="label">
                    <span class="label-text font-semibold">Bibliografia <span class="text-error">*</span></span>
                </label>
                <textarea
                    id="bibliografia"
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
                <label for="preco" class="label">
                    <span class="label-text font-semibold">Preço <span class="text-error">*</span></span>
                </label>
                <input
                    type="number"
                    id="preco"
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
                <input 
                    type="file" 
                    name="imagem" 
                    accept="image/*" 
                    class="file-input file-input-bordered w-full"
                    :class="{ 'file-input-error': imagemError, 'file-input-success': previewImage && !imagemError }"
                    @change="handleImageChange($event)" />
                <p class="text-xs text-base-content/60">Tamanho máximo: 2MB</p>
                <p x-show="imagemError" x-text="imagemError" class="text-sm text-error" x-transition></p>
                <div x-show="previewImage" class="mt-2" x-transition>
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
                    <span x-show="!submitting">Criar Livro</span>
                    <span x-show="submitting">A criar...</span>
                </button>
            </div>
        </div>
    </form>
</x-modal>