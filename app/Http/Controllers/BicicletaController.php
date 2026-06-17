<?php

namespace App\Http\Controllers;

use App\Models\Bicicleta;
use Illuminate\Http\Request;

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
            ], 400); // 400 Bad Request (Petición incorrecta por lógica de negocio)
        }

        // Si pasa la validación, la entrega con éxito
        return response()->json([
            'status' => 'success',
            'message' => 'Bicicleta apta para alquiler',
            'data' => $bicicleta
        ], 200);
    }

    public function index() { return response()->json(Bicicleta::all(), 200); }
    public function store(Request $request) {}
    public function show(string $id) {}
    public function update(Request $request, string $id) {}
    public function destroy(string $id) {}
}