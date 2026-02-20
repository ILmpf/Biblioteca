@props([
    'url',
    'text' => 'Voltar'
])

<a 
    href="{{ $url }}" 
    {{ $attributes->merge(['class' => 'inline-flex items-center gap-2 text-sm hover:text-primary transition-colors group mb-4']) }}
>
    <x-fas-arrow-left class="h-4 w-4 group-hover:-translate-x-1 transition-transform" />
    <span class="font-medium">{{ $text }}</span>
</a>