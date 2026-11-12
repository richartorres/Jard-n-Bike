@extends('layouts.admin') {{-- O el layout principal que utilices en tu panel --}}

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-6">Reporte Financiero y Operativo</h1>

    {{-- Formulario para filtrar por fechas --}}
    <form method="GET" action="{{ route('admin.reportes') }}" class="mb-6 flex gap-4 items-center bg-white p-4 shadow rounded-lg">
        <div>
            <label class="block text-sm font-medium text-gray-700">Fecha Inicio:</label>
            <input type="date" name="fecha_inicio" value="{{ $fechaInicio }}" class="border rounded p-2">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Fecha Fin:</label>
            <input type="date" name="fecha_fin" value="{{ $fechaFin }}" class="border rounded p-2">
        </div>
        <div class="mt-5">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Filtrar</button>
        </div>
    </form>

    {{-- Resultados del Procedimiento Almacenado --}}
    @php
        $data = $reporte[0] ?? null;
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 shadow rounded-lg border-l-4 border-green-500">
            <p class="text-gray-500 text-sm">Total Viajes Realizados</p>
            <p class="text-3xl font-bold">{{ $data->total_viajes_realizados ?? 0 }}</p>
        </div>
        <div class="bg-white p-6 shadow rounded-lg border-l-4 border-blue-500">
            <p class="text-gray-500 text-sm">Ingresos Generados</p>
            <p class="text-3xl font-bold">${{ number_format($data->ingresos_generados ?? 0, 2) }}</p>
        </div>
        <div class="bg-white p-6 shadow rounded-lg border-l-4 border-yellow-500">
            <p class="text-gray-500 text-sm">Costo Promedio por Viaje</p>
            <p class="text-3xl font-bold">${{ number_format($data->costo_promedio_viaje ?? 0, 2) }}</p>
        </div>
    </div>
</div>
@endsection