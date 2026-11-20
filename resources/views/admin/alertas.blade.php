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

        $novedadesPorBiciMap = collect($novedadesPorBici ?? []);
        $tieneAlertas = (collect($bicicletasAlerta ?? [])->isNotEmpty()) || ($novedadesSoloEstacion->isNotEmpty());
    @endphp

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
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
            {{-- Alertas de Bicicletas --}}
            @foreach($bicicletasAlerta ?? [] as $bici)
                @php
                    $biciPk = $bici->id_bicicleta ?? $bici->id ?? 1;
                    $novBici = $novedadesPorBiciMap->get($biciPk) ?? $novedadesPorBiciMap->get((string) $biciPk);
                    $reportadoPor = $novBici
                        ? (optional($novBici->usuario)->name ?? ($novBici->user_id ? 'Usuario #' . $novBici->user_id : 'Operario'))
                        : null;
                    $estNombreBici = $novBici ? optional($novBici->estacion)->nombre : null;
                @endphp
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 p-4 rounded-xl border border-gray-100 bg-gray-50/50 hover:bg-gray-50 transition">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-red-100 text-red-600 flex items-center justify-center font-bold text-xs shrink-0 mt-0.5">
                            ⚠️
                        </div>
                        <div>
                            <div class="flex flex-wrap items-center gap-2">
                                <h4 class="text-xs font-bold text-gray-900">
                                    Bici #{{ $biciPk }} - {{ $bici->modelo ?? 'Bicicleta' }} ({{ $bici->codigo_qr ?? 'N/A' }})
                                </h4>
                                @if($novBici && !empty($novBici->tipo_novedad))
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                        {{ $novBici->tipo_novedad }}
                                    </span>
                                @endif
                            </div>

                            <p class="text-[11px] text-gray-500 mt-0.5">
                                Batería: <span class="text-red-600 font-semibold">{{ $bici->nivel_bateria ?? 0 }}%</span>
                                · Estado: <span class="font-semibold text-amber-600">{{ $bici->estado ?? 'N/A' }}</span>
                                @if($estNombreBici)
                                    · <span class="text-gray-600">🏢 {{ $estNombreBici }}</span>
                                @endif
                            </p>

                            @if($novBici)
                                <p class="text-xs text-gray-700 mt-1.5">
                                    {{ $novBici->descripcion ?? 'Sin descripción' }}
                                </p>
                                <p class="text-[11px] text-gray-400 mt-1">
                                    Reportado por <span class="font-medium text-gray-600">{{ $reportadoPor }}</span>
                                    @if(!empty($novBici->created_at))
                                        · {{ \Illuminate\Support\Carbon::parse($novBici->created_at)->format('d/m/Y H:i') }}
                                    @endif
                                </p>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Botones de Acción Rápida -->
                    <div class="flex items-center gap-2 w-full sm:w-auto justify-end shrink-0">
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

            {{-- Alertas de Solo Estación --}}
            @foreach($novedadesSoloEstacion as $novEst)
                @php
                    $novEstId = $novEst->id_novedad ?? $novEst->id ?? 0;
                    $estNombre = optional($novEst->estacion)->nombre ?? ('Estación #' . ($novEst->estacion_id ?? $novEst->id_estacion));
                    $reportadoPorEst = optional($novEst->usuario)->name ?? ($novEst->user_id ? 'Usuario #' . $novEst->user_id : 'Operario');
                @endphp
                <div id="alerta-estacion-{{ $novEstId }}" class="alerta-estacion-item flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 p-4 rounded-xl border border-amber-100 bg-amber-50/30 hover:bg-amber-50/50 transition">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center font-bold text-xs shrink-0 mt-0.5">
                            🏢
                        </div>
                        <div>
                            <div class="flex flex-wrap items-center gap-2">
                                <h4 class="text-xs font-bold text-gray-900">
                                    {{ $estNombre }} (#{{ $novEstId }})
                                </h4>
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-amber-100 text-amber-800 border border-amber-200">
                                    {{ $novEst->tipo_novedad ?? 'Falla Estación' }}
                                </span>
                            </div>

                            <p class="text-xs text-gray-700 mt-1.5">
                                {{ $novEst->descripcion ?? 'Sin descripción' }}
                            </p>

                            <p class="text-[11px] text-gray-400 mt-1">
                                Reportado por <span class="font-medium text-gray-600">{{ $reportadoPorEst }}</span>
                                @if(!empty($novEst->created_at))
                                    · {{ \Illuminate\Support\Carbon::parse($novEst->created_at)->format('d/m/Y H:i') }}
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 w-full sm:w-auto justify-end shrink-0">
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

            @if(!$tieneAlertas)
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
            const quitada = ocultarElementoPorId('alerta-estacion-' + id);
            if (quitada && !yaEstaba) {
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
            });
            if (ocultadasEnCarga > 0) {
                actualizarBadgeActivas(ocultadasEnCarga);
            }
        });
    </script>
@endsection