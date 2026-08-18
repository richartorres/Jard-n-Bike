@extends('layouts.app')

@section('content')
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Gestión de Usuarios</h2>
                <p class="text-xs text-gray-500 mt-0.5">Control de cuentas de clientes y administradores.</p>
            </div>
            <!-- Buscador en tiempo real -->
            <div class="w-full sm:w-auto">
                <input type="text" id="searchInput" placeholder="Buscar por nombre o correo..." 
                    class="w-full sm:w-64 border border-gray-200 rounded-xl px-3.5 py-2 text-xs focus:outline-none focus:border-emerald-600 transition">
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-100 text-[11px] text-gray-400 font-semibold uppercase tracking-wider">
                        <th class="py-3 px-4">Nombre</th>
                        <th class="py-3 px-4">Correo Electrónico</th>
                        <th class="py-3 px-4">Rol</th>
                        <th class="py-3 px-4">Registro</th>
                        <th class="py-3 px-4 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="text-xs text-gray-700 divide-y divide-gray-50" id="userTableBody">
                    @forelse($usuarios ?? [] as $userItem)
                        <tr class="hover:bg-gray-50/50 transition user-row">
                            <td class="py-4 px-4 font-bold text-gray-900 user-name">{{ $userItem->name }}</td>
                            <td class="py-4 px-4 text-gray-500 user-email">{{ $userItem->email }}</td>
                            <td class="py-4 px-4">
                                <span class="px-2.5 py-1 rounded-full text-[11px] font-semibold {{ $userItem->role === 'admin' ? 'bg-amber-50 text-amber-600' : 'bg-blue-50 text-blue-600' }}">
                                    {{ ucfirst($userItem->role) }}
                                </span>
                            </td>
                            <td class="py-4 px-4 text-gray-400">{{ $userItem->created_at ? $userItem->created_at->format('d/m/Y') : 'N/A' }}</td>
                            <td class="py-4 px-4 text-right font-medium relative">
                                <!-- Menú de Acciones desplegable -->
                                <div class="relative inline-block text-left" x-data="{ open: false }">
                                    <button @click="open = !open" @click.away="open = false" class="text-gray-400 hover:text-gray-600 p-1.5 rounded-lg hover:bg-gray-100 transition">
                                        •••
                                    </button>

                                    <div x-show="open" style="display: none;" 
                                         class="absolute right-0 mt-2 w-48 bg-white border border-gray-100 rounded-xl shadow-lg z-50 py-1 text-left">
                                        
                                        <!-- Cambiar Rol -->
                                        <form action="{{ route('admin.usuarios.update-role', $userItem->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="role" value="{{ $userItem->role === 'admin' ? 'cliente' : 'admin' }}">
                                            <button type="submit" class="w-full text-left px-4 py-2 text-xs text-gray-700 hover:bg-gray-50 flex items-center gap-2">
                                                <span>🔄</span> Cambiar a {{ $userItem->role === 'admin' ? 'Cliente' : 'Admin' }}
                                            </button>
                                        </form>

                                        <!-- Eliminar Usuario (Evita borrar tu propio usuario admin activo) -->
                                        @if(auth()->id() !== $userItem->id)
                                            <form action="{{ route('admin.usuarios.destroy', $userItem->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este usuario permanentemente?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-full text-left px-4 py-2 text-xs text-red-600 hover:bg-red-50 flex items-center gap-2">
                                                    <span>🗑️</span> Eliminar cuenta
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-gray-400 text-xs">
                                No se encontraron usuarios registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Script para el Buscador instantáneo y soporte de AlpineJS para el menú -->
    <script src="//unpkg.com/alpinejs" defer></script>
    <script>
        document.getElementById('searchInput').addEventListener('keyup', function() {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll('#userTableBody tr.user-row');

            rows.forEach(row => {
                let name = row.querySelector('.user-name').textContent.toLowerCase();
                let email = row.querySelector('.user-email').textContent.toLowerCase();
                
                if (name.includes(filter) || email.includes(filter)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    </script>
@endsection