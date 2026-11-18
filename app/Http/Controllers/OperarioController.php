<?php

namespace App\Http\Controllers;

use App\Models\NovedadCampo;
use App\Models\Bicicleta;
use App\Models\Estacion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OperarioController extends Controller
{
    public function index()
    {
        $novedades = NovedadCampo::with(['bicicleta', 'estacion', 'usuario'])
            ->orderBy('created_at', 'desc')
            ->get();

        $bicicletas = Bicicleta::all();
        $estaciones = Estacion::all();

        return view('admin.operaciones', compact('novedades', 'bicicletas', 'estaciones'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tipo_novedad' => 'required|string',
            'descripcion'  => 'required|string|max:500',
        ]);

        // Asegura un user_id válido
        $userId = Auth::id() ?? User::value('id');

        // Usamos una instancia y save() para evitar cualquier bloqueo de Mass Assignment o nombres de columnas
        $novedad = new NovedadCampo();
        $novedad->id_bicicleta = $request->bicicleta_id ?? $request->id_bicicleta ?? null;
        $novedad->id_estacion  = $request->estacion_id ?? $request->id_estacion ?? null;
        $novedad->user_id      = $userId;
        $novedad->tipo_novedad = $request->tipo_novedad;
        $novedad->descripcion  = $request->descripcion;
        $novedad->save();

        return back()->with('success', '¡Incidencia registrada correctamente!');
    }
}