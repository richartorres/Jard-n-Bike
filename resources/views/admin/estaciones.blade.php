@extends('layouts.app')

@section('content')
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Control de Estaciones</h2>
                <p class="text-xs text-gray-500 mt-0.5">Administra los puntos de préstamo y capacidad de anclaje.</p>
            </div>
            <button onclick="openModal('modal-estacion')" class="bg-[#101828] hover:bg-gray-800 text-white px-4 py-2 rounded-xl text-xs font-bold transition shadow">
                + Nueva Estación
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-100 text-[11px] text-gray-400 font-semibold uppercase tracking-wider">
                        <th class="py-3 px-4">Código / Nombre</th>
                        <th class="py-3 px-4">Dirección</th>
                        <th class="py-3 px-4">Capacidad</th>
                        <th class="py-3 px-4">Energía Disp.</th>
                        <th class="py-3 px-4">Estado</th>
                        <th class="py-3 px-4 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="text-xs text-gray-700 divide-y divide-gray-50">
                    @forelse($estaciones ?? [] as $estacion)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="py-4 px-4">
                                <span class="font-bold text-gray-950 block">{{ $estacion->nombre }}</span>
                                <span class="text-[10px] text-gray-400 font-mono">{{ $estacion->codigo }}</span>
                            </td>
                            <td class="py-4 px-4 text-gray-500">{{ $estacion->direccion }}</td>
                            <td class="py-4 px-4 font-medium">{{ $estacion->capacidad }} anclajes</td>
                            <td class="py-4 px-4 font-semibold text-emerald-600">{{ $estacion->energia_disp }}%</td>
                            <td class="py-4 px-4">
                                <span class="bg-emerald-50 text-emerald-600 font-semibold px-2.5 py-1 rounded-full text-[11px]">
                                    {{ $estacion->estado }}
                                </span>
                            </td>
                            <td class="py-4 px-4 text-right font-medium">
                                <div class="flex items-center justify-end gap-2">
                                    <!-- Botón para cambiar estado Activa / Inactiva -->
                                    <form action="{{ route('estaciones.updateEstado', $estacion->id_estacion) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="estado" value="{{ $estacion->estado == 'Activa' ? 'Inactiva' : 'Activa' }}">
                                        <button type="submit" class="text-xs px-2.5 py-1 rounded-lg font-medium {{ $estacion->estado == 'Activa' ? 'bg-amber-50 text-amber-600 hover:bg-amber-100' : 'bg-emerald-50 text-emerald-600 hover:bg-emerald-100' }}" title="Cambiar estado">
                                            {{ $estacion->estado == 'Activa' ? 'Desactivar' : 'Activar' }}
                                        </button>
                                    </form>

                                    <!-- Botón para eliminar estación -->
                                    <form action="{{ route('estaciones.destroy', $estacion->id_estacion) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro de eliminar esta estación?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-50 text-red-500 hover:bg-red-100 px-2.5 py-1 rounded-lg text-xs font-medium" title="Eliminar">
                                            ✕
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-gray-400 text-xs">
                                No hay estaciones registradas en el sistema.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- MODAL REGISTRAR NUEVA ESTACIÓN -->
    <div id="modal-estacion" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-bold text-lg text-gray-900">📍 Registrar Nueva Estación</h3>
                <button type="button" onclick="closeModal('modal-estacion')" class="text-gray-400 hover:text-gray-600 font-bold">✕</button>
            </div>
            <form action="{{ route('estaciones.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Código de Estación</label>
                    <input type="text" name="codigo" required placeholder="Ej. EST-NORTE" class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-emerald-700">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Nombre de la Estación</label>
                    <input type="text" name="nombre" required placeholder="Ej. Estación Terminal Norte" class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-emerald-700">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Dirección / Ubicación</label>
                    <input type="text" name="direccion" required placeholder="Ej. Carrera 4 # 12-30" class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-emerald-700">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Capacidad de Anclajes</label>
                    <input type="number" name="capacidad" required placeholder="Ej. 10" class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-emerald-700">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Coordenadas (Lat, Lng)</label>
                    <input type="text" name="coordenadas" required placeholder="Ej. 5.59833,-75.81922" class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-emerald-700">
                </div>
                <div class="flex justify-end gap-2 pt-3">
                    <button type="button" onclick="closeModal('modal-estacion')" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-xl text-xs font-medium">Cancelar</button>
                    <button type="submit" class="bg-[#101828] hover:bg-gray-800 text-white px-4 py-2 rounded-xl text-xs font-medium">Guardar Estación</button>
                </div>
            </form>
        </div>
    </div>
@endsection