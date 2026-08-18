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
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="bg-gray-50 min-h-[100dvh] flex flex-col lg:flex-row overflow-x-hidden">

  <!-- Botón Menú Móvil (Hamburguesa) - MOVIDO A LA IZQUIERDA -->
    <div class="lg:hidden bg-[#0A3D27] text-white p-4 flex items-center gap-3 sticky top-0 z-50 shadow-md">
        <button id="menu-btn" class="p-2 rounded-xl bg-emerald-900/60 hover:bg-emerald-900 text-white focus:outline-none transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
            </svg>
        </button>
        <div class="flex items-center gap-2">
            <div class="bg-white p-1.5 rounded-full text-[#0A3D27] text-sm">🚲</div>
            <h1 class="font-bold text-sm">Jardín Bike <span class="text-[10px] text-emerald-300 font-normal uppercase tracking-widest">Ops</span></h1>
        </div>
    </div>

    <!-- Sidebar Estático -->
    <aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-[#0A3D27] text-white flex flex-col p-6 transform -translate-x-full lg:translate-x-0 lg:static transition-transform duration-300 ease-in-out shadow-2xl lg:shadow-none">
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
            <a href="{{ url('/admin') }}" class="flex items-center gap-3 p-3 rounded-xl text-sm font-medium transition {{ Request::is('admin*') ? 'bg-emerald-900/50 text-white' : 'text-emerald-200 hover:text-white hover:bg-emerald-900/30' }}">📊 Dashboard</a>
            <a href="{{ url('/inventario') }}" class="flex items-center gap-3 p-3 rounded-xl text-sm font-medium transition {{ Request::is('inventario*') ? 'bg-emerald-900/50 text-white' : 'text-emerald-200 hover:text-white hover:bg-emerald-900/30' }}">🚲 Inventario</a>
            <a href="{{ url('/estaciones') }}" class="flex items-center gap-3 p-3 rounded-xl text-sm font-medium transition {{ Request::is('estaciones*') ? 'bg-emerald-900/50 text-white' : 'text-emerald-200 hover:text-white hover:bg-emerald-900/30' }}">📍 Estaciones</a>
            <a href="{{ url('/usuarios') }}" class="flex items-center gap-3 p-3 rounded-xl text-sm font-medium transition {{ Request::is('usuarios*') ? 'bg-emerald-900/50 text-white' : 'text-emerald-200 hover:text-white hover:bg-emerald-900/30' }}">👥 Usuarios</a>
            <a href="{{ url('/alertas') }}" class="flex items-center gap-3 p-3 rounded-xl text-sm font-medium transition {{ Request::is('alertas*') ? 'bg-emerald-900/50 text-white' : 'text-emerald-200 hover:text-white hover:bg-emerald-900/30' }}">⚠️ Alertas</a>
        </nav>
    </aside>

    <!-- Overlay móvil -->
    <div id="overlay" class="fixed inset-0 bg-black/50 z-40 hidden lg:hidden"></div>

    <!-- Contenido Principal -->
    <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-x-hidden flex flex-col justify-between">
        
        <div>
            <!-- AQUÍ SE INYECTA EL CONTENIDO DE CADA VISTA (INVENTARIO, DASHBOARD, ETC.) -->
            @yield('content')
        </div>

        <!-- Botón conectado para ver la app cliente (Movido abajo a la derecha) -->
        <div class="flex justify-end mt-12 mb-4">
            <a href="{{ url('/mapa') }}">
                <button class="bg-[#FFBC00] hover:bg-[#F0B000] text-[#101828] px-5 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider transition shadow-sm text-center flex items-center justify-center gap-2">
                    <span>🗺️</span> VER APP CLIENTE
                </button>
            </a>
        </div>
    </main>

    <!-- Script de control para menú móvil lateral y modales -->
    <script>
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');
        const menuBtn = document.getElementById('menu-btn');
        const closeBtn = document.getElementById('close-btn');

        function toggleMenu() {
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }

        if (menuBtn) menuBtn.addEventListener('click', toggleMenu);
        if (closeBtn) closeBtn.addEventListener('click', toggleMenu);
        if (overlay) overlay.addEventListener('click', toggleMenu);

        // FUNCIONES GLOBALES PARA MODALES
        window.openModal = function(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) modal.classList.remove('hidden');
        }

        window.closeModal = function(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) modal.classList.add('hidden');
        }
    </script>
</body>
</html>