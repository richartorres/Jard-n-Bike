<?php

use Illuminate\Support\Facades\Route;
use App\Models\Estacion;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\AlquilerController; // <--- Importante importar el controlador

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

Route::get('/login', function () {
    return view('login');
});
Route::post('/login', [LoginController::class, 'login']); 

Route::get('/registro', function () {
    return view('registro');
});
Route::post('/registro', [RegisterController::class, 'store']);

// ==========================================
// RUTAS DEL SISTEMA (MAPA, VIAJES Y ADMIN)
// ==========================================

// 🗺️ Pantalla del mapa (Carga estaciones activas desde la BD)
Route::get('/mapa', function () {
    $estaciones = Estacion::where('estado', 'Activa')->get();
    return view('mapa', compact('estaciones'));
});

// Panel de administración
Route::get('/admin', function () {
    return view('admi');
})->name('admin');

// ==========================================
// RUTAS DE ALQUILER (Protegidas por Auth)
// ==========================================
Route::middleware(['auth'])->group(function () {
    // 🚀 Iniciar alquiler desde el escaneo del QR o ID de la bici
    Route::post('/iniciar-viaje', [AlquilerController::class, 'store']);
    
    // 🚲 Pantalla de viaje activo (con mapa interactivo Leaflet y estaciones)
    Route::get('/viaje-activo', [AlquilerController::class, 'viajeActivo']);
    
    // 💵 Finalizar viaje pasando el ID exacto del alquiler para registrar la estación de destino
    Route::post('/finalizar-viaje/{id}', [AlquilerController::class, 'finalizarViaje']);
});