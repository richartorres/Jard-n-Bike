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
     * Middleware o verificación previa de administrador
     */
    private function verificarAdmin()
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            return redirect('/mapa')->with('error', 'No tienes permisos de administrador.');
        }
        return $user;
    }

    /**
     * Vista 1: Dashboard Principal (Tarjetas y accesos rápidos)
     */
    public function dashboard()
    {
        if ($response = $this->verificarAdmin()) {
            if ($response instanceof \Illuminate\Http\RedirectResponse) return $response;
        }

        // Estadísticas para las tarjetas del Dashboard
        $bicicletasActivas = Bicicleta::where('estado', 'Disponible')
            ->orWhere('estado', 'En uso')
            ->orWhere('estado', 'Activo')
            ->count();
        
        $viajesHoy = Alquiler::whereDate('created_at', Carbon::today())->count();
        if ($viajesHoy === 0) {
            $viajesHoy = Alquiler::count();
        }
        
        $alertasCriticas = Bicicleta::where('nivel_bateria', '<', 20)
            ->orWhere('estado', 'Mantenimiento')
            ->count();

        $ingresosHoy = Alquiler::whereDate('created_at', Carbon::today())
            ->where('estado_alquiler', 'Completado')
            ->sum('valor_total');
            
        if ($ingresosHoy == 0) {
            $ingresosHoy = Alquiler::where('estado_alquiler', 'Completado')->sum('valor_total');
        }

        // Retorna específicamente la vista del dashboard que creaste
        return view('admin.dashboard', compact(
            'bicicletasActivas', 
            'viajesHoy', 
            'alertasCriticas', 
            'ingresosHoy'
        ));
    }

    /**
     * Vista 2: Inventario (Enfocado netamente en la tabla de bicicletas)
     */
    public function inventario()
    {
        if ($response = $this->verificarAdmin()) {
            if ($response instanceof \Illuminate\Http\RedirectResponse) return $response;
        }

        // Control de inventario cargando la relación 'estacion' de forma segura
        try {
            $bicicletas = Bicicleta::with('estacion')->get();
        } catch (\Exception $e) {
            $bicicletas = Bicicleta::all();
        }

        // Retorna específicamente la vista del inventario que creaste
        return view('admin.inventario', compact('bicicletas'));
    }

    /**
     * Método index por compatibilidad con la ruta principal /admin
     */
    public function index()
    {
        return $this->dashboard();
    }
}