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
                                <button class="text-gray-400 hover:text-gray-600">•••</button>
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
@endsection