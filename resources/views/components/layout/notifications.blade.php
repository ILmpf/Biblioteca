<!-- Toast Notifications - Fixed Position -->
<div class="fixed top-32 left-1/2 -translate-x-1/2 z-50 w-full max-w-lg px-4">
    <div class="space-y-3">
        <!-- Notificação de Sucesso -->
        @if(session('success'))
            <div 
                class="alert alert-success shadow-xl max-h-96 overflow-y-auto"
                x-data="{ show: true }" 
                x-show="show" 
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform -translate-y-2"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform translate-y-0"
                x-transition:leave-end="opacity-0 transform -translate-y-2"
                x-init="setTimeout(() => show = false, 5000)"
            >
                <div class="flex items-start gap-2 flex-1 min-w-0">
                    <x-fas-check-circle class="h-4 w-4 shrink-0 mt-0.5" />
                    <span class="text-sm wrap-break-word">{{ session('success') }}</span>
                </div>
                <button @click="show = false" class="btn btn-xs btn-ghost btn-circle">
                    <x-fas-times class="h-3 w-3" />
                </button>
            </div>
        @endif

        <!-- Notificação de Erro -->
        @if(session('error'))
            <div 
                class="alert alert-error shadow-xl max-h-96 overflow-y-auto"
                x-data="{ show: true }" 
                x-show="show" 
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform -translate-y-2"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform translate-y-0"
                x-transition:leave-end="opacity-0 transform -translate-y-2"
                x-init="setTimeout(() => show = false, 6000)"
            >
                <div class="flex items-start gap-2 flex-1 min-w-0">
                    <x-fas-exclamation-circle class="h-4 w-4 shrink-0 mt-0.5" />
                    <span class="text-sm wrap-break-word">{{ session('error') }}</span>
                </div>
                <button @click="show = false" class="btn btn-xs btn-ghost btn-circle">
                    <x-fas-times class="h-3 w-3" />
                </button>
            </div>
        @endif

        <!-- Notificação de Info -->
        @if(session('info'))
            <div 
                class="alert alert-info shadow-xl max-h-96 overflow-y-auto"
                x-data="{ show: true }" 
                x-show="show" 
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform -translate-y-2"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform translate-y-0"
                x-transition:leave-end="opacity-0 transform -translate-y-2"
                x-init="setTimeout(() => show = false, 5000)"
            >
                <div class="flex items-start gap-2 flex-1 min-w-0">
                    <x-fas-info-circle class="h-4 w-4 shrink-0 mt-0.5" />
                    <span class="text-sm wrap-break-word">{{ session('info') }}</span>
                </div>
                <button @click="show = false" class="btn btn-xs btn-ghost btn-circle">
                    <x-fas-times class="h-3 w-3" />
                </button>
            </div>
        @endif

        <!-- Notificação de Aviso -->
        @if(session('warning'))
            <div 
                class="alert alert-warning shadow-xl max-h-96 overflow-y-auto"
                x-data="{ show: true }" 
                x-show="show" 
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform -translate-y-2"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform translate-y-0"
                x-transition:leave-end="opacity-0 transform -translate-y-2"
                x-init="setTimeout(() => show = false, 5000)"
            >
                <div class="flex items-start gap-2 flex-1 min-w-0">
                    <x-fas-exclamation-triangle class="h-4 w-4 shrink-0 mt-0.5" />
                    <span class="text-sm wrap-break-word">{{ session('warning') }}</span>
                </div>
                <button @click="show = false" class="btn btn-xs btn-ghost btn-circle">
                    <x-fas-times class="h-3 w-3" />
                </button>
            </div>
        @endif

        <!-- Erros de Validação -->
        @if($errors->any())
            <div 
                class="alert alert-error shadow-xl max-h-96 overflow-y-auto"
                x-data="{ show: true }" 
                x-show="show" 
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform -translate-y-2"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform translate-y-0"
                x-transition:leave-end="opacity-0 transform -translate-y-2"
            >
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 mb-1">
                        <x-fas-exclamation-circle class="h-4 w-4 shrink-0" />
                        <h3 class="font-semibold text-sm">Erro ao processar</h3>
                    </div>
                    <ul class="text-xs ml-6 list-disc list-inside space-y-0.5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <button @click="show = false" class="btn btn-xs btn-ghost btn-circle self-start">
                    <x-fas-times class="h-3 w-3" />
                </button>
            </div>
        @endif
    </div>
</div>