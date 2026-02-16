@props([
    'label',
    'name',
    'type' => 'text',
    'icon' => null,
    'placeholder' => '',
    'required' => false,
    'autofocus' => false,
])

<div class="form-control" @if($type === 'password') x-data="{ showPassword: false }" @endif>
    <label for="{{ $name }}" class="label">
        <span class="label-text font-medium">{{ $label }}</span>
    </label>
    <div class="relative">
        @if($icon)
            <span class="absolute inset-y-0 left-0 flex items-center pl-4">
                <x-dynamic-component :component="$icon" class="h-5 w-5 text-base-content/40" />
            </span>
        @endif
        <input
            @if($type === 'password')
                :type="showPassword ? 'text' : 'password'"
            @else
                type="{{ $type }}"
            @endif
            id="{{ $name }}"
            name="{{ $name }}"
            placeholder="{{ $placeholder }}"
            value="{{ old($name) }}"
            class="input input-bordered w-full @if($type === 'password') pr-12 @endif @error($name) input-error @enderror"
            {{ $required ? 'required' : '' }}
            {{ $autofocus ? 'autofocus' : '' }}
            {{ $attributes }}
        />
        @if($type === 'password')
            <button
                type="button"
                @click="showPassword = !showPassword"
                class="absolute inset-y-0 right-0 flex items-center pr-4 text-base-content/60 hover:text-base-content transition-colors"
            >
                <x-fas-eye x-show="!showPassword" class="h-5 w-5" />
                <x-fas-eye-slash x-show="showPassword" class="h-5 w-5" />
            </button>
        @endif
    </div>
    @error($name)
        <label class="label">
            <span class="label-text-alt text-red-500">{{ $message }}</span>
        </label>
    @enderror
</div>