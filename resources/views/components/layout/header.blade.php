@props([
    'title',
    'description' => null,
    'gradient' => true,
    'centered' => false,
])

<header {{ $attributes->merge(['class' => 'py-12 md:py-16 relative overflow-hidden border-b border-base-200']) }}>
    @if($gradient)
        <div class="absolute inset-0 bg-gradient-to-r from-primary/5 via-secondary/5 to-accent/5 -z-10"></div>
    @endif
    
    <div class="flex flex-col {{ $centered ? 'items-center text-center' : 'md:flex-row md:items-center md:justify-between' }} gap-6">
        <!-- Título e Descrição -->
        <div>
            @isset($breadcrumb)
                {{ $breadcrumb }}
            @endisset
            
            <h1 class="text-4xl md:text-5xl font-bold tracking-tight text-primary">
                {{ $title }}
            </h1>
            
            @if($description)
                <p class="text-base-content/70 mt-3 text-lg">
                    {{ $description }}
                </p>
            @endif
        </div>
        
        <!-- Ações do Cabeçalho -->
        @isset($actions)
            <div class="flex gap-3 items-center">
                {{ $actions }}
            </div>
        @endisset
    </div>
</header>