@props([
    'field',
    'successMessage' => null,
    'condition' => null,
])

<template x-if="{{ $condition ? $condition . ' && ' : '' }}errors.{{ $field }}">
    <label class="label">
        <span class="label-text-alt text-red-500 flex items-center gap-1">
            <x-fas-exclamation-circle class="h-3 w-3" />
            <span x-text="errors.{{ $field }}"></span>
        </span>
    </label>
</template>

@if($successMessage)
<template x-if="{{ $condition ? $condition . ' && ' : '' }}form.{{ $field }} && !errors.{{ $field }}">
    <label class="label">
        <span class="label-text-alt text-green-500 flex items-center gap-1">
            <x-fas-check-circle class="h-3 w-3" />
            {{ $successMessage }}
        </span>
    </label>
</template>
@endif
