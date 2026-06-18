<?php

namespace App\Http\Controllers;

use App\Models\Estacion;
use Illuminate\Http\Request;

class EstacionController extends Controller
{
    /**
     * Lista todas las estaciones activas con sus coordenadas reales de Jardín
     */
    public function index()
    {
        // Traemos solo las estaciones que estén marcadas como Activas
        $estaciones = Estacion::where('estado', 'Activa')->get();

        // Devolvemos la respuesta estructurada en un JSON limpio para la app
        return response()->json([
            'status' => 'success',
            'count' => $estaciones->count(),
            'estaciones' => $estaciones
        ], 200);
    }
}