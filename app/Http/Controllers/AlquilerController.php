<?php

namespace App\Http\Controllers;

use App\Models\Alquiler;
use App\Models\Bicicleta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AlquilerController extends Controller
{
    /**
     * Inicia un nuevo alquiler al escanear el QR o ID de la bicicleta
     */
    public function store(Request $request)
    {
        // 1. Validar que llegue el código o ID de la bicicleta
        $request->validate([
            'bicicleta_id' => 'required',
        ]);

        // Asegurarnos de que haya un usuario autenticado
        if (!Auth::check()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Debes iniciar sesión para alquilar una bicicleta.'
            ], 401);
        }

        $userId = Auth::id();

        // 2. Buscar la bicicleta por ID o por Código QR exacto
        $bicicleta = Bicicleta::where('id_bicicleta', $request->bicicleta_id)
            ->orWhere('codigo_qr', $request->bicicleta_id)
            ->first();

        if (!$bicicleta) {
            return response()->json([
                'status' => 'error', 
                'message' => 'La bicicleta no existe o el código QR es inválido.'
            ], 404);
        }

        // 3. Verificar si el usuario ya tiene un alquiler activo
        $alquilerActivo = Alquiler::where('user_id', $userId)
            ->where('estado_alquiler', 'Activo')
            ->first();

        if ($alquilerActivo) {
            return response()->json([
                'status' => 'error',
                'message' => 'Ya tienes un viaje activo en curso.'
            ], 400);
        }

        // 4. Verificar estado de la bicicleta
        if ($bicicleta->estado !== 'Disponible') {
            return response()->json([
                'status' => 'error', 
                'message' => 'Alquiler rechazado: La bicicleta ya está en uso o en mantenimiento.'
            ], 400);
        }

        // 5. Regla de negocio: Validar batería mínima (20%)
        if ($bicicleta->nivel_bateria < 20) {
            return response()->json([
                'status' => 'error',
                'message' => 'Alquiler rechazado: La bicicleta tiene batería insuficiente (' . $bicicleta->nivel_bateria . '%).'
            ], 400);
        }

        // 6. Crear el registro del Alquiler en la base de datos
        $alquiler = Alquiler::create([
            'user_id' => $userId,
            'bicicleta_id' => $bicicleta->id_bicicleta,
            'estacion_origen_id' => $bicicleta->estacion_act,
            'fecha_inicio' => Carbon::now(),
            'estado_alquiler' => 'Activo',
            'valor_total' => 0.00
        ]);

        // 7. Actualizar el estado de la bicicleta a "En uso"
        $bicicleta->update([
            'estado' => 'En uso'
        ]);

        return response()->json([
            'status' => 'success',
            'message' => '¡Viaje iniciado con éxito!',
            'redirect' => url('/viaje-activo')
        ], 201);
    }

    /**
     * Finaliza el alquiler activo del usuario logueado
     */
    public function finalizar(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['status' => 'error', 'message' => 'No autorizado'], 401);
        }

        $userId = Auth::id();

        // Buscar automáticamente el alquiler activo de este usuario
        $alquiler = Alquiler::where('user_id', $userId)
            ->where('estado_alquiler', 'Activo')
            ->latest('fecha_inicio')
            ->first();

        if (!$alquiler) {
            return response()->json([
                'status' => 'error',
                'message' => 'No tienes ningún alquiler activo para finalizar.'
            ], 404);
        }

        // Capturar fechas y calcular tiempo transcurrido
        $fechaFin = Carbon::now();
        $fechaInicio = Carbon::parse($alquiler->fecha_inicio);
        $minutos = $fechaInicio->diffInMinutes($fechaFin);
        
        // REGLA DE NEGOCIO: $4.000 COP por cada 15 minutos o fracción
        $unidadesDe15 = ceil(max($minutos, 1) / 15); 
        $valorTarifa = $unidadesDe15 * 4000;

        // Actualizar el registro del alquiler
        $alquiler->update([
            'estacion_destino_id' => $request->estacion_destino_id ?? $alquiler->estacion_origen_id,
            'fecha_fin' => $fechaFin,
            'valor_total' => $request->valor_total ?? $valorTarifa, // Acepta el cálculo del front o lo recalcula
            'estado_alquiler' => 'Completado'
        ]);

        // Liberar la bicicleta: volver a ponerla "Disponible" en su estación actual o de origen
        $bicicleta = Bicicleta::find($alquiler->bicicleta_id);
        if ($bicicleta) {
            $bicicleta->update([
                'estado' => 'Disponible',
                'estacion_act' => $alquiler->estacion_origen_id 
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => '¡Viaje finalizado con éxito!',
            'redirect' => url('/mapa'),
            'resumen' => [
                'tiempo_minutos' => $minutos,
                'total_pagar' => $valorTarifa
            ]
        ], 200);
    }

    /**
     * Muestra la vista del viaje activo con los datos reales del usuario
     */
    public function mostrarViajeActivo()
    {
        $alquiler = Alquiler::where('user_id', Auth::id())
            ->where('estado_alquiler', 'Activo')
            ->with(['bicicleta', 'estacionOrigen'])
            ->latest('fecha_inicio')
            ->first();

        // Si no hay viaje activo, redirigir al mapa
        if (!$alquiler) {
            return redirect('/mapa');
        }

        return view('viaje-activo', compact('alquiler'));
    }

    /**
     * Historial de usuario
     */
    public function historialUsuario($user_id)
    {
        $historial = Alquiler::where('user_id', $user_id)
            ->with(['bicicleta', 'estacionOrigen'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'historial' => $historial
        ], 200);
    }

    public function index() { 
        return response()->json(Alquiler::with(['bicicleta', 'estacionOrigen'])->get(), 200); 
    }
}