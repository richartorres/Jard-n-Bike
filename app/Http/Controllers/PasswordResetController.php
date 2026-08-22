<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password as PasswordFacade;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

class PasswordResetController extends Controller
{
    // Mostrar formulario de "olvidé mi contraseña"
    public function showLinkRequestForm() {
        return view('auth.forgot-password');
    }

    // Enviar el correo con el link mediante la API HTTP de Resend
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

        // 4. Enviar mediante la API HTTP de Resend (Puerto 443 libre en Render, sin exponer secretos)
        $response = Http::withToken(env('MAIL_PASSWORD'))
            ->post('https://api.resend.com/emails', [
                'from' => 'Jardín Bike <onboarding@resend.dev>',
                'to' => [$user->email],
                'subject' => 'Restablecer contraseña - Jardín Bike',
                'html' => '
                    <div style="font-family: Inter, sans-serif; padding: 20px; color: #101828;">
                        <h2 style="color: #105B3A;">Jardín Bike</h2>
                        <p>Has solicitado restablecer tu contraseña. Haz clic en el siguiente botón para continuar:</p>
                        <a href="' . $url . '" style="display: inline-block; background-color: #FFBC00; color: #101828; padding: 12px 24px; border-radius: 12px; text-decoration: none; font-weight: bold; margin-top: 10px;">Restablecer contraseña</a>
                        <p style="margin-top: 20px; font-size: 12px; color: #667085;">Si no solicitaste este cambio, puedes ignorar este mensaje.</p>
                    </div>
                '
            ]);

        if ($response->successful()) {
            return back()->with('status', 'Hemos enviado un link de recuperación a tu correo.');
        }

       return back()->withErrors(['email' => 'Error de Resend: ' . $response->body()]);
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