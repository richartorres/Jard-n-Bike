<?php

use Illuminate\Support\Facades\Route;
use App\Models\Estacion;
use App\Http\Controllers\BicicletaController;
use App\Http\Controllers\EstacionController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AdminController;
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

// Panel de administración (Conectado al controlador para mostrar datos reales de la BD)
Route::get('/admin', [AdminController::class, 'index'])->name('admin');

Route::post('/bicicletas/store', [BicicletaController::class, 'store'])->name('bicicletas.store');
Route::post('/estaciones/store', [EstacionController::class, 'store'])->name('estaciones.store');

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