<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Chatbot Laboratorio</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/awesomplete/1.1.5/awesomplete.css" />

    <style>
        body {
            background: #f0f2f5;
            font-family: Arial, sans-serif;
        }

        .chat-container {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            height: 100vh;
            width: 100%;
            position: relative;
        }

        .chat-card {
            width: 80%;
            max-width: 800px;
            border-radius: 15px;
            overflow: hidden;
            background: white;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            z-index: 1;
            margin: auto;
        }

        .chat-header {
            background: linear-gradient(to right, #0a58ca, #ff6fa4f0);
            color: white;
            padding: 15px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .chat-header span {
            font-weight: bold;
        }

        #chat-messages {
            background: #f9f9f9;
            flex-grow: 1;
            overflow-y: auto;
            padding: 10px;
            border-top: 1px solid #ddd;
            border-bottom: 1px solid #ddd;
            scroll-behavior: smooth;
            max-height: 300px;
        }

        .chat-input {
            position: relative;
            display: flex;
            padding: 10px;
            height: 364px;
        }

        .chat-input input {
            flex: 1;
            border: 1px solid #ccc;
            border-radius: 20px;
            padding: 8px 15px;
        }

        .chat-input button {
            background: #0A58CB;
            color: white;
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            margin-left: 10px;
        }

        #modal-config {
            position: fixed;
            top: 0;
            right: 0;
            width: 30%;
            height: 100vh;
            background: white;
            padding: 20px;
            overflow-y: auto;
            box-shadow: -5px 0 15px rgba(0, 0, 0, 0.1);
            display: none;
            z-index: 2;
        }

        #tabla-resultados {
            overflow-x: auto;
        }

        .loader-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background-color: rgba(255, 255, 255, 0.6);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .loader-size {
            width: 160px;
            height: 160px;
            border-width: 12px;
            /* más grueso */
        }

        @media (max-width: 600px) {
            body {
                font-size: 1.05rem;
            }

            .chat-container {
                flex-direction: column;
                align-items: stretch;
                justify-content: space-between;
            }

            .chat-card {
                width: 100%;
                border-radius: 0;
                margin: 0;
            }

            #modal-config {
                width: 100%;
                height: auto;
                position: relative;
            }

            #chat-messages {
                font-size: 1.1rem;
            }

            .chat-input input,
            .chat-input button {
                font-size: 1rem;
            }

            #tabla-resultados {
                overflow-x: auto;
            }

            .table-primary {
                width: 100%;
                /* Que la tabla ocupe todo el contenedor */
                border-collapse: collapse;
                table-layout: auto;
                /* columnas se ajustan según contenido */
            }

            th,
            td {
                padding: 8px 12px;
                border: 1px solid #ddd;
                text-align: left;
                white-space: nowrap;
                /* evita que texto largo se rompa en varias líneas */
            }

            thead th {
                background-color: #cfe2ff;
            }

            tbody tr:hover {
                background-color: #f1f1f1;
            }
        }
    </style>
</head>

<body>

    <div class="chat-container">
        <div class="chat-card">
            <div class="chat-header">
                <div>
                    <span class="d-block">Chat con BeizaNetFlow</span>
                    <small>💊 Precio de Artículos por Laboratorio</small>
                </div>
                <div class="d-flex gap-2">

                    @php
                        $estado = session('isConfiguracion', '0');
                    @endphp

                    <button class="btn btn-sm btn-light" onclick="toggleModal()" title="Configuración"
                        @if ($estado === '0') disabled @endif>
                        ⚙️
                    </button>

                    <a class="btn btn-sm btn-danger" href="/login" title="Salir">⏻</a>
                </div>
            </div>


            <div id="chat-messages">
                <div><strong>BeizaNetFlow:</strong> Hola 👋 ¿En qué puedo ayudarte?</div>
            </div>

            <div class="chat-input">
                <input type="text" id="user-input" placeholder="Buscar laboratorio...">

                <button id="btnVolverLaboratorio" onclick="volverALaboratorio()" class="btn btn-sm btn-primary">🔙
                    Volver a Laboratorio</button>

                <button id="btnLimpiarChat" onclick="limpiarChat()" class="btn btn-sm btn-warning"
                    style="margin-left: 10px;">🧹 Limpiar Chat</button>

                <button onclick="enviarPregunta2()">➤</button>
            </div>
        </div>


        <!-- Modal configuración -->
        <form method="POST" action="{{ route('subirExcel') }}" enctype="multipart/form-data"
            onsubmit="return validarYMostrarLoader()">
            @csrf
            <div id="modal-config">
                <div class="d-flex justify-content-between mb-3">
                    <h5>Configuración</h5>
                    <button class="btn btn-danger btn-sm" type="button" onclick="toggleModal()">✖</button>

                </div>

                <div class="mb-3">
                    <label class="form-label">Subir archivo Excel:</label>
                    <input type="file" name="archivo_excel" class="form-control" accept=".xlsx, .xls"
                        id="excel-upload">
                </div>
                <button type="submit" class="btn btn-success">Cargar Excel</button>

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                    </div>
                @endif

        </form>

        @if (session('abrir_modal'))
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    toggleModal(); // Esta función debe estar ya definida
                });
            </script>
        @endif

        <div id="loader" class="loader-overlay" style="display: none;">
            <div class="spinner-border text-primary loader-size" role="status"></div>
        </div>

        <div class="mb-3">
            <label for="busqueda">Buscar laboratorio:</label>
            <div class="input-group">
                <input type="text" id="busqueda" class="form-control" placeholder="Ej: Caferma, Química, etc.">
                <button class="btn btn-primary" type="button" id="btnBuscar">Buscar</button>
            </div>
        </div>

        <!-- Tabla de resultados -->
        <div id="tabla-resultados" class="mt-3">
            <h5>Laboratorios encontrados</h5>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-primary">
                        <tr>
                            <th>Laboratorio</th>
                            <th>Porcentaje %</th>
                            <th>Operación</th>
                        </tr>
                    </thead>


                    <tbody id="tabla-body"></tbody>
                </table>
            </div>
        </div>

        <!-- Mensaje si no hay resultados -->
        <div id="mensaje-vacio" class="alert alert-warning d-none mt-3">
            No se encontraron laboratorios.
        </div>

        <button id="btnGuardar" class="btn btn-primary w-100 mt-3" type="button">Guardar Cambios</button>
    </div>
    </div>

    <!-- Bootstrap JS -->
    <script>
        const RUTA_LISTA_LABORATORIOS = "{{ route('listaLaboratoriosExcel') }}";
        const RUTA_LISTA_ARTICULOS = "{{ route('ListarArticulosExcel') }}";
        const RUTA_DATOS_ARTICULO = "{{ route('ListarDatosArticulo') }}";
        const RUTA_GUARDAR_CONFIGURACION = "{{ route('guardarConfiguracion') }}";
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/awesomplete/1.1.5/awesomplete.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="./scripts/CargarExcel/cargarExcel.js?v={{ time() }}"></script>


</body>

</html>
