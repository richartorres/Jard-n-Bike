<?php

namespace App\Http\Controllers;

use App\Models\NovedadCampo;
use App\Models\Bicicleta;
use App\Models\Estacion;
use App\Models\User; // <-- Asegúrate de tener esta línea importada
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OperarioController extends Controller
{
    public function index()
    {
        $novedades = NovedadCampo::with(['bicicleta', 'estacion', 'usuario'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Obtenemos los registros asegurando compatibilidad con los nombres reales de tus tablas
        $bicicletas = Bicicleta::all();
        $estaciones = Estacion::all();

        return view('admin.operaciones', compact('novedades', 'bicicletas', 'estaciones'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'tipo_novedad' => 'required|string',
                'descripcion'  => 'required|string|max:500',
            ]);

            $userId = Auth::id() ?? \App\Models\User::value('id');

            NovedadCampo::create([
                'id_bicicleta' => $request->id_bicicleta ?? $request->bicicleta_id ?? null,
                'id_estacion'  => $request->id_estacion ?? $request->estacion_id ?? null,
                'user_id'      => $userId,
                'tipo_novedad' => $request->tipo_novedad,
                'descripcion'  => $request->descripcion,
            ]);

            return back()->with('success', '¡Incidencia registrada correctamente!');
            
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al guardar: ' . $e->getMessage()]);
        }
    }
}