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

    @php
        $novedadesSoloEstacion = collect($novedadesCampo ?? [])->filter(function ($nov) {
            $bId = $nov->bicicleta_id ?? $nov->id_bicicleta ?? optional($nov->bicicleta)->id_bicicleta ?? null;
            $eId = $nov->estacion_id ?? $nov->id_estacion ?? optional($nov->estacion)->id_estacion ?? null;
            return empty($bId) && !empty($eId);
        });
        $tieneAlertasArriba = (collect($bicicletasAlerta ?? [])->isNotEmpty()) || ($novedadesSoloEstacion->isNotEmpty());
    @endphp

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Centro de Alertas e Incidencias</h2>
                <p class="text-xs text-gray-500 mt-0.5">Monitoreo en tiempo real de unidades y estaciones que requieren atención técnica.</p>
            </div>
            <span id="badge-alertas-activas" class="bg-red-50 text-red-600 px-3 py-1 rounded-xl text-xs font-bold">
                {{ $alertasCriticasCount ?? 0 }} Activas
            </span>
        </div>

        <div class="space-y-3">
            @foreach($bicicletasAlerta ?? [] as $bici)
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
            @endforeach

            @foreach($novedadesSoloEstacion as $novEst)
                @php
                    $novEstId = $novEst->id_novedad ?? $novEst->id ?? 0;
                    $estNombre = optional($novEst->estacion)->nombre ?? ('Estación #' . ($novEst->estacion_id ?? $novEst->id_estacion));
                @endphp
                <div id="alerta-estacion-{{ $novEstId }}" class="alerta-estacion-item flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 p-4 rounded-xl border border-amber-100 bg-amber-50/30 hover:bg-amber-50/50 transition">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center font-bold text-xs shrink-0">
                            🏢
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-gray-900">
                                {{ $estNombre }} · {{ $novEst->tipo_novedad ?? 'Falla Estación' }} (#{{ $novEstId }})
                            </h4>
                            <p class="text-[11px] text-gray-500 mt-0.5">
                                {{ $novEst->descripcion ?? 'Sin descripción' }}
                                @if(!empty($novEst->created_at))
                                    <span class="text-gray-400"> · {{ \Illuminate\Support\Carbon::parse($novEst->created_at)->format('d/m/Y H:i') }}</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 w-full sm:w-auto justify-end">
                        <button
                            type="button"
                            onclick="marcarEstacionResuelta('{{ $novEstId }}')"
                            class="bg-emerald-50 text-emerald-600 hover:bg-emerald-100 border border-emerald-200 px-3 py-1.5 rounded-xl text-xs font-semibold transition shadow-sm cursor-pointer"
                            title="Marcar incidencia de estación como resuelta"
                        >
                            ✓ Resolver
                        </button>
                    </div>
                </div>
            @endforeach

            @if(!$tieneAlertasArriba)
                <div id="estado-todo-en-orden" class="py-12 text-center">
                    <div class="w-12 h-12 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto mb-3 text-lg">
                        ✓
                    </div>
                    <p class="text-xs font-bold text-gray-800">¡Todo en orden!</p>
                    <p class="text-[11px] text-gray-400 mt-0.5">No hay alertas críticas ni bicicletas o estaciones con novedades en este momento.</p>
                </div>
            @endif
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
                            $novedadId = $novedad->id_novedad ?? ($novedad->id ?? '0');
                            $esSoloEstacion = empty($biciPk) && !empty($estacionPk);
                        @endphp
                        <tr id="fila-novedad-{{ $novedadId }}" class="hover:bg-gray-50/50 transition">
                            <td class="py-4 px-4 font-bold text-gray-900">#{{ $novedadId }}</td>
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
                                    <span class="block text-[11px] {{ $biciPk ? 'text-gray-400' : 'font-medium text-gray-800' }}">
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
                            
                            <!-- Columna de Acción -->
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
                                @elseif($esSoloEstacion)
                                    <button
                                        type="button"
                                        onclick="marcarEstacionResuelta('{{ $novedadId }}')"
                                        class="bg-emerald-50 text-emerald-600 hover:bg-emerald-100 border border-emerald-200 px-3 py-1.5 rounded-xl text-xs font-semibold transition shadow-sm cursor-pointer"
                                        title="Marcar incidencia de estación como resuelta"
                                    >
                                        ✓ Resolver
                                    </button>
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

    <script>
        const CLAVE_ESTACIONES_RESUELTAS = 'jardin_bike_estaciones_resueltas';

        function obtenerEstacionesResueltas() {
            try {
                return JSON.parse(localStorage.getItem(CLAVE_ESTACIONES_RESUELTAS) || '[]');
            } catch (e) {
                return [];
            }
        }

        function actualizarBadgeActivas(restar) {
            const badge = document.getElementById('badge-alertas-activas');
            if (!badge) return;
            const match = badge.textContent.match(/(\d+)/);
            if (!match) return;
            const actual = parseInt(match[1], 10);
            const nuevo = Math.max(0, actual - restar);
            badge.textContent = nuevo + ' Activas';
        }

        function ocultarElementoPorId(id) {
            const el = document.getElementById(id);
            if (el) {
                el.remove();
                return true;
            }
            return false;
        }

        function marcarEstacionResuelta(id) {
            if (!id) return;
            const resueltas = obtenerEstacionesResueltas();
            const yaEstaba = resueltas.includes(String(id));
            if (!yaEstaba) {
                resueltas.push(String(id));
                localStorage.setItem(CLAVE_ESTACIONES_RESUELTAS, JSON.stringify(resueltas));
            }
            const quitadaAlerta = ocultarElementoPorId('alerta-estacion-' + id);
            ocultarElementoPorId('fila-novedad-' + id);
            if (quitadaAlerta && !yaEstaba) {
                actualizarBadgeActivas(1);
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            const resueltas = obtenerEstacionesResueltas();
            let ocultadasEnCarga = 0;
            resueltas.forEach(function (id) {
                if (ocultarElementoPorId('alerta-estacion-' + id)) {
                    ocultadasEnCarga++;
                }
                ocultarElementoPorId('fila-novedad-' + id);
            });
            if (ocultadasEnCarga > 0) {
                actualizarBadgeActivas(ocultadasEnCarga);
            }
        });
    </script>
@endsection