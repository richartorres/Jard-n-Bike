@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    {{-- Encabezado y Botón de Exportación --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
        <div>
            <span class="text-xs font-bold uppercase tracking-wider text-emerald-700">Auditoría & Inteligencia de Negocio</span>
            <h1 class="text-3xl font-extrabold text-slate-900">Reporte Financiero Histórico</h1>
        </div>
        <a href="{{ route('admin.reportes.pdf', ['fecha_inicio' => $fechaInicio, 'fecha_fin' => $fechaFin]) }}"
           class="inline-flex items-center gap-2 bg-amber-400 hover:bg-amber-500 text-slate-900 font-bold px-5 py-2.5 rounded-xl shadow transition">
            📄 Descargar Reporte PDF
        </a>
    </div>

    {{-- Filtros de Fecha --}}
    <form method="GET" action="{{ route('admin.reportes') }}" class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 mb-8 flex flex-wrap items-end gap-4">
        <div>
            <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Desde</label>
            <input type="date" name="fecha_inicio" value="{{ $fechaInicio }}" class="border-slate-300 rounded-xl px-3 py-2 text-sm">
        </div>
        <div>
            <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Hasta</label>
            <input type="date" name="fecha_fin" value="{{ $fechaFin }}" class="border-slate-300 rounded-xl px-3 py-2 text-sm">
        </div>
        <button type="submit" class="bg-emerald-900 hover:bg-emerald-800 text-white font-semibold px-6 py-2 rounded-xl text-sm transition">
            Aplicar Rango
        </button>
    </form>

    {{-- Tarjetas KPI --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
            <p class="text-xs font-bold uppercase text-slate-400">Viajes Completados</p>
            <p class="text-4xl font-extrabold text-slate-900 mt-2">{{ $data->total_viajes_realizados ?? 0 }}</p>
            <p class="text-xs text-emerald-600 font-medium mt-2">En el periodo seleccionado</p>
        </div>
        <div class="bg-emerald-950 text-white p-6 rounded-2xl shadow-md">
            <p class="text-xs font-bold uppercase text-emerald-300">Ingresos Totales</p>
            <p class="text-4xl font-extrabold mt-2">${{ number_format($data->ingresos_generados ?? 0, 0, ',', '.') }}</p>
            <p class="text-xs text-emerald-400 mt-2">Recaudación neta verificada</p>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
            <p class="text-xs font-bold uppercase text-slate-400">Ticket Promedio / Viaje</p>
            <p class="text-4xl font-extrabold text-slate-900 mt-2">${{ number_format($data->costo_promedio_viaje ?? 0, 0, ',', '.') }}</p>
            <p class="text-xs text-slate-500 mt-2">Promedio por alquiler</p>
        </div>
    </div>

    {{-- Gráfica de Evolución Histórica --}}
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 mb-8">
        <h2 class="text-lg font-bold text-slate-800 mb-4">Comportamiento Histórico de Ingresos</h2>
        <div class="h-72">
            <canvas id="graficaIngresos"></canvas>
        </div>
    </div>

    {{-- Tabla de Desglose Diario --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100">
            <h3 class="font-bold text-slate-800">Desglose por Fecha</h3>
        </div>
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50 text-slate-500 uppercase text-xs">
                <tr>
                    <th class="px-6 py-3">Fecha</th>
                    <th class="px-6 py-3">Viajes</th>
                    <th class="px-6 py-3">Ticket Promedio</th>
                    <th class="px-6 py-3 text-right">Ingresos del Día</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($historico as $fila)
                <tr class="hover:bg-slate-50">
                    <td class="px-6 py-3 font-medium text-slate-800">{{ \Carbon\Carbon::parse($fila->fecha)->format('d/m/Y') }}</td>
                    <td class="px-6 py-3">{{ $fila->viajes }}</td>
                    <td class="px-6 py-3">${{ number_format($fila->ticket_promedio, 0, ',', '.') }}</td>
                    <td class="px-6 py-3 text-right font-bold text-emerald-700">${{ number_format($fila->ingresos, 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-8 text-center text-slate-400">No hay viajes completados en este rango de fechas.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const historicoData = {!! json_encode($historico) !!};
    const labels = historicoData.map(item => item.fecha);
    const ingresos = historicoData.map(item => item.ingresos);

    new Chart(document.getElementById('graficaIngresos'), {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Ingresos ($ COP)',
                data: ingresos,
                backgroundColor: '#064e3b',
                borderRadius: 8,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    });
</script>
@endsection