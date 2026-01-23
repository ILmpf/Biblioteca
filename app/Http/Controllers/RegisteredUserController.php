<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
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
            'action' => route('admin.users.store'),
            'buttonText' => 'Criar Admin',
            'role' => 'admin',
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')],
            'password' => ['required', 'string', 'min:8', 'max:255'],
        ]);

        $role = 'cidadão';

        if ($request->role === 'admin') {
            Gate::authorize('isAdmin');
            $role = 'admin';
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $role,
        ]);

        if ($role === 'cidadão') {
            Auth::login($user);

            return redirect('/')->with('success', 'Registo efetuado com sucesso!');
        }

        return back()->with('success', 'Admin registado com sucesso!');
    }
}
