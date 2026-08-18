<?php

namespace App\Http\Controllers;

use App\Models\Bicicleta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BicicletaController extends Controller
{
    /**
     * Simula el escaneo de un código QR y valida restricciones del negocio (SENA).
     */
    public function consultarPorQr(string $codigo_qr)
    {
        // Buscamos la bicicleta por su código QR
        $bicicleta = Bicicleta::where('codigo_qr', $codigo_qr)->first();

        // Si la bicicleta no existe, disparamos error 404
        if (!$bicicleta) {
            return response()->json([
                'status' => 'error',
                'message' => 'Código QR inválido. La bicicleta no existe en el sistema.'
            ], 404);
        }

        // REGLA DEL NEGOCIO: Bloquear si tiene menos del 20% de batería
        if ($bicicleta->nivel_bateria < 20) {
            return response()->json([
                'status' => 'error',
                'bateria' => $bicicleta->nivel_bateria . '%',
                'message' => 'Alquiler bloqueado: La bicicleta tiene menos del 20% de batería y requiere carga.'
            ], 400); // 400 Bad Request
        }

        // Si pasa la validación, la entrega con éxito
        return response()->json([
            'status' => 'success',
            'message' => 'Bicicleta apta para alquiler',
            'data' => $bicicleta
        ], 200);
    }

    public function index() 
    { 
        return response()->json(Bicicleta::all(), 200); 
    }

    /**
     * Almacena una nueva bicicleta desde el panel operativo (Web).
     */
    public function store(Request $request) 
    {
        // Validamos los campos que vienen del modal
        $request->validate([
            'codigo_qr' => 'required|unique:bicicletas,codigo_qr',
            'modelo' => 'required',
            'num_serie' => 'required',
        ]);

        // Creamos el registro en la base de datos con los valores por defecto requeridos por tu tabla
        Bicicleta::create([
            'codigo_qr' => $request->codigo_qr,
            'modelo' => $request->modelo,
            'num_serie' => $request->num_serie,
            'estacion_act' => 1,          // Asignada por defecto a la estación inicial
            'nivel_bateria' => 100,       // Nueva bicicleta con carga completa
            'estado' => 'Disponible',     // Lista para ser usada por un usuario
            'kilometraje' => 0.00,        // Kilometraje inicial en cero
        ]);

        // Redirige de vuelta al panel para que se vea reflejada inmediatamente
        return redirect()->back();
    }

    public function show(string $id) {}

    public function update(Request $request, string $id) {}

    /**
     * Cambia el estado operativo de la bicicleta (Ej. Disponible / Mantenimiento).
     */
    public function updateEstado(Request $request, $id)
    {
        $bici = Bicicleta::findOrFail($id);
        $bici->estado = $request->estado;
        $bici->save();

        return back()->with('success', 'Estado de la bicicleta actualizado.');
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

    public function inventario()
    {
        // Traemos las bicicletas con su relación de estación y ordenadas por ID
        $bicicletas = Bicicleta::with('estacionOrigen')->get();
        
        // Puedes mandar datos del usuario autenticado si lo requieres
        $user = Auth::user();

        return view('admin.inventario', compact('bicicletas', 'user'));
    }
}