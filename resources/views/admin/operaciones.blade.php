@extends('layouts.app') <!-- O la ruta exacta de tu layout maestro si cambia el nombre -->

@section('content')
<div class="max-w-7xl mx-auto">
    
    <!-- Cabecera de la sección -->
    <header class="flex justify-between items-center mb-8">
        <div>
            <span class="text-xs font-bold text-emerald-700 tracking-wider uppercase">PANEL OPERATIVO</span>
            <h2 class="text-2xl font-bold text-gray-800">Gestión de Novedades de Campo 🛠️</h2>
        </div>
    </header>

    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-100 border border-emerald-400 text-emerald-700 rounded-xl shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Formulario para Registrar Nueva Novedad -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 lg:col-span-1">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">📝 Registrar Novedad</h3>
            
            <form action="{{ route('operaciones.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Bicicleta afectada:</label>
                    <select name="bicicleta_id" class="w-full border-gray-300 rounded-xl border p-2.5 text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none" required>
                        <option value="">Seleccione una bicicleta...</option>
                        @foreach($bicicletas as $bici)
                            <option value="{{ $bici->id_bicicleta }}">Bici #{{ $bici->id_bicicleta }} ({{ $bici->codigo_qr }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Estación de referencia:</label>
                    <select name="estacion_id" class="w-full border-gray-300 rounded-xl border p-2.5 text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none" required>
                        <option value="">Seleccione una estación...</option>
                        @foreach($estaciones as $est)
                            {{-- Usa $est->id o $est->id_estacion según la estructura de tu BD --}}
                            <option value="{{ $est->id_estacion ?? $est->id }}">
                                {{ $est->nombre_estacion ?? $est->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de Novedad:</label>
                    <select name="tipo_novedad" class="w-full border-gray-300 rounded-xl border p-2.5 text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none" required>
                        <option value="Batería Baja">Batería Baja / Descargada</option>
                        <option value="Daño Físico">Daño Físico (Frenos, Llantas)</option>
                        <option value="Falla Anclaje">Falla en Anclaje de Estación</option>
                        <option value="Mantenimiento Preventivo">Mantenimiento Preventivo</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Descripción del problema:</label>
                    <textarea name="descripcion" rows="3" class="w-full border-gray-300 rounded-xl border p-2.5 text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none" placeholder="Detalles de la incidencia..." required></textarea>
                </div>

                <button type="submit" class="w-full bg-emerald-800 hover:bg-emerald-900 text-white font-medium py-2.5 rounded-xl transition shadow-md">
                    Guardar Incidencia
                </button>
            </form>
        </div>

        <!-- Tabla de Historial de Novedades -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 lg:col-span-2">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">📋 Historial de Incidencias en Ruta</h3>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-gray-100 text-xs text-gray-400 uppercase tracking-wider">
                            <th class="py-3 px-4">ID</th>
                            <th class="py-3 px-4">Bici / Estación</th>
                            <th class="py-3 px-4">Tipo</th>
                            <th class="py-3 px-4">Descripción</th>
                            <th class="py-3 px-4">Fecha</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 text-sm text-gray-600">
                        @forelse($novedades as $nov)
                            <tr>
                                <td class="py-3 px-4 font-semibold text-gray-800">#{{ $nov->id_novedad }}</td>
                                <td class="py-3 px-4">
                                    <span class="block font-medium text-gray-800">Bici: #{{ $nov->bicicleta_id }}</span>
                                    <span class="text-xs text-gray-400">{{ $nov->estacion->nombre_estacion ?? 'N/A' }}</span>
                                </td>
                                <td class="py-3 px-4">
                                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                        {{ $nov->tipo_novedad }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 max-w-xs truncate">{{ $nov->descripcion }}</td>
                                <td class="py-3 px-4 text-xs text-gray-400">{{ $nov->created_at }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-6 text-center text-gray-400">No hay novedades registradas en campo.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</div>
@endsection