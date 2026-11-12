<?php

namespace App\Http\Controllers;

use App\Models\NovedadCampo;
use App\Models\Bicicleta;
use App\Models\Estacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OperarioController extends Controller
{
    /**
     * Muestra el panel de operaciones y novedades de campo para los técnicos.
     */
    public function index()
    {
        // Obtener todas las novedades con sus relaciones
        $novedades = NovedadCampo::with(['bicicleta', 'estacion', 'usuario'])
            ->orderBy('created_at', 'desc')
            ->get();

        $bicicletas = Bicicleta::all();
        $estaciones = Estacion::all();

        return view('admin.operaciones', compact('novedades', 'bicicletas', 'estaciones'));
    }

    /**
     * Registra una nueva incidencia o novedad reportada en campo.
     */
    public function store(Request $request)
    {
        $request->validate([
            'bicicleta_id' => 'required|exists:bicicletas,id_bicicleta',
            'estacion_id' => 'required|exists:estaciones,id_estacion',
            'tipo_novedad' => 'required|string',
            'descripcion' => 'required|string',
        ]);

        NovedadCampo::create([
            'bicicleta_id' => $request->bicicleta_id,
            'estacion_id' => $request->estacion_id,
            'user_id' => Auth::id() ?? 1, // Si está autenticado o asignado por defecto
            'tipo_novedad' => $request->tipo_novedad,
            'descripcion' => $request->descripcion,
        ]);

        return back()->with('success', '¡Novedad de campo registrada correctamente!');
    }
}