<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Nueva Contraseña - Jardín Bike</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-[#F8F9FA] min-h-[100dvh] flex flex-col justify-center items-center p-4 sm:p-6">

<div class="w-full max-w-md bg-white sm:rounded-3xl shadow-none sm:shadow-xl overflow-hidden border-0 sm:border border-gray-100 p-6 sm:p-8 relative">

    <div class="text-center mb-8">
        <div class="inline-block bg-[#105B3A] p-4 rounded-2xl mb-3 shadow-lg shadow-emerald-950/10">
            <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
            </svg>
        </div>
        <h1 class="text-2xl font-bold text-[#101828]">Nueva contraseña</h1>
        <p class="text-gray-500 text-sm mt-1">Ingresa y confirma tu nueva contraseña segura.</p>
    </div>

    <!-- Mensaje de errores de validación -->
    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-2xl text-sm mb-4">
            <ul class="list-disc pl-4 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('password.update') }}" method="POST" class="space-y-4">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <input type="hidden" name="email" value="{{ request()->email }}">

        <!-- Nueva Contraseña -->
        <div>
            <label class="block text-xs font-semibold text-[#344054] mb-1.5 uppercase tracking-wide">Nueva Contraseña</label>
            <div class="relative flex items-center">
                <span class="absolute left-4 text-[#98A2B3]">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                </span>
                <input type="password" id="password" name="password" placeholder="••••••••" class="w-full pl-12 pr-12 py-3.5 bg-gray-50 sm:bg-white rounded-2xl border border-gray-200 outline-none focus:border-[#FFBC00] focus:ring-2 focus:ring-[#FFBC00]/20 text-base transition" required>
                <!-- Botón del Ojo -->
                <button type="button" onclick="togglePassword('password', 'eye-icon-password')" class="absolute right-4 text-[#98A2B3] hover:text-[#105B3A] transition">
                    <svg id="eye-icon-password" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
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
                <input type="password" id="password_confirmation" name="password_confirmation" placeholder="••••••••" class="w-full pl-12 pr-12 py-3.5 bg-gray-50 sm:bg-white rounded-2xl border border-gray-200 outline-none focus:border-[#FFBC00] focus:ring-2 focus:ring-[#FFBC00]/20 text-base transition" required>
                <!-- Botón del Ojo -->
                <button type="button" onclick="togglePassword('password_confirmation', 'eye-icon-confirm')" class="absolute right-4 text-[#98A2B3] hover:text-[#105B3A] transition">
                    <svg id="eye-icon-confirm" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                </button>
            </div>
        </div>

        <button type="submit" class="w-full bg-[#FFBC00] hover:bg-[#E6A900] text-[#101828] font-bold py-4 rounded-2xl transition shadow-lg shadow-amber-400/20 mt-2 flex items-center justify-center gap-2 text-base">
            Guardar nueva contraseña
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </button>
    </form>

</div>

<script>
    function togglePassword(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        
        if (input.type === "password") {
            input.type = "text";
            icon.innerHTML = '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line>';
        } else {
            input.type = "password";
            icon.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle>';
        }
    }
</script>

</body>
</html>