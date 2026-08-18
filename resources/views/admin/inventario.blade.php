@extends('layouts.app')

@section('content')

    <!-- Tabla de Inventario conectada a la Base de Datos -->
    <div class="bg-white p-5 sm:p-6 rounded-2xl border border-gray-100 shadow-sm">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-6">
            <div>
                <h3 class="font-bold text-base sm:text-lg text-gray-900">Control de inventario</h3>
                <p class="text-xs text-gray-500 mt-0.5">Administra las bicicletas y su estado operativo actual.</p>
            </div>
            <div class="flex items-center gap-2 w-full sm:w-auto">
                <button onclick="openModal('modal-bici')" class="bg-emerald-900 hover:bg-emerald-800 text-white px-4 py-2 rounded-xl text-xs font-medium transition flex-1 sm:flex-none text-center">
                    + Agregar Bici
                </button>
                <button class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-xl text-xs font-medium transition flex-1 sm:flex-none text-center">
                    Exportar CSV
                </button>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left min-w-[500px]">
                <thead>
                    <tr class="text-gray-400 text-xs uppercase tracking-wider border-b border-gray-100">
                        <th class="pb-3 font-semibold">ID</th>
                        <th class="pb-3 font-semibold">UBICACIÓN</th>
                        <th class="pb-3 font-semibold">BATERÍA</th>
                        <th class="pb-3 font-semibold">ESTADO</th>
                        <th class="pb-3 font-semibold text-right">ACCIONES</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($bicicletas ?? [] as $bici)
                        <tr>
                            <td class="py-4 font-semibold text-gray-900">BICI-{{ $bici->id_bicicleta ?? $bici->id }}</td>
                            <td class="py-4 text-gray-600">{{ $bici->estacionOrigen->nombre ?? 'Sin estación' }}</td>
                            <td class="py-4 {{ ($bici->nivel_bateria ?? 100) < 20 ? 'text-red-500' : 'text-emerald-600' }} font-medium">
                                {{ $bici->nivel_bateria ?? 100 }}%
                            </td>
                            <td class="py-4">
                                <span class="px-2.5 py-1 rounded-full text-xs font-medium 
                                    {{ ($bici->estado ?? '') == 'Mantenimiento' ? 'bg-amber-50 text-amber-700' : 'bg-emerald-50 text-emerald-700' }}">
                                    {{ $bici->estado ?? 'Disponible' }}
                                </span>
                            </td>
                            <td class="py-4 relative text-right">
                                <button onclick="toggleDropdown(event, 'dropdown-{{ $bici->id_bicicleta ?? $bici->id }}')" class="p-2 text-gray-400 hover:text-gray-600 rounded-lg hover:bg-gray-100 transition">
                                    <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01"></path>
                                    </svg>
                                </button>

                                <div id="dropdown-{{ $bici->id_bicicleta ?? $bici->id }}" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-2xl shadow-xl border border-gray-100 z-30 py-1 text-left">
                                    <form action="{{ url('/bicicletas/' . ($bici->id_bicicleta ?? $bici->id) . '/estado') }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="estado" value="{{ ($bici->estado ?? '') == 'Mantenimiento' ? 'Disponible' : 'Mantenimiento' }}">
                                        <button type="submit" class="w-full text-left px-4 py-2.5 text-xs font-medium text-gray-700 hover:bg-gray-50 flex items-center gap-2">
                                            <span>🔧</span> {{ ($bici->estado ?? '') == 'Mantenimiento' ? 'Marcar Disponible' : 'Enviar a Mantenimiento' }}
                                        </button>
                                    </form>

                                    <form action="{{ url('/bicicletas/' . ($bici->id_bicicleta ?? $bici->id)) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar esta bicicleta?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-full text-left px-4 py-2.5 text-xs font-medium text-red-600 hover:bg-red-50 flex items-center gap-2 border-t border-gray-100">
                                            <span>🗑️</span> Eliminar unidad
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-6 text-center text-gray-500">No hay bicicletas registradas en la base de datos.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- MODAL AGREGAR BICICLETA -->
    <div id="modal-bici" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-bold text-lg text-gray-900">🚲 Registrar Nueva Bicicleta</h3>
                <button type="button" onclick="closeModal('modal-bici')" class="text-gray-400 hover:text-gray-600 font-bold">✕</button>
            </div>
            <form action="{{ url('/bicicletas') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Código QR / Identificador</label>
                    <input type="text" name="codigo_qr" required placeholder="Ej. QR-JARDIN-004" class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-emerald-700">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Modelo</label>
                    <input type="text" name="modelo" required placeholder="Ej. Eléctrica Urbana" class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-emerald-700">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Número de Serie</label>
                    <input type="text" name="num_serie" required placeholder="Ej. SN-998234" class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-emerald-700">
                </div>
                <div class="flex justify-end gap-2 pt-3">
                    <button type="button" onclick="closeModal('modal-bici')" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-xl text-xs font-medium">Cancelar</button>
                    <button type="submit" class="bg-emerald-900 hover:bg-emerald-800 text-white px-4 py-2 rounded-xl text-xs font-medium">Guardar Bicicleta</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Scripts de Modales y Dropdowns -->
    <script>
        function openModal(modalId) {
            document.getElementById(modalId).classList.remove('hidden');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }

        function toggleDropdown(event, dropdownId) {
            event.stopPropagation();
            document.querySelectorAll('[id^="dropdown-"]').forEach(el => {
                if (el.id !== dropdownId) el.classList.add('hidden');
            });
            const dropdown = document.getElementById(dropdownId);
            dropdown.classList.toggle('hidden');
        }

        window.addEventListener('click', () => {
            document.querySelectorAll('[id^="dropdown-"]').forEach(el => {
                el.classList.add('hidden');
            });
        });
    </script>

@endsection