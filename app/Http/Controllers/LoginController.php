<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        // 1. Validamos usando 'email'
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 2. Intentamos loguear comparando contra la base de datos
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            // 🔍 VALIDACIÓN DE ROL: Si es operario, va directo a su panel operativo
            if ($user->role === 'operario') {
                return redirect()->intended('/operaciones');
            }

            // Si el usuario autenticado es admin, va al panel de control
            if ($user->role === 'admin') {
                return redirect()->intended('/admin');
            }

            // Si es un cliente u otro rol normal, lo mandamos al mapa
            return redirect()->intended('/mapa');
        }

        // 3. Si falla, regresa al login con un mensaje de error
        return back()->withErrors([
            'email' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }
}