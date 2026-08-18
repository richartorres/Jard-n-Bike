@extends('layouts.app')

@section('content')
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Centro de Alertas e Incidencias</h2>
                <p class="text-xs text-gray-500 mt-0.5">Monitoreo en tiempo real de unidades que requieren atención técnica.</p>
            </div>
            <span class="bg-red-50 text-red-600 px-3 py-1 rounded-xl text-xs font-bold">
                {{ $alertasCriticasCount ?? 0 }} Activas
            </span>
        </div>

        <div class="space-y-3">
            @forelse($bicicletasAlerta ?? [] as $bici)
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 p-4 rounded-xl border border-gray-100 bg-gray-50/50 hover:bg-gray-50 transition">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl bg-red-100 text-red-600 flex items-center justify-center font-bold text-xs shrink-0">
                            ⚠️
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-gray-900">{{ $bici->id_bicicleta }} - {{ $bici->modelo }}</h4>
                            <p class="text-[11px] text-gray-500 mt-0.5">
                                Nivel de batería crítico: <span class="text-red-600 font-semibold">{{ $bici->nivel_bateria }}%</span> | Estado: <span class="font-semibold text-amber-600">{{ $bici->estado }}</span>
                            </p>
                        </div>
                    </div>
                    
                    <!-- Botones de Acción Rápida -->
                    <div class="flex items-center gap-2 w-full sm:w-auto justify-end">
                        <!-- Botón para resolver rápido (Cambia estado a Disponible y recarga batería al 100%) -->
                        <form action="{{ route('bicicletas.updateEstado', $bici->id_bicicleta) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="estado" value="Disponible">
                            <input type="hidden" name="nivel_bateria" value="100">
                            <button type="submit" class="bg-emerald-50 text-emerald-600 hover:bg-emerald-100 border border-emerald-200 px-3 py-1.5 rounded-xl text-xs font-semibold transition shadow-sm" title="Marcar como reparada y batería al 100%">
                                ⚡ Resolver / Recargar
                            </button>
                        </form>

                        <!-- Enlace opcional para revisar en inventario -->
                        <a href="{{ route('admin.inventario') }}" class="bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 px-3 py-1.5 rounded-xl text-xs font-semibold transition shadow-sm">
                            Ver Inventario
                        </a>
                    </div>
                </div>
            @empty
                <div class="py-12 text-center">
                    <div class="w-12 h-12 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto mb-3 text-lg">
                        ✓
                    </div>
                    <p class="text-xs font-bold text-gray-800">¡Todo en orden!</p>
                    <p class="text-[11px] text-gray-400 mt-0.5">No hay alertas críticas ni bicicletas con batería baja en este momento.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection