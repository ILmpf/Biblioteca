@props([
    'href',
    'active' => false,
])

<a
    href="{{ $href }}"
    @class([
        'flex items-center gap-2 px-3 py-2 rounded-md text-lg transition',
        'text-primary bg-base-200' => $active,
        'text-base-content hover:text-primary hover:bg-base-200' => ! $active,
    ])
>
    @isset($icon)
        {{ $icon }}
    @endisset

    <span>{{ $slot }}</span>
</a>
