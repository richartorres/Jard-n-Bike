@extends('layouts.app')

@section('content')
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Gestión de Usuarios</h2>
                <p class="text-xs text-gray-500 mt-0.5">Control de cuentas de clientes y administradores.</p>
            </div>
            <div class="flex gap-2">
                <input type="text" placeholder="Buscar usuario..." class="border border-gray-200 rounded-xl px-3 py-1.5 text-xs focus:outline-none focus:border-emerald-600">
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
                <tbody class="text-xs text-gray-700 divide-y divide-gray-50">
                    @forelse($usuarios ?? [] as $userItem)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="py-4 px-4 font-bold text-gray-900">{{ $userItem->name }}</td>
                            <td class="py-4 px-4 text-gray-500">{{ $userItem->email }}</td>
                            <td class="py-4 px-4">
                                <span class="px-2.5 py-1 rounded-full text-[11px] font-semibold {{ $userItem->role === 'admin' ? 'bg-amber-50 text-amber-600' : 'bg-blue-50 text-blue-600' }}">
                                    {{ ucfirst($userItem->role) }}
                                </span>
                            </td>
                            <td class="py-4 px-4 text-gray-400">{{ $userItem->created_at ? $userItem->created_at->format('d/m/Y') : 'N/A' }}</td>
                            <td class="py-4 px-4 text-right font-medium">
                                <button class="text-gray-400 hover:text-gray-600">•••</button>
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
@endsection