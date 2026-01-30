<nav class="border-b border-border px-6">
    <div class="max-w-7xl mx-auto h-30 flex items-center justify-between">
        <div>
            <a href="/">
                <img src="/images/logo.png" alt="Biblioteca logo" class="w-auto h-auto">
            </a>
        </div>

        <div class="flex gap-x-5 items-center">
            @auth
                <x-navbar.dropdown
                    :active="request()->routeIs('livro.*', 'autor.*', 'editora.*')"
                    class="w-40"
                >
                    <x-slot:trigger>
                        <x-fas-layer-group class="w-6 h-6" />
                        <span>Catálogo</span>
                    </x-slot:trigger>

                    <x-navbar.dropdown-item
                        href="{{ route('livro.index') }}"
                        :active="request()->routeIs('livro.*')"
                    >
                        <x-slot:icon>
                            <x-fas-book class="w-5 h-5" />
                        </x-slot:icon>
                        Livros
                    </x-navbar.dropdown-item>

                    <x-navbar.dropdown-item
                        href=""
                        :active="request()->routeIs('autor.*')"
                    >
                        <x-slot:icon>
                            <x-fas-signature class="w-5 h-5" />
                        </x-slot:icon>
                        Autores
                    </x-navbar.dropdown-item>

                    <x-navbar.dropdown-item
                        href="{{ route('editora.index') }}"
                        :active="request()->routeIs('editora.*')"
                    >
                        <x-slot:icon>
                            <x-fas-building class="w-5 h-5" />
                        </x-slot:icon>
                        Editoras
                    </x-navbar.dropdown-item>
                </x-navbar.dropdown>

                <x-navbar.link
                    href="{{route('requisicao.index')}}"
                    :active="request()->routeIs('requisicoes.*')"
                >
                    <x-slot:icon>
                        <x-fas-clipboard-list class="w-6 h-6" />
                    </x-slot:icon>
                    Requisições
                </x-navbar.link>

                @can('isAdmin')
                    <x-navbar.dropdown class="w-52">
                        <x-slot:trigger>
                            <x-icons.admin class="w-6 h-6" />
                            <span>Admin</span>
                        </x-slot:trigger>

                        <li>
                            <details>
                                <summary class="flex items-center gap-2">
                                    <x-fas-users class="w-5 h-5" />
                                    Utilizadores
                                </summary>

                                <ul class="ml-4 mt-1 border-l border-border">
                                    <li>
                                        <a href="{{ route('admin.users.create') }}" class="flex items-center gap-2">
                                            <x-fas-user-plus class="w-4 h-4" />
                                            Criar utilizador
                                        </a>
                                    </li>

                                    <li>
                                        <a href="/admin/users" class="flex items-center gap-2">
                                            <x-fas-user class="w-4 h-4" />
                                            Listar utilizadores
                                        </a>
                                    </li>
                                </ul>
                            </details>
                        </li>

                        <x-navbar.dropdown-item href="/admin/settings">
                            <x-slot:icon>
                                <x-fas-gear class="w-5 h-5" />
                            </x-slot:icon>
                            Gerir Biblioteca
                        </x-navbar.dropdown-item>
                    </x-navbar.dropdown>
                @endcan


                <x-navbar.dropdown align="end">
                    <x-slot:trigger>
                        <div class="flex items-center gap-2">

                            <img src="{{asset(auth()->user()->image_path)}}" alt=""
                                class="w-10 h-10 rounded-full object-cover ring-1"
                            />
                        </div>
                    </x-slot:trigger>

                    <x-navbar.dropdown-item href="">
                        <x-slot:icon>
                            <x-fas-user class="w-5 h-5" />
                        </x-slot:icon>
                        Perfil
                    </x-navbar.dropdown-item>

                    <li>
                        <form method="POST" action="/logout">
                            @csrf
                            <button
                                type="submit"
                                class="w-full flex items-center gap-2 py-2 text-left hover:bg-base-300 rounded cursor-pointer"
                            >
                                <x-fas-right-from-bracket class="w-5 h-5" />
                                Sair
                            </button>
                        </form>
                    </li>
                </x-navbar.dropdown>
            @endauth

            @guest
                <a href="/login">Login</a>
                <a href="/register" class="btn btn-primary">Registar</a>
            @endguest
        </div>
    </div>

</nav>
