@extends('layouts.app')

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
        <div class="mb-6 p-4 bg-emerald-100 border border-emerald-400 text-emerald-700 rounded-xl shadow-sm text-sm font-medium">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 p-4 bg-red-50 border border-red-300 text-red-700 rounded-xl shadow-sm text-sm">
            <p class="font-semibold mb-1">Revisa los siguientes campos:</p>
            <ul class="list-disc pl-5 space-y-0.5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Formulario para Registrar Nueva Novedad -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 lg:col-span-1">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">📝 Registrar Novedad</h3>
            
            <form action="{{ route('operaciones.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de Novedad:</label>
                    <select name="tipo_novedad" class="w-full border-gray-300 rounded-xl border p-2.5 text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none" required>
                        <option value="">Seleccione un tipo...</option>
                        <option value="Batería Baja" {{ old('tipo_novedad') == 'Batería Baja' ? 'selected' : '' }}>Batería Baja / Descargada</option>
                        <option value="Falla Estación" {{ old('tipo_novedad') == 'Falla Estación' ? 'selected' : '' }}>Falla en Anclaje de Estación</option>
                        <option value="Mantenimiento Bici" {{ old('tipo_novedad') == 'Mantenimiento Bici' ? 'selected' : '' }}>Mantenimiento Bici (Preventivo/Correctivo)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Bicicleta afectada:</label>
                    <select name="bicicleta_id" class="w-full border-gray-300 rounded-xl border p-2.5 text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                        <option value="">Seleccione una bicicleta...</option>
                        @foreach($bicicletas as $bici)
                            <option value="{{ $bici->id_bicicleta }}" {{ old('bicicleta_id') == $bici->id_bicicleta ? 'selected' : '' }}>
                                Bici #{{ $bici->id_bicicleta }} ({{ $bici->codigo_qr }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Estación de referencia:</label>
                    <select name="estacion_id" class="w-full border-gray-300 rounded-xl border p-2.5 text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                        <option value="">Seleccione una estación...</option>
                        @foreach($estaciones as $est)
                            <option value="{{ $est->id_estacion }}" {{ old('estacion_id') == $est->id_estacion ? 'selected' : '' }}>
                                {{ $est->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Descripción del problema:</label>
                    <textarea name="descripcion" rows="3" class="w-full border-gray-300 rounded-xl border p-2.5 text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none" placeholder="Detalles de la incidencia..." required>{{ old('descripcion') }}</textarea>
                </div>

                <button type="submit" class="w-full bg-emerald-800 hover:bg-emerald-900 text-white font-medium py-2.5 rounded-xl transition shadow-md">
                    Guardar Incidencia
                </button>
            </form>
        </div>

        <!-- Panel Derecho: Pendientes (Solo Bici, Solo Estación o Ambas) + Botón Ver Historial -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 lg:col-span-2 flex flex-col justify-between">
            <div>
                <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">📋 Historial de Incidencias en Ruta</h3>

                    <button
                        type="button"
                        onclick="abrirHistorialModal()"
                        class="inline-flex items-center gap-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-800 border border-emerald-200 px-3.5 py-2 rounded-xl text-xs font-semibold transition shadow-sm cursor-pointer"
                    >
                        <span>📜</span>
                        <span>Ver historial ({{ isset($novedades) ? $novedades->count() : 0 }})</span>
                    </button>
                </div>

                @php
                    $estacionesResueltasIds = collect(session('estaciones_resueltas', []))
                        ->map(fn ($v) => (string) $v);

                    $novedadesPendientes = collect($novedades ?? [])->filter(function ($item) use ($estacionesResueltasIds) {
                        $tieneBici = !empty($item->bicicleta_id);
                        $tieneEstacion = !empty($item->estacion_id);

                        // Si no tiene ni bici ni estación, queda solo en el historial modal
                        if (!$tieneBici && !$tieneEstacion) {
                            return false;
                        }

                        // Caso 1 y 3: Solo bici o Bici + Estación -> pendiente si la bici tiene problema
                        if ($tieneBici) {
                            $bici = $item->bicicleta ?? null;
                            if (!$bici) {
                                return false;
                            }
                            $estado = strtolower(trim((string) ($bici->estado ?? '')));
                            $bateria = (int) ($bici->nivel_bateria ?? 100);

                            return $estado !== 'disponible' || $bateria < 20;
                        }

                        // Caso 2: Solo estación -> si ya fue resuelta en esta sesión, no se muestra
                        $novId = (string) ($item->id_novedad ?? $item->id ?? '');
                        if ($estacionesResueltasIds->contains($novId)) {
                            return false;
                        }

                        // Si la estación ya está 'Activa' o 'Disponible' en BD, no es un reporte pendiente por resolver
                        $estacion = $item->estacion ?? null;
                        $estadoEstacion = strtolower(trim((string) ($estacion->estado ?? '')));

                        if (in_array($estadoEstacion, ['activa', 'disponible'], true)) {
                            return false;
                        }

                        return true;
                    });
                @endphp

                @if($novedadesPendientes->isNotEmpty())
                    <div class="space-y-3">
                        @foreach($novedadesPendientes as $novPendiente)
                            @php
                                $tieneBici = !empty($novPendiente->bicicleta_id);
                                $tieneEstacion = !empty($novPendiente->estacion_id);
                                $biciPendiente = $novPendiente->bicicleta ?? null;
                                $estacionPendiente = $novPendiente->estacion ?? null;

                                if ($tieneBici && $tieneEstacion) {
                                    $alcanceLabel = 'Bici + Estación';
                                } elseif ($tieneBici) {
                                    $alcanceLabel = 'Solo Bici';
                                } else {
                                    $alcanceLabel = 'Solo Estación';
                                }
                            @endphp
                            <div class="p-4 rounded-xl border border-amber-200 bg-amber-50/40">
                                <div class="flex flex-wrap items-center justify-between gap-2 mb-1.5">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <span class="text-xs font-bold text-amber-800 uppercase tracking-wider">
                                            ⚠️ Reporte pendiente (#{{ $novPendiente->id_novedad ?? $novPendiente->id }})
                                        </span>
                                        <span class="px-2 py-0.5 rounded-md text-[11px] font-semibold bg-white text-gray-700 border border-amber-200">
                                            {{ $alcanceLabel }}
                                        </span>
                                    </div>
                                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-800 border border-amber-200">
                                        {{ $novPendiente->tipo_novedad }}
                                    </span>
                                </div>

                                <p class="text-sm font-semibold text-gray-800">
                                    @if($tieneBici)
                                        🚲 Bici #{{ $novPendiente->bicicleta_id }}
                                        @if($biciPendiente)
                                            <span class="text-xs font-semibold text-amber-700">
                                                ({{ $biciPendiente->estado }} · {{ $biciPendiente->nivel_bateria }}%)
                                            </span>
                                        @endif
                                    @endif

                                    @if($tieneBici && $tieneEstacion)
                                        <span class="text-xs font-normal text-gray-500"> · </span>
                                    @endif

                                    @if($tieneEstacion)
                                        <span class="text-xs font-semibold text-gray-700">
                                            🏢 {{ optional($estacionPendiente)->nombre ?? ('Estación #' . $novPendiente->estacion_id) }}
                                            @if(!empty($estacionPendiente->estado))
                                                <span class="text-amber-700 font-semibold">({{ $estacionPendiente->estado }})</span>
                                            @endif
                                        </span>
                                    @endif
                                </p>

                                <p class="text-sm text-gray-600 mt-1">{{ $novPendiente->descripcion }}</p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="py-12 px-4 text-center rounded-xl border border-dashed border-gray-200 bg-gray-50/50">
                        <div class="w-11 h-11 rounded-full bg-emerald-50 text-emerald-700 flex items-center justify-center mx-auto mb-3 text-base font-bold">
                            ✓
                        </div>
                        <p class="text-sm font-semibold text-gray-800">No hay reportes por ahora</p>
                        <p class="text-xs text-gray-400 mt-1 max-w-sm mx-auto">
                            El panel está listo para nuevos registros. Cuando quieras revisar incidencias anteriores, pulsa <span class="font-semibold text-gray-600">Ver historial</span>.
                        </p>
                    </div>
                @endif
            </div>
        </div>

    </div>

</div>

<!-- Modal de Historial Completo -->
<div
    id="modalHistorialIncidencias"
    class="fixed inset-0 z-50 hidden items-center justify-center bg-gray-900/50 backdrop-blur-[1px] p-4"
    onclick="cerrarHistorialAlFondo(event)"
>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-xl w-full max-w-4xl max-h-[85vh] flex flex-col overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <div>
                <h4 class="text-base font-bold text-gray-900">Historial de Incidencias en Ruta</h4>
                <p class="text-xs text-gray-500 mt-0.5">Registro completo de novedades reportadas en campo.</p>
            </div>
            <button
                type="button"
                onclick="cerrarHistorialModal()"
                class="w-8 h-8 rounded-lg text-gray-400 hover:text-gray-700 hover:bg-gray-100 flex items-center justify-center text-sm font-bold transition cursor-pointer"
                aria-label="Cerrar historial"
            >
                ✕
            </button>
        </div>

        <div class="p-6 overflow-y-auto">
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
                                    <span class="block font-medium text-gray-800">
                                        {{ $nov->bicicleta_id ? 'Bici #' . $nov->bicicleta_id : 'Sin bici' }}
                                    </span>
                                    <span class="text-xs text-gray-400">
                                        {{ optional($nov->estacion)->nombre ?? 'Sin estación' }}
                                    </span>
                                </td>
                                <td class="py-3 px-4">
                                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                        {{ $nov->tipo_novedad }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 max-w-xs truncate">{{ $nov->descripcion }}</td>
                                <td class="py-3 px-4 text-xs text-gray-400 whitespace-nowrap">
                                    {{ $nov->created_at ? \Illuminate\Support\Carbon::parse($nov->created_at)->format('d/m/Y H:i') : 'N/A' }}
                                </td>
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

        <div class="px-6 py-3.5 border-t border-gray-100 bg-gray-50/60 flex justify-end">
            <button
                type="button"
                onclick="cerrarHistorialModal()"
                class="bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 px-4 py-1.5 rounded-xl text-xs font-semibold transition cursor-pointer"
            >
                Cerrar
            </button>
        </div>
    </div>
</div>

<script>
    function abrirHistorialModal() {
        const modal = document.getElementById('modalHistorialIncidencias');
        if (!modal) return;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.classList.add('overflow-hidden');
    }

    function cerrarHistorialModal() {
        const modal = document.getElementById('modalHistorialIncidencias');
        if (!modal) return;
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.classList.remove('overflow-hidden');
    }

    function cerrarHistorialAlFondo(event) {
        if (event.target && event.target.id === 'modalHistorialIncidencias') {
            cerrarHistorialModal();
        }
    }

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            cerrarHistorialModal();
        }
    });
</script>
@endsection