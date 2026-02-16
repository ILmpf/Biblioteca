<x-layout>
    <div class="relative overflow-hidden">
        <div class="absolute inset-0 bg-linear-to-br from-primary/10 via-secondary/5 to-accent/10 -z-10"></div>
        
        <div class="py-16 md:py-24 lg:py-32">
            <div class="max-w-4xl mx-auto text-center space-y-8">
                <!-- Título Principal -->
                <div class="space-y-4">
                    <h1 class="text-5xl md:text-6xl lg:text-7xl font-bold tracking-tight">
                        A Tua Biblioteca
                        <span class="bg-linear-to-r from-primary to-secondary bg-clip-text text-transparent">
                            Digital
                        </span>
                    </h1>
                    <p class="text-xl md:text-2xl text-base-content/70 max-w-2xl mx-auto">
                        Descobre, requisita e aproveita milhares de livros. A leitura está a um clique de distância.
                    </p>
                </div>

                <!-- Barra de Pesquisa -->
                <div class="max-w-2xl mx-auto">
                    <form action="{{ route('livro.index') }}" method="GET" class="relative">
                        <div class="join w-full shadow-xl">
                            <input 
                                type="text" 
                                name="nome"
                                placeholder="Pesquisa por título, autor, editora..." 
                                class="input input-bordered input-lg join-item w-full focus:outline-none focus:ring-2 focus:ring-primary"
                            />
                            <button type="submit" class="btn btn-primary btn-lg join-item px-8">
                                <x-fas-search class="h-5 w-5" />
                                <span class="hidden sm:inline">Pesquisar</span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Botões -->
                <div class="flex flex-wrap gap-4 justify-center pt-4">
                    @guest
                        <a href="{{ route('register') }}" class="btn btn-primary btn-lg gap-2 shadow-lg hover:shadow-xl transition-all">
                            <x-fas-user-plus class="h-5 w-5" />
                            Criar Conta Grátis
                        </a>
                        <a href="{{ route('livro.index') }}" class="btn btn-outline btn-lg gap-2">
                            <x-fas-book class="h-5 w-5" />
                            Explorar Catálogo
                        </a>
                    @else
                        <a href="{{ route('livro.index') }}" class="btn btn-primary btn-lg gap-2 shadow-lg">
                            <x-fas-book class="h-5 w-5" />
                            Ver Catálogo
                        </a>
                        <a href="{{ route('requisicao.index') }}" class="btn btn-outline btn-lg gap-2">
                            <x-fas-clipboard-list class="h-5 w-5" />
                            Minhas Requisições
                        </a>
                    @endguest
                </div>
            </div>
        </div>
    </div>

    <!-- Secção de Estatísticas -->
    <div class="py-12 border-y border-base-300">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
            <div class="space-y-2">
                <div class="text-4xl md:text-5xl font-bold text-primary">
                    {{ number_format($stats['total_books']) }}
                </div>
                <div class="text-base-content/70 font-medium">
                    Livros no Catálogo
                </div>
            </div>
            <div class="space-y-2">
                <div class="text-4xl md:text-5xl font-bold text-success">
                    {{ number_format($stats['available_books']) }}
                </div>
                <div class="text-base-content/70 font-medium">
                    Disponíveis Agora
                </div>
            </div>
            <div class="space-y-2">
                <div class="text-4xl md:text-5xl font-bold text-secondary">
                    {{ number_format($stats['total_authors']) }}+
                </div>
                <div class="text-base-content/70 font-medium">
                    Autores Diferentes
                </div>
            </div>
        </div>
    </div>

    <!-- Secção de Livros em Destaque -->
    @if($featuredBooks->count() > 0)
        <div class="py-16">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-3xl md:text-4xl font-bold">Livros em Destaque</h2>
                    <p class="text-base-content/70 mt-2">Os mais recentes adicionados à nossa coleção</p>
                </div>
                <a href="{{ route('livro.index') }}" class="btn btn-outline gap-2 hidden md:flex">
                    Ver Todos
                    <x-fas-arrow-right class="h-4 w-4" />
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($featuredBooks as $livro)
                    <a href="{{ route('livro.show', $livro) }}" 
                       class="card bg-base-100 shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-2 group">
                        <figure class="relative h-80 overflow-hidden">
                            <img 
                                src="{{ $livro->imagem }}" 
                                alt="{{ $livro->nome }}"
                                class="object-cover w-full h-full group-hover:scale-110 transition-transform duration-500"
                            />
                            @if($livro->isAvailable())
                                <div class="absolute top-4 right-4">
                                    <div class="badge badge-success gap-1 shadow-lg">
                                        <x-fas-check class="h-3 w-3" />
                                        Disponível
                                    </div>
                                </div>
                            @else
                                <div class="absolute top-4 right-4">
                                    <div class="badge badge-error gap-1 shadow-lg">
                                        <x-fas-times class="h-3 w-3" />
                                        Indisponível
                                    </div>
                                </div>
                            @endif
                        </figure>
                        <div class="card-body">
                            <h3 class="card-title text-lg line-clamp-2">{{ $livro->nome }}</h3>
                            <div class="space-y-1 text-sm text-base-content/70">
                                <div class="flex items-center gap-2">
                                    <x-fas-user class="h-3 w-3" />
                                    <span>{{ $livro->autor->pluck('nome')->join(', ') }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <x-fas-building class="h-3 w-3" />
                                    <span>{{ $livro->editora->nome }}</span>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="text-center mt-8 md:hidden">
                <a href="{{ route('livro.index') }}" class="btn btn-outline gap-2">
                    Ver Todos os Livros
                    <x-fas-arrow-right class="h-4 w-4" />
                </a>
            </div>
        </div>
    @endif

    <!-- Secção de Características -->
    <div class="py-16 bg-base-200/50">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold mb-3">Como Funciona</h2>
            <p class="text-base-content/70">Simples, rápido e gratuito</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Passo 1 -->
            <div class="card bg-base-100 shadow-lg">
                <div class="card-body items-center text-center space-y-4">
                    <div class="w-16 h-16 rounded-full bg-primary/10 flex items-center justify-center">
                        <x-fas-search class="h-8 w-8 text-primary" />
                    </div>
                    <div>
                        <h3 class="card-title justify-center text-xl">1. Procura</h3>
                        <p class="text-base-content/70 mt-2">
                            Navega pelo nosso extenso catálogo e encontra o livro perfeito para ti
                        </p>
                    </div>
                </div>
            </div>

            <!-- Passo 2 -->
            <div class="card bg-base-100 shadow-lg">
                <div class="card-body items-center text-center space-y-4">
                    <div class="w-16 h-16 rounded-full bg-secondary/10 flex items-center justify-center">
                        <x-fas-clipboard-check class="h-8 w-8 text-secondary" />
                    </div>
                    <div>
                        <h3 class="card-title justify-center text-xl">2. Requisita</h3>
                        <p class="text-base-content/70 mt-2">
                            Faz a requisição do livro com apenas um clique. É instantâneo!
                        </p>
                    </div>
                </div>
            </div>

            <!-- Passo 3 -->
            <div class="card bg-base-100 shadow-lg">
                <div class="card-body items-center text-center space-y-4">
                    <div class="w-16 h-16 rounded-full bg-success/10 flex items-center justify-center">
                        <x-fas-book-open class="h-8 w-8 text-success" />
                    </div>
                    <div>
                        <h3 class="card-title justify-center text-xl">3. Aproveita</h3>
                        <p class="text-base-content/70 mt-2">
                            Recebe confirmação e começa a tua jornada de leitura
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Guests -->
    @guest
        <div class="py-20">
            <div class="card bg-linear-to-br from-primary to-secondary text-primary-content shadow-2xl">
                <div class="card-body items-center text-center py-16 px-8">
                    <h2 class="card-title text-3xl md:text-4xl lg:text-5xl font-bold mb-4">
                        Pronto para Começar?
                    </h2>
                    <p class="text-lg md:text-xl mb-8 max-w-2xl opacity-90">
                        Junta-te a milhares de leitores e descobre um mundo de conhecimento. 
                        Criar uma conta é grátis e demora menos de um minuto.
                    </p>
                    <div class="flex flex-wrap gap-4 justify-center">
                        <a href="{{ route('register') }}" class="btn btn-lg bg-white text-primary hover:bg-base-100 gap-2 shadow-xl">
                            <x-fas-user-plus class="h-5 w-5" />
                            Criar Conta Grátis
                        </a>
                        <a href="{{ route('login') }}" class="btn btn-lg btn-outline border-white text-white hover:bg-white hover:text-primary gap-2">
                            <x-fas-sign-in-alt class="h-5 w-5" />
                            Já Tenho Conta
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endguest
</x-layout>
