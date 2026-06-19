<!DOCTYPE html>
<html lang="es">
<head>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-gray-50 min-h-screen flex">

    <aside class="w-64 bg-[#0A3D27] text-white flex flex-col p-6">
        <div class="flex items-center gap-2 mb-10">
            <div class="bg-white p-2 rounded-full text-[#0A3D27]">🚲</div>
            <div>
                <h1 class="font-bold text-sm">Jardín Bike</h1>
                <p class="text-[10px] text-emerald-300 uppercase tracking-widest">Operations</p>
            </div>
        </div>
        <nav class="space-y-4 flex-1">
            <a href="#" class="flex items-center gap-3 bg-emerald-900/50 p-3 rounded-xl text-sm font-medium">📊 Dashboard</a>
            <a href="#" class="flex items-center gap-3 p-3 text-emerald-200 hover:text-white transition text-sm">🚲 Inventario</a>
            <a href="#" class="flex items-center gap-3 p-3 text-emerald-200 hover:text-white transition text-sm">📍 Estaciones</a>
            <a href="#" class="flex items-center gap-3 p-3 text-emerald-200 hover:text-white transition text-sm">👥 Usuarios</a>
            <a href="#" class="flex items-center gap-3 p-3 text-emerald-200 hover:text-white transition text-sm">⚠️ Alertas</a>
            <a href="#" class="flex items-center gap-3 p-3 text-emerald-200 hover:text-white transition text-sm">📈 Reportes</a>
            <a href="#" class="flex items-center gap-3 p-3 text-emerald-200 hover:text-white transition text-sm">⚙️ Ajustes</a>
        </nav>
        <div class="flex items-center gap-3 pt-6 border-t border-emerald-900">
            <div class="bg-amber-400 w-8 h-8 rounded-full flex items-center justify-center font-bold text-[#0A3D27]">MA</div>
            <div class="text-xs">
                <p class="font-bold">María Álvarez</p>
                <p class="text-emerald-300">Operadora · Jardín</p>
            </div>
        </div>
    </aside>

    <main class="flex-1 p-8">
        <header class="flex justify-between items-center mb-8">
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-widest font-semibold">Panel Operativo</p>
                <h2 class="text-2xl font-bold text-gray-900">Buenos días, María 🍃</h2>
            </div>
            <div class="flex items-center gap-4">
                <input type="text" placeholder="Buscar bici, usuario o estación..." class="bg-white border border-gray-200 rounded-lg px-4 py-2 text-sm w-64 shadow-sm">
                <button class="bg-white border px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-50">Ver app cliente</button>
            </div>
        </header>

        <div class="grid grid-cols-4 gap-6 mb-8">
            <div class="bg-white p-6 rounded-2xl border shadow-sm"><p class="text-xs text-gray-500">BICICLETAS ACTIVAS</p><p class="text-3xl font-bold">47</p></div>
            <div class="bg-white p-6 rounded-2xl border shadow-sm"><p class="text-xs text-gray-500">VIAJES HOY</p><p class="text-3xl font-bold">184</p></div>
            <div class="bg-white p-6 rounded-2xl border shadow-sm"><p class="text-xs text-gray-500">ALERTAS CRÍTICAS</p><p class="text-3xl font-bold text-red-500">3</p></div>
            <div class="bg-white p-6 rounded-2xl border shadow-sm"><p class="text-xs text-gray-500">INGRESOS DEL DÍA</p><p class="text-3xl font-bold">$736.000</p></div>
        </div>

        <div class="bg-white p-6 rounded-2xl border shadow-sm">
            <div class="flex justify-between mb-6">
                <h3 class="font-bold text-lg">Control de inventario</h3>
                <button class="bg-emerald-900 text-white px-4 py-2 rounded-lg text-xs font-medium">Exportar CSV</button>
            </div>
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-gray-400 text-left border-b">
                        <th class="pb-3">ID</th><th class="pb-3">UBICACIÓN</th><th class="pb-3">BATERÍA</th><th class="pb-3">ESTADO</th><th class="pb-3">ACCIONES</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    <tr><td class="py-4 font-semibold">BICI-012</td><td class="py-4">Parque Principal</td><td class="py-4 text-red-500">Crítico</td><td class="py-4">Mantenimiento</td><td class="py-4">...</td></tr>
                    <tr><td class="py-4 font-semibold">BICI-031</td><td class="py-4">Estación Cascada</td><td class="py-4 text-emerald-500">92% saludable</td><td class="py-4">Disponible</td><td class="py-4">...</td></tr>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>