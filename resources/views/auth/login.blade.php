<x-layout>
    <x-form title="Login" description="Bem vindo de volta.">
        <form action="/login" method="POST" class="mt-10 space-y-4">
            @csrf

            <x-form.field name="email" type="email" label="Email"/>
            <x-form.field name="password" type="password" label="Password"/>

            <button class="btn mt-2 h-10" type="submit" data-test="login-button">Login</button>
        </form>
    </x-form>
</x-layout>
