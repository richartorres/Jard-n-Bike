@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    {{-- Encabezado y Botón de Exportación --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
        <div>
            <span class="text-xs font-bold uppercase tracking-wider text-emerald-700">Auditoría & Inteligencia de Negocio</span>
            <h1 class="text-3xl font-extrabold text-slate-900">Reporte Financiero Histórico</h1>
        </div>
        <div class="flex items-center gap-3">
            <button onclick="notificarExportacion()" class="inline-flex items-center gap-2 bg-emerald-900 hover:bg-emerald-800 text-white font-bold px-5 py-2.5 rounded-xl shadow transition">
                📊 Generar Alerta de Resumen
            </button>
            <a href="{{ route('admin.reportes.pdf', ['fecha_inicio' => $fechaInicio, 'fecha_fin' => $fechaFin]) }}"
               class="inline-flex items-center gap-2 bg-amber-400 hover:bg-amber-500 text-slate-900 font-bold px-5 py-2.5 rounded-xl shadow transition">
                📄 Descargar Reporte PDF
            </a>
        </div>
    </div>

    {{-- Filtros de Fecha --}}
    <form method="GET" action="{{ route('admin.reportes') }}" id="filtroForm" class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 mb-8 flex flex-wrap items-end gap-4">
        <div>
            <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Desde</label>
            <input type="date" name="fecha_inicio" value="{{ $fechaInicio }}" class="border-slate-300 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-emerald-500">
        </div>
        <div>
            <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Hasta</label>
            <input type="date" name="fecha_fin" value="{{ $fechaFin }}" class="border-slate-300 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-emerald-500">
        </div>
        <button type="submit" class="bg-emerald-900 hover:bg-emerald-800 text-white font-semibold px-6 py-2 rounded-xl text-sm transition shadow-sm">
            Aplicar Rango
        </button>
    </form>

    {{-- Tarjetas KPI Estadísticas --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        {{-- Tarjeta KPI de Ingresos Totales Optimizada --}}
        <div class="bg-emerald-950 text-white p-6 rounded-2xl shadow-md flex items-center justify-between col-span-1 md:col-span-1 overflow-hidden">
            <div class="min-w-0 flex-1 pr-3">
                <p class="text-xs font-bold uppercase text-emerald-300 tracking-wider">Ingresos Totales</p>
                <p class="text-2xl sm:text-3xl font-extrabold mt-2 truncate">${{ number_format($data->ingresos_generados ?? 0, 0, ',', '.') }}</p>
                <p class="text-xs text-emerald-400 mt-2">💰 Recaudación neta verificada</p>
            </div>
            <div class="bg-emerald-900/60 p-3.5 rounded-2xl text-amber-400 text-xl shrink-0">📈</div>
        </div>

        <div class="bg-emerald-950 text-white p-6 rounded-2xl shadow-md flex items-center justify-between">
            <div class="overflow-hidden pr-2">
                <p class="text-xs font-bold uppercase text-emerald-300">Ingresos Totales</p>
                <p class="text-3xl font-extrabold mt-2 tracking-tight">${{ number_format($data->ingresos_generados ?? 0, 0, ',', '.') }}</p>
                <p class="text-xs text-emerald-400 mt-2">💰 Recaudación neta verificada</p>
            </div>
            <div class="bg-emerald-900/60 p-3.5 rounded-2xl text-amber-400 text-xl shrink-0">📈</div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-bold uppercase text-slate-400">Ticket Promedio / Viaje</p>
                <p class="text-4xl font-extrabold text-slate-900 mt-2">${{ number_format($data->costo_promedio_viaje ?? 0, 0, ',', '.') }}</p>
                <p class="text-xs text-slate-500 mt-2">📊 Media por alquiler</p>
            </div>
            <div class="bg-slate-50 p-4 rounded-2xl text-slate-700 text-2xl">⚡</div>
        </div>
    </div>

    {{-- Gráfica de Evolución Histórica --}}
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 mb-8">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-bold text-slate-800">Comportamiento Histórico de Ingresos</h2>
            <span class="text-xs bg-emerald-50 text-emerald-700 font-semibold px-3 py-1 rounded-full">Actualizado en vivo</span>
        </div>
        <div class="h-72">
            <canvas id="graficaIngresos"></canvas>
        </div>
    </div>

    {{-- Tabla de Desglose Diario --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center">
            <h3 class="font-bold text-slate-800">Desglose por Fecha</h3>
            <span class="text-xs text-slate-400">Total registros: {{ count($historico) }}</span>
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
                <tr class="hover:bg-slate-50 transition">
                    <td class="px-6 py-3 font-medium text-slate-800">{{ \Carbon\Carbon::parse($fila->fecha)->format('d/m/Y') }}</td>
                    <td class="px-6 py-3"><span class="bg-slate-100 text-slate-700 px-2.5 py-1 rounded-lg text-xs font-bold">{{ $fila->viajes }} viajes</span></td>
                    <td class="px-6 py-3 font-semibold text-slate-600">${{ number_format($fila->ticket_promedio, 0, ',', '.') }}</td>
                    <td class="px-6 py-3 text-right font-extrabold text-emerald-700">${{ number_format($fila->ingresos, 0, ',', '.') }}</td>
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

{{-- Scripts de Chart.js y SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Gráfica de Ingresos
    const historicoData = {!! json_encode($historico) !!};
    const labels = historicoData.map(item => item.fecha);
    const ingresos = historicoData.map(item => item.ingresos);

        const ctx = document.getElementById('graficaIngresos').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Ingresos ($ COP)',
                    data: ingresos,
                    backgroundColor: '#064e3b',
                    borderRadius: 8,
                    hoverBackgroundColor: '#047857'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { 
                    y: { 
                        beginAtZero: true, 
                        grid: { color: '#f1f5f9' } 
                    },
                    x: { 
                        grid: { display: false },
                        ticks: {
                            maxRotation: 45, // Rota las fechas automáticamente si se acumulan
                            minRotation: 0,
                            autoSkip: true,  // Oculta fechas salteadas si hay demasiadas para evitar amontonamiento
                            maxTicksLimit: 10 // Límite máximo de fechas visibles simultáneamente
                        }
                    }
                }
            }
        });
    // Función de Alerta Elegante con SweetAlert2
    function notificarExportacion() {
        Swal.fire({
            title: '¡Resumen Financiero Listado!',
            text: 'Los datos del rango seleccionado se encuentran listos para auditoría o exportación.',
            icon: 'success',
            confirmButtonText: 'Entendido',
            confirmButtonColor: '#064e3b',
            background: '#ffffff',
            customClass: {
                popup: 'rounded-2xl shadow-xl'
            }
        });
    }
</script>
@endsection