<?php

use Illuminate\Support\Facades\Route;
use App\Models\Estacion;
use Illuminate\Http\Request;

// Ruta de bienvenida por defecto de Laravel
Route::get('/', function () {
    return view('welcome');
});

// 🗺️ Tu ruta para renderizar la pantalla del mapa
Route::get('/mapa', function () {
    // Tomamos las estaciones activas de la base de datos para pasárselas a la vista
    $estaciones = Estacion::where('estado', 'Activa')->get();
    return view('mapa', compact('estaciones'));
});

// 🚀 Ruta para procesar el inicio del viaje desde el escáner
Route::post('/iniciar-viaje', function (Request $request) {
    // Aquí crearías el registro en la base de datos (tabla 'viajes') en el futuro
    return response()->json(['status' => 'viaje_iniciado']);
});

// 🚲 Ruta para la pantalla de viaje en curso
Route::get('/viaje-activo', function () {
    return view('viaje_activo'); // Esta pantalla la crearemos enseguida
});