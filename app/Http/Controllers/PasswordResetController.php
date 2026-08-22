<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password as PasswordFacade;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class PasswordResetController extends Controller
{
    // Mostrar formulario de "olvidé mi contraseña"
    public function showLinkRequestForm() {
        return view('auth.forgot-password');
    }

    // Enviar el correo con el link usando el sistema SMTP estándar de Laravel (Brevo)
    public function sendResetLinkEmail(Request $request) {
        $request->validate(['email' => 'required|email']);

        // 1. Verificar si el usuario existe en la base de datos
        $user = \App\Models\User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'No pudimos encontrar un usuario con ese correo.']);
        }

        // 2. Generar el token usando el Password Broker
        $token = Password::createToken($user);

        // 3. Crear la URL absoluta segura
        $url = url(route('password.reset', ['token' => $token, 'email' => $user->email], false));

        // 4. Enviar mediante el sistema de correo SMTP configurado
        try {
            Mail::send('auth.emails.reset-password', ['url' => $url, 'user' => $user], function ($message) use ($user) {
                $message->to($user->email);
                $message->subject('Restablecer contraseña - Jardín Bike');
            });

            return back()->with('status', 'Hemos enviado un link de recuperación a tu correo.');
        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'No se pudo enviar el correo en este momento. Inténtalo más tarde.']);
        }
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