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

        // Captura los IDs sin importar cómo los envíe el formulario
        NovedadCampo::create([
            'id_bicicleta' => $request->bicicleta_id ?? $request->id_bicicleta ?? null,
            'id_estacion'  => $request->estacion_id ?? $request->id_estacion ?? null,
            'user_id'      => $userId,
            'tipo_novedad' => $request->tipo_novedad,
            'descripcion'  => $request->descripcion,
        ]);

        return back()->with('success', '¡Incidencia registrada correctamente!');
    }
}