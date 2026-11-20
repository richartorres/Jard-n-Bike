@extends('layouts.app')

@section('content')
    @if(session('success'))
        <div class="mb-5 p-3.5 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-semibold flex items-center gap-2">
            <span>✓</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-5 p-3.5 rounded-2xl bg-red-50 border border-red-200 text-red-700 text-xs font-semibold">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-5 p-3.5 rounded-2xl bg-red-50 border border-red-200 text-red-700 text-xs">
            {{ implode(' | ', $errors->all()) }}
        </div>
    @endif

    @php
        $estacionesResueltasServer = collect(session('estaciones_resueltas', []))->map(fn ($v) => (string) $v);

        $novedadesSoloEstacion = collect($novedadesCampo ?? [])->filter(function ($nov) use ($estacionesResueltasServer) {
            $bId = $nov->bicicleta_id ?? $nov->id_bicicleta ?? optional($nov->bicicleta)->id_bicicleta ?? null;
            $eId = $nov->estacion_id ?? $nov->id_estacion ?? optional($nov->estacion)->id_estacion ?? null;
            $nId = (string) ($nov->id_novedad ?? $nov->id ?? '');
            return empty($bId) && !empty($eId) && !$estacionesResueltasServer->contains($nId);
        });

        $novedadesPorBiciMap = collect($novedadesPorBici ?? []);
        $bicisList = collect($bicicletasAlerta ?? []);
        $totalActivasCalculado = $bicisList->count() + $novedadesSoloEstacion->count();
    @endphp

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8 space-y-8">
        {{-- Cabecera Principal --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-gray-100">
            <div>
                <span class="text-[11px] font-bold uppercase tracking-wider text-emerald-700">Monitoreo Operativo</span>
                <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mt-0.5">Centro de Alertas e Incidencias</h2>
                <p class="text-xs text-gray-500 mt-1">Atención priorizada separada por bicicletas en ruta e incidencias en estaciones.</p>
            </div>

            <div class="flex items-center gap-2.5 self-start sm:self-center">
                <span class="hidden md:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-medium bg-gray-50 text-gray-600 border border-gray-200/70">
                    🚲 <span id="contador-bicis-alerta">{{ $bicisList->count() }}</span> Bicis
                </span>
                <span class="hidden md:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-medium bg-gray-50 text-gray-600 border border-gray-200/70">
                    🏢 <span id="contador-estaciones-alerta">{{ $novedadesSoloEstacion->count() }}</span> Estaciones
                </span>
                <span id="badge-alertas-activas" class="bg-red-50 text-red-600 border border-red-200/80 px-3.5 py-1.5 rounded-xl text-xs font-bold shadow-2xs">
                    {{ $totalActivasCalculado }} Activas
                </span>
            </div>
        </div>

        {{-- Categoría 1: Bicicletas --}}
        <section id="seccion-alertas-bicis" class="space-y-3.5">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
                    <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Bicicletas en Atención</h3>
                </div>
                <span class="text-[11px] font-semibold text-gray-400">Batería &lt; 20% o Mantenimiento</span>
            </div>

            @if($bicisList->isNotEmpty())
                <div class="space-y-3">
                    @foreach($bicisList as $bici)
                        @php
                            $biciPk = $bici->id_bicicleta ?? $bici->id ?? 1;
                            $novBici = $novedadesPorBiciMap->get($biciPk) ?? $novedadesPorBiciMap->get((string) $biciPk);
                            $reportadoPor = $novBici
                                ? (optional($novBici->usuario)->name ?? ($novBici->user_id ? 'Usuario #' . $novBici->user_id : 'Operario'))
                                : null;
                            $estNombreBici = $novBici ? optional($novBici->estacion)->nombre : null;
                        @endphp
                        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 p-4 sm:p-5 rounded-2xl border border-rose-100/90 bg-rose-50/25 hover:bg-rose-50/45 transition">
                            <div class="flex items-start gap-4">
                                <div class="w-11 h-11 rounded-2xl bg-rose-100 text-rose-600 flex items-center justify-center font-bold text-sm shrink-0">
                                    🚲
                                </div>
                                <div class="space-y-1">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <h4 class="text-sm font-bold text-gray-900">
                                            Bici #{{ $biciPk }} · {{ $bici->modelo ?? 'Bicicleta' }}
                                        </h4>
                                        <span class="px-2 py-0.5 rounded-lg text-[10px] font-semibold bg-white text-gray-600 border border-gray-200">
                                            {{ $bici->codigo_qr ?? 'N/A' }}
                                        </span>
                                        @if($novBici && !empty($novBici->tipo_novedad))
                                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-amber-100 text-amber-800 border border-amber-200">
                                                {{ $novBici->tipo_novedad }}
                                            </span>
                                        @endif
                                    </div>

                                    <p class="text-xs text-gray-600 flex flex-wrap items-center gap-x-2 gap-y-1">
                                        <span>Batería: <strong class="text-rose-600">{{ $bici->nivel_bateria ?? 0 }}%</strong></span>
                                        <span class="text-gray-300">•</span>
                                        <span>Estado: <strong class="text-amber-700">{{ $bici->estado ?? 'N/A' }}</strong></span>
                                        @if($estNombreBici)
                                            <span class="text-gray-300">•</span>
                                            <span class="text-gray-700 font-medium">🏢 {{ $estNombreBici }}</span>
                                        @endif
                                    </p>

                                    @if($novBici)
                                        <p class="text-xs text-gray-700 bg-white/80 border border-gray-100 rounded-xl px-3 py-2 mt-1.5">
                                            “{{ $novBici->descripcion ?? 'Sin descripción' }}”
                                        </p>
                                        <p class="text-[11px] text-gray-400">
                                            Reportado por <span class="font-semibold text-gray-600">{{ $reportadoPor }}</span>
                                            @if(!empty($novBici->created_at))
                                                · {{ \Illuminate\Support\Carbon::parse($novBici->created_at)->format('d/m/Y H:i') }}
                                            @endif
                                        </p>
                                    @endif
                                </div>
                            </div>

                            <div class="flex items-center gap-2 self-end lg:self-center shrink-0">
                                <form action="{{ route('bicicletas.updateEstado', $biciPk) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="estado" value="Disponible">
                                    <input type="hidden" name="nivel_bateria" value="100">
                                    <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-3.5 py-2 rounded-xl text-xs font-semibold transition shadow-xs cursor-pointer" title="Marcar como reparada y batería al 100%">
                                        ⚡ Resolver / Recargar
                                    </button>
                                </form>

                                <a href="{{ route('admin.inventario') }}" class="bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 px-3.5 py-2 rounded-xl text-xs font-semibold transition">
                                    Inventario
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="p-5 rounded-2xl border border-dashed border-gray-200 bg-gray-50/50 text-center text-xs text-gray-400">
                    Sin bicicletas críticas o en mantenimiento en este momento.
                </div>
            @endif
        </section>

        {{-- Categoría 2: Estaciones --}}
        <section id="seccion-alertas-estaciones" class="space-y-3.5 pt-2 border-t border-gray-100">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                    <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Estaciones con Incidencias</h3>
                </div>
                <span class="text-[11px] font-semibold text-gray-400">Reportes de infraestructura / anclaje</span>
            </div>

            <div id="lista-alertas-estaciones" class="space-y-3">
                @forelse($novedadesSoloEstacion as $novEst)
                    @php
                        $novEstId = $novEst->id_novedad ?? $novEst->id ?? 0;
                        $estNombre = optional($novEst->estacion)->nombre ?? ('Estación #' . ($novEst->estacion_id ?? $novEst->id_estacion));
                        $reportadoPorEst = optional($novEst->usuario)->name ?? ($novEst->user_id ? 'Usuario #' . $novEst->user_id : 'Operario');
                    @endphp
                    <div id="alerta-estacion-{{ $novEstId }}" class="alerta-estacion-item flex flex-col lg:flex-row lg:items-center justify-between gap-4 p-4 sm:p-5 rounded-2xl border border-amber-200/80 bg-amber-50/25 hover:bg-amber-50/45 transition">
                        <div class="flex items-start gap-4">
                            <div class="w-11 h-11 rounded-2xl bg-amber-100 text-amber-700 flex items-center justify-center font-bold text-sm shrink-0">
                                🏢
                            </div>
                            <div class="space-y-1">
                                <div class="flex flex-wrap items-center gap-2">
                                    <h4 class="text-sm font-bold text-gray-900">
                                        {{ $estNombre }}
                                    </h4>
                                    <span class="px-2 py-0.5 rounded-lg text-[10px] font-semibold bg-white text-gray-600 border border-gray-200">
                                        #{{ $novEstId }}
                                    </span>
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-amber-100 text-amber-800 border border-amber-200">
                                        {{ $novEst->tipo_novedad ?? 'Falla Estación' }}
                                    </span>
                                </div>

                                <p class="text-xs text-gray-700 bg-white/80 border border-gray-100 rounded-xl px-3 py-2 mt-1">
                                    “{{ $novEst->descripcion ?? 'Sin descripción' }}”
                                </p>

                                <p class="text-[11px] text-gray-400">
                                    Reportado por <span class="font-semibold text-gray-600">{{ $reportadoPorEst }}</span>
                                    @if(!empty($novEst->created_at))
                                        · {{ \Illuminate\Support\Carbon::parse($novEst->created_at)->format('d/m/Y H:i') }}
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 self-end lg:self-center shrink-0">
                            <button
                                type="button"
                                onclick="marcarEstacionResuelta('{{ $novEstId }}')"
                                class="bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 px-3.5 py-2 rounded-xl text-xs font-semibold transition cursor-pointer"
                                title="Marcar incidencia de estación como resuelta"
                            >
                                ✓ Resolver
                            </button>
                        </div>
                    </div>
                @empty
                    <div id="vacio-estaciones" class="p-5 rounded-2xl border border-dashed border-gray-200 bg-gray-50/50 text-center text-xs text-gray-400">
                        No hay incidencias pendientes en estaciones.
                    </div>
                @endforelse
            </div>
        </section>

        {{-- Resumen General Cuando Todo Está Resuelto --}}
        <div id="estado-todo-en-orden" class="{{ $totalActivasCalculado === 0 ? '' : 'hidden' }} pt-2">
            <div class="py-8 px-4 rounded-2xl bg-emerald-50/50 border border-emerald-100 text-center">
                <div class="w-11 h-11 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center mx-auto mb-2.5 text-base font-bold">
                    ✓
                </div>
                <p class="text-xs font-bold text-gray-800">¡Todo en orden!</p>
                <p class="text-[11px] text-gray-500 mt-0.5">No hay alertas activas en bicicletas ni en estaciones.</p>
            </div>
        </div>
    </div>

    <script>
        const CLAVE_ESTACIONES_RESUELTAS = 'jardin_bike_estaciones_resueltas';
        const CSRF_TOKEN = '{{ csrf_token() }}';

        function obtenerEstacionesResueltas() {
            try {
                return JSON.parse(localStorage.getItem(CLAVE_ESTACIONES_RESUELTAS) || '[]');
            } catch (e) {
                return [];
            }
        }

        function guardarEstacionLocal(id) {
            const resueltas = obtenerEstacionesResueltas();
            if (!resueltas.includes(String(id))) {
                resueltas.push(String(id));
                localStorage.setItem(CLAVE_ESTACIONES_RESUELTAS, JSON.stringify(resueltas));
            }
        }

        function actualizarContadores(restar) {
            const badge = document.getElementById('badge-alertas-activas');
            const contEst = document.getElementById('contador-estaciones-alerta');

            let totalActual = 0;
            if (badge) {
                const match = badge.textContent.match(/(\d+)/);
                if (match) {
                    totalActual = Math.max(0, parseInt(match[1], 10) - restar);
                    badge.textContent = totalActual + ' Activas';
                }
            }

            if (contEst) {
                const actualEst = parseInt(contEst.textContent || '0', 10);
                contEst.textContent = Math.max(0, actualEst - restar);
            }

            const listaEst = document.getElementById('lista-alertas-estaciones');
            if (listaEst && listaEst.querySelectorAll('.alerta-estacion-item').length === 0) {
                listaEst.innerHTML = '<div class="p-5 rounded-2xl border border-dashed border-gray-200 bg-gray-50/50 text-center text-xs text-gray-400">No hay incidencias pendientes en estaciones.</div>';
            }

            const todoEnOrden = document.getElementById('estado-todo-en-orden');
            if (todoEnOrden && totalActual === 0) {
                todoEnOrden.classList.remove('hidden');
            }
        }

        async function marcarEstacionResuelta(id) {
            if (!id) return;
            guardarEstacionLocal(id);

            const tarjeta = document.getElementById('alerta-estacion-' + id);
            const existiaEnDom = !!tarjeta;
            if (tarjeta) {
                tarjeta.remove();
            }
            if (existiaEnDom) {
                actualizarContadores(1);
            }

            try {
                await fetch("{{ url('/alertas/estaciones') }}/" + encodeURIComponent(id) + "/resolver", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': CSRF_TOKEN,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                });
            } catch (e) {
                // Respaldado por sesión y localStorage
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            const resueltas = obtenerEstacionesResueltas();
            let ocultadas = 0;
            resueltas.forEach(function (id) {
                const tarjeta = document.getElementById('alerta-estacion-' + id);
                if (tarjeta) {
                    tarjeta.remove();
                    ocultadas++;
                }
            });
            if (ocultadas > 0) {
                actualizarContadores(ocultadas);
            }
        });
    </script>
@endsection