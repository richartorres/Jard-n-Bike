<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <!-- ¡Clave para móviles! Adapta la escala exacta a cualquier celular -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Jardín Bike - Movilidad Eléctrica</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-[#F8F9FA] min-h-[100dvh] flex flex-col justify-center items-center p-4 sm:p-6">

    <!-- Contenedor Principal Estilo Tarjeta Flotante -->
    <div class="w-full max-w-md bg-white sm:rounded-3xl shadow-none sm:shadow-xl overflow-hidden border-0 sm:border border-gray-100 p-6 sm:p-8 relative">
        
        <!-- Botón Admin Superior -->
        <div class="flex justify-end mb-2">
            <a href="{{ url('/login') }}" class="text-xs font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 px-3 py-1.5 rounded-full transition">
                Admin
            </a>
        </div>

        <!-- Logo con Persona en Bicicleta Profesional (100% Seguro y Visible) -->
        <div class="text-center mb-8">
            <div class="inline-block bg-[#105B3A] p-4 sm:p-5 rounded-2xl sm:rounded-3xl mb-3 sm:mb-4 shadow-lg shadow-emerald-900/10">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="5.5" cy="17.5" r="3.5"></circle><circle cx="18.5" cy="17.5" r="3.5"></circle>
                    <path d="M15 17.5H9L12 11L16 15"></path>
                    <path d="M12 11L14 8L18 8"></path>
                    <circle cx="12" cy="8" r="1"></circle>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-[#101828]">Jardín Bike</h1>
            <p class="text-gray-500 text-sm">ANTIOQUIA · CO</p>
        </div>

        <!-- Banner Principal -->
        <div class="bg-gradient-to-br from-[#115C39] to-[#1E7B4D] text-white rounded-2xl p-6 mb-6 shadow-lg relative overflow-hidden">
            <div class="inline-flex items-center gap-1.5 bg-white/20 backdrop-blur-md text-[11px] px-3 py-1 rounded-full mb-3 font-medium">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-300 animate-pulse"></span>
                Movilidad limpia
            </div>
            <h2 class="text-2xl sm:text-3xl font-bold mb-2 leading-tight">Pedalea Jardín,<br>sin esfuerzo.</h2>
            <p class="text-emerald-100 text-xs sm:text-sm font-normal leading-relaxed">Descubre cascadas, cafetales y balcones coloridos en una e-bike. Desbloquea con un QR.</p>
        </div>

        <!-- Características / Beneficios -->
        <div class="space-y-2.5 mb-6">
            <div class="bg-[#F8F9FA] p-3.5 rounded-xl border border-gray-100 flex items-center gap-3.5">
                <div class="bg-[#E8F3EE] text-[#105B3A] p-2 rounded-xl">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>
                </div>
                <div>
                    <p class="font-semibold text-xs text-[#101828]">100% eléctrica</p>
                    <p class="text-[11px] text-gray-500">Cero emisiones para tu recorrido</p>
                </div>
            </div>

            <div class="bg-[#F8F9FA] p-3.5 rounded-xl border border-gray-100 flex items-center gap-3.5">
                <div class="bg-[#E8F3EE] text-[#105B3A] p-2 rounded-xl">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                </div>
                <div>
                    <p class="font-semibold text-xs text-[#101828]">Estaciones en el pueblo</p>
                    <p class="text-[11px] text-gray-500">6 puntos estratégicos de retiro</p>
                </div>
            </div>

            <!-- Corrección del error tipográfico bg-#[F8F9FA] a bg-[#F8F9FA] -->
            <div class="bg-[#F8F9FA] p-3.5 rounded-xl border border-gray-100 flex items-center gap-3.5">
                <div class="bg-[#E8F3EE] text-[#105B3A] p-2 rounded-xl">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                </div>
                <div>
                    <p class="font-semibold text-xs text-[#101828]">Garantía segura</p>
                    <p class="text-[11px] text-gray-500">Tarjeta vinculada como respaldo</p>
                </div>
            </div>
        </div>

        <!-- Botones de Acción -->
        <div class="space-y-2.5">
            <a href="{{ url('/registro') }}" class="w-full bg-[#FFBC00] hover:bg-[#F0B000] transition py-3.5 rounded-xl font-bold shadow-md shadow-yellow-500/10 text-[#101828] text-sm flex items-center justify-center gap-2">
                Crear cuenta
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M10.78 7.22L7 3.44L8.06 2.38L13.68 8L8.06 13.62L7 12.56L10.78 8.78H2.32V7.22H10.78Z" fill="#101828"/></svg>
            </a>
           
            <a href="{{ url('/login') }}" class="block w-full text-center py-3.5 rounded-xl font-semibold text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 transition text-sm">
                Ya tengo cuenta, iniciar sesión
            </a>
        </div>

        <!-- Tarifa Footer -->
        <p class="text-center text-[11px] text-gray-400 mt-5">Tarifa $4.000 COP · USD $1 cada 15 minutos</p>
    </div>

</body>
</html>