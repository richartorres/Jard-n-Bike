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

    /**
     * Almacena una nueva estación desde el panel operativo (Web).
     */
    public function store(Request $request)
    {
        // Validamos los datos incluyendo las coordenadas
        $request->validate([
            'codigo' => 'required|unique:estaciones,codigo',
            'nombre' => 'required',
            'direccion' => 'required',
            'capacidad' => 'required|integer',
            'coordenadas' => 'required',
        ]);

        // Creamos el registro mapeando exactamente las columnas de tu tabla
        Estacion::create([
            'codigo' => $request->codigo,
            'nombre' => $request->nombre,
            'direccion' => $request->direccion,
            'coordenadas' => $request->coordenadas, // Captura las coordenadas del formulario
            'capacidad' => $request->capacidad,
            'energia_disp' => 100,                // Energía inicial al 100%
            'estado' => 'Activa',                 // Estado activo por defecto
        ]);

        // Redirige de vuelta al panel operativo
        return redirect()->back();
    }
}