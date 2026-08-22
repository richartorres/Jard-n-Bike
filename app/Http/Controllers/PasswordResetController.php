<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password as PasswordFacade;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
    // Mostrar formulario de "olvidé mi contraseña"
    public function showLinkRequestForm() {
        return view('auth.forgot-password');
    }

    // Enviar el correo con el link
    public function sendResetLinkEmail(Request $request) {
        $request->validate(['email' => 'required|email']);

        $status = PasswordFacade::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', 'Hemos enviado un link de recuperación a tu correo.')
            : back()->withErrors(['email' => 'No pudimos encontrar un usuario con ese correo.']);
    }

    // Mostrar formulario para poner la nueva contraseña
    public function showResetForm(Request $request, $token = null) {
        return view('auth.reset-password')->with(['token' => $token, 'email' => $request->email]);
    }

    // Procesar el cambio de contraseña
    public function reset(Request $request) {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        $status = PasswordFacade::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->password = Hash::make($password);
                $user->setRememberToken(Str::random(60));
                $user->save();
            }
        );

        return $status == Password::PASSWORD_RESET
            ? redirect('/login')->with('status', '¡Contraseña actualizada con éxito!')
            : back()->withErrors(['email' => 'El token de recuperación es inválido o ha expirado.']);
    }
}