<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jardín Bike - Mapa</title>
    
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Inter', sans-serif; }
        #map { height: 100vh; width: 100vw; z-index: 1; }
        .no-leaflet-attribution .leaflet-control-attribution { display: none; }
    </style>

    <script src="https://unpkg.com/html5-qrcode"></script>
</head>
<body class="bg-gray-100 overflow-hidden relative antialiased">

    <div class="absolute top-4 left-1/2 -translate-x-1/2 w-[90%] max-w-md bg-white/95 backdrop-blur-md shadow-xl rounded-2xl p-4 z-50 flex justify-between items-center border border-gray-100">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center text-emerald-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
            <div>
                <p class="text-xs text-gray-400 font-medium tracking-wide">SALDO DISPONIBLE</p>
                <p class="text-lg font-bold text-gray-800">$24.000 COP <span class="text-sm font-normal text-gray-500">· 90 min</span></p>
            </div>
        </div>
        <div class="bg-emerald-50 text-emerald-700 font-semibold px-3 py-1.5 rounded-xl text-xs tracking-wide">
            🚲 Jardín, Ant.
        </div>
    </div>

    <div id="map" class="no-leaflet-attribution"></div>

    <div class="absolute bottom-6 left-1/2 -translate-x-1/2 w-[90%] max-w-md z-50">
        <button onclick="abrirScanner()" class="w-full bg-amber-400 hover:bg-amber-500 text-gray-900 font-bold py-4 rounded-2xl shadow-2xl transition-all active:scale-95 text-center tracking-wide flex items-center justify-center gap-2 text-base">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" /></svg>
            ESCANEAR PARA DESBLOQUEAR
        </button>
    </div>

        <div id="qr-reader" class="hidden absolute inset-0 z-[100] bg-white flex flex-col items-center justify-center p-6">
            <h2 class="text-xl font-bold mb-4 text-gray-800">Escanea el código QR</h2>
            <div id="reader" class="w-full max-w-sm rounded-2xl overflow-hidden shadow-lg border-2 border-amber-400"></div>
            
            <button type="button" onclick="cerrarScanner()" class="mt-8 bg-gray-900 text-white px-8 py-3 rounded-xl font-bold hover:bg-gray-700 transition">
                Cancelar
            </button>
        </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        // Mapa
        const map = L.map('map').setView([5.59833, -75.81922], 16);
        L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', { maxZoom: 19 }).addTo(map);

        // Datos estaciones
        const estaciones = {!! json_encode($estaciones) !!};
        estaciones.forEach(estacion => {
            const [lat, lng] = estacion.coordenadas.split(',');
            if (lat && lng) {
                const marker = L.marker([parseFloat(lat), parseFloat(lng)]).addTo(map);
                marker.bindPopup(`<div class="p-1"><h3 class="font-bold text-sm">${estacion.nombre}</h3><p class="text-xs text-gray-500">${estacion.direccion}</p></div>`);
            }
        });

        // Lógica Escáner
        const html5QrCode = new Html5Qrcode("reader");

        function abrirScanner() {
            document.getElementById('qr-reader').classList.remove('hidden');
            html5QrCode.start(
                { facingMode: "environment" },
                { fps: 10, qrbox: 250 },
                (decodedText) => {
                    // 1. Detenemos el escáner de forma inmediata
                    html5QrCode.stop().then(() => {
                        document.getElementById('qr-reader').classList.add('hidden');
                        
                        // 2. Enviamos el dato al servidor
                        fetch('/iniciar-viaje', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ bicicleta_id: decodedText })
                        })
                        .then(response => {
                            // 3. Redireccionamos instantáneamente a la pantalla de viaje
                            window.location.href = "/viaje-activo";
                        });
                    }).catch(err => {
                        console.error("Error al detener el escáner: ", err);
                    });
                }
            ).catch(err => alert("Error al acceder a la cámara"));
        }

        function cerrarScanner() {
    // 1. Intentamos detener el escáner si está activo
    if (html5QrCode && html5QrCode.isScanning) {
        html5QrCode.stop().then(() => {
            console.log("Escáner detenido");
        }).catch(err => {
            console.error("Error al detener el escáner: ", err);
        });
    }
    
    // 2. Ocultamos el contenedor sí o sí
    document.getElementById('qr-reader').classList.add('hidden');
}

        
    </script>
</body>
</html>