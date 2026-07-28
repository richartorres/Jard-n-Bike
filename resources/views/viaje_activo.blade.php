<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Viaje en Curso - Jardín Bike</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-gray-100 min-h-[100dvh] flex flex-col items-center justify-center p-4 sm:p-6">

    <div class="bg-white p-6 sm:p-8 rounded-3xl shadow-none sm:shadow-xl w-full max-w-sm text-center border-0 sm:border border-gray-100">
        <h2 class="text-gray-500 text-xs sm:text-sm font-semibold uppercase tracking-widest">Viaje Activo</h2>
        <h1 class="text-4xl sm:text-5xl font-bold text-gray-900 mt-2 mb-6" id="cronometro">00:00</h1>
        
        <div class="bg-amber-50 border border-amber-200/60 rounded-2xl p-4 mb-6 sm:mb-8">
            <p class="text-amber-800 text-xs sm:text-sm font-medium">Costo estimado actual</p>
            <p class="text-2xl font-bold text-amber-900 mt-0.5">$1.200 COP</p>
        </div>

        <!-- RUTA CORREGIDA CON url() -->
        <button onclick="window.location.href='{{ url('/mapa') }}'" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-4 rounded-2xl transition shadow-lg shadow-red-600/20 active:scale-95 text-sm sm:text-base">
            FINALIZAR VIAJE
        </button>
    </div>

    <script>
        // Simulador de cronómetro simple
        let segundos = 0;
        setInterval(() => {
            segundos++;
            let min = Math.floor(segundos / 60);
            let seg = segundos % 60;
            document.getElementById('cronometro').innerText = 
                (min < 10 ? "0" + min : min) + ":" + (seg < 10 ? "0" + seg : seg);
        }, 1000);
    </script>
</body>
</html>