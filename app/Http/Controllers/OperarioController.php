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
            'bicicleta_id' => 'nullable|exists:bicicletas,id_bicicleta',
            'estacion_id'  => 'nullable|exists:estaciones,id_estacion',
            'tipo_novedad' => 'required|in:Mantenimiento Bici,Falla Estación,Batería Baja',
            'descripcion'  => 'required|string',
        ]);

        // Asegura un user_id válido que exista en la tabla users
        $userId = Auth::id() ?? User::value('id');

        NovedadCampo::create([
            'bicicleta_id' => $request->bicicleta_id,
            'estacion_id'  => $request->estacion_id,
            'user_id'      => $userId,
            'tipo_novedad' => $request->tipo_novedad,
            'descripcion'  => $request->descripcion,
        ]);

        return back()->with('success', '¡Novedad de campo registrada correctamente!');
    }

    public function guardar(Request $request)
    {
        return $this->store($request);
    }
}