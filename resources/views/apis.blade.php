<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Bootstrap ICONS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />

    <style>
        #drawingCanvas {
            cursor: crosshair;
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
        <div class="card-body bg-light text-center" style="height: 150px;">
            <p>Contenido pendiente.</p>
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
            <video id="camara" width="640" height="360" autoplay playsinline class="border mb-2 mx-auto d-block"></video>
          <!-----Boton de captura, guardado y muestra de la foto----->
          <button id="btnCapturar" class="btn btn-success mb-3">Tomar foto</button>
          <canvas id="foto" width="640" height="360" class="border mb-3 mx-auto d-block"></canvas>
          <a id="btnGuardar" download="foto.png" class="btn btn-info">Guardar</a>
        </div>
     </div>
    </div>


    <script>
        //-- Espacio para API de Geolocalización --

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
        BtnBorrar.addEventListener('click', function(){
            ctx.clearRect(0,0, canvas.width, canvas.height)
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
        navigator.mediaDevices.getUserMedia({ video: true })
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
