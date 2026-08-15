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

        // 2. Tarjetas de Estadísticas (Dinámicas basadas en tus tablas)
        
        // Bicicletas activas o disponibles (según los estados de tu BD)
        $bicicletasActivas = Bicicleta::where('estado', 'Disponible')
            ->orWhere('estado', 'En uso')
            ->orWhere('estado', 'Activo')
            ->count();
        
        // Viajes realizados hoy (con opción de respaldo total si quieres ver los acumulados de prueba)
        $viajesHoy = Alquiler::whereDate('created_at', Carbon::today())->count();
        if ($viajesHoy === 0) {
            $viajesHoy = Alquiler::count(); // Muestra el total histórico si la fecha local difiere de la BD
        }
        
        // Alertas críticas (Bicicletas con batería menor a 20% o en mantenimiento)
        $alertasCriticas = Bicicleta::where('nivel_bateria', '<', 20)
            ->orWhere('estado', 'Mantenimiento')
            ->count();

        // Ingresos del día (Suma de los alquileres completados, con respaldo histórico si es necesario)
        $ingresosHoy = Alquiler::whereDate('created_at', Carbon::today())
            ->where('estado_alquiler', 'Completado')
            ->sum('valor_total');
            
        if ($ingresosHoy == 0) {
            $ingresosHoy = Alquiler::where('estado_alquiler', 'Completado')->sum('valor_total');
        }

        // 3. Control de inventario seguro cargando la relación 'estacion' definida en el modelo
        try {
            $bicicletas = Bicicleta::with('estacion')->get();
        } catch (\Exception $e) {
            $bicicletas = Bicicleta::all();
        }

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