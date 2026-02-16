@props(['label' => false, 'name', 'type' => 'text'])

<div class="space-y-2">
    @if($label)
        <label for="{{$name}}" class="floating-label w-full">
            <span>{{$label}}</span>
        </label>
    @endif

    @if($type === 'textarea')
        <textarea
            name="{{$name}}"
            id="{{$name}}"
            class="textarea w-full"
            {{$attributes}}
        >{{old($name)}}</textarea>
    @else
        <input
            type="{{$type}}"
            id="{{$name}}"
            name="{{$name}}"
            class="input input-md w-full"
            value="{{old($name, '')}}"
            {{$attributes}}/>
    @endif

    <x-form.error name="{{$name}}"/>
</div>
