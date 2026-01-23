<x-layout>
    <x-form
        :title="$title"
        :description="$description"
    >
        <form action="{{ $action }}" method="POST" class="mt-10 space-y-4">
            @csrf

            <x-form.field name="name" label="Nome" />
            <x-form.field name="email" type="email" label="Email"/>
            <x-form.field name="password" type="password" label="Password"/>

            @isset($role)
                <input type="hidden" name="role" value="{{ $role }}">
            @endisset

            <button class="btn mt-2 h-10" type="submit">
                {{ $buttonText }}
            </button>
        </form>
    </x-form>
</x-layout>
