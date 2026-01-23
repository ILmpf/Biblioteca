<nav class="border-b border-border px-6">
    <div class="max-w-7xl mx-auto h-16 flex items-center justify-between">
        <div>
            <a href="/">
                <img src="/images/logo.png" alt="Biblioteca logo" width="300">
            </a>
        </div>

        <div class="flex gap-x-5 items-center">
            @auth
                <a href="{{route('livro.index')}}" class="flex items-center gap-0.5">
                    <x-icons.book />
                    <span>Livros</span>
                </a>
                <a href="" class="flex items-center gap-0.5">
                    <x-icons.building />
                    <span>Editoras</span>
                </a>
                <a href="" class="flex items-center gap-0.5">
                    <x-icons.author />
                    <span>Autores</span>
                </a>
                <a href="" class="flex items-center gap-0.5">
                    <x-icons.clipboard />
                    <span>Requisições</span>
                </a>

                @can('isAdmin')
                    <div class="relative group">
                        <button class="flex items-center gap-0.5 focus:outline-none">
                            <x-icons.admin />
                            <span>Admin</span>
                            <svg class="w-4 h-4 ml-1 transition-transform group-hover:rotate-180"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div
                            class="absolute right-0 mt-2 w-48 bg-base-100 border border-border rounded-md shadow-lg
                   opacity-0 invisible group-hover:opacity-100 group-hover:visible
                   transition-all duration-200 z-50"
                        >
                            <a href="{{route('admin.users.create')}}"
                               class="block px-4 py-2 hover:bg-white">
                                Users
                            </a>

                            <a href="/admin/users"
                               class="block px-4 py-2 hover:bg-white">
                                Requisições
                            </a>

                            <a href="/admin/settings"
                               class="block px-4 py-2 hover:bg-white">
                                Gerir Biblioteca
                            </a>
                        </div>
                    </div>
                @endcan

                <form method="POST" action="/logout">
                    @csrf
                    <button class="cursor-pointer btn btn-outline">Log Out</button>
                </form>
            @endauth

            @guest
                    <a href="/login" >Login</a>
                    <a href="/register" class="btn btn-primary">Registar</a>
            @endguest

        </div>
    </div>

</nav>
