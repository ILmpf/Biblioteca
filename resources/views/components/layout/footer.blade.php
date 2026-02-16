<footer class="bg-base-200 border-t border-base-300 mt-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Conteúdo Principal -->
        <div class="py-12 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- Secção Sobre -->
            <div class="space-y-4">
                <div class="flex items-center gap-3">
                    <img src="/images/logo.png" alt="Biblioteca logo" class="w-auto h-12">
                </div>
                <p class="text-sm text-base-content/70 leading-relaxed">
                    A tua biblioteca digital de confiança. Acede a milhares de livros, reserva online e desfruta da leitura.
                </p>
                <div class="flex gap-3">
                    <a href="#" class="btn btn-circle btn-sm btn-ghost hover:btn-primary" aria-label="Facebook">
                        <x-fab-facebook class="h-5 w-5" />
                    </a>
                    <a href="#" class="btn btn-circle btn-sm btn-ghost hover:btn-primary" aria-label="Twitter">
                        <x-fab-twitter class="h-5 w-5" />
                    </a>
                    <a href="#" class="btn btn-circle btn-sm btn-ghost hover:btn-primary" aria-label="Instagram">
                        <x-fab-instagram class="h-5 w-5" />
                    </a>
                    <a href="#" class="btn btn-circle btn-sm btn-ghost hover:btn-primary" aria-label="LinkedIn">
                        <x-fab-linkedin class="h-5 w-5" />
                    </a>
                </div>
            </div>

            <!-- Links -->
            <div>
                <h3 class="font-semibold text-base-content mb-4 flex items-center gap-2">
                    <x-fas-link class="h-4 w-4 text-primary" />
                    Links Rápidos
                </h3>
                <ul class="space-y-2 text-sm">
                    <li>
                        <a href="{{ route('home') }}" class="text-base-content/70 hover:text-primary transition-colors flex items-center gap-2">
                            <x-fas-home class="h-3 w-3" />
                            Página Inicial
                        </a>
                    </li>
                    @auth
                        <li>
                            <a href="{{ route('livro.index') }}" class="text-base-content/70 hover:text-primary transition-colors flex items-center gap-2">
                                <x-fas-book class="h-3 w-3" />
                                Catálogo
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('requisicao.index') }}" class="text-base-content/70 hover:text-primary transition-colors flex items-center gap-2">
                                <x-fas-clipboard-list class="h-3 w-3" />
                                As Minhas Requisições
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('profile.show') }}" class="text-base-content/70 hover:text-primary transition-colors flex items-center gap-2">
                                <x-fas-user class="h-3 w-3" />
                                Perfil
                            </a>
                        </li>
                    @else
                        <li>
                            <a href="/login" class="text-base-content/70 hover:text-primary transition-colors flex items-center gap-2">
                                <x-fas-right-to-bracket class="h-3 w-3" />
                                Login
                            </a>
                        </li>
                        <li>
                            <a href="/register" class="text-base-content/70 hover:text-primary transition-colors flex items-center gap-2">
                                <x-fas-user-plus class="h-3 w-3" />
                                Registar
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>

            <!-- Recursos -->
            <div>
                <h3 class="font-semibold text-base-content mb-4 flex items-center gap-2">
                    <x-fas-book-open class="h-4 w-4 text-primary" />
                    Recursos
                </h3>
                <ul class="space-y-2 text-sm">
                    <li>
                        <a href="#" class="text-base-content/70 hover:text-primary transition-colors flex items-center gap-2">
                            <x-fas-question-circle class="h-3 w-3" />
                            Como Funciona
                        </a>
                    </li>
                    <li>
                        <a href="#" class="text-base-content/70 hover:text-primary transition-colors flex items-center gap-2">
                            <x-fas-circle-info class="h-3 w-3" />
                            FAQ
                        </a>
                    </li>
                    <li>
                        <a href="#" class="text-base-content/70 hover:text-primary transition-colors flex items-center gap-2">
                            <x-fas-headset class="h-3 w-3" />
                            Suporte
                        </a>
                    </li>
                    <li>
                        <a href="#" class="text-base-content/70 hover:text-primary transition-colors flex items-center gap-2">
                            <x-fas-file-lines class="h-3 w-3" />
                            Regulamento
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Contacto -->
            <div>
                <h3 class="font-semibold text-base-content mb-4 flex items-center gap-2">
                    <x-fas-envelope class="h-4 w-4 text-primary" />
                    Contacto
                </h3>
                <ul class="space-y-3 text-sm">
                    <li class="flex items-start gap-2 text-base-content/70">
                        <x-fas-location-dot class="h-4 w-4 text-primary mt-0.5 shrink-0" />
                        <span>Rua da Biblioteca, 123<br>1000-001 Lisboa</span>
                    </li>
                    <li class="flex items-center gap-2 text-base-content/70">
                        <x-fas-phone class="h-4 w-4 text-primary shrink-0" />
                        <a href="tel:+351210000000" class="hover:text-primary transition-colors">+351 21 000 0000</a>
                    </li>
                    <li class="flex items-center gap-2 text-base-content/70">
                        <x-fas-envelope class="h-4 w-4 text-primary shrink-0" />
                        <a href="mailto:info@biblioteca.pt" class="hover:text-primary transition-colors">info@biblioteca.pt</a>
                    </li>
                    <li class="flex items-center gap-2 text-base-content/70">
                        <x-fas-clock class="h-4 w-4 text-primary shrink-0" />
                        <span>Seg-Sex: 9h-20h<br>Sáb: 10h-14h</span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Barra Inferior -->
        <div class="border-t border-base-300 py-6">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4 text-sm text-base-content/60">
                <div class="flex items-center gap-2">
                    <x-fas-copyright class="h-4 w-4" />
                    <span>{{ date('Y') }} Biblioteca. Todos os direitos reservados.</span>
                </div>
                <div class="flex gap-6">
                    <a href="#" class="hover:text-primary transition-colors">Privacidade</a>
                    <a href="#" class="hover:text-primary transition-colors">Termos de Uso</a>
                    <a href="#" class="hover:text-primary transition-colors">Cookies</a>
                </div>
            </div>
        </div>
    </div>
</footer>