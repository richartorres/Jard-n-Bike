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
            'id_bicicleta' => 'nullable|exists:bicicletas,id',
            'id_estacion'  => 'nullable|exists:estaciones,id_estacion',
            'tipo_novedad' => 'required|in:Mantenimiento Bici,Falla Estación,Batería Baja',
            'descripcion'  => 'required|string|max:500',
        ]);

        // Asegura un user_id válido que exista en la tabla users
        $userId = Auth::id() ?? User::value('id');

        NovedadCampo::create([
            'id_bicicleta' => $request->id_bicicleta,
            'id_estacion'  => $request->id_estacion,
            'user_id'      => $userId,
            'tipo_novedad' => $request->tipo_novedad,
            'descripcion'  => $request->descripcion,
        ]);

        return back()->with('success', '¡Incidencia registrada y enviada al centro de alertas exitosamente!');
    }

    public function guardar(Request $request)
    {
        return $this->store($request);
    }
}