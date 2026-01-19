<a {{ $attributes(['class' => 'card card-side bg-base-100 shadow-sm']) }}>
    <figure class="w-48 h-48 overflow-hidden">
            {{$image}}
    </figure>
    <div class="card-body">
        <h2 class="card-title">{{$title}}</h2>
        {{$slot}}
        <div class="card-actions justify-end">
            {{$actions}}
        </div>
    </div>
</a>
