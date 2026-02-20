@props([
    'title',
    'description' => null,
])

<div {{ $attributes->merge(['class' => 'text-center py-16']) }}>
    <!-- Ícone -->
    @isset($icon)
        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-base-200 mb-4">
            {{ $icon }}
        </div>
    @endisset
    
    <!-- Título -->
    <h3 class="text-xl font-semibold mb-2">{{ $title }}</h3>
    
    <!-- Descrição (opcional) -->
    @if($description)
        <p class="text-base-content/70 mb-4">{{ $description }}</p>
    @endif
    
    <!-- Ação (opcional) -->
    @isset($action)
        {{ $action }}
    @endisset
</div>