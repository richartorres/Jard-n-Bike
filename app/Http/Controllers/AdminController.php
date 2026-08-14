<?php

namespace App\Http\Controllers;

use App\Models\Bicicleta;
use App\Models\Alquiler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AdminController extends Controller
{
    /**
     * Método principal del panel de administración
     */
    public function index()
    {
        return $this->cargarDatosPanel();
    }

    /**
     * Método alternativo por si la ruta apunta a dashboard
     */
    public function dashboard()
    {
        return $this->cargarDatosPanel();
    }

    /**
     * Lógica central para recopilar todos los datos de la base de datos
     */
    private function cargarDatosPanel()
    {
        // 1. Datos del usuario autenticado
        $user = Auth::user();

        // Verificar si está logueado y si su rol es administrador
        if (!$user || $user->role !== 'admin') {
            return redirect('/mapa')->with('error', 'No tienes permisos de administrador.');
        }

        // 2. Tarjetas de Estadísticas (Dinámicas)
        // Nota: Si tus bicis rodando usan el estado 'Activo' o 'En uso', puedes ajustarlo aquí.
        $bicicletasActivas = Bicicleta::where('estado', 'En uso')
            ->orWhere('estado', 'Activo')
            ->count();
        
        // Viajes realizados hoy (basado en la fecha del servidor)
        $viajesHoy = Alquiler::whereDate('created_at', Carbon::today())->count();
        
        // Alertas críticas (Bicicletas con batería menor a 20% o en mantenimiento)
        $alertasCriticas = Bicicleta::where('nivel_bateria', '<', 20)
            ->orWhere('estado', 'Mantenimiento')
            ->count();

        // Ingresos del día sumando los valores totales de los alquileres completados hoy
        $ingresosHoy = Alquiler::whereDate('created_at', Carbon::today())
            ->where('estado_alquiler', 'Completado')
            ->sum('valor_total');

        // 3. Control de inventario (Carga todas las bicis o las que necesites mostrar)
        $bicicletas = Bicicleta::with('estacionOrigen')->get(); 

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