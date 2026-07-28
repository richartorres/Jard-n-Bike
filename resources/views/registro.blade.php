<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Jardín Bike</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-[#F8F9FA] flex justify-center items-center min-h-screen p-4">


<!-- Contenedor Principal Estilo Tarjeta Flotante -->
<div class="max-w-md w-full bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100 p-6 sm:p-8 relative">

    <div class="max-w-md w-full">
        <div class="flex items-center justify-between mb-6">
            <a href="{{ url('/login') }}" class="p-2 rounded-full border border-gray-200 hover:bg-gray-50 transition">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M15.833 10H4.166" stroke="#101828" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M10 15.833L4.167 10L10 4.167" stroke="#101828" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </a>
        </div>

        <h1 class="text-2xl font-bold text-[#101828] mb-1">Crea tu cuenta</h1>
        <p class="text-gray-500 mb-8">Tomará menos de un minuto.</p>

        <!-- Formulario conectado a Laravel por POST -->
        <form action="{{ url('/registro') }}" method="POST" class="space-y-5">
            @csrf <!-- Token de seguridad obligatorio -->

            <!-- Mostrar errores de validación si los hay -->
            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl text-sm">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Nombre completo -->
            <div>
                <label class="block text-xs font-semibold text-[#344054] mb-1.5 uppercase tracking-wide">Nombre completo</label>
                <div class="relative flex items-center">
                    <span class="absolute left-4 text-[#98A2B3]">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                    </span>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Ej. Sofía Ramírez" class="w-full pl-12 pr-4 py-3 bg-white rounded-2xl border border-gray-200 outline-none focus:border-[#105B3A] transition placeholder:text-gray-400" required>
                </div>
            </div>

            <!-- Correo electrónico (Necesario para iniciar sesión después) -->
            <div>
                <label class="block text-xs font-semibold text-[#344054] mb-1.5 uppercase tracking-wide">Correo Electrónico</label>
                <div class="relative flex items-center">
                    <span class="absolute left-4 text-[#98A2B3]">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"></rect><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path></svg>
                    </span>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="sofia@example.com" class="w-full pl-12 pr-4 py-3 bg-white rounded-2xl border border-gray-200 outline-none focus:border-[#105B3A] transition placeholder:text-gray-400" required>
                </div>
            </div>

            <!-- Contraseña (Obligatoria para la seguridad de la BD) -->
            <div>
                <label class="block text-xs font-semibold text-[#344054] mb-1.5 uppercase tracking-wide">Contraseña</label>
                <div class="relative flex items-center">
                    <span class="absolute left-4 text-[#98A2B3]">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                    </span>
                    <input type="password" name="password" placeholder="••••••••" class="w-full pl-12 pr-4 py-3 bg-white rounded-2xl border border-gray-200 outline-none focus:border-[#105B3A] transition placeholder:text-gray-400" required>
                </div>
            </div>

            <!-- Teléfono -->
            <div>
                <label class="block text-xs font-semibold text-[#344054] mb-1.5 uppercase tracking-wide">Teléfono</label>
                <div class="relative flex items-center">
                    <span class="absolute left-4 text-[#98A2B3]">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                    </span>
                    <input type="tel" name="phone" placeholder="+57 300 000 0000" class="w-full pl-12 pr-4 py-3 bg-white rounded-2xl border border-gray-200 outline-none focus:border-[#105B3A] transition placeholder:text-gray-400">
                </div>
            </div>

            <!-- Seguridad -->
            <div class="flex gap-3 bg-[#F9FAFB] p-4 rounded-2xl border border-gray-100 items-start">
                <span class="text-[#105B3A] mt-0.5">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path><path d="M9 12l2 2 4-4"></path></svg>
                </span>
                <p class="text-xs text-gray-600 leading-relaxed">Tus datos viajan cifrados de forma segura en la base de datos.</p>
            </div>

            <!-- Botón de confirmar como type="submit" -->
            <button type="submit" class="block w-full text-center py-4 rounded-2xl font-bold text-[#101828] bg-[#FFBC00] hover:bg-[#F0B000] transition shadow-[0_4px_12px_rgba(255,188,0,0.25)]">
                Confirmar y Registrarse
            </button>
        </form>
    </div>

    </div>


</body>
</html>