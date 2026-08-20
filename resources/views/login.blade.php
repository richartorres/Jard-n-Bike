<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <!-- ¡Clave para móviles! Obliga al navegador a escalar correctamente -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Iniciar Sesión - Jardín Bike</title>
    <!-- CDN seguro con https:// explícito -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts con https:// explícito -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-[#F8F9FA] min-h-[100dvh] flex flex-col justify-center items-center p-4 sm:p-6">

<!-- Contenedor Principal Adaptativo -->
<div class="w-full max-w-md bg-white sm:rounded-3xl shadow-none sm:shadow-xl overflow-hidden border-0 sm:border border-gray-100 p-6 sm:p-8 relative">

    <div class="text-center mb-8">
        <div class="inline-block bg-[#105B3A] p-4 rounded-2xl mb-3 shadow-lg shadow-emerald-950/10">
            <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="5.5" cy="17.5" r="3.5"></circle><circle cx="18.5" cy="17.5" r="3.5"></circle>
                <path d="M15 17.5H9L12 11L16 15"></path>
                <path d="M12 11L14 8L18 8"></path>
                <circle cx="12" cy="8" r="1"></circle>
            </svg>
        </div>
        <h1 class="text-2xl font-bold text-[#101828]">¡Bienvenido de nuevo!</h1>
        <p class="text-gray-500 text-sm mt-1">Inicia sesión para continuar tu viaje.</p>
    </div>

    <!-- Formulario de Acceso -->
    <form action="{{ url('/login') }}" method="POST" class="space-y-4">
        @csrf 

        <!-- Mensaje de error si las credenciales fallan -->
        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-2xl text-sm">
                <ul class="list-disc pl-4 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div>
            <label class="block text-xs font-semibold text-[#344054] mb-1.5 uppercase tracking-wide">Correo Electrónico</label>
            <div class="relative flex items-center">
                <span class="absolute left-4 text-[#98A2B3]">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                </span>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="Ej. test@example.com" class="w-full pl-12 pr-4 py-3.5 bg-gray-50 sm:bg-white rounded-2xl border border-gray-200 outline-none focus:border-[#FFBC00] focus:ring-2 focus:ring-[#FFBC00]/20 text-base transition" required>
            </div>
        </div>

        <div>
            <label class="block text-xs font-semibold text-[#344054] mb-1.5 uppercase tracking-wide">Contraseña</label>
            <div class="relative flex items-center">
                <span class="absolute left-4 text-[#98A2B3]">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                </span>
                <input type="password" name="password" placeholder="••••••••" class="w-full pl-12 pr-4 py-3.5 bg-gray-50 sm:bg-white rounded-2xl border border-gray-200 outline-none focus:border-[#FFBC00] focus:ring-2 focus:ring-[#FFBC00]/20 text-base transition" required>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="button" class="text-xs text-[#105B3A] font-semibold hover:text-[#FFBC00] transition">¿Olvidaste tu contraseña?</button>
        </div>

        <button type="submit" class="w-full bg-[#FFBC00] hover:bg-[#E6A900] text-[#101828] font-bold py-4 rounded-2xl transition shadow-lg shadow-amber-400/20 mt-2 flex items-center justify-center gap-2 text-base">
            Iniciar sesión
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </button>
    </form>

    <div class="mt-8 text-center border-t border-gray-100 pt-6">
        <p class="text-sm text-gray-500">¿Aún no tienes cuenta?
            <a href="{{ url('/registro') }}" class="text-[#105B3A] font-bold hover:text-[#FFBC00] transition ml-1">Regístrate aquí</a>
        </p>
    </div>

</div>

</body>
</html>