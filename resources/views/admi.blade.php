<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Jardín Bike - Operations</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        /* Ocultar scrollbar en móviles para mejor estética lateral */
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="bg-gray-50 min-h-[100dvh] flex flex-col lg:flex-row overflow-x-hidden">

    <!-- Botón Menú Móvil (Hamburguesa) -->
    <div class="lg:hidden bg-[#0A3D27] text-white p-4 flex justify-between items-center sticky top-0 z-50 shadow-md">
        <div class="flex items-center gap-2">
            <div class="bg-white p-1.5 rounded-full text-[#0A3D27] text-sm">🚲</div>
            <h1 class="font-bold text-sm">Jardín Bike <span class="text-[10px] text-emerald-300 font-normal uppercase tracking-widest">Ops</span></h1>
        </div>
        <button id="menu-btn" class="p-2 rounded-xl bg-emerald-900/60 hover:bg-emerald-900 text-white focus:outline-none transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
            </svg>
        </button>
    </div>

    <!-- Sidebar (Fijo en PC, Oculto/Desplegable en Móvil) -->
    <aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-[#0A3D27] text-white flex flex-col p-6 transform -translate-x-full lg:translate-x-0 lg:static transition-transform duration-300 ease-in-out shadow-2xl lg:shadow-none">
        
        <!-- Botón cerrar en móvil -->
        <div class="flex justify-between items-center mb-8 lg:mb-10">
            <div class="flex items-center gap-2">
                <div class="bg-white p-2 rounded-full text-[#0A3D27]">🚲</div>
                <div>
                    <h1 class="font-bold text-sm">Jardín Bike</h1>
                    <p class="text-[10px] text-emerald-300 uppercase tracking-widest">Operations</p>
                </div>
            </div>
            <button id="close-btn" class="lg:hidden text-gray-300 hover:text-white p-1">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <nav class="space-y-2 flex-1 overflow-y-auto no-scrollbar">
            <a href="{{ url('/admin') }}" class="flex items-center gap-3 bg-emerald-900/50 p-3 rounded-xl text-sm font-medium transition">📊 Dashboard</a>
            <a href="{{ url('/inventario') }}" class="flex items-center gap-3 p-3 text-emerald-200 hover:text-white hover:bg-emerald-900/30 rounded-xl transition text-sm">🚲 Inventario</a>
            <a href="{{ url('/estaciones') }}" class="flex items-center gap-3 p-3 text-emerald-200 hover:text-white hover:bg-emerald-900/30 rounded-xl transition text-sm">📍 Estaciones</a>
            <a href="{{ url('/usuarios') }}" class="flex items-center gap-3 p-3 text-emerald-200 hover:text-white hover:bg-emerald-900/30 rounded-xl transition text-sm">👥 Usuarios</a>
            <a href="{{ url('/alertas') }}" class="flex items-center gap-3 p-3 text-emerald-200 hover:text-white hover:bg-emerald-900/30 rounded-xl transition text-sm">⚠️ Alertas</a>
            <a href="{{ url('/reportes') }}" class="flex items-center gap-3 p-3 text-emerald-200 hover:text-white hover:bg-emerald-900/30 rounded-xl transition text-sm">📈 Reportes</a>
            <a href="{{ url('/ajustes') }}" class="flex items-center gap-3 p-3 text-emerald-200 hover:text-white hover:bg-emerald-900/30 rounded-xl transition text-sm">⚙️ Ajustes</a>
        </nav>

        <div class="flex items-center gap-3 pt-6 border-t border-emerald-900 mt-4">
            <div class="bg-amber-400 w-9 h-9 rounded-full flex items-center justify-center font-bold text-[#0A3D27] shrink-0">RT</div>
            <div class="text-xs truncate">
                <p class="font-bold truncate">Richar Torres</p>
                <p class="text-emerald-300">Operador · Jardín</p>
            </div>
        </div>
    </aside>

    <!-- Overlay oscuro para móvil al abrir menú -->
    <div id="overlay" class="fixed inset-0 bg-black/50 z-40 hidden lg:hidden"></div>

    <!-- Contenido Principal -->
    <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-x-hidden">
        <header class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 sm:mb-8">
            <div>
                <p class="text-[11px] sm:text-xs text-gray-500 uppercase tracking-widest font-semibold">Panel Operativo</p>
                <h2 class="text-xl sm:text-2xl font-bold text-gray-900">Buenos días, Richar 🍃</h2>
            </div>

            <a href="{{ url('/') }}" class="w-full sm:w-auto">
                <button class="w-full sm:w-auto bg-[#FFBC00] hover:bg-[#F0B000] text-[#101828] px-4 py-2.5 rounded-xl text-sm font-medium transition shadow-sm text-center">
                    Ver app cliente
                </button>
            </a>
        </header>

        <!-- Tarjetas de Estadísticas (Responsive Grid) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-6 sm:mb-8">
            <div class="bg-white p-5 sm:p-6 rounded-2xl border border-gray-100 shadow-sm"><p class="text-[11px] sm:text-xs text-gray-500 font-medium">BICICLETAS ACTIVAS</p><p class="text-2xl sm:text-3xl font-bold text-gray-900 mt-1">47</p></div>
            <div class="bg-white p-5 sm:p-6 rounded-2xl border border-gray-100 shadow-sm"><p class="text-[11px] sm:text-xs text-gray-500 font-medium">VIAJES HOY</p><p class="text-2xl sm:text-3xl font-bold text-gray-900 mt-1">184</p></div>
            <div class="bg-white p-5 sm:p-6 rounded-2xl border border-gray-100 shadow-sm"><p class="text-[11px] sm:text-xs text-gray-500 font-medium">ALERTAS CRÍTICAS</p><p class="text-2xl sm:text-3xl font-bold text-red-500 mt-1">3</p></div>
            <div class="bg-white p-5 sm:p-6 rounded-2xl border border-gray-100 shadow-sm"><p class="text-[11px] sm:text-xs text-gray-500 font-medium">INGRESOS DEL DÍA</p><p class="text-2xl sm:text-3xl font-bold text-gray-900 mt-1">$736.000</p></div>
        </div>

        <!-- Tabla con Scroll Horizontal Responsivo -->
        <div class="bg-white p-5 sm:p-6 rounded-2xl border border-gray-100 shadow-sm">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-6">
                <h3 class="font-bold text-base sm:text-lg text-gray-900">Control de inventario</h3>
                <button class="bg-emerald-900 hover:bg-emerald-800 text-white px-4 py-2 rounded-xl text-xs font-medium transition w-full sm:w-auto">Exportar CSV</button>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left min-w-[500px]">
                    <thead>
                        <tr class="text-gray-400 text-xs uppercase tracking-wider border-b border-gray-100">
                            <th class="pb-3 font-semibold">ID</th>
                            <th class="pb-3 font-semibold">UBICACIÓN</th>
                            <th class="pb-3 font-semibold">BATERÍA</th>
                            <th class="pb-3 font-semibold">ESTADO</th>
                            <th class="pb-3 font-semibold">ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr>
                            <td class="py-4 font-semibold text-gray-900">BICI-012</td>
                            <td class="py-4 text-gray-600">Parque Principal</td>
                            <td class="py-4 text-red-500 font-medium">Crítico</td>
                            <td class="py-4"><span class="bg-amber-50 text-amber-700 px-2.5 py-1 rounded-full text-xs font-medium">Mantenimiento</span></td>
                            <td class="py-4 text-gray-400 font-bold tracking-widest cursor-pointer hover:text-gray-600">...</td>
                        </tr>
                        <tr>
                            <td class="py-4 font-semibold text-gray-900">BICI-031</td>
                            <td class="py-4 text-gray-600">Estación Cascada</td>
                            <td class="py-4 text-emerald-600 font-medium">92% saludable</td>
                            <td class="py-4"><span class="bg-emerald-50 text-emerald-700 px-2.5 py-1 rounded-full text-xs font-medium">Disponible</span></td>
                            <td class="py-4 text-gray-400 font-bold tracking-widest cursor-pointer hover:text-gray-600">...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- Script de control para menú móvil lateral -->
    <script>
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');
        const menuBtn = document.getElementById('menu-btn');
        const closeBtn = document.getElementById('close-btn');

        function toggleMenu() {
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }

        menuBtn.addEventListener('click', toggleMenu);
        closeBtn.addEventListener('click', toggleMenu);
        overlay.addEventListener('click', toggleMenu);
    </script>
</body>
</html>