@extends('layouts.app')

@section('content')
    @if(session('success'))
        <div class="mb-4 p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-semibold">
            ✓ {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 p-3.5 rounded-xl bg-red-50 border border-red-200 text-red-700 text-xs font-semibold">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-4 p-3.5 rounded-xl bg-red-50 border border-red-200 text-red-700 text-xs">
            {{ implode(' | ', $errors->all()) }}
        </div>
    @endif

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
                @php
                    $biciPk = $bici->id_bicicleta ?? $bici->id ?? 1;
                @endphp
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 p-4 rounded-xl border border-gray-100 bg-gray-50/50 hover:bg-gray-50 transition">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl bg-red-100 text-red-600 flex items-center justify-center font-bold text-xs shrink-0">
                            ⚠️
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-gray-900">
                                Bici #{{ $biciPk }} - {{ $bici->modelo ?? 'Bicicleta' }} ({{ $bici->codigo_qr ?? 'N/A' }})
                            </h4>
                            <p class="text-[11px] text-gray-500 mt-0.5">
                                Nivel de batería crítico: <span class="text-red-600 font-semibold">{{ $bici->nivel_bateria ?? 0 }}%</span> | Estado: <span class="font-semibold text-amber-600">{{ $bici->estado ?? 'N/A' }}</span>
                            </p>
                        </div>
                    </div>
                    
                    <!-- Botones de Acción Rápida -->
                    <div class="flex items-center gap-2 w-full sm:w-auto justify-end">
                        <form action="{{ route('bicicletas.updateEstado', $biciPk) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="estado" value="Disponible">
                            <input type="hidden" name="nivel_bateria" value="100">
                            <button type="submit" class="bg-emerald-50 text-emerald-600 hover:bg-emerald-100 border border-emerald-200 px-3 py-1.5 rounded-xl text-xs font-semibold transition shadow-sm" title="Marcar como reparada y batería al 100%">
                                ⚡ Resolver / Recargar
                            </button>
                        </form>

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

    <!-- 📋 Historial de Novedades de Campo Reportadas por Operarios -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h3 class="text-base font-bold text-gray-900">Historial de Novedades de Campo</h3>
                <p class="text-xs text-gray-500 mt-0.5">Incidencias reportadas en ruta por el personal operativo.</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-100 text-[11px] text-gray-400 font-semibold uppercase tracking-wider">
                        <th class="py-3 px-4">ID</th>
                        <th class="py-3 px-4">Bici / Estación</th>
                        <th class="py-3 px-4">Tipo</th>
                        <th class="py-3 px-4">Descripción</th>
                        <th class="py-3 px-4">Reportado por</th>
                        <th class="py-3 px-4">Fecha</th>
                        <th class="py-3 px-4 text-right">Acción</th>
                    </tr>
                </thead>
                <tbody class="text-xs text-gray-700 divide-y divide-gray-50">
                    @forelse($novedadesCampo ?? [] as $novedad)
                        @php
                            $biciPk = $novedad->bicicleta_id ?? $novedad->id_bicicleta ?? optional($novedad->bicicleta)->id_bicicleta ?? null;
                            $estacionPk = $novedad->estacion_id ?? $novedad->id_estacion ?? optional($novedad->estacion)->id_estacion ?? null;
                            $biciRel = $novedad->bicicleta;
                            $estadoBiciActual = optional($biciRel)->estado;
                            $bateriaBiciActual = optional($biciRel)->nivel_bateria;
                        @endphp
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="py-4 px-4 font-bold text-gray-900">#{{ $novedad->id_novedad ?? ($novedad->id ?? '0') }}</td>
                            <td class="py-4 px-4 text-gray-600">
                                @if($biciPk)
                                    <span class="block font-medium text-gray-800">
                                        🚲 Bici #{{ $biciPk }}
                                        @if($estadoBiciActual)
                                            <span class="text-[10px] font-semibold {{ $estadoBiciActual === 'Disponible' ? 'text-emerald-600' : 'text-amber-600' }}">
                                                ({{ $estadoBiciActual }}{{ $bateriaBiciActual !== null ? ' · ' . $bateriaBiciActual . '%' : '' }})
                                            </span>
                                        @endif
                                    </span>
                                @endif
                                @if($estacionPk)
                                    <span class="block text-[11px] text-gray-400">
                                        🏢 {{ optional($novedad->estacion)->nombre ?? ('Estación #' . $estacionPk) }}
                                    </span>
                                @elseif(!$biciPk)
                                    General / N/A
                                @endif
                            </td>
                            <td class="py-4 px-4">
                                <span class="px-2.5 py-1 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-600">
                                    {{ $novedad->tipo_novedad ?? 'Incidencia' }}
                                </span>
                            </td>
                            <td class="py-4 px-4 text-gray-600">{{ $novedad->descripcion ?? 'Sin descripción' }}</td>
                            <td class="py-4 px-4 text-gray-500">
                                {{ optional($novedad->usuario)->name ?? ($novedad->user_id ? 'Usuario #' . $novedad->user_id : 'Operario') }}
                            </td>
                            <td class="py-4 px-4 text-gray-400">
                                {{ !empty($novedad->created_at) ? \Illuminate\Support\Carbon::parse($novedad->created_at)->format('d/m/Y H:i') : 'N/A' }}
                            </td>
                            
                            <!-- Columna de Acción Mecánica (misma lógica que Resolver / Recargar) -->
                            <td class="py-4 px-4 text-right">
                                @if($biciPk)
                                    <form action="{{ route('bicicletas.updateEstado', $biciPk) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="estado" value="Disponible">
                                        <input type="hidden" name="nivel_bateria" value="100">
                                        <button type="submit" class="bg-emerald-50 text-emerald-600 hover:bg-emerald-100 border border-emerald-200 px-3 py-1.5 rounded-xl text-xs font-semibold transition shadow-sm" title="Marcar bicicleta como reparada y batería al 100%">
                                            ⚡ Resolver
                                        </button>
                                    </form>
                                @else
                                    <span class="text-[11px] text-gray-400 italic">N/A</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-gray-400 text-xs">
                                No hay novedades de campo registradas recientemente.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection