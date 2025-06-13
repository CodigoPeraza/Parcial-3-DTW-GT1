<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Bootstrap ICONS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        #drawingCanvas {
            cursor: crosshair;
        }

        .coordinates {
            background: #e8f4fd;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 18px;
            border-left: 4px solid #3498db;
        }

        #map {
            height: 400px !important;
            /* Altura fija */
            width: 100% !important;
            position: relative !important;
            z-index: 1;
        }

        #error {
            color: #e74c3c;
            text-align: center;
            padding: 10px;
        }

        .btn-copy,
        .btn-refresh {
            background: #2c3e50;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s;
        }

        .btn-copy:hover,
        .btn-refresh:hover {
            background: #3498db;
        }

        .btn-toggle {
            background: #2c3e50;
            color: white;
            border: none;
            padding: 6px 10px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
        }

        .actions {
            display: flex;
            justify-content: space-between;
            gap: 10px;
        }

        .container_geo {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f8f9fa;
            box-sizing: border-box;
        }

        .sub_container_geo {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            box-sizing: border-box;
        }
    </style>

</head>

<body>

    <div class="container py-4">
        <!-- Espacio para API de Geolocalización -->
        <div class="card mb-4">
            <div class="card-header">
                <h5>API de Geolocalización</h5>
            </div>
            <div class="card-body bg-light text-center">
                <div class="coordinates">
                    <i class="fas fa-location-dot" style="color: #e74c3c;"></i>
                    <span id="coordinates">Obteniendo ubicación...</span>
                    <div class="actions">
                        <button id="copyBtn" class="btn-copy"><i class="far fa-copy"></i> Copiar</button>
                        <button id="refreshBtn" class="btn-refresh"><i class="fas fa-sync-alt"></i> Actualizar
                            ubicación</button>
                    </div>
                </div>
                <div class="container_geo">
                    <div class="sub_container_geo">
                        <div id="map">

                        </div>
                        <p id="error"></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- API de Canvas -->
        <div class="card mb-4">
            <div class="card-header">
                <h5><i class="bi bi-easel-fill"></i> API de Canvas</h5><br>
            </div>
            <div class="card-body text-center">
                <h6>Mi dibujo</h6>
                <canvas id="drawingCanvas" width="600" height="400" class="border mb-3"></canvas><br>

                <input type="color" id="colorPicker" style="display: none;" />
                <button id="colorBtn" class="btn btn-outline-secondary" title="Seleccionar color">
                    <i class="bi bi-palette-fill"></i></button>
                <button id="clearCanvas" class="btn btn-outline-secondary"><i class="bi bi-eraser-fill"></i></button>

                <button id="saveCanvas" class="btn btn-primary">Guardar dibujo</button>

            </div>
        </div>

        <!-- Espacio para API de Video -->
        <!-----Implementacion del video y la imagen de portada----->
        <div class="card mb-4">
            <div class="card-header">
                <h5>API de Captura de video</h5>
            </div>
            <div class="card-body text-center">
                <video id="camara" width="640" height="360" autoplay playsinline
                    class="border mb-2 mx-auto d-block"></video>
                <!-----Boton de captura, guardado y muestra de la foto----->
                <button id="btnCapturar" class="btn btn-success mb-3">Tomar foto</button>
                <canvas id="foto" width="640" height="360" class="border mb-3 mx-auto d-block"></canvas>
                <a id="btnGuardar" download="foto.png" class="btn btn-info">Guardar</a>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script>
        //-- Espacio para API de Geolocalización --

        let map, darkLayer, lightLayer, currentLayer;
        let marker, circle;

        const coordSpan = document.getElementById('coordinates');
        const errorEl = document.getElementById('error');
        const copyBtn = document.getElementById('copyBtn');
        const refreshBtn = document.getElementById('refreshBtn');

        const initMap = (lat, lng, accuracy) => {
            if (!map) {
                map = L.map('map').setView([lat, lng], 15);
                setTimeout(() => {
                    map.invalidateSize();
                }, 100);

                // Capas
                darkLayer = L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}.png');
                lightLayer = L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}.png');

                currentLayer = lightLayer; // Por motivos de claridad al usar la api se dejo el modo claro por defecto 
                currentLayer.addTo(map);

                addToggleButton();
            } else {
                map.setView([lat, lng], 15);
            }

            if (marker) map.removeLayer(marker);
            if (circle) map.removeLayer(circle);

            const customIcon = L.icon({
                iconUrl: 'https://cdn-icons-png.flaticon.com/512/149/149060.png',
                iconSize: [40, 40],
                popupAnchor: [0, -20]
            });

            marker = L.marker([lat, lng], {
                    icon: customIcon
                }).addTo(map)
                .bindPopup(`<b>¡Ubicación actual!</b><br><small>Precisión: ~${Math.round(accuracy)} m</small>`)
                .openPopup();

            circle = L.circle([lat, lng], {
                color: '#3498db',
                fillColor: '#3498db',
                fillOpacity: 0.2,
                radius: accuracy
            }).addTo(map).bindTooltip("Margen de error del GPS");
        };

        const addToggleButton = () => {
            const toggleBtn = L.control({
                position: 'topright'
            });
            toggleBtn.onAdd = () => {
                const btn = L.DomUtil.create('button', 'btn-toggle');
                btn.innerHTML = '<i class="fas fa-adjust"></i> Alternar mapa';
                btn.onclick = () => {
                    map.removeLayer(currentLayer);
                    currentLayer = currentLayer === darkLayer ? lightLayer : darkLayer;
                    currentLayer.addTo(map);
                };
                return btn;
            };
            toggleBtn.addTo(map);
        };

        const getLocation = () => {
            coordSpan.textContent = "Obteniendo ubicación...";
            errorEl.textContent = "";

            navigator.geolocation.getCurrentPosition(
                (pos) => {
                    const {
                        latitude,
                        longitude,
                        accuracy
                    } = pos.coords;
                    const coordsText = `Latitud: ${latitude.toFixed(6)}, Longitud: ${longitude.toFixed(6)}`;
                    coordSpan.textContent = coordsText;

                    copyBtn.onclick = () => {
                        navigator.clipboard.writeText(coordsText);
                        copyBtn.innerHTML = '<i class="fas fa-check"></i> Copiado!';
                        setTimeout(() => {
                            copyBtn.innerHTML = '<i class="far fa-copy"></i> Copiar';
                        }, 2000);
                    };

                    if (accuracy > 100) {
                        errorEl.textContent = "⚠️ Precisión baja. Activa GPS o Wi-Fi para mayor exactitud.";
                    }

                    initMap(latitude, longitude, accuracy);
                },
                (error) => {
                    coordSpan.textContent = "No se pudo obtener la ubicación.";
                    switch (error.code) {
                        case error.PERMISSION_DENIED:
                            errorEl.textContent = "El usuario denegó el permiso de geolocalización.";
                            break;
                        case error.POSITION_UNAVAILABLE:
                            errorEl.textContent = "La información de ubicación no está disponible.";
                            break;
                        case error.TIMEOUT:
                            errorEl.textContent = "La solicitud de geolocalización ha caducado.";
                            break;
                        default:
                            errorEl.textContent = "Error desconocido al obtener la ubicación.";
                    }

                    if (!map) {
                        map = L.map('map').setView([14.6349, -90.5069], 10);
                        lightLayer = L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}.png');
                        lightLayer.addTo(map);
                    }
                }, {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 0
                }
            );
        };

        // Aqui se Realiza la Cargar Ubicación al inicio
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(getLocation, 100);
        });
        refreshBtn.addEventListener('click', getLocation);

        //-----------------------------------------
        //-- Espacio para API de Canvas --
        const canvas = document.getElementById('drawingCanvas');
        const ctx = canvas.getContext('2d');

        const colorInput = document.getElementById('colorPicker');
        const colorBtn = document.getElementById('colorBtn');

        colorBtn.addEventListener('click', () => colorInput.click());

        //Para que el fondo sea blanco, porque por defecto es transparente al guardalo
        window.onload = () => {
            ctx.fillStyle = 'white';
            ctx.fillRect(0, 0, canvas.width, canvas.height);
        };

        let drawing = false;

        canvas.addEventListener('mousedown', (e) => {
            drawing = true;
            ctx.beginPath();
            ctx.moveTo(e.offsetX, e.offsetY);
        });

        canvas.addEventListener('mousemove', (e) => {
            if (!drawing) return;
            ctx.lineTo(e.offsetX, e.offsetY);
            ctx.strokeStyle = colorInput.value;
            ctx.lineWidth = 4;
            ctx.stroke();
            drawing = true;
        });

        canvas.addEventListener('mouseup', () => {
            drawing = false;
        });

        canvas.addEventListener('mouseout', () => {
            drawing = false;
        });

        // Boton para descargar el canvas
        const BtnGuardar = document.getElementById('saveCanvas');
        BtnGuardar.addEventListener('click', () => {
            const dataURL = canvas.toDataURL('image/jpeg', 1.0);

            const link = document.createElement('a');
            link.href = dataURL;
            link.download = 'midibujo.jpg';
            link.click();
        });

        //Boton para borrar dibujo
        const BtnBorrar = document.getElementById('clearCanvas');
        BtnBorrar.addEventListener('click', function() {
            ctx.clearRect(0, 0, canvas.width, canvas.height)
            ctx.fillStyle = 'white';
            ctx.fillRect(0, 0, canvas.width, canvas.height);
        })

        //--------------------------------
        //-- Espacio para API de Video --
        //-----Script de los eventos----
        // Declaración de constantes
        const camara = document.getElementById('camara');
        const btnCapturar = document.getElementById('btnCapturar');
        const canvasFoto = document.getElementById('foto');
        const contexto = canvasFoto.getContext('2d');
        const btnGuardar = document.getElementById('btnGuardar');

        // Conceder acceso a la camara
        navigator.mediaDevices.getUserMedia({
                video: true
            })
            .then((stream) => {
                camara.srcObject = stream;
            })
            //Manejo de errores
            .catch((err) => {
                console.error('Error al acceder a la cámara:', err);
            });
        // Tomar foto requerida
        btnCapturar.addEventListener('click', () => {
            contexto.drawImage(camara, 0, 0, canvasFoto.width, canvasFoto.height);
            const imagenData = canvasFoto.toDataURL('image/png');
            btnGuardar.href = imagenData;
        });
        //-------------------------------
    </script>
