<x-layout>
    <div class="min-h-[calc(100vh-4rem)] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="w-full {{ isset($role) && $role === 'admin' ? 'max-w-md' : 'max-w-6xl' }}">
            <div class="grid {{ isset($role) && $role === 'admin' ? 'grid-cols-1' : 'lg:grid-cols-2' }} gap-8 items-center">
                
                <div class="w-full max-w-md mx-auto {{ isset($role) && $role === 'admin' ? '' : 'lg:order-1' }}">
                    <div class="card bg-base-100 shadow-2xl border border-base-300">
                        <div class="card-body p-8 sm:p-10">
                            @if(!isset($role) || $role !== 'admin')
                                <!-- Logo Mobile -->
                                <div class="flex justify-center mb-6 lg:hidden">
                                    <img src="/images/logo.png" alt="Biblioteca logo" class="w-auto h-16">
                                </div>
                            @endif

                            <div class="text-center mb-8">
                                <h1 class="text-3xl font-bold text-base-content">{{ $title ?? 'Criar Conta' }}</h1>
                                <p class="text-base-content/60 mt-2">{{ $description ?? 'Junte-se à nossa biblioteca digital' }}</p>
                            </div>

                            <form action="{{ $action }}" method="POST" class="space-y-5" x-data="registerForm()" @submit="if (!validateForm()) { $event.preventDefault(); }">
                                @csrf

                                <!-- Campos -->
                                <div class="form-control">
                                    <label for="name" class="label">
                                        <span class="label-text font-medium">Nome Completo</span>
                                    </label>
                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-0 flex items-center">
                                            <x-fas-user class="h-5 w-5 text-base-content/40" />
                                        </span>
                                        <input
                                            type="text"
                                            id="name"
                                            name="name"
                                            x-model="form.name"
                                            class="input input-bordered w-full"
                                            :class="{
                                                'input-error': errors.name,
                                                'input-success': form.name && !errors.name
                                            }"
                                            @input="validateName"
                                            @blur="validateName"
                                            required
                                            autofocus
                                        />
                                    </div>
                                    <x-form.validation-feedback field="name" success-message="Nome válido" />
                                    <x-form.error name="name" />
                                </div>

                                <div class="form-control">
                                    <label for="email" class="label">
                                        <span class="label-text font-medium">Email</span>
                                    </label>
                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-0 flex items-center">
                                            <x-fas-envelope class="h-5 w-5 text-base-content/40" />
                                        </span>
                                        <input
                                            type="email"
                                            id="email"
                                            name="email"
                                            x-model="form.email"
                                            class="input input-bordered w-full"
                                            :class="{
                                                'input-error': errors.email,
                                                'input-success': form.email && !errors.email
                                            }"
                                            @input="validateEmail"
                                            @blur="validateEmail"
                                            required
                                        />
                                    </div>
                                    <x-form.validation-feedback field="email" success-message="Email válido" />
                                    <x-form.error name="email" />
                                </div>

                                @if(!isset($role) || $role !== 'admin')
                                    <!-- Password -->
                                    <div class="form-control" x-data="{ showPassword: false }">
                                        <label for="password" class="label">
                                            <span class="label-text font-medium">Password</span>
                                        </label>
                                        <div class="relative">
                                            <span class="absolute inset-y-0 left-0 flex items-center pl-4">
                                                <x-fas-lock class="h-5 w-5 text-base-content/40" />
                                            </span>
                                            <input
                                                :type="showPassword ? 'text' : 'password'"
                                                id="password"
                                                name="password"
                                                x-model="form.password"
                                                class="input input-bordered w-full pr-12"
                                                :class="{
                                                    'input-error': errors.password,
                                                    'input-success': form.password && !errors.password
                                                }"
                                                @input="validatePassword"
                                                @blur="validatePassword"
                                                required
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

                                    <!-- Confirmar Password -->
                                    <div class="form-control">
                                        <label for="password_confirmation" class="label">
                                            <span class="label-text font-medium">Confirmar Password</span>
                                        </label>
                                        <div class="relative">
                                            <span class="absolute inset-y-0 left-0 flex items-center pl-4">
                                                <x-fas-check-circle class="h-5 w-5 text-base-content/40" />
                                            </span>
                                            <input
                                                type="password"
                                                id="password_confirmation"
                                                name="password_confirmation"
                                                x-model="form.password_confirmation"
                                                class="input input-bordered w-full"
                                                :class="{
                                                    'input-error': errors.password_confirmation,
                                                    'input-success': form.password_confirmation && !errors.password_confirmation && form.password_confirmation === form.password
                                                }"
                                                @input="touched.password_confirmation = true; validatePasswordConfirmation()"
                                                @blur="touched.password_confirmation = true; validatePasswordConfirmation()"
                                                required
                                            />
                                        </div>
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
                                @endif

                                @isset($role)
                                    <input type="hidden" name="role" value="{{ $role }}">
                                @endisset

                                @if(!isset($role) || $role !== 'admin')
                                    <!-- Termos -->
                                    <div class="form-control">
                                        <label class="label cursor-pointer justify-start gap-3">
                                            <input type="checkbox" name="terms" class="checkbox checkbox-sm" required />
                                            <span class="label-text text-sm">
                                                Aceito os
                                                <a href="#" class="text-primary hover:text-primary-focus font-medium">Termos de Uso</a>
                                                e
                                                <a href="#" class="text-primary hover:text-primary-focus font-medium">Política de Privacidade</a>
                                            </span>
                                        </label>
                                    </div>
                                @endif

                                <!-- Botão Submeter -->
                                <button
                                    type="submit"
                                    class="btn btn-primary w-full gap-2 h-12 text-base"
                                    :disabled="hasErrors"
                                >
                                    <x-fas-user-plus class="h-5 w-5" />
                                    {{ $buttonText ?? 'Criar Conta' }}
                                </button>
                            </form>
                                <!-- Link Login -->
                            <div class="text-center mt-6 pt-6 border-t border-base-300">
                                <p class="text-base-content/70">
                                    Já tem conta?
                                    <a href="/login" class="text-primary hover:text-primary-focus font-medium transition-colors">
                                        Entrar agora
                                    </a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                @if(!isset($role) || $role !== 'admin')
                    <!-- Lado Direito - Marca -->
                    <div class="hidden lg:flex flex-col justify-center space-y-8 bg-linear-to-br from-primary/10 via-secondary/5 to-accent/10 rounded-3xl p-12 h-full lg:order-2">
                    <div class="space-y-4">
                        <div class="flex items-center gap-3 mb-8">
                            <img src="/images/logo.png" alt="Biblioteca logo" class="w-auto h-16">
                        </div>
                        <h2 class="text-4xl font-bold text-base-content leading-tight">
                            Começa a tua jornada literária.
                        </h2>
                        <p class="text-lg text-base-content/70 leading-relaxed">
                            Cria a tua conta gratuita e tem acesso imediato a milhares de livros. Simples, rápido e seguro.
                        </p>
                    </div>

                    <div class="space-y-4">
                        <div class="flex items-start gap-4">
                            <div class="shrink-0 w-12 h-12 rounded-full bg-success/20 flex items-center justify-center">
                                <x-fas-check class="h-6 w-6 text-success" />
                            </div>
                            <div>
                                <h3 class="font-semibold text-base-content">Registo Gratuito</h3>
                                <p class="text-sm text-base-content/60">E rápido!</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="shrink-0 w-12 h-12 rounded-full bg-success/20 flex items-center justify-center">
                                <x-fas-shield-halved class="h-6 w-6 text-success" />
                            </div>
                            <div>
                                <h3 class="font-semibold text-base-content">Dados Protegidos</h3>
                                <p class="text-sm text-base-content/60">A Segurança é a nossa prioridade</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="shrink-0 w-12 h-12 rounded-full bg-success/20 flex items-center justify-center">
                                <x-fas-bolt class="h-6 w-6 text-success" />
                            </div>
                            <div>
                                <h3 class="font-semibold text-base-content">Acesso Instantâneo</h3>
                                <p class="text-sm text-base-content/60">Começa a requisitar imediatamente</p>
                            </div>
                        </div>
                    </div>

                    <!-- Indicadores de Confiança -->
                    <div class="bg-base-100/50 rounded-2xl p-6 border border-base-300">
                        <div class="flex items-center justify-around text-center">
                            <div>
                                <div class="text-3xl font-bold text-primary">10k+</div>
                                <div class="text-xs text-base-content/60 mt-1">Utilizadores</div>
                            </div>
                            <div class="divider divider-horizontal"></div>
                            <div>
                                <div class="text-3xl font-bold text-primary">50k+</div>
                                <div class="text-xs text-base-content/60 mt-1">Livros</div>
                            </div>
                            <div class="divider divider-horizontal"></div>
                            <div>
                                <div class="text-3xl font-bold text-primary">4.9</div>
                                <div class="text-xs text-base-content/60 mt-1">Avaliação</div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        function registerForm() {
            return {
                form: {
                    name: '',
                    email: '',
                    password: '',
                },
                errors: {
                    name: '',
                    email: '',
                    password: '',
                },
                passwordStrength: '',
                passwordStrengthValue: 0,
                passwordChecks: {
                    minLength: false,
                    hasLower: false,
                    hasUpper: false,
                    hasNumber: false,
                    hasSpecial: false,
                },

                get hasErrors() {
                    return !!(this.errors.name || this.errors.email || this.errors.password || this.errors.password_confirmation);
                },

                validateName() {
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

                validatePassword() {
                    const password = this.form.password;

                    if (!password || password === '') {
                        this.errors.password = 'A password é obrigatória';
                        this.resetPasswordChecks();
                        return false;
                    }

                    // Password checks
                    this.passwordChecks.minLength = password.length >= 8;
                    this.passwordChecks.hasLower = /[a-z]/.test(password);
                    this.passwordChecks.hasUpper = /[A-Z]/.test(password);
                    this.passwordChecks.hasNumber = /\d/.test(password);
                    this.passwordChecks.hasSpecial = /[^a-zA-Z0-9]/.test(password);

                    // Regras da Password
                    if (!this.passwordChecks.minLength) {
                        this.errors.password = 'A password deve ter pelo menos 8 caracteres';
                        this.calculatePasswordStrength();
                        return false;
                    }

                    if (!this.passwordChecks.hasLower) {
                        this.errors.password = 'A password deve conter pelo menos uma letra minúscula';
                        this.calculatePasswordStrength();
                        return false;
                    }

                    if (!this.passwordChecks.hasUpper) {
                        this.errors.password = 'A password deve conter pelo menos uma letra maiúscula';
                        this.calculatePasswordStrength();
                        return false;
                    }

                    if (!this.passwordChecks.hasNumber) {
                        this.errors.password = 'A password deve conter pelo menos um número';
                        this.calculatePasswordStrength();
                        return false;
                    }

                    if (!this.passwordChecks.hasSpecial) {
                        this.errors.password = 'A password deve conter pelo menos um carácter especial';
                        this.calculatePasswordStrength();
                        return false;
                    }

                    if (password.length > 255) {
                        this.errors.password = 'A password não pode ter mais de 255 caracteres';
                        return false;
                    }

                    this.calculatePasswordStrength();
                    this.errors.password = '';
                    return true;
                },

                calculatePasswordStrength() {
                    let strength = 0;
                    const password = this.form.password;

                    if (!password) {
                        this.passwordStrength = '';
                        this.passwordStrengthValue = 0;
                        return;
                    }

                    if (password.length >= 8) strength += 25;
                    if (password.length >= 12) strength += 25;

                    if (/[a-z]/.test(password)) strength += 15;
                    if (/[A-Z]/.test(password)) strength += 15;
                    if (/\d/.test(password)) strength += 10;
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

                resetPasswordChecks() {
                    this.passwordChecks = {
                        minLength: false,
                        hasLower: false,
                        hasUpper: false,
                        hasNumber: false,
                        hasSpecial: false,
                    };
                    this.passwordStrength = '';
                    this.passwordStrengthValue = 0;
                },

                validatePasswordConfirmation() {
                    if (!this.touched.password_confirmation) {
                        return true;
                    }

                    if (!this.form.password_confirmation || this.form.password_confirmation === '') {
                        this.errors.password_confirmation = 'Por favor, confirma a password';
                        return false;
                    }

                    if (this.form.password_confirmation !== this.form.password) {
                        this.errors.password_confirmation = 'As passwords não coincidem';
                        return false;
                    }

                    this.errors.password_confirmation = '';
                    return true;
                },

                validateForm() {
                    let isValid = true;

                    isValid = this.validateName() && isValid;
                    isValid = this.validateEmail() && isValid;
                    
                    @if(!isset($role) || $role !== 'admin')
                        isValid = this.validatePassword() && isValid;
                        isValid = this.validatePasswordConfirmation() && isValid;
                    @endif

                    return isValid;
                },
            }
        }
    </script>
</x-layout>
