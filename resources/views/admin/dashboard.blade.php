@extends('layouts.app')

@section('content')
    <!-- Tarjetas de Estadísticas Reales -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-6 sm:mb-8">
        <div class="bg-white p-5 sm:p-6 rounded-2xl border border-gray-100 shadow-sm">
            <p class="text-[11px] sm:text-xs text-gray-500 font-medium">BICICLETAS ACTIVAS</p>
            <p class="text-2xl sm:text-3xl font-bold text-gray-900 mt-1">{{ $bicicletasActivas ?? 0 }}</p>
        </div>
        <div class="bg-white p-5 sm:p-6 rounded-2xl border border-gray-100 shadow-sm">
            <p class="text-[11px] sm:text-xs text-gray-500 font-medium">VIAJES</p>
            <p class="text-2xl sm:text-3xl font-bold text-gray-900 mt-1">{{ $viajesHoy ?? 0 }}</p>
        </div>
        <div class="bg-white p-5 sm:p-6 rounded-2xl border border-gray-100 shadow-sm">
            <p class="text-[11px] sm:text-xs text-gray-500 font-medium">ALERTAS CRÍTICAS</p>
            <p class="text-2xl sm:text-3xl font-bold text-red-500 mt-1">{{ $alertasCriticas ?? 0 }}</p>
        </div>
        <div class="bg-white p-5 sm:p-6 rounded-2xl border border-gray-100 shadow-sm">
            <p class="text-[11px] sm:text-xs text-gray-500 font-medium">INGRESOS</p>
            <p class="text-2xl sm:text-3xl font-bold text-gray-900 mt-1">${{ number_format($ingresosHoy ?? 0, 0, ',', '.') }}</p>
        </div>
    </div>

    <!-- Accesos Rápidos para Operaciones Clave -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">
        <div class="bg-emerald-900 text-white p-5 rounded-2xl flex justify-between items-center shadow-md">
            <div>
                <h3 class="font-bold text-base">Gestión de Bicicletas</h3>
                <p class="text-xs text-emerald-200 mt-0.5">Agrega nuevas unidades al sistema.</p>
            </div>
            <button onclick="openModal('modal-bici')" class="bg-white text-emerald-900 hover:bg-emerald-50 px-4 py-2 rounded-xl text-xs font-bold transition shadow">
                + Agregar Bici
            </button>
        </div>

        <div class="bg-[#101828] text-white p-5 rounded-2xl flex justify-between items-center shadow-md">
            <div>
                <h3 class="font-bold text-base">Gestión de Estaciones</h3>
                <p class="text-xs text-gray-300 mt-0.5">Da de alta nuevos puntos de anclaje.</p>
            </div>
            <button onclick="openModal('modal-estacion')" class="bg-[#FFBC00] hover:bg-[#F0B000] text-[#101828] px-4 py-2 rounded-xl text-xs font-bold transition shadow">
                + Nueva Estación
            </button>
        </div>
    </div>
@endsection