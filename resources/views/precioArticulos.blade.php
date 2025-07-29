<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Chatbot Laboratorio</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

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
            display: flex;
            padding: 10px;
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
                    <button class="btn btn-sm btn-light" onclick="toggleModal()" title="Configuración">⚙️</button>
                    <a class="btn btn-sm btn-danger" href="/login" title="Salir">⏻</a>
                </div>
            </div>


            <div id="chat-messages">
                <div><strong>BeizaNetFlow:</strong> Hola 👋 ¿En qué puedo ayudarte?</div>
            </div>

            <div class="chat-input">
                <input type="text" id="user-input" placeholder="Escribe tu mensaje...">
                <button onclick="enviarPregunta()">➤</button>
            </div>
        </div>

        <!-- Modal configuración -->
        <div id="modal-config">
            <div class="d-flex justify-content-between mb-3">
                <h5>Configuración</h5>
                <button class="btn btn-danger btn-sm" onclick="toggleModal()">✖</button>
            </div>

            <div class="mb-3">
                <label class="form-label">Subir archivo Excel:</label>
                <input type="file" class="form-control" accept=".xlsx, .xls" id="excel-upload"
                    onchange="mostrarTabla()">
            </div>

            <div id="tabla-resultados" class="mt-3 d-none">
                <h5>Laboratorios cargados</h5>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-primary">
                            <tr>
                                <th>Laboratorio</th>
                                <th>Porcentaje %</th>
                                <th>Tipo</th>
                            </tr>
                        </thead>
                        <tbody id="tabla-body"></tbody>
                    </table>
                </div>
            </div>

            <button class="btn btn-primary w-100 mt-3" onclick="guardarConfiguracion()">Guardar Cambios</button>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const RUTA_SUBIR_EXCEL = "{{ route('subirExcel') }}";
    </script>
    <script src="./scripts/CargarExcel/cargarExcel.js?v={{ time() }}"></script>


</body>

</html>
