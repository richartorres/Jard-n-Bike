<?php

namespace App\Http\Controllers;

use App\Models\Alquiler;
use App\Models\Bicicleta;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AlquilerController extends Controller
{
   /**
     * Inicia un nuevo alquiler (Punto A)
     */
    public function store(Request $request)
    {
        // 1. Validar los datos mínimos (bicicleta_id debe ser un entero válido)
        $request->validate([
            'user_id' => 'required|integer',
            'bicicleta_id' => 'required|integer', 
            'estacion_origen_id' => 'required|integer',
        ]);

        // 2. Buscar la bicicleta por su ID numérico (llave primaria)
        $bicicleta = Bicicleta::find($request->bicicleta_id);

        if (!$bicicleta) {
            return response()->json(['status' => 'error', 'message' => 'La bicicleta no existe.'], 404);
        }

        // 3. Verificar estado
        if ($bicicleta->estado !== 'Disponible') {
            return response()->json([
                'status' => 'error', 
                'message' => 'Alquiler rechazado: La bicicleta ya está en uso o en mantenimiento.'
            ], 400);
        }

        // 4. REGLA DEL SENA: Validar batería antes de rentar
        if ($bicicleta->nivel_bateria < 20) {
            return response()->json([
                'status' => 'error',
                'message' => 'Alquiler rechazado: La bicicleta tiene batería insuficiente (' . $bicicleta->nivel_bateria . '%).'
            ], 400);
        }

        // 5. Crear el registro del Alquiler
        $alquiler = Alquiler::create([
            'user_id' => $request->user_id,
            'bicicleta_id' => $bicicleta->id_bicicleta, // Usamos el ID correcto de la tabla
            'estacion_origen_id' => $request->estacion_origen_id,
            'fecha_inicio' => Carbon::now(),
            'estado_alquiler' => 'Activo',
            'valor_total' => 0.00
        ]);

        // 6. Actualizar el estado de la bicicleta a "En Uso"
        $bicicleta->update([
            'estado' => 'En Uso'
        ]);

        return response()->json([
            'status' => 'success',
            'message' => '¡Viaje iniciado con éxito! Disfruta de Jardín Bike.',
            'alquiler' => $alquiler
        ], 201);
    }

    /**
     * Finaliza un alquiler activo (Punto B) y calcula el costo
     */
    public function finalizar(Request $request, $id_alquiler)
    {
        // 1. Validar que llegue la estación de destino
        $request->validate([
            'estacion_destino_id' => 'required|integer',
        ]);

        // 2. Buscar el alquiler que esté 'Activo'
        $alquiler = Alquiler::find($id_alquiler);

        if (!$alquiler || $alquiler->estado_alquiler !== 'Activo') {
            return response()->json([
                'status' => 'error',
                'message' => 'El alquiler no existe o ya fue finalizado previamente.'
            ], 404);
        }

        // 3. Capturar la fecha de fin actual
        $fecha_fin = Carbon::now();
        $fecha_inicio = Carbon::parse($alquiler->fecha_inicio);

        // 4. Calcular la diferencia en minutos reales
        $minutos = $fecha_inicio->diffInMinutes($fecha_fin);
        
        // 🚨 REGLA DE NEGOCIO: $4.000 COP por cada 15 minutos o fracción
        // Si el viaje dura 0 minutos (o menos de 1), cobramos una fracción mínima (1)
        $unidades_de_15 = ceil(max($minutos, 1) / 15); 
        $valor_tarifa = $unidades_de_15 * 4000;

        // 5. Actualizar el registro del alquiler
        $alquiler->update([
            'estacion_destino_id' => $request->estacion_destino_id,
            'fecha_fin' => $fecha_fin,
            'valor_total' => $valor_tarifa,
            'estado_alquiler' => 'Completado'
        ]);

        // 6. Liberar la bicicleta: volver a ponerla "Disponible"
        $bicicleta = Bicicleta::find($alquiler->bicicleta_id);
        if ($bicicleta) {
            $bicicleta->update([
                'estado' => 'Disponible',
                'estacion_act' => $request->estacion_destino_id // La bici se queda en la nueva estación
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => '¡Bicicleta entregada con éxito! Alquiler cerrado.',
            'resumen' => [
                'tiempo_total_minutos' => $minutos,
                'fracciones_cobradas' => $unidades_de_15,
                'total_a_pagar' => '$' . number_format($valor_tarifa, 0, ',', '.') . ' COP',
                'alquiler' => $alquiler
            ]
        ], 200);
    }

    public function index() { return response()->json(Alquiler::with(['bicicleta', 'estacionOrigen'])->get(), 200); }
    public function show(string $id) {}
    public function update(Request $request, string $id) {}
    public function destroy(string $id) {}



        /**
     * Obtiene el historial de alquileres de un usuario específico
     */
    public function historialUsuario($user_id)
    {
        // Buscamos los alquileres del usuario trayendo los datos de la bicicleta y la estación de origen
        $historial = Alquiler::where('user_id', $user_id)
            ->with(['bicicleta', 'estacionOrigen'])
            ->orderBy('created_at', 'desc') // Los viajes más recientes primero
            ->get();

        if ($historial->isEmpty()) {
            return response()->json([
                'status' => 'success',
                'message' => 'El usuario no tiene viajes registrados aún.',
                'historial' => []
            ], 200);
        }

        return response()->json([
            'status' => 'success',
            'historial' => $historial
        ], 200);
    }

}