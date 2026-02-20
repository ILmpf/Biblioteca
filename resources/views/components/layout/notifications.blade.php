<!-- Componente de Notificações Inline -->
<div class="space-y-4">
    <!-- Notificação de Sucesso -->
    @if(session('success'))
        <div 
            class="alert alert-success shadow-lg"
            x-data="{ show: true }" 
            x-show="show" 
            x-transition
        >
            <div class="flex items-center gap-3 flex-1">
                <x-fas-check-circle class="h-5 w-5 shrink-0" />
                <span>{{ session('success') }}</span>
            </div>
            <button @click="show = false" class="btn btn-sm btn-ghost btn-circle">
                <x-fas-times class="h-4 w-4" />
            </button>
        </div>
    @endif

    <!-- Notificação de Erro -->
    @if(session('error'))
        <div 
            class="alert alert-error shadow-lg"
            x-data="{ show: true }" 
            x-show="show" 
            x-transition
        >
            <div class="flex items-center gap-3 flex-1">
                <x-fas-exclamation-circle class="h-5 w-5 shrink-0" />
                <span>{{ session('error') }}</span>
            </div>
            <button @click="show = false" class="btn btn-sm btn-ghost btn-circle">
                <x-fas-times class="h-4 w-4" />
            </button>
        </div>
    @endif

    <!-- Notificação de Info -->
    @if(session('info'))
        <div 
            class="alert alert-info shadow-lg"
            x-data="{ show: true }" 
            x-show="show" 
            x-transition
        >
            <div class="flex items-center gap-3 flex-1">
                <x-fas-info-circle class="h-5 w-5 shrink-0" />
                <span>{{ session('info') }}</span>
            </div>
            <button @click="show = false" class="btn btn-sm btn-ghost btn-circle">
                <x-fas-times class="h-4 w-4" />
            </button>
        </div>
    @endif

    <!-- Notificação de Aviso -->
    @if(session('warning'))
        <div 
            class="alert alert-warning shadow-lg"
            x-data="{ show: true }" 
            x-show="show" 
            x-transition
        >
            <div class="flex items-center gap-3 flex-1">
                <x-fas-exclamation-triangle class="h-5 w-5 shrink-0" />
                <span>{{ session('warning') }}</span>
            </div>
            <button @click="show = false" class="btn btn-sm btn-ghost btn-circle">
                <x-fas-times class="h-4 w-4" />
            </button>
        </div>
    @endif

    <!-- Erros de Validação -->
    @if($errors->any())
        <div 
            class="alert alert-error shadow-lg"
            x-data="{ show: true }" 
            x-show="show" 
            x-transition
        >
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-2">
                    <x-fas-exclamation-circle class="h-5 w-5 shrink-0" />
                    <h3 class="font-bold">Erro ao processar</h3>
                </div>
                <ul class="text-sm ml-8 list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            <button @click="show = false" class="btn btn-sm btn-ghost btn-circle self-start">
                <x-fas-times class="h-4 w-4" />
            </button>
        </div>
    @endif
</div>