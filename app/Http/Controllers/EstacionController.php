<?php

namespace App\Http\Controllers;

use App\Models\Estacion;
use App\Models\Bicicleta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EstacionController extends Controller
{
    /**
     * Muestra la vista web con la lista de estaciones en el panel operativo.
     */
    public function index()
    {
        // Traemos todas las estaciones de la base de datos
        $estaciones = Estacion::all(); 
        $user = Auth::user();

        // Retornamos la vista del panel administrativo de estaciones
        return view('admin.estaciones', compact('estaciones', 'user'));
    }

    /**
     * Almacena una nueva estación desde el panel operativo (Web).
     */
    public function store(Request $request)
    {
        // Validamos los datos del modal
        $request->validate([
            'codigo' => 'required|unique:estaciones,codigo',
            'nombre' => 'required',
            'direccion' => 'required',
            'capacidad' => 'required|integer',
            'coordenadas' => 'required',
        ]);

        // Creamos el registro en la base de datos
        Estacion::create([
            'codigo' => $request->codigo,
            'nombre' => $request->nombre,
            'direccion' => $request->direccion,
            'coordenadas' => $request->coordenadas,
            'capacidad' => $request->capacidad,
            'energia_disp' => 100,            // Valor inicial
            'estado' => 'Activa',              // Estado inicial por defecto
        ]);

        // Redirigimos de vuelta a la misma vista web de manera limpia
        return redirect()->back()->with('success', '¡Estación creada con éxito!');
    }

    /**
     * Cambia el estado operativo de la estación ('Activa' o 'Inactiva').
     */
    public function updateEstado(Request $request, $id_estacion)
    {
        $estacion = Estacion::findOrFail($id_estacion);
        
        $estacion->estado = $request->input('estado');
        $estacion->save();

        return redirect()->back()->with('success', 'Estado de la estación actualizado con éxito.');
    }

    /**
     * Elimina una estación del sistema desde el panel de control.
     */
    public function destroy($id_estacion)
    {
        $estacion = Estacion::findOrFail($id_estacion);
        $estacion->delete();

        return redirect()->back()->with('success', 'Estación eliminada correctamente.');
    }
}