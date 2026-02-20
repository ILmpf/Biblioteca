<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RegisterUserAction
{
    public function handle(array $data): array
    {
        $role = $data['role'] ?? 'cidadão';

        $password = $role === 'admin' ? Str::password(8) : $data['password'];

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($password),
            'role' => $role,
        ]);

        if ($role === 'cidadão') {
            event(new Registered($user));
            $message = 'Registo efetuado com sucesso! Um email de confirmação foi enviado. Por favor verifica a tua caixa de correio.';
        } else {
            $message = 'Administrador registado com sucesso! Foi enviado um email para confirmar a conta.';
        }

        return [
            'user' => $user,
            'password' => $password,
            'message' => $message,
        ];
    }
}
