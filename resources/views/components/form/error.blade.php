@props(['name'])

@error($name)
<p class="text-sm text-red-500">{{$message}}</p>
@enderror
