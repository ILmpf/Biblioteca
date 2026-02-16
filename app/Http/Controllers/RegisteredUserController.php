<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\RegisterUserAction;
use App\Http\Requests\StoreUserRequest;
use App\Notifications\AdminAccountCreated;
use Illuminate\Support\Facades\Gate;
use \Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register', [
            'title' => 'Regista uma conta',
            'description' => 'Requisita os teus livros hoje.',
            'action' => route('register'),
            'buttonText' => 'Criar Conta',
        ]);
    }

    public function createAdmin(): View
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

    public function store(StoreUserRequest $request, RegisterUserAction $action): RedirectResponse
    {
        $role = $request->input('role', 'cidadão');
        if ($role === 'admin') {
            Gate::authorize('isAdmin');
        }

        $result = $action->handle($request->validated() + ['role' => $role]);

        if ($role === 'admin') {
            $result['user']->notify(new AdminAccountCreated($result['password']));
        }

        return back()->with('success', $result['message']);
    }
}
