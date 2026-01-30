@props([
    'active' => false,
    'align' => 'end',
])

<div {{ $attributes->merge([
    'class' => "dropdown dropdown-{$align}"
]) }}>
    <label
        tabindex="0"
        class="btn btn-ghost gap-1 flex items-center
               {{ $active ? 'text-primary' : '' }}"
    >
        {{ $trigger }}

        <x-icons.arrow-down
            class="w-4 h-4 transition-transform ui-open:rotate-180"
        />
    </label>

    <ul
        tabindex="0"
        class="menu dropdown-content mt-2 p-2 shadow bg-base-200
               rounded-box border border-accent z-[50]"
    >
        {{ $slot }}
    </ul>
</div>
