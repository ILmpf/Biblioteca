<x-layout>
    <x-form title="Regista uma conta" description="Requisita os teus livros hoje.">
        <form action="/register" method="POST" class="mt-10 space-y-4">
            @csrf

            <x-form.field name="name" label="Nome" />
            <x-form.field name="email" type="email" label="Email"/>
            <x-form.field name="password" type="password" label="Password"/>

            <button class="btn mt-2 h-10" type="submit">Criar Conta</button>
        </form>
    </x-form>
</x-layout>
