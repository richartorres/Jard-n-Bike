<?php

namespace App\Http\Controllers;

use App\Models\NovedadCampo;
use App\Models\Bicicleta;
use App\Models\Estacion;
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
        $request->validate([
            'bicicleta_id' => 'required',
            'estacion_id' => 'required',
            'tipo_novedad' => 'required|string',
            'descripcion' => 'required|string',
        ]);

        NovedadCampo::create([
            'bicicleta_id' => $request->bicicleta_id,
            'estacion_id' => $request->estacion_id,
            'user_id' => Auth::id() ?? 1,
            'tipo_novedad' => $request->tipo_novedad,
            'descripcion' => $request->descripcion,
        ]);

        return back()->with('success', '¡Novedad de campo registrada correctamente!');
    }
}