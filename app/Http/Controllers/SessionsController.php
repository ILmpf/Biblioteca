<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionsController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {
        $attributes = $request->validate([
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'max:255'],
        ]);

        $user = User::where('email', $attributes['email'])->first();

        if (! $user) {
            return back()
                ->withErrors(['email' => 'Não foi encontrada nenhuma conta com estas credenciais.'])
                ->withInput();
        }

        if (! $user->hasVerifiedEmail()) {
            return back()
                ->withErrors(['email' => 'Precisas de confirmar a tua antes de iniciar sessão.'])
                ->withInput();
        }

        $remember = $request->boolean('remember');

        if (! Auth::attempt($attributes, $remember)) {
            return back()
                ->withErrors(['password' => 'Não foi possível autenticar-te com as credenciais apresentadas.'])
                ->withInput();

        }
        $request->session()->regenerate();

        if (! $remember) {
            $user->setRememberToken(null);
            $user->save();

            cookie()->queue(cookie()->forget(Auth::getRecallerName()));
        }

        return redirect()->intended('/')->with('success', 'Login efetuado.');
    }

    public function destroy(Request $request)
    {
        auth()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
