@props([
    'href' => '#',
    'active' => false,
])

<li>
    <a
        href="{{ $href }}"
        @class([
            'flex items-center gap-2',
            'active' => $active,
        ])
    >
        {{ $icon ?? null }}
        {{ $slot }}
    </a>
</li>
