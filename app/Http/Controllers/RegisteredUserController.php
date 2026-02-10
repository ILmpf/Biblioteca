<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class RegisteredUserController extends Controller
{
    public function create()
    {
        return view('auth.register', [
            'title' => 'Regista uma conta',
            'description' => 'Requisita os teus livros hoje.',
            'action' => route('register'),
            'buttonText' => 'Criar Conta',
        ]);
    }

    public function createAdmin()
    {
        Gate::authorize('isAdmin');

        return view('auth.register', [
            'title' => 'Criar Administrador',
            'description' => 'Cria um novo utilizador administrador.',
            'action' => route('register'),
            'buttonText' => 'Criar Admin',
            'role' => 'admin',
        ]);
    }

    public function store(Request $request)
    {
        $role = 'cidadão';

        if ($request->role === 'admin') {
            Gate::authorize('isAdmin');
            $role = 'admin';
        }

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')],
        ];

        if ($role === 'cidadão') {
            $rules['password'] = ['required', 'string', 'min:8', 'max:255'];
        }

        $validated = $request->validate($rules);

        $password = $role === 'admin' ? Str::random(8) : $validated['password'];

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($password),
            'role' => $role,
        ]);

        if ($role === 'cidadão') {
            event(new Registered($user));

            return back()->with('success', 'Registo efetuado com sucesso! Um email de confirmação foi enviado. Por favor verifica a tua caixa de correio.');
        }

        return back()->with('success', 'Admin registado com sucesso!');
    }
}
