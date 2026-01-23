@props(['label', 'name', 'type' => 'text'])

<div class="space-y-2 w-full">
    <label for="{{$name}}" class="floating-label w-full">
        <span>{{$label}}</span>
        <input type="{{$type}}" placeholder="{{$label}}" class="input input-md w-full" id="{{$name}}" name="{{$name}}" value="{{old($name, '')}}" {{$attributes}}/>
    </label>

    @error($name)
    <p class="text-sm text-red-500">{{$message}}</p>
    @enderror
</div>
