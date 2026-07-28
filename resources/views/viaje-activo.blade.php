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
        <h1 class="text-4xl sm:text-5xl font-bold text-gray-900 mt-2 mb-2" id="cronometro">00:00</h1>
        
        <p class="text-xs text-gray-400 mb-6">Tarifa: $4.000 COP c/u 15 min</p>

        <div class="bg-amber-50 border border-amber-200/60 rounded-2xl p-4 mb-6 sm:mb-8">
            <p class="text-amber-800 text-xs sm:text-sm font-medium">Costo estimado actual</p>
            <p class="text-2xl font-bold text-amber-900 mt-0.5" id="costo-estimado">$4.000 COP</p>
        </div>

        <button onclick="finalizarViaje()" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-4 rounded-2xl transition shadow-lg shadow-red-600/20 active:scale-95 text-sm sm:text-base">
            FINALIZAR VIAJE
        </button>
    </div>

    <!-- Modal de Selección de Pago -->
    <div id="modal-pago" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl p-6 w-full max-w-sm text-center shadow-2xl">
            <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-3 text-xl">
                💵
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-1">Registrar Pago</h3>
            <p class="text-xs text-gray-500 mb-4">Total a cancelar por el usuario:</p>
            <p class="text-2xl font-extrabold text-[#105B3A] mb-6" id="total-final">$0 COP</p>

            <div class="space-y-2.5">
                <button onclick="confirmarPago('Efectivo')" class="w-full bg-[#0A3D27] hover:bg-emerald-900 text-white font-semibold py-3 rounded-xl text-sm transition">
                    Cobrado en Efectivo
                </button>
                <button onclick="confirmarPago('Transferencia')" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-800 font-semibold py-3 rounded-xl text-sm transition">
                    Pagado por Transferencia
                </button>
            </div>
        </div>
    </div>

    <script>
        // Sincronizamos con la hora de inicio proveniente de la base de datos (pasada desde Laravel)
        const fechaInicio = new Date("{{ $alquiler->fecha_inicio ?? now() }}").getTime();
        let costoActual = 4000;

        function actualizarCronometro() {
            const ahora = new Date().getTime();
            const segundosTranscurridos = Math.floor((ahora - fechaInicio) / 1000);
            
            if (segundosTranscurridos >= 0) {
                let min = Math.floor(segundosTranscurridos / 60);
                let seg = segundosTranscurridos % 60;
                document.getElementById('cronometro').innerText = 
                    (min < 10 ? "0" + min : min) + ":" + (seg < 10 ? "0" + seg : seg);

                // Regla de cobro: $4.000 COP cada 15 minutos (900 segundos)
                let bloques15Min = Math.ceil(segundosTranscurridos / 900);
                if (bloques15Min < 1) bloques15Min = 1;
                costoActual = bloques15Min * 4000;

                document.getElementById('costo-estimado').innerText = costoActual.toLocaleString('es-CO') + " COP";
            }
        }

        setInterval(actualizarCronometro, 1000);
        actualizarCronometro();

        function finalizarViaje() {
            document.getElementById('total-final').innerText = costoActual.toLocaleString('es-CO') + " COP";
            document.getElementById('modal-pago').classList.remove('hidden');
        }

        function confirmarPago(metodo) {
            fetch("{{ url('/finalizar-viaje') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ 
                    valor_total: costoActual, 
                    metodo_pago: metodo 
                })
            })
            .then(() => {
                window.location.href = "{{ url('/mapa') }}";
            })
            .catch(() => {
                window.location.href = "{{ url('/mapa') }}";
            });
        }
    </script>
</body>
</html>