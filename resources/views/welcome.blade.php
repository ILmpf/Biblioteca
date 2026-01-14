<x-layout>
    <section class="relative min-h-screen flex flex-col justify-between bg-cover bg-center"
        style="background-image: url('https://images.unsplash.com/photo-1524995997946-a1c2e315a42f');">

        <div class="absolute inset-0 bg-base-100/80"></div>

        <div class="relative z-10 flex-1 flex items-center justify-center">
            <div class="text-center max-w-xl px-6">

                <div class="mb-6 flex justify-center">
                    <div
                        class="w-20 h-20 rounded-full bg-primary text-primary-content flex items-center justify-center shadow-lg">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5
                                     S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18
                                     c1.746 0 3.332.477 4.5 1.253m0-13
                                     C13.168 5.477 14.754 5 16.5 5
                                     c1.746 0 3.332.477 4.5 1.253v13
                                     C19.832 18.477 18.246 18 16.5 18
                                     c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                </div>

                <h1 class="text-5xl font-bold text-base-content mb-4">
                    Biblioteca
                </h1>
                <p class="text-lg text-base-content/70 mb-10">
                    A tua Biblioteca digital aqui.
                </p>

                <a href="/login"
                    class="btn btn-primary btn-lg gap-2
                          transition-all duration-300 ease-out
                          hover:scale-105 hover:shadow-xl">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6
                                 A2.25 2.25 0 005.25 5.25v13.5
                                 A2.25 2.25 0 007.5 21h6
                                 a2.25 2.25 0 002.25-2.25V15" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                            d="M18 12H9m0 0l3-3m-3 3l3 3" />
                    </svg>
                    Login
                </a>
            </div>
        </div>


    </section>
</x-layout>
