<?php

use Illuminate\Support\Facades\Route;
use App\Models\Estacion;
use Illuminate\Http\Request;
use App\Http\Controllers\LoginController;    // <--- Esta línea es la que evita el error del editor
use App\Http\Controllers\RegisterController; 




// ==========================================
// RUTAS DE VISTAS GENERALES Y LANDING
// ==========================================
Route::get('/', function () {
    return view('landing');
});

Route::get('/landing', function () {
    return view('landing');
})->name('landing');

// ==========================================
// RUTAS DE AUTENTICACIÓN (LOGIN Y REGISTRO)
// ==========================================

// Ver formulario de Login
Route::get('/login', function () {
    return view('login');
});

// Procesar datos del Login contra la BD de XAMPP
Route::post('/login', [LoginController::class, 'login']); 

// Ver formulario de Registro
Route::get('/registro', function () {
    return view('registro');
});

// Procesar el Registro y guardar en la BD de XAMPP
Route::post('/registro', [RegisterController::class, 'store']);

// ==========================================
// RUTAS DEL SISTEMA (MAPA, VIAJES Y ADMIN)
// ==========================================

// 🗺️ Pantalla del mapa (Carga estaciones activas desde la BD)
Route::get('/mapa', function () {
    $estaciones = Estacion::where('estado', 'Activa')->get();
    return view('mapa', compact('estaciones'));
});

// 🚀 Ruta para procesar el inicio del viaje
Route::post('/iniciar-viaje', function (Request $request) {
    return response()->json(['status' => 'viaje_iniciado']);
});

// 🚲 Ruta para la pantalla de viaje en curso
Route::get('/viaje-activo', function () {
    return view('viaje_activo');
});

// Panel de administración
Route::get('/admin', function () {
    return view('admi');
})->name('admin');