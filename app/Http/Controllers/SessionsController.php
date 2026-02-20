<?php

declare(strict_types=1);

namespace App\Http\Controllers;

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
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'max:255'],
        ]);

        $remember = $request->boolean('remember');

        if (! Auth::attempt($credentials, $remember)) {
            return back()
                ->withErrors(['email' => 'As credenciais fornecidas não correspondem aos nossos registos.'])
                ->withInput($request->only('email'));
        }

        $user = Auth::user();

        if (! $user->hasVerifiedEmail()) {
            Auth::logout();

            return back()
                ->withErrors(['email' => 'Precisas de confirmar a tua conta antes de iniciar sessão.'])
                ->withInput($request->only('email'));
        }

        $request->session()->regenerate();

        return redirect()->intended('/')->with('success', 'Login efetuado.');
    }

    public function destroy(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
