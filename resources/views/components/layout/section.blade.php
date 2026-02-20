@props([
    'title' => null,
])

<div {{ $attributes->merge(['class' => 'mt-6']) }}>
    @if($title)
        <h2 class="text-2xl font-bold mb-4">{{ $title }}</h2>
    @endif
    
    {{ $slot }}
</div>