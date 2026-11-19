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
            'tipo_novedad' => 'required|in:Mantenimiento Bici,Falla Estación,Batería Baja',
            'bicicleta_id' => 'nullable|required_if:tipo_novedad,Mantenimiento Bici,Batería Baja|exists:bicicletas,id_bicicleta',
            'estacion_id'  => 'nullable|required_if:tipo_novedad,Falla Estación|exists:estaciones,id_estacion',
            'descripcion'  => 'required|string|max:500',
        ]);

        $userId = Auth::id() ?? User::value('id');

        NovedadCampo::create([
            'bicicleta_id' => $request->filled('bicicleta_id') ? $request->bicicleta_id : null,
            'estacion_id'  => $request->filled('estacion_id') ? $request->estacion_id : null,
            'user_id'      => $userId,
            'tipo_novedad' => $request->tipo_novedad,
            'descripcion'  => $request->descripcion,
            'created_at'   => now(),
        ]);

        return back()->with('success', '¡Incidencia registrada correctamente!');
    }
}