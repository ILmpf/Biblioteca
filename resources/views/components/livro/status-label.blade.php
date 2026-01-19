@props(['status' => true])

@php
    $classes = 'inline-block rounded-full border px-2 py-1 text-xs font-medium';

    if($status === true)
        {
            $classes .= ' bg-green-500/20 text-green-700 border-green-500/50';
        }
    else
        {
            $classes .= ' bg-red-500/20 text-red-700 border-red-500/50';
        }

@endphp

<span {{$attributes(['class' => $classes]) }}>
    {{$slot}}
</span>
