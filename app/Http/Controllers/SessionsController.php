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
        $attributes = $request->validate([
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'max:255'],
        ]);

        if (! Auth::attempt($attributes)) {
            return back()
                ->withErrors(['password' => 'Não foi possível autenticar-te com as credenciais apresentadas.'])
                ->withInput();

        }
        $request->session()->regenerate();

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
