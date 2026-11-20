<?php

namespace App\Http\Controllers;

use App\Models\Bicicleta;
use App\Models\Alquiler;
use App\Models\Estacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\NovedadCampo;

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

        try {
            $bicicletas = Bicicleta::with('estacion')->get();
        } catch (\Exception $e) {
            $bicicletas = Bicicleta::all();
        }

        return view('admin.inventario', compact('bicicletas'));
    }

    /**
     * Método index por compatibilidad con la ruta principal /admin
     */
    public function index()
    {
        return $this->dashboard();
    }

    public function estaciones()
    {
        if ($response = $this->verificarAdmin()) {
            if ($response instanceof \Illuminate\Http\RedirectResponse) return $response;
        }

        $estaciones = Estacion::all();

        return view('admin.estaciones', compact('estaciones'));
    }

    public function usuarios()
    {
        if ($response = $this->verificarAdmin()) {
            if ($response instanceof \Illuminate\Http\RedirectResponse) return $response;
        }
        $usuarios = \App\Models\User::all();
        return view('admin.usuarios', compact('usuarios'));
    }

    public function alertas()
    {
        if (!auth()->check() || !in_array(auth()->user()->role, ['admin', 'operario'])) {
            return redirect()->route('mapa')->with('error', 'Acceso no autorizado.');
        }

        $bicicletasAlerta = Bicicleta::where('nivel_bateria', '<', 20)
            ->orWhere('estado', 'Mantenimiento')
            ->get();

        $novedadesEstacionCount = 0;
        $novedadesPorBici = collect();

        try {
            $novedadesCampo = NovedadCampo::with(['bicicleta', 'estacion', 'usuario'])
                ->where(function ($query) {
                    $query->whereHas('bicicleta', function ($q) {
                        $q->where('nivel_bateria', '<', 20)
                            ->orWhere('estado', 'Mantenimiento');
                    })->orWhere(function ($q) {
                        $q->whereNull('bicicleta_id')
                            ->whereNotNull('estacion_id');
                    });
                })
                ->orderByDesc('id_novedad')
                ->get();

            $novedadesPorBici = $novedadesCampo
                ->filter(fn ($item) => !empty($item->bicicleta_id))
                ->groupBy('bicicleta_id')
                ->map(fn ($grupo) => $grupo->first());

            $novedadesEstacionCount = $novedadesCampo
                ->filter(function ($item) {
                    if (!empty($item->bicicleta_id) || empty($item->estacion_id)) {
                        return false;
                    }
                    $estadoEst = strtolower(trim((string) (optional($item->estacion)->estado ?? '')));
                    return $estadoEst !== 'activa';
                })
                ->sortByDesc(fn ($item) => (int) ($item->id_novedad ?? $item->id ?? 0))
                ->unique(fn ($item) => (string) $item->estacion_id)
                ->count();
        } catch (\Throwable $e) {
            $novedadesCampo = NovedadCampo::orderByDesc('id_novedad')->get();
        }

        $alertasCriticasCount = $bicicletasAlerta->count() + $novedadesEstacionCount;

        return view('admin.alertas', compact(
            'bicicletasAlerta',
            'alertasCriticasCount',
            'novedadesCampo',
            'novedadesPorBici'
        ));
    }

    /**
     * Cambiar el rol de un usuario (Cliente <-> Admin)
     */
    public function updateRole(Request $request, $id)
    {
        if ($response = $this->verificarAdmin()) {
            if ($response instanceof \Illuminate\Http\RedirectResponse) return $response;
        }

        $user = \App\Models\User::findOrFail($id);

        $nuevoRol = $request->input('role');
        if (in_array($nuevoRol, ['admin', 'operario', 'cliente'])) {
            $user->role = $nuevoRol;
            $user->save();
        }

        return redirect()->back()->with('success', 'Rol de usuario actualizado correctamente.');
    }

    /**
     * Eliminar la cuenta de un usuario permanentemente
     */
    public function destroyUser($id)
    {
        if ($response = $this->verificarAdmin()) {
            if ($response instanceof \Illuminate\Http\RedirectResponse) return $response;
        }

        if (Auth::id() == $id) {
            return redirect()->back()->with('error', 'No puedes eliminar tu propia cuenta de administrador.');
        }

        $user = \App\Models\User::findOrFail($id);
        $user->delete();

        return redirect()->back()->with('success', 'Usuario eliminado correctamente.');
    }

    /**
     * Vista de Reporte Financiero usando el Procedimiento Almacenado
     */
    public function reporteFinanciero(Request $request)
    {
        $hoy = now('America/Bogota')->toDateString();

        $fechaInicio = $request->input('fecha_inicio', now('America/Bogota')->startOfMonth()->toDateString());
        $fechaFin = $request->input('fecha_fin', $hoy);

        if ($fechaInicio > $hoy) {
            $fechaInicio = $hoy;
        }

        if ($fechaFin > $hoy) {
            $fechaFin = $hoy;
        }

        if ($fechaInicio > $fechaFin) {
            $fechaInicio = $fechaFin;
        }

        $resumen = DB::select('CALL sp_reporte_financiero_rango(?, ?)', [$fechaInicio, $fechaFin]);
        $historico = DB::select('CALL sp_reporte_historico_diario(?, ?)', [$fechaInicio, $fechaFin]);

        return view('admin.reportes', [
            'data' => $resumen[0] ?? null,
            'historico' => $historico,
            'fechaInicio' => $fechaInicio,
            'fechaFin' => $fechaFin,
            'fechaMaxima' => $hoy,
        ]);
    }

    public function exportarReportePdf(Request $request)
    {
        $hoy = now('America/Bogota')->toDateString();

        $fechaInicio = $request->input('fecha_inicio', now('America/Bogota')->startOfMonth()->toDateString());
        $fechaFin = $request->input('fecha_fin', $hoy);

        if ($fechaInicio > $hoy) {
            $fechaInicio = $hoy;
        }

        if ($fechaFin > $hoy) {
            $fechaFin = $hoy;
        }

        if ($fechaInicio > $fechaFin) {
            $fechaInicio = $fechaFin;
        }

        $resumen = DB::select('CALL sp_reporte_financiero_rango(?, ?)', [$fechaInicio, $fechaFin]);
        $historico = DB::select('CALL sp_reporte_historico_diario(?, ?)', [$fechaInicio, $fechaFin]);

        $pdf = Pdf::loadView('admin.reportes_pdf', [
            'data' => $resumen[0] ?? null,
            'historico' => $historico,
            'fechaInicio' => $fechaInicio,
            'fechaFin' => $fechaFin,
        ]);

        return $pdf->download("Reporte_JardinBike_{$fechaInicio}_a_{$fechaFin}.pdf");
    }

    public function resolverEstacion($id)
{
    if (!auth()->check() || !in_array(auth()->user()->role, ['admin', 'operario'])) {
        return redirect()->route('mapa')->with('error', 'Acceso no autorizado.');
    }

    $novedad = NovedadCampo::where('id_novedad', $id)->first();
    $estacionId = $novedad->estacion_id ?? $novedad->id_estacion ?? null;

    if ($estacionId) {
        $estacion = Estacion::where('id_estacion', $estacionId)->first();
        if ($estacion) {
            $estacion->estado = 'Activa';
            $estacion->save();
        }
    }

    if (request()->expectsJson()) {
        return response()->json([
            'ok' => true,
            'id' => (string) $id,
            'estado' => 'Activa',
        ]);
    }

    return back()->with('success', 'Incidencia de estación resuelta: la estación ahora está Activa.');
}
}