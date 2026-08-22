<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Recuperar Contraseña - Jardín Bike</title>
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
                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                <polyline points="22,6 12,13 2,6"></polyline>
            </svg>
        </div>
        <h1 class="text-2xl font-bold text-[#101828]">¿Olvidaste tu contraseña?</h1>
        <p class="text-gray-500 text-sm mt-1">Introduce tu correo y te enviaremos un enlace de recuperación.</p>
    </div>

    <!-- Mensaje de éxito si se envió el correo -->
    @if (session('status'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-2xl text-sm mb-4">
            {{ session('status') }}
        </div>
    @endif

    <!-- Mensaje de error si el correo no existe o hay fallas -->
    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-2xl text-sm mb-4">
            <ul class="list-disc pl-4 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('password.email') }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label class="block text-xs font-semibold text-[#344054] mb-1.5 uppercase tracking-wide">Correo Electrónico</label>
            <div class="relative flex items-center">
                <span class="absolute left-4 text-[#98A2B3]">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                </span>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="Ej. test@example.com" class="w-full pl-12 pr-4 py-3.5 bg-gray-50 sm:bg-white rounded-2xl border border-gray-200 outline-none focus:border-[#FFBC00] focus:ring-2 focus:ring-[#FFBC00]/20 text-base transition" required>
            </div>
        </div>

        <button type="submit" class="w-full bg-[#FFBC00] hover:bg-[#E6A900] text-[#101828] font-bold py-4 rounded-2xl transition shadow-lg shadow-amber-400/20 mt-2 flex items-center justify-center gap-2 text-base">
            Enviar enlace de recuperación
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </button>
    </form>

    <div class="mt-8 text-center border-t border-gray-100 pt-6">
        <a href="{{ url('/login') }}" class="text-sm text-[#105B3A] font-bold hover:text-[#FFBC00] transition">
            ← Volver al inicio de sesión
        </a>
    </div>

</div>

</body>
</html>