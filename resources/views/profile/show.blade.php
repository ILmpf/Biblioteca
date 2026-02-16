<x-layout>
    <div class="min-h-screen bg-linear-to-br from-base-200 to-base-300">
        <div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <!-- Secção Cabeçalho -->
            <div class="text-center mb-10">
                <h1 class="text-4xl font-bold mb-2">O Meu Perfil</h1>
                <p class="text-base-content/70">Gere as tuas informações pessoais e configurações de conta aqui.</p>
            </div>

            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" x-data="profileForm()" @submit="if (!validateForm()) { $event.preventDefault(); }" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Mensagens de Validação -->
                @if(session('success'))
                    <div class="alert alert-success shadow-lg" x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)">
                        <x-fas-check-circle class="h-6 w-6" />
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-error shadow-lg">
                        <div>
                            <x-fas-exclamation-circle class="h-6 w-6" />
                            <div>
                                <h3 class="font-bold">Erros de Validação</h3>
                                <ul class="list-disc list-inside mt-2">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Perfil -->
                <div class="card bg-base-100 shadow-2xl hover:shadow-3xl transition-all duration-300">
                    <div class="card-body p-8">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="card-title text-2xl font-bold flex items-center gap-2">
                                <x-fas-user class="h-7 w-7 text-primary" />
                                Detalhes Pessoais
                            </h2>
                            <div class="badge badge-primary badge-lg gap-2">
                                <x-fas-shield-alt class="h-4 w-4" />
                                {{ ucfirst($user->role) }}
                            </div>
                        </div>
                        
                        <div class="flex flex-col md:flex-row items-center md:items-start gap-8">
                            <!-- Secção Avatar -->
                            <div class="flex flex-col items-center space-y-4">
                                <div class="avatar">
                                    <div class="w-32 h-32 rounded-full ring ring-primary ring-offset-base-100 ring-offset-4 hover:ring-offset-8 transition-all duration-300">
                                        <div class="w-full h-full flex items-center justify-center overflow-hidden from-primary to-secondary">
                                            <template x-if="imagePreview">
                                                <img :src="imagePreview" alt="Avatar" class="object-cover w-full h-full" />
                                            </template>
                                            <template x-if="!imagePreview">
                                                @if($user->image_path)
                                                    <img src="{{ asset($user->image_path) }}" alt="Avatar" class="object-cover w-full h-full" />
                                                @else
                                                    <span class="text-5xl text-white font-bold select-none">
                                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                                    </span>
                                                @endif
                                            </template>
                                        </div>
                                    </div>
                                </div>

                                <!-- Upload de Imagem -->
                                <div class="form-control w-full" x-show="editing" x-transition x-cloak>
                                    <label for="profile_image_input" class="label cursor-pointer">
                                        <span class="btn btn-sm btn-outline btn-primary w-full">
                                            <x-fas-camera class="h-4 w-4 mr-2" />
                                            Alterar Foto
                                        </span>
                                    </label>
                                    <input 
                                        type="file" 
                                        id="profile_image_input"
                                        name="image_path" 
                                        class="hidden" 
                                        @change="previewImage($event)" 
                                        accept="image/*"
                                    >
                                    <x-form.error name="image_path" />
                                </div>
                            </div>

                            <!-- Informação Pessoal -->
                            <div class="flex-1 w-full space-y-4">
                                <div class="form-control w-full">
                                    <label class="label">
                                        <span class="label-text font-semibold flex items-center gap-2">
                                            <x-fas-user class="h-4 w-4" />
                                            Nome Completo
                                        </span>
                                    </label>
                                    <input 
                                        type="text" 
                                        name="name" 
                                        x-model="form.name" 
                                        :readonly="!editing" 
                                        class="input input-bordered w-full focus:input-primary transition-all duration-200"
                                        :class="{
                                            'input-primary': editing && !errors.name,
                                            'input-error': editing && errors.name,
                                            'input-success': editing && form.name && !errors.name
                                        }"
                                        @input="validateName"
                                        @blur="validateName"
                                        required 
                                    />
                                    <x-form.validation-feedback field="name" success-message="Nome válido" condition="editing" />
                                    <x-form.error name="name" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informação da Conta -->
                <div class="card bg-base-100 shadow-2xl hover:shadow-3xl transition-all duration-300">
                    <div class="card-body p-8">
                        <h2 class="card-title text-2xl font-bold mb-6 flex items-center gap-2">
                            <x-fas-shield-alt class="h-7 w-7 text-secondary" />
                            Informações da Conta
                        </h2>
                        
                        <div class="space-y-4">
                            <!-- Campos -->
                            <div class="form-control w-full">
                                <label class="label">
                                    <span class="label-text font-semibold flex items-center gap-2">
                                        <x-fas-envelope class="h-4 w-4" />
                                        Email
                                    </span>
                                </label>
                                <input 
                                    type="email" 
                                    name="email" 
                                    x-model="form.email" 
                                    :readonly="!editing" 
                                    class="input input-bordered w-full focus:input-secondary transition-all duration-200"
                                    :class="{
                                        'input-secondary': editing && !errors.email,
                                        'input-error': editing && errors.email,
                                        'input-success': editing && form.email && !errors.email
                                    }"
                                    @input="validateEmail"
                                    @blur="validateEmail"
                                    required 
                                />
                                <x-form.validation-feedback field="email" success-message="Email válido" condition="editing" />
                                <x-form.error name="email" />
                            </div>

                            <div class="divider" x-show="editing" x-transition x-cloak>Alterar password</div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4" x-show="editing" x-transition x-cloak>
                                <div class="form-control w-full" x-data="{ showPassword: false }">
                                    <label class="label">
                                        <span class="label-text font-semibold flex items-center gap-2">
                                            <x-fas-lock class="h-4 w-4" />
                                            Nova password
                                        </span>
                                    </label>
                                    <div class="relative">
                                        <input 
                                            :type="showPassword ? 'text' : 'password'" 
                                            name="password" 
                                            x-model="form.password" 
                                            class="input input-bordered w-full pr-12 focus:input-warning transition-all duration-200"
                                            :class="{
                                                'input-error': errors.password,
                                                'input-success': form.password && !errors.password && form.password.length >= 8
                                            }"
                                            @input="validatePassword"
                                            @blur="validatePassword"
                                            autocomplete="new-password" 
                                        />
                                        <button
                                            type="button"
                                            @click="showPassword = !showPassword"
                                            class="absolute inset-y-0 right-0 flex items-center pr-4 text-base-content/60 hover:text-base-content transition-colors"
                                        >
                                            <x-fas-eye x-show="!showPassword" class="h-5 w-5" />
                                            <x-fas-eye-slash x-show="showPassword" class="h-5 w-5" />
                                        </button>
                                    </div>
                                    <x-form.validation-feedback field="password" />
                                    <template x-if="form.password && !errors.password">
                                        <div class="mt-2">
                                            <div class="flex items-center justify-between mb-1">
                                                <span class="text-xs" :class="{
                                                    'text-red-500': passwordStrength === 'fraca',
                                                    'text-yellow-500': passwordStrength === 'média',
                                                    'text-green-500': passwordStrength === 'forte'
                                                }">
                                                    Força: <span x-text="passwordStrength" class="font-semibold capitalize"></span>
                                                </span>
                                            </div>
                                            <progress 
                                                class="progress w-full" 
                                                :class="{
                                                    'progress-error': passwordStrength === 'fraca',
                                                    'progress-warning': passwordStrength === 'média',
                                                    'progress-success': passwordStrength === 'forte'
                                                }"
                                                :value="passwordStrengthValue" 
                                                max="100"
                                            ></progress>
                                            <div class="mt-2 space-y-1">
                                                <div class="flex items-center gap-2 text-xs" :class="passwordChecks.minLength ? 'text-green-500' : 'text-base-content/60'">
                                                    <x-fas-check-circle x-show="passwordChecks.minLength" class="h-3 w-3" />
                                                    <x-fas-circle x-show="!passwordChecks.minLength" class="h-3 w-3" />
                                                    <span>Pelo menos 8 caracteres</span>
                                                </div>
                                                <div class="flex items-center gap-2 text-xs" :class="passwordChecks.hasLower ? 'text-green-500' : 'text-base-content/60'">
                                                    <x-fas-check-circle x-show="passwordChecks.hasLower" class="h-3 w-3" />
                                                    <x-fas-circle x-show="!passwordChecks.hasLower" class="h-3 w-3" />
                                                    <span>Uma letra minúscula</span>
                                                </div>
                                                <div class="flex items-center gap-2 text-xs" :class="passwordChecks.hasUpper ? 'text-green-500' : 'text-base-content/60'">
                                                    <x-fas-check-circle x-show="passwordChecks.hasUpper" class="h-3 w-3" />
                                                    <x-fas-circle x-show="!passwordChecks.hasUpper" class="h-3 w-3" />
                                                    <span>Uma letra maiúscula</span>
                                                </div>
                                                <div class="flex items-center gap-2 text-xs" :class="passwordChecks.hasNumber ? 'text-green-500' : 'text-base-content/60'">
                                                    <x-fas-check-circle x-show="passwordChecks.hasNumber" class="h-3 w-3" />
                                                    <x-fas-circle x-show="!passwordChecks.hasNumber" class="h-3 w-3" />
                                                    <span>Um número</span>
                                                </div>
                                                <div class="flex items-center gap-2 text-xs" :class="passwordChecks.hasSpecial ? 'text-green-500' : 'text-base-content/60'">
                                                    <x-fas-check-circle x-show="passwordChecks.hasSpecial" class="h-3 w-3" />
                                                    <x-fas-circle x-show="!passwordChecks.hasSpecial" class="h-3 w-3" />
                                                    <span>Um carácter especial</span>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                    <x-form.error name="password" />
                                </div>
                                <div class="form-control w-full">
                                    <label class="label">
                                        <span class="label-text font-semibold flex items-center gap-2">
                                            <x-fas-check-circle class="h-4 w-4" />
                                            Confirmar password
                                        </span>
                                    </label>
                                    <input 
                                        type="password" 
                                        name="password_confirmation" 
                                        x-model="form.password_confirmation" 
                                        class="input input-bordered w-full focus:input-warning transition-all duration-200"
                                        :class="{
                                            'input-error': errors.password_confirmation,
                                            'input-success': form.password_confirmation && !errors.password_confirmation && form.password_confirmation === form.password
                                        }"
                                        @input="touched.password_confirmation = true; validatePasswordConfirmation()"
                                        @blur="touched.password_confirmation = true; validatePasswordConfirmation()"
                                        autocomplete="new-password" 
                                    />
                                    <template x-if="errors.password_confirmation">
                                        <label class="label">
                                            <span class="label-text-alt text-red-500 flex items-center gap-1">
                                                <x-fas-exclamation-circle class="h-3 w-3" />
                                                <span x-text="errors.password_confirmation"></span>
                                            </span>
                                        </label>
                                    </template>
                                    <template x-if="form.password_confirmation && !errors.password_confirmation && form.password_confirmation === form.password">
                                        <label class="label">
                                            <span class="label-text-alt text-green-500 flex items-center gap-1">
                                                <x-fas-check-circle class="h-3 w-3" />
                                                As passwords coincidem
                                            </span>
                                        </label>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <!-- Botões de Ação -->
                        <div class="card-actions justify-end mt-8 pt-6 border-t border-base-300">
                            <template x-if="!editing">
                                <button 
                                    type="button" 
                                    class="btn btn-primary btn-wide gap-2" 
                                    @click="editing = true"
                                >
                                    <x-fas-edit class="h-5 w-5" />
                                    Editar Perfil
                                </button>
                            </template>
                            <template x-if="editing">
                                <div class="flex gap-3">
                                    <button 
                                        type="button" 
                                        class="btn btn-ghost gap-2" 
                                        @click="resetForm"
                                    >
                                        <x-fas-times class="h-5 w-5" />
                                        Cancelar
                                    </button>
                                    <button 
                                        type="submit" 
                                        class="btn btn-primary gap-2 text-white"
                                        :disabled="hasErrors"
                                    >
                                        <x-fas-save class="h-5 w-5" />
                                        Guardar Alterações
                                    </button>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function profileForm() {
            return {
                editing: false,
                imagePreview: null,
                form: {
                    name: @js(old('name', $user->name)),
                    email: @js(old('email', $user->email)),
                    password: '',
                    password_confirmation: '',
                },
                errors: {
                    name: '',
                    email: '',
                    password: '',
                    password_confirmation: '',
                },
                touched: {
                    password_confirmation: false,
                },
                passwordStrength: '',
                passwordStrengthValue: 0,
                
                get hasErrors() {
                    return !!(this.errors.name || this.errors.email || this.errors.password || this.errors.password_confirmation);
                },
                
                validateName() {
                    if (!this.editing) return;
                    
                    if (!this.form.name || this.form.name.trim() === '') {
                        this.errors.name = 'O nome é obrigatório';
                        return false;
                    }
                    
                    if (this.form.name.length > 255) {
                        this.errors.name = 'O nome não pode ter mais de 255 caracteres';
                        return false;
                    }
                    
                    this.errors.name = '';
                    return true;
                },
                
                validateEmail() {
                    if (!this.editing) return;
                    
                    if (!this.form.email || this.form.email.trim() === '') {
                        this.errors.email = 'O email é obrigatório';
                        return false;
                    }
                    
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailRegex.test(this.form.email)) {
                        this.errors.email = 'Formato de email inválido';
                        return false;
                    }
                    
                    if (this.form.email.length > 255) {
                        this.errors.email = 'O email não pode ter mais de 255 caracteres';
                        return false;
                    }
                    
                    this.errors.email = '';
                    return true;
                },
                
                // Nova Password
                validatePassword() {
                    if (!this.editing) return;
                    
                    if (!this.form.password || this.form.password === '') {
                        this.errors.password = '';
                        this.passwordStrength = '';
                        this.passwordStrengthValue = 0;

                        if (!this.form.password_confirmation) {
                            this.errors.password_confirmation = '';
                        }
                        return true;
                    }
                    
                    if (this.form.password.length < 8) {
                        this.errors.password = 'A password deve ter pelo menos 8 caracteres';
                        this.passwordStrength = 'fraca';
                        this.passwordStrengthValue = 20;
                        return false;
                    }
                    
                    if (this.form.password.length > 255) {
                        this.errors.password = 'A password não pode ter mais de 255 caracteres';
                        return false;
                    }
                    
                    this.calculatePasswordStrength();
                    this.errors.password = '';
                    
                    if (this.touched.password_confirmation) {
                        this.validatePasswordConfirmation();
                    }
                    
                    return true;
                },
                
                // Confirmar Password
                validatePasswordConfirmation() {
                    if (!this.editing) return;
                    
                    if (!this.form.password || this.form.password === '') {
                        this.errors.password_confirmation = '';
                        return true;
                    }
                    
                    if (!this.touched.password_confirmation) {
                        return true;
                    }
                    
                    if (!this.form.password_confirmation || this.form.password_confirmation === '') {
                        this.errors.password_confirmation = 'Por favor, confirme a password';
                        return false;
                    }
                    
                    if (this.form.password !== this.form.password_confirmation) {
                        this.errors.password_confirmation = 'As passwords não coincidem';
                        return false;
                    }
                    
                    this.errors.password_confirmation = '';
                    return true;
                },
                
                // Regras da password
                calculatePasswordStrength() {
                    let strength = 0;
                    const password = this.form.password;
                    
                    // Comprimento
                    if (password.length >= 8) strength += 25;
                    if (password.length >= 12) strength += 25;
                    
                    // Contém letras minúsculas
                    if (/[a-z]/.test(password)) strength += 15;
                    
                    // Contém letras maiúsculas
                    if (/[A-Z]/.test(password)) strength += 15;
                    
                    // Contém números
                    if (/\d/.test(password)) strength += 10;
                    
                    // Contém caracteres especiais
                    if (/[^a-zA-Z0-9]/.test(password)) strength += 10;
                    
                    this.passwordStrengthValue = strength;
                    
                    if (strength < 40) {
                        this.passwordStrength = 'fraca';
                    } else if (strength < 75) {
                        this.passwordStrength = 'média';
                    } else {
                        this.passwordStrength = 'forte';
                    }
                },
                
                validateForm() {
                    let isValid = true;
                    
                    isValid = this.validateName() && isValid;
                    isValid = this.validateEmail() && isValid;
                    isValid = this.validatePassword() && isValid;
                    

                    if (this.form.password) {
                        this.touched.password_confirmation = true;
                    }
                    isValid = this.validatePasswordConfirmation() && isValid;
                    
                    return isValid;
                },
                
                // Pré-visualização da Imagem
                previewImage(event) {
                    const file = event.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = e => this.imagePreview = e.target.result;
                        reader.readAsDataURL(file);
                    } else {
                        this.imagePreview = null;
                    }
                },
                
                resetForm() {
                    this.editing = false;
                    this.imagePreview = null;
                    this.form.name = @js($user->name);
                    this.form.email = @js($user->email);
                    this.form.password = '';
                    this.form.password_confirmation = '';
                    this.errors = {
                        name: '',
                        email: '',
                        password: '',
                        password_confirmation: '',
                    };
                    this.touched = {
                        password_confirmation: false,
                    };
                    this.passwordStrength = '';
                    this.passwordStrengthValue = 0;
                    
                    const fileInput = document.getElementById('profile_image_input');
                    if (fileInput) {
                        fileInput.value = '';
                    }
                }
            }
        }
    </script>
</x-layout>