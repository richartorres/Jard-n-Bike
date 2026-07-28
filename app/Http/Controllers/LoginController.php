<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        // 1. Validamos usando 'email' (que sí existe en tu tabla users)
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 2. Intentamos loguear comparando contra la base de datos
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Si es correcto, lo mandamos al mapa que ya configuraste
            return redirect()->intended('/mapa');
        }

        // 3. Si falla, regresa al login con un mensaje de error
        return back()->withErrors([
            'email' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }
}