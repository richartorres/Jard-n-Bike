<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Viaje en Curso - Jardín Bike</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col items-center justify-center p-6">

    <div class="bg-white p-8 rounded-3xl shadow-xl w-full max-w-sm text-center">
        <h2 class="text-gray-500 text-sm font-semibold uppercase tracking-widest">Viaje Activo</h2>
        <h1 class="text-4xl font-bold text-gray-900 mt-2 mb-6" id="cronometro">00:00</h1>
        
        <div class="bg-amber-100 border border-amber-200 rounded-2xl p-4 mb-8">
            <p class="text-amber-800 text-sm">Costo estimado actual</p>
            <p class="text-2xl font-bold text-amber-900">$1.200 COP</p>
        </div>

        <button onclick="window.location.href='/mapa'" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-4 rounded-2xl transition">
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