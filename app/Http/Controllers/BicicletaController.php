<?php

namespace App\Http\Controllers;

use App\Models\Bicicleta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Estacion;

class BicicletaController extends Controller
{
    /**
     * Simula el escaneo de un código QR y valida restricciones del negocio.
     */
    public function consultarPorQr(string $codigo_qr)
    {
        $bicicleta = Bicicleta::where('codigo_qr', $codigo_qr)->first();

        if (!$bicicleta) {
            return response()->json([
                'status' => 'error',
                'message' => 'Código QR inválido. La bicicleta no existe en el sistema.'
            ], 404);
        }

        if ($bicicleta->nivel_bateria < 20) {
            return response()->json([
                'status' => 'error',
                'bateria' => $bicicleta->nivel_bateria . '%',
                'message' => 'Alquiler bloqueado: La bicicleta tiene menos del 20% de batería y requiere carga.'
            ], 400);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Bicicleta apta para alquiler',
            'data' => $bicicleta
        ], 200);
    }

    /**
     * Vista principal del inventario para el panel operativo (Web).
     */
    public function index() 
    {
        $bicicletas = Bicicleta::with('estacionOrigen')->get();
        $estaciones = Estacion::all();
        $user = Auth::user();

        // Si prefieres usar la ruta directa a 'admin.inventario'
        return view('admin.inventario', compact('bicicletas', 'estaciones', 'user'));
    }

    /**
     * Almacena una nueva bicicleta desde el panel operativo (Web).
     */
    public function store(Request $request) 
    {
        $request->validate([
            'codigo_qr' => 'required|unique:bicicletas,codigo_qr',
            'modelo' => 'required',
            'num_serie' => 'required',
        ]);

        Bicicleta::create([
            'codigo_qr' => $request->codigo_qr,
            'modelo' => $request->modelo,
            'num_serie' => $request->num_serie,
            'estacion_act' => 1,          // Estación inicial por defecto
            'nivel_bateria' => 100,       // Carga completa por defecto
            'estado' => 'Disponible',     // Estado inicial
            'kilometraje' => 0.00,        // Kilometraje inicial
        ]);

        return redirect()->back()->with('success', 'Bicicleta registrada correctamente.');
    }

    public function show(string $id) {}

    public function update(Request $request, string $id) {}

    /**
     * Cambia el estado operativo de la bicicleta (Disponible / Mantenimiento).
     */
   public function updateEstado(Request $request, $id)
{
    $bicicleta = Bicicleta::findOrFail($id);

    // Si viene un nuevo estado en la petición, lo actualizamos
    if ($request->has('estado')) {
        $bicicleta->estado = $request->input('estado');
    }

    // Si viene un nivel de batería en la petición, lo actualizamos
    if ($request->has('nivel_bateria')) {
        $bicicleta->nivel_bateria = $request->input('nivel_bateria');
    }

    $bicicleta->save();

    return redirect()->back()->with('success', 'Unidad actualizada y recargada exitosamente.');
}

    /**
     * Elimina una bicicleta del sistema desde el panel de control.
     */
    public function destroy($id)
    {
        $bici = Bicicleta::findOrFail($id);
        $bici->delete();

        return back()->with('success', 'Bicicleta eliminada del sistema.');
    }

    /**
     * Método alternativo de inventario (mapeado a index para evitar conflictos).
     */
    public function inventario()
    {
        return $this->index();
    }
}