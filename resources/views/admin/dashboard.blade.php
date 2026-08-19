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

    <!-- Sidebar -->
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
            <a href="{{ url('/admin') }}" class="flex items-center gap-3 bg-emerald-900/50 p-3 rounded-xl text-sm font-medium transition">📊 Dashboard</a>
            <a href="{{ url('/inventario') }}" class="flex items-center gap-3 p-3 text-emerald-200 hover:text-white hover:bg-emerald-900/30 rounded-xl transition text-sm">🚲 Inventario</a>
            <a href="{{ url('/estaciones') }}" class="flex items-center gap-3 p-3 text-emerald-200 hover:text-white hover:bg-emerald-900/30 rounded-xl transition text-sm">📍 Estaciones</a>
            <a href="{{ url('/usuarios') }}" class="flex items-center gap-3 p-3 text-emerald-200 hover:text-white hover:bg-emerald-900/30 rounded-xl transition text-sm">👥 Usuarios</a>
            <a href="{{ url('/alertas') }}" class="flex items-center gap-3 p-3 text-emerald-200 hover:text-white hover:bg-emerald-900/30 rounded-xl transition text-sm">⚠️ Alertas</a>
        </nav>

        <!-- Datos Dinámicos del Usuario Logueado -->
        <div class="flex items-center gap-3 pt-6 border-t border-emerald-900 mt-4">
            <div class="bg-amber-400 w-9 h-9 rounded-full flex items-center justify-center font-bold text-[#0A3D27] shrink-0">
                {{ strtoupper(substr($user->name ?? 'Admin', 0, 2)) }}
            </div>
            <div class="text-xs truncate">
                <p class="font-bold truncate">{{ $user->name ?? 'Usuario' }}</p>
                <p class="text-emerald-300">Administrador · Jardín</p>
            </div>
        </div>
    </aside>

    <!-- Overlay móvil -->
    <div id="overlay" class="fixed inset-0 bg-black/50 z-40 hidden lg:hidden"></div>

    <!-- Contenido Principal -->
    <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-x-hidden">
        <header class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 sm:mb-8">
            <div>
                <p class="text-[11px] sm:text-xs text-gray-500 uppercase tracking-widest font-semibold">Panel Operativo</p>
                <h2 class="text-xl sm:text-2xl font-bold text-gray-900">Saludos, {{ explode(' ', trim($user->name ?? 'Richar'))[0] }} 🚲</h2>
            </div>

            <!-- Botón conectado para ver la app cliente -->
            <a href="{{ url('/mapa') }}" class="w-full sm:w-auto">
                <button class="w-full sm:w-auto bg-[#FFBC00] hover:bg-[#F0B000] text-[#101828] px-4 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider transition shadow-sm text-center flex items-center justify-center gap-2">
                    <span>🗺️</span> VER APP CLIENTE
                </button>
            </a>
        </header>

        <!-- Tarjetas de Estadísticas Reales -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-6 sm:mb-8">
            <div class="bg-white p-5 sm:p-6 rounded-2xl border border-gray-100 shadow-sm">
                <p class="text-[11px] sm:text-xs text-gray-500 font-medium">BICICLETAS ACTIVAS</p>
                <p class="text-2xl sm:text-3xl font-bold text-gray-900 mt-1">{{ $bicicletasActivas ?? 0 }}</p>
            </div>
            <div class="bg-white p-5 sm:p-6 rounded-2xl border border-gray-100 shadow-sm">
                <p class="text-[11px] sm:text-xs text-gray-500 font-medium">VIAJES DE HOY</p>
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

        <!-- Accesos Rápidos para Operaciones Clave (Agregar Estación y Bicicleta) -->
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

      
        
    </main>

    <!-- MODAL AGREGAR BICICLETA -->
    <div id="modal-bici" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-bold text-lg text-gray-900">🚲 Registrar Nueva Bicicleta</h3>
                <button type="button" onclick="closeModal('modal-bici')" class="text-gray-400 hover:text-gray-600 font-bold">✕</button>
            </div>
            <form action="{{ route('bicicletas.store') }}" method="POST" class="space-y-4">
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

    <!-- MODAL AGREGAR ESTACIÓN -->
    <div id="modal-estacion" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-bold text-lg text-gray-900">📍 Registrar Nueva Estación</h3>
                <button type="button" onclick="closeModal('modal-estacion')" class="text-gray-400 hover:text-gray-600 font-bold">✕</button>
            </div>
            <form action="{{ route('estaciones.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Código de Estación</label>
                    <input type="text" name="codigo" required placeholder="Ej. EST-NORTE" class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-emerald-700">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Nombre de la Estación</label>
                    <input type="text" name="nombre" required placeholder="Ej. Estación Terminal Norte" class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-emerald-700">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Dirección / Ubicación</label>
                    <input type="text" name="direccion" required placeholder="Ej. Carrera 4 # 12-30" class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-emerald-700">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Capacidad de Anclajes</label>
                    <input type="number" name="capacidad" required placeholder="Ej. 10" class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-emerald-700">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Coordenadas (Lat, Lng)</label>
                    <input type="text" name="coordenadas" required placeholder="Ej. 5.59833,-75.81922" class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-emerald-700">
                </div>
                <div class="flex justify-end gap-2 pt-3">
                    <button type="button" onclick="closeModal('modal-estacion')" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-xl text-xs font-medium">Cancelar</button>
                    <button type="submit" class="bg-[#101828] hover:bg-gray-800 text-white px-4 py-2 rounded-xl text-xs font-medium">Guardar Estación</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Script de control para menú móvil lateral, modales y tablas -->
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
</body>
</html>