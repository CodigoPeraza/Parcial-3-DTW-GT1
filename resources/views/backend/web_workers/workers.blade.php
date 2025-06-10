@extends('backend.menus.superior')

@section('content-admin-css')
<link href="{{ asset('css/adminlte.min.css') }}" type="text/css" rel="stylesheet" />
<link href="{{ asset('css/dataTables.bootstrap4.css') }}" type="text/css" rel="stylesheet" />
<link href="{{ asset('css/toastr.min.css') }}" type="text/css" rel="stylesheet" />
<link href="{{ asset('css/buttons_estilo.css') }}" rel="stylesheet">
@stop

<div id="divcontenedor" style="display: none;">
    <section class="content-header py-2">
        <div class="container-fluid px-2 d-flex justify-content-center">
            <h1 class="h4 mb-0">Web Worker para lista de números</h1>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid px-2">
            <div class="card shadow-sm border-success mb-0">
                <div class="card-header">
                    <p class="text-start">Mostrar lista de números</p><button type="button"
                        class="btn btn-outline-primary" onclick="cargarListaNúmeros()">Cargar</button>
                </div>
                <ul class="list-group list-group-flush" id="resultList">
                </ul>
            </div>
        </div>
    </section>
</div>

@extends('backend.menus.footerjs')
@section('archivos-js')

<script src="{{ asset('js/dataTables.bootstrap4.js') }}" type="text/javascript"></script>

<script src="{{ asset('js/toastr.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/axios.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>
<script src="{{ asset('js/alertaPersonalizada.js') }}"></script>

<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function () {

        let worker;

        // Función para llamar a la generación de los 100k números
        function cargarListaNúmeros() {
            // Generar 100,000 números aleatorios
            const numbers = Array.from({ length: 100000 }, () => Math.floor(Math.random() * 1000000));
            worker.postMessage(numbers);

            worker.onmessage = function (e) {
                const sorted = e.data;
                const list = document.getElementById('resultList');
                sorted.forEach(num => {
                    const li = document.createElement('li');
                    li.textContent = num;
                    li.classList.add('list-group-item');
                    list.appendChild(li);
                });
            };

            worker.onerror = function (error) {
                console.error("Error en el worker:", error.message);
                toastr.error("Error en el Web Worker.");
            };
        }

        // Creamos un bloque trycatch para manejar nuestros errores
        try {
            worker = new Worker('/js/web_worker/web_worker.js');
            document.getElementById("divcontenedor").style.display = "block";
        } catch (err) {
            console.error("Error al usar Web Worker:", err.message);
            toastr.error("Fallo al iniciar Web Worker.");
        }
    });
</script>

@stop