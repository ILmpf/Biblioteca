@props(['name', 'title'])

<div
    x-data="{ show: false, name: @js($name) }"
    x-show="show"
    @open-modal.window="if($event.detail === name) show = true;"
    @close-modal="show = false"
    @keydown.esc.window="show = false"
    x-transition:enter="ease-out duration-200"
    x-transition:enter-start="opacity-0 -translate-y-4 -translate-x-4"
    x-transition:enter-end="opacity-100"
    x-transition:leave="ease-in duration-150"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0 -translate-y-4 -translate-x-4"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-xs"
    role="dialog"
    aria-modal="true"
    aria-labelledby="modal-{{$name}}-title"
    :aria-hidden="!show"
    tabindex="-1"
>

    <div  @click.away="show = false" class="card max-w-3xl w-full max-h-[80dvh] overflow-auto bg-base-100 card-md shadow-sm">
        <div class="card-body justify-between">
            <div class="flex items-center justify-between">
                <h2 id="modal-{{$name}}-title" class="card-title">{{$title}}</h2>

                <button @click="show = false" aria-label="Close modal" class="cursor-pointer">
                    <x-fas-window-close class="w-auto h-6" />
                </button>
            </div>

            <div class="card-actions w-full">
                {{$slot}}
            </div>

        </div>
    </div>

</div>
