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
            <button id="saveCanvas" class="btn btn-primary">Guardar dibujo</button>
            <button id="clearCanvas" class="btn btn-primary"><i class="bi bi-eraser-fill"></i></button>
        </div>
    </div>

    <!-- Espacio para API de Video -->
     <!-----Implementacion del video y la imagen de portada----->
    <div class="card mb-4">
        <div class="card-header">
            <h5>API de Video</h5>
        </div>
        <div class="card-body text-center">
            <video id="videoEP3" width="640" height="360" controls poster="{{asset('images/cine.jpg')}}">
            <source src="{{asset('videos/cocolito.mp4')}}" type="video/mp4">
        </video>
        <!-----Botonoes del video----->
    <div class="mt-3">
        <button id="btnReproducir" class="btn btn-primary me-2">Reproducir</button>
        <button id="btnPausa" class="btn btn-danger me-2">Pausar</button>
        <button id="btnReiniciar" class="btn btn-secondary">Reiniciar</button>
    </div>
    </div>
    </div>
    </div>

    <script>
        //-- Espacio para API de Geolocalización --

        //-----------------------------------------
        //-- Espacio para API de Canvas --
        const canvas = document.getElementById('drawingCanvas');
        const ctx = canvas.getContext('2d');

        //Para que el fondo sea blanco, porque por defecto es transparente al guardalo
        ctx.fillStyle = 'white';
        ctx.fillRect(0, 0, canvas.width, canvas.height);

        let drawing = false;
    
        canvas.addEventListener('mousedown', (e) => {
            drawing = true;
            ctx.beginPath();
            ctx.moveTo(e.offsetX, e.offsetY);
        });

        canvas.addEventListener('mousemove', (e) => {
            if (!drawing) return;
            ctx.lineTo(e.offsetX, e.offsetY);
            ctx.strokeStyle = 'black';  // Color del pincel
            ctx.lineWidth = 2;          // Grosor del pincel
            ctx.stroke();
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
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        });

        //Boton para borrar dibujo
        const BtnBorrar = document.getElementById('clearCanvas');
        BtnBorrar.addEventListener('click', function(){
            ctx.clearRect(0,0, canvas.width, canvas.height)
        })

        //--------------------------------
        //-- Espacio para API de Video --
        //-----Script de los eventos----
        window.addEventListener('DOMContentLoaded', function () {
            //Declaracion de constantes
            const video = document.getElementById('videoEP3');
            const btnPlay = document.getElementById('btnReproducir');
            const btnPause = document.getElementById('btnPausa');
            const btnReset = document.getElementById('btnReiniciar');
            //Eventos de los botones
            //Reproducir
            btnPlay.addEventListener('click', () => {
                video.play();
            });
            //Pausa
            btnPause.addEventListener('click', () => {
                video.pause();
            });
            //Reinicio
            btnReset.addEventListener('click', () => {
                video.currentTime = 0;
                video.play();
            });
        });

        //-------------------------------
    </script>