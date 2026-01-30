@props(['status' => 'active'])

@php
    $base = 'inline-block rounded-full px-3 py-1 text-xs font-medium';
    $classes = $base;

    if ($status === 'active') {
        $classes .= ' bg-yellow-50 text-yellow-700 border border-yellow-400';
    } elseif ($status === 'completed') {
        $classes .= ' bg-green-50 text-green-700 border border-green-400';
    } elseif ($status === 'cancelled') {
        $classes .= ' bg-red-50 text-red-700 border border-red-400';
    }

@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
  {{$slot}}
</span>
