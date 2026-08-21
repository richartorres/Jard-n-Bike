<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Registro - Jardín Bike</title>
    <!-- Tailwind CSS Play CDN Oficial y Seguro con https:// -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts con https:// explícito -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-[#F8F9FA] min-h-[100dvh] flex flex-col justify-center items-center p-4 sm:p-6">

<!-- Contenedor Principal Estilo Tarjeta Flotante -->
<div class="w-full max-w-md bg-white sm:rounded-3xl shadow-none sm:shadow-xl overflow-hidden border-0 sm:border border-gray-100 p-6 sm:p-8 relative">

    <div class="w-full">
        <div class="flex items-center justify-between mb-4">
            <a href="{{ url('/login') }}" class="p-2 rounded-full border border-gray-200 hover:bg-gray-50 transition">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M15.833 10H4.166" stroke="#101828" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M10 15.833L4.167 10L10 4.167" stroke="#101828" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </a>
        </div>

        <!-- Logo de la bicicleta en verde institucional (Igual que en el Login) -->
        <div class="flex justify-center mb-4">
            <div class="w-14 h-14 bg-[#105B3A] rounded-2xl flex items-center justify-center shadow-md shadow-emerald-900/10">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="5.5" cy="17.5" r="3.5"></circle>
                    <circle cx="18.5" cy="17.5" r="3.5"></circle>
                    <path d="M15 6a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm-3 11.5V14l-3-3 4-3 2 3h2"></path>
                </svg>
            </div>
        </div>

        <h1 class="text-2xl font-bold text-[#101828] text-center mb-1">Crea tu cuenta</h1>
        <p class="text-gray-500 mb-6 text-center text-sm">Tomará menos de un minuto.</p>

        <!-- Formulario conectado a Laravel por POST -->
        <form action="{{ url('/registro') }}" method="POST" class="space-y-4">
            @csrf <!-- Token de seguridad obligatorio -->

            <!-- Mostrar errores de validación si los hay -->
            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-2xl text-sm">
                    <ul class="list-disc pl-4 space-y-1">
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
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Ej. Sofía Ramírez" class="w-full pl-12 pr-4 py-3.5 bg-gray-50 sm:bg-white rounded-2xl border border-gray-200 outline-none focus:border-[#105B3A] focus:ring-2 focus:ring-[#105B3A]/20 text-base transition placeholder:text-gray-400" required>
                </div>
            </div>

            <!-- Correo electrónico -->
            <div>
                <label class="block text-xs font-semibold text-[#344054] mb-1.5 uppercase tracking-wide">Correo Electrónico</label>
                <div class="relative flex items-center">
                    <span class="absolute left-4 text-[#98A2B3]">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"></rect><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path></svg>
                    </span>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="sofia@example.com" class="w-full pl-12 pr-4 py-3.5 bg-gray-50 sm:bg-white rounded-2xl border border-gray-200 outline-none focus:border-[#105B3A] focus:ring-2 focus:ring-[#105B3A]/20 text-base transition placeholder:text-gray-400" required>
                </div>
            </div>

            <!-- Contraseña -->
            <div>
                <label class="block text-xs font-semibold text-[#344054] mb-1.5 uppercase tracking-wide">Contraseña</label>
                <div class="relative flex items-center">
                    <span class="absolute left-4 text-[#98A2B3]">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                    </span>
                    <input type="password" id="password" name="password" placeholder="••••••••" class="w-full pl-12 pr-12 py-3.5 bg-gray-50 sm:bg-white rounded-2xl border border-gray-200 outline-none focus:border-[#105B3A] focus:ring-2 focus:ring-[#105B3A]/20 text-base transition placeholder:text-gray-400" required>
                    <!-- Botón del Ojo -->
                    <button type="button" onclick="togglePassword('password', 'eye-icon-1')" class="absolute right-4 text-[#98A2B3] hover:text-[#105B3A] transition">
                        <svg id="eye-icon-1" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                    </button>
                </div>
            </div>

            <!-- Confirmar Contraseña -->
            <div>
                <label class="block text-xs font-semibold text-[#344054] mb-1.5 uppercase tracking-wide">Confirmar Contraseña</label>
                <div class="relative flex items-center">
                    <span class="absolute left-4 text-[#98A2B3]">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                    </span>
                    <input type="password" id="password_confirmation" name="password_confirmation" placeholder="••••••••" class="w-full pl-12 pr-12 py-3.5 bg-gray-50 sm:bg-white rounded-2xl border border-gray-200 outline-none focus:border-[#105B3A] focus:ring-2 focus:ring-[#105B3A]/20 text-base transition placeholder:text-gray-400" required>
                    <!-- Botón del Ojo -->
                    <button type="button" onclick="togglePassword('password_confirmation', 'eye-icon-2')" class="absolute right-4 text-[#98A2B3] hover:text-[#105B3A] transition">
                        <svg id="eye-icon-2" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                    </button>
                </div>
            </div>

            <!-- Teléfono -->
            <div>
                <label class="block text-xs font-semibold text-[#344054] mb-1.5 uppercase tracking-wide">Teléfono</label>
                <div class="relative flex items-center">
                    <span class="absolute left-4 text-[#98A2B3]">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                    </span>
                    <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="+57 300 000 0000" class="w-full pl-12 pr-4 py-3.5 bg-gray-50 sm:bg-white rounded-2xl border border-gray-200 outline-none focus:border-[#105B3A] focus:ring-2 focus:ring-[#105B3A]/20 text-base transition placeholder:text-gray-400">
                </div>
            </div>

            <!-- Seguridad -->
            <div class="flex gap-3 bg-gray-50 p-4 rounded-2xl border border-gray-100 items-start">
                <span class="text-[#105B3A] mt-0.5">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path><path d="M9 12l2 2 4-4"></path></svg>
                </span>
                <p class="text-xs text-gray-600 leading-relaxed">Tus datos viajan cifrados de forma segura en la base de datos.</p>
            </div>

            <!-- Botón de confirmar -->
            <button type="submit" class="block w-full text-center py-4 rounded-2xl font-bold text-[#101828] bg-[#FFBC00] hover:bg-[#F0B000] transition shadow-lg shadow-amber-400/20 text-base">
                Confirmar y Registrarse
            </button>
        </form>
    </div>

</div>

<!-- Script para alternar la visibilidad de las contraseñas -->
<script>
    function togglePassword(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        
        if (input.type === "password") {
            input.type = "text";
            // Icono de ojo tachado (ocultar)
            icon.innerHTML = '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line>';
        } else {
            input.type = "password";
            // Icono de ojo normal (ver)
            icon.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle>';
        }
    }
</script>

</body>
</html>