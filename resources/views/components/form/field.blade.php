@props(['label', 'name', 'type' => 'text'])

<div class="space-y-2">
    <label for="{{$name}}" class="floating-label">
        <span>{{$label}}</span>
        <input type="{{$type}}" placeholder="{{$label}}" class="input input-md" id="{{$name}}" name="{{$name}}" value="{{old($name, '')}}" {{$attributes}}/>
    </label>

    @error($name)
    <p class="text-sm text-red-500">{{$message}}</p>
    @enderror
</div>
