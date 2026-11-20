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

        $bicicletaId = $request->filled('bicicleta_id') ? (int) $request->bicicleta_id : null;

        $novedad = NovedadCampo::create([
            'bicicleta_id' => $bicicletaId,
            'estacion_id'  => $request->filled('estacion_id') ? (int) $request->estacion_id : null,
            'user_id'      => $userId,
            'tipo_novedad' => $request->tipo_novedad,
            'descripcion'  => $request->descripcion,
            'created_at'   => now(),
        ]);

        if ($bicicletaId) {
            $bicicleta = Bicicleta::where('id_bicicleta', $bicicletaId)->first();

            if ($bicicleta) {
                $bicicleta->estado = 'Mantenimiento';

                if ($request->tipo_novedad === 'Batería Baja' && (int) $bicicleta->nivel_bateria >= 20) {
                    $bicicleta->nivel_bateria = 15;
                }

                $bicicleta->save();
            }
        }
        
        session()->flash('ultima_novedad_id', $novedad->id_novedad ?? $novedad->id);
        return back()->with('success', '¡Incidencia registrada correctamente!');
    }
}