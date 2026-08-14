<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Viaje en Curso - Jardín Bike</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <!-- Leaflet Routing Machine CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.css" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        #map { position: absolute; inset: 0; width: 100%; height: 100dvh; z-index: 0; }
        .no-leaflet-attribution .leaflet-control-attribution { display: none; }
        .leaflet-routing-container { display: none !important; }
    </style>
</head>
<body class="bg-gray-100 h-[100dvh] flex flex-col justify-between relative overflow-hidden antialiased">

    <!-- 1. MAPA DE FONDO (Ocupará toda la pantalla detrás) -->
    <div id="map" class="absolute inset-0 w-full h-full z-0 no-leaflet-attribution"></div>

    <!-- 2. PANEL SUPERIOR: Cronómetro y Costo en Vivo -->
    <div class="relative mx-auto mt-4 w-[92%] max-w-md bg-white/95 backdrop-blur-md shadow-xl rounded-2xl p-4 z-20 border border-gray-100 flex justify-between items-center">
        <div>
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">TIEMPO DE VIAJE</p>
            <h1 class="text-2xl font-black text-gray-900" id="cronometro">00:00</h1>
        </div>
        <div class="text-right">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">COSTO ESTIMADO</p>
            <p class="text-xl font-bold text-amber-600" id="costo-estimado">$4.000 COP</p>
        </div>
    </div>

    <!-- 3. PANEL INFERIOR FLOTANTE (Oculto por defecto hasta estar a 10 metros de una estación) -->
    <div id="contenedor-finalizar" class="relative mx-auto mb-6 w-[92%] max-w-md bg-white/95 backdrop-blur-md shadow-2xl rounded-3xl p-4 z-20 border border-gray-100 mt-auto hidden transition-all duration-300">
        <button onclick="finalizarViaje()" class="w-full bg-red-600 hover:bg-red-700 active:scale-95 text-white font-bold py-4 rounded-2xl transition shadow-lg shadow-red-600/25 cursor-pointer text-base flex items-center justify-center gap-2">
            <span>🏁</span> FINALIZAR VIAJE
        </button>
    </div>

    <!-- MODAL DE SELECCIÓN DE PAGO -->
    <div id="modal-pago" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl p-6 w-full max-w-sm text-center shadow-2xl">
            <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-3 text-xl">
                💵
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-1">Registrar Pago</h3>
            <p class="text-xs text-gray-500 mb-4">Total a cancelar por el usuario:</p>
            <p class="text-2xl font-extrabold text-[#105B3A] mb-6" id="total-final">$0 COP</p>

            <div class="space-y-2.5">
                <button onclick="confirmarPago('Efectivo')" class="w-full bg-[#0A3D27] hover:bg-emerald-900 text-white font-semibold py-3 rounded-xl text-sm transition cursor-pointer">
                    Cobrado en Efectivo
                </button>
                <button onclick="confirmarPago('Transferencia')" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-800 font-semibold py-3 rounded-xl text-sm transition cursor-pointer">
                    Pagado por Transferencia
                </button>
            </div>
        </div>
    </div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <!-- Leaflet Routing Machine JS -->
    <script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.js"></script>

    <script>
        let estacionDestinoIdSeleccionada = null;
        let marcadorUsuario = null;
        let circuloPrecision = null;
        let controlRuta = null;

        // 1. Inicializar Mapa centrado en Jardín
        const map = L.map('map', { zoomControl: false }).setView([5.59833, -75.81922], 16);
        L.control.zoom({ position: 'bottomright' }).addTo(map);

        L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', { maxZoom: 19 }).addTo(map);

        // 2. Cargar estaciones desde Laravel
        const estaciones = JSON.parse('{!! json_encode($estaciones) !!}');
        
        estaciones.forEach(estacion => {
            if (estacion.coordenadas) {
                const [lat, lng] = estacion.coordenadas.split(',');
                if (lat && lng) {
                    const marker = L.marker([parseFloat(lat), parseFloat(lng)]).addTo(map);
                    
                    const popupContent = `
                        <div class="p-1 text-center">
                            <h3 class="font-bold text-sm text-gray-900">${estacion.nombre}</h3>
                            <p class="text-xs text-gray-500 mb-2">${estacion.direccion}</p>
                            <button onclick="elegirDestino(${estacion.id_estacion}, '${estacion.nombre}', ${lat}, ${lng})" class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold px-3 py-1.5 rounded-xl shadow transition cursor-pointer">
                                📍 Elegir como destino
                            </button>
                        </div>
                    `;
                    marker.bindPopup(popupContent);
                }
            }
        });

        // 3. Fijar destino y trazar ruta opcional
        function elegirDestino(idEstacion, nombreEstacion, latEstacion, lngEstacion) {
            estacionDestinoIdSeleccionada = idEstacion;
            map.closePopup();

            if (!marcadorUsuario) {
                alert("Destino fijado (" + nombreEstacion + ").");
                return;
            }

            const posUsuario = marcadorUsuario.getLatLng();

            if (controlRuta) {
                map.removeControl(controlRuta);
            }

            controlRuta = L.Routing.control({
                waypoints: [
                    L.latLng(posUsuario.lat, posUsuario.lng),
                    L.latLng(latEstacion, lngEstacion)
                ],
                language: 'es',
                routeWhileDragging: false,
                addWaypoints: false,
                fitSelectedRoutes: true,
                showAlternatives: false,
                lineOptions: {
                    styles: [{ color: '#0edbdba9', weight: 6, opacity: 0.85 }]
                },
                createMarker: function() { return null; }
            }).addTo(map);
        }

        // 4. Geolocalización en tiempo real y validación de 10 metros
        if (navigator.geolocation) {
            navigator.geolocation.watchPosition(position => {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                const accuracy = position.coords.accuracy;

                if (!marcadorUsuario) {
                    marcadorUsuario = L.marker([lat, lng], {
                        icon: L.divIcon({
                            className: 'user-pin',
                            html: '<div style="background-color: #8910b9; width: 18px; height: 18px; border: 3px solid white; border-radius: 50%; box-shadow: 0 0 12px rgba(0,0,0,0.5);"></div>',
                            iconSize: [18, 18]
                        })
                    }).addTo(map).bindPopup("📍 ¡Estás aquí!").openPopup();

                    circuloPrecision = L.circle([lat, lng], { radius: accuracy, color: '#10B981', fillOpacity: 0.15 }).addTo(map);
                    map.setView([lat, lng], 17);
                } else {
                    marcadorUsuario.setLatLng([lat, lng]);
                    circuloPrecision.setLatLng([lat, lng]);
                    circuloPrecision.setRadius(accuracy);
                }

                let cercaDeEstacion = false;

                // Revisar la distancia a cada estación
                estaciones.forEach(est => {
                    if (est.coordenadas) {
                        const [eLat, eLng] = est.coordenadas.split(',');
                        if (eLat && eLng) {
                            const R = 6371e3; // Radio de la tierra en metros
                            const dLat = (parseFloat(eLat) - lat) * Math.PI / 180;
                            const dLon = (parseFloat(eLng) - lng) * Math.PI / 180;
                            const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                                    Math.cos(lat * Math.PI / 180) * Math.cos(parseFloat(eLat) * Math.PI / 180) *
                                    Math.sin(dLon/2) * Math.sin(dLon/2);
                            const distancia = R * (2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a)));

                            // Si está a 10 metros o menos
                            if (distancia <= 10) {
                                cercaDeEstacion = true;
                                estacionDestinoIdSeleccionada = est.id_estacion;
                            }
                        }
                    }
                });

                // Mostrar u ocultar el contenedor del botón basado en la proximidad
                const contenedorFinalizar = document.getElementById('contenedor-finalizar');
                if (cercaDeEstacion) {
                    contenedorFinalizar.classList.remove('hidden');
                } else {
                    contenedorFinalizar.classList.add('hidden');
                }

            }, error => {
                console.warn("No se pudo obtener la ubicación GPS.");
            }, { enableHighAccuracy: true, maximumAge: 0, timeout: 5000 });
        }

        // 5. Cronómetro y Costos en vivo
        const fechaInicioString = "{{ optional($alquiler)->fecha_inicio ? \Carbon\Carbon::parse($alquiler->fecha_inicio)->toIso8601String() : now()->toIso8601String() }}";
        const fechaInicio = new Date(fechaInicioString).getTime();
        let costoActual = 4000;

        function actualizarCronometro() {
            const ahora = new Date().getTime();
            const segundosTranscurridos = Math.floor((ahora - fechaInicio) / 1000);
            
            if (segundosTranscurridos >= 0) {
                let min = Math.floor(segundosTranscurridos / 60);
                let seg = segundosTranscurridos % 60;
                document.getElementById('cronometro').innerText = 
                    (min < 10 ? "0" + min : min) + ":" + (seg < 10 ? "0" + seg : seg);

                let bloques15Min = Math.ceil(segundosTranscurridos / 900);
                if (bloques15Min < 1) bloques15Min = 1;
                costoActual = bloques15Min * 4000;

                document.getElementById('costo-estimado').innerText = costoActual.toLocaleString('es-CO') + " COP";
            }
        }

        setInterval(actualizarCronometro, 1000);
        actualizarCronometro();

        // 6. Finalizar viaje y pasarela
        function finalizarViaje() {
            document.getElementById('total-final').innerText = costoActual.toLocaleString('es-CO') + " COP";
            document.getElementById('modal-pago').classList.remove('hidden');
        }

        function confirmarPago(metodo) {
            if (!estacionDestinoIdSeleccionada && estaciones.length > 0) {
                estacionDestinoIdSeleccionada = estaciones[0].id_estacion;
            }

            fetch("{{ url('/finalizar-viaje/' . ($alquiler->id_alquiler ?? 1)) }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ 
                    valor_total: costoActual, 
                    metodo_pago: metodo,
                    estacion_destino_id: estacionDestinoIdSeleccionada 
                })
            })
            .then(response => response.json())
            .then(data => {
                window.location.href = "{{ url('/mapa') }}";
            })
            .catch(() => {
                window.location.href = "{{ url('/mapa') }}";
            });
        }
    </script>
</body>
</html>