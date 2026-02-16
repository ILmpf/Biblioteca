<x-layout>
    <div class="min-h-[calc(100vh-4rem)] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-6xl">
            <div class="grid lg:grid-cols-2 gap-8 items-center">
                <!-- Lado Esquerdo - Marca -->
                <div class="hidden lg:flex flex-col justify-center space-y-8 bg-linear-to-br from-primary/10 via-secondary/5 to-accent/10 rounded-3xl p-12 h-full">
                    <div class="space-y-4">
                        <div class="flex items-center gap-3 mb-8">
                            <img src="/images/logo.png" alt="Biblioteca logo" class="w-auto h-16">
                        </div>
                        <h2 class="text-4xl font-bold text-base-content leading-tight">
                            Bem-vindo de volta!
                        </h2>
                        <p class="text-lg text-base-content/70 leading-relaxed">
                            Acede à tua conta para continuar a explorar o nosso vasto catálogo de livros e gerir as tuas requisições.
                        </p>
                    </div>

                    <div class="space-y-4">
                        <div class="flex items-start gap-4">
                            <div class="shrink-0 w-12 h-12 rounded-full bg-primary/20 flex items-center justify-center">
                                <x-fas-book class="h-6 w-6 text-primary" />
                            </div>
                            <div>
                                <h3 class="font-semibold text-base-content">Milhares de Livros</h3>
                                <p class="text-sm text-base-content/60">Acesso a uma vasta coleção digital</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="shrink-0 w-12 h-12 rounded-full bg-primary/20 flex items-center justify-center">
                                <x-fas-clock class="h-6 w-6 text-primary" />
                            </div>
                            <div>
                                <h3 class="font-semibold text-base-content">Disponível 24/7</h3>
                                <p class="text-sm text-base-content/60">Reserva livros a qualquer hora</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="shrink-0 w-12 h-12 rounded-full bg-primary/20 flex items-center justify-center">
                                <x-fas-user class="h-6 w-6 text-primary" />
                            </div>
                            <div>
                                <h3 class="font-semibold text-base-content">Gestão Simples</h3>
                                <p class="text-sm text-base-content/60">Controla as tuas requisições facilmente</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Lado Direito - Formulário de Login -->
                <div class="w-full max-w-md mx-auto">
                    <div class="card bg-base-100 shadow-2xl border border-base-300">
                        <div class="card-body p-8 sm:p-10">
                            <!-- Logo Mobile -->
                            <div class="flex justify-center mb-6 lg:hidden">
                                <img src="/images/logo.png" alt="Biblioteca logo" class="w-auto h-16">
                            </div>

                            <div class="text-center mb-8">
                                <h1 class="text-3xl font-bold text-base-content">Login</h1>
                                <p class="text-base-content/60 mt-2">Acede à tua conta</p>
                            </div>

                            <form action="/login" method="POST" class="space-y-5">
                                @csrf

                                <x-auth.input 
                                    label="Email" 
                                    name="email" 
                                    type="email"
                                    icon="fas-envelope"
                                    required 
                                    autofocus 
                                />

                                <x-auth.input 
                                    label="Password" 
                                    name="password" 
                                    type="password"
                                    icon="fas-lock"
                                    required 
                                />

                                <!-- Recuperar Password -->
                                <div class="flex items-center justify-between">
                                    <label class="label cursor-pointer gap-2">
                                        <input type="checkbox" name="remember" class="checkbox checkbox-sm" />
                                        <span class="label-text">Lembrar-me</span>
                                    </label>
                                    <a href="#" class="label-text text-primary hover:text-primary-focus transition-colors text-sm font-medium">
                                        Esqueceste a password?
                                    </a>
                                </div>

                                <!-- Botão Submeter -->
                                <button
                                    type="submit"
                                    class="btn btn-primary w-full gap-2 h-12 text-base"
                                    data-test="login-button"
                                >
                                    <x-fas-right-to-bracket class="h-5 w-5" />
                                    Entrar
                                </button>
                            </form>

                            <!-- Link Registar -->
                            <div class="text-center mt-6 pt-6 border-t border-base-300">
                                <p class="text-base-content/70">
                                    Não tens conta?
                                    <a href="/register" class="text-primary hover:text-primary-focus font-medium transition-colors">
                                        Registar agora
                                    </a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>
