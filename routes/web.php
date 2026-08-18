<?php

use Illuminate\Support\Facades\Route;
use App\Models\Estacion;
use App\Http\Controllers\BicicletaController;
use App\Http\Controllers\EstacionController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\AlquilerController;

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
// RUTAS DEL SISTEMA (MAPA Y CLIENTE)
// ==========================================
Route::get('/mapa', function () {
    $estaciones = Estacion::where('estado', 'Activa')->get();
    return view('mapa', compact('estaciones'));
});

// ==========================================
// RUTAS DEL PANEL DE ADMINISTRACIÓN (OPS)
// ==========================================
Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');
Route::get('/inventario', [AdminController::class, 'inventario'])->name('admin.inventario');
Route::get('/estaciones', [AdminController::class, 'estaciones'])->name('admin.estaciones');
Route::get('/usuarios', [AdminController::class, 'usuarios'])->name('admin.usuarios');
Route::get('/alertas', [AdminController::class, 'alertas'])->name('admin.alertas');

// Acciones de administración (Creación y gestión)
Route::post('/bicicletas', [BicicletaController::class, 'store'])->name('bicicletas.store'); // <-- Quitamos el '/store' del endpoint para que sea limpio
Route::post('/estaciones/store', [EstacionController::class, 'store'])->name('estaciones.store');

Route::patch('/bicicletas/{id}/estado', [BicicletaController::class, 'updateEstado'])->name('bicicletas.updateEstado');
Route::delete('/bicicletas/{id}', [BicicletaController::class, 'destroy'])->name('bicicletas.destroy');
// ==========================================
// RUTAS DE ALQUILER (Protegidas por Auth)
// ==========================================
Route::middleware(['auth'])->group(function () {
    // Iniciar alquiler desde el escaneo del QR o ID de la bici
    Route::post('/iniciar-viaje', [AlquilerController::class, 'store']);
    
    // Pantalla de viaje activo
    Route::get('/viaje-activo', [AlquilerController::class, 'viajeActivo']);
    
    // Finalizar viaje pasando el ID exacto del alquiler
    Route::post('/finalizar-viaje/{id}', [AlquilerController::class, 'finalizarViaje']);
});