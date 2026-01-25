<div {{ $attributes->merge([
    'class' => 'card card-side bg-transparent shadow-sm
               hover:shadow-xl hover:-translate-y-0.5
               transition-all duration-200'
    ]) }}>
    <a href="{{ $attributes->get('href') }}" class="flex flex-1">
        <figure class="w-48 h-48 shrink-0 overflow-hidden rounded-1">
            {{ $image }}
        </figure>

        <div class="card-body flex flex-col bg-yellow-100 rounded-r-xl">
            <h2 class="card-title">{{ $title }}</h2>

            @isset($editora)
                <p class="text-sm text-base-content/60">
                    {{ $editora }}
                </p>
            @endisset

            {{ $slot }}

            <div class="mt-auto pt-4 flex justify-end">
                {{ $actions }}
            </div>
        </div>
    </a>
</div>
