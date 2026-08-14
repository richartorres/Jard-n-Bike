<?php

namespace App\Http\Controllers;

use App\Models\Bicicleta;
use App\Models\Alquiler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function index()
    {
        // 1. Datos del usuario autenticado
        $user = Auth::user();

        // Verificar si está logueado y si su rol es administrador
        if (!$user || $user->role !== 'admin') {
            return redirect('/mapa')->with('error', 'No tienes permisos de administrador.');
        }

        // 2. Tarjetas de Estadísticas (Dinámicas)
        $bicicletasActivas = Bicicleta::where('estado', 'En uso')->count();
        
        // Viajes realizados hoy
        $viajesHoy = Alquiler::whereDate('created_at', Carbon::today())->count();
        
        // Alertas críticas (Bicicletas con batería menor a 20% o en mantenimiento)
        $alertasCriticas = Bicicleta::where('nivel_bateria', '<', 20)
            ->orWhere('estado', 'Mantenimiento')
            ->count();

        // Ingresos del día sumando los valores totales de los alquileres completados hoy
        $ingresosHoy = Alquiler::whereDate('created_at', Carbon::today())
            ->where('estado_alquiler', 'Completado')
            ->sum('valor_total');

        // 3. Control de inventario 
        $bicicletas = Bicicleta::with('estacionOrigen')->take(10)->get(); 

        return view('admi', compact(
            'user', 
            'bicicletasActivas', 
            'viajesHoy', 
            'alertasCriticas', 
            'ingresosHoy', 
            'bicicletas'
        ));
    }
}