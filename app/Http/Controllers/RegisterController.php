<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validamos que los campos obligatorios cumplan con las reglas y sean únicos en la tabla users
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        // 2. Creamos el usuario en la base de datos de XAMPP con contraseña cifrada
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // 3. Autenticamos al usuario automáticamente tras registrarse y lo mandamos al mapa
        Auth::login($user);

        return redirect('/mapa');
    }
}