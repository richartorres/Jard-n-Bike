<!DOCTYPE html>

<html lang="es">

<head>

    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>body { font-family: 'Inter', sans-serif; }</style>

</head>

<body class="bg-[#F8F9FA] flex justify-center items-center min-h-screen p-4">

<!-- Contenedor Principal Estilo Tarjeta Flotante -->
<div class="max-w-md w-full bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100 p-6 sm:p-8 relative">

    <div class="max-w-md w-full">

        <div class="text-center mb-10">

            <div class="inline-block bg-[#105B3A] p-5 rounded-3xl mb-4 shadow-lg shadow-emerald-900/10">

                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">

                    <circle cx="5.5" cy="17.5" r="3.5"></circle><circle cx="18.5" cy="17.5" r="3.5"></circle>

                    <path d="M15 17.5H9L12 11L16 15"></path>

                    <path d="M12 11L14 8L18 8"></path>

                    <circle cx="12" cy="8" r="1"></circle>

                </svg>

            </div>

            <h1 class="text-2xl font-bold text-[#101828]">¡Bienvenido de nuevo!</h1>

            <p class="text-gray-500 text-sm">Inicia sesión para continuar tu viaje.</p>

        </div>



        <!-- 1. Envolvemos todo en un formulario que apunta a la ruta /login por POST -->

        <form action="{{ url('/login') }}" method="POST" class="space-y-4">

            @csrf <!-- ¡Obligatorio por seguridad en Laravel! -->



            <!-- Mensaje de error si las credenciales fallan -->

            @if($errors->any())

                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl text-sm">

                    <ul>

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

                    <!-- 2. Agregamos name="email" para que coincida con la base de datos y el controlador -->

                    <input type="email" name="email" value="{{ old('email') }}" placeholder="Ej. test@example.com" class="w-full pl-12 pr-4 py-4 bg-white rounded-2xl border border-gray-200 outline-none focus:border-[#FFBC00] focus:ring-1 focus:ring-[#FFBC00] transition" required>

                </div>

            </div>



            <div>

                <label class="block text-xs font-semibold text-[#344054] mb-1.5 uppercase tracking-wide">Contraseña</label>

                <div class="relative flex items-center">

                    <span class="absolute left-4 text-[#98A2B3]">

                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>

                    </span>

                    <!-- 3. Agregamos name="password" -->

                    <input type="password" name="password" placeholder="••••••••" class="w-full pl-12 pr-4 py-4 bg-white rounded-2xl border border-gray-200 outline-none focus:border-[#FFBC00] focus:ring-1 focus:ring-[#FFBC00] transition" required>

                </div>

            </div>



            <button type="button" class="text-xs text-[#105B3A] font-semibold hover:text-[#FFBC00] transition">¿Olvidaste tu contraseña?</button>



            <!-- 4. Cambiamos el botón a type="submit" para que procese el formulario mediante el LoginController -->

            <button type="submit" class="w-full bg-[#FFBC00] hover:bg-[#E6A900] text-[#101828] font-bold py-4 rounded-2xl transition shadow-[0_4px_12px_rgba(255,188,0,0.3)] mt-4 flex items-center justify-center gap-2">

                Iniciar sesión

                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>

            </button>

        </form>



        <div class="mt-8 text-center">

            <p class="text-sm text-gray-500">¿Aún no tienes cuenta?

                <a href="{{ url('/registro') }}" class="text-[#105B3A] font-bold hover:text-[#FFBC00] transition">Regístrate aquí</a>

            </p>

        </div>

    </div>

 </div>

</body>

</html>