<?php

use Illuminate\Support\Facades\Route;
use App\Models\Estacion;
use Illuminate\Http\Request;
use App\Http\Controllers\LoginController;

// Ruta de aterrizaje (Landing Page)
Route::get('/', function () {
    return view('landing');
});

// 🗺️ Pantalla del mapa
Route::get('/mapa', function () {
    $estaciones = Estacion::where('estado', 'Activa')->get();
    return view('mapa', compact('estaciones'));
});

// Rutas de Registro y Autenticación
Route::get('/registro', function () {
    return view('registro');
});

// Para ver el formulario de login
Route::get('/login', function () {
    return view('login');
});

// Lógica de inicio de sesión
Route::post('/login', [LoginController::class, 'login']); 

// 🚀 Ruta para procesar el inicio del viaje
Route::post('/iniciar-viaje', function (Request $request) {
    return response()->json(['status' => 'viaje_iniciado']);
});

// 🚲 Ruta para la pantalla de viaje en curso
Route::get('/viaje-activo', function () {
    return view('viaje_activo');
});

Route::get('/admi', function () {
    return view('admi');
});