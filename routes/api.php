<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EstacionController;
use App\Http\Controllers\BicicletaController;


// Ruta por defecto para el usuario autenticado
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Tus nuevas rutas para el sistema de eBikes (CRUD completo)
Route::apiResource('estaciones', EstacionController::class);
Route::apiResource('bicicletas', BicicletaController::class);
Route::get('/bicicletas/qr/{codigo_qr}', [BicicletaController::class, 'consultarPorQr']);