<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Lotes</title>
    <link href=" https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Playwrite+PT+Guides&family=Playwrite+TZ:wght@100..400&family=Quicksand:wght@300..700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

    <link rel="stylesheet" href="CSS/styles.css">


</head>

<body>
    <nav class="navbar bg-primary" data-bs-theme="dark">
        <div class="container-fluid">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="salir-sistema-nav">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="black"
                        class="bi bi-box-arrow-right" viewBox="0 0 18 18">
                        <path fill-rule="evenodd"
                            d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0z" />
                        <path fill-rule="evenodd"
                            d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z" />
                    </svg>

                    <a class="salir-sistema-a" href="login">Salir del sistema</a>
                </li>
            </ul>
        </div>
    </nav>

    <div id="overlay" style="display: none;">
        <div id="loader" class="spinner-border text-primary" style="width: 5rem; height: 5rem;" role="status">
            <span class="visually-hidden">Cargando...</span>
        </div>
    </div>

    <div class="container mt-5">
        <h1 class="mb-4">Gestión de Lotes</h1>


        <div class="laboratorio-producto-div">


            <div class="laboratorio-div">


                <input type="hidden" id="laboratorio_id" name="laboratorio_id">

                <div class="input-group mb-3"  >
                    <span class="input-group-text" id="inputGroup-sizing-default">Laboratorio</span>
                    <input class="form-control" list="datalistOptions" name="laboratorio" id="laboratorio"
                        placeholder="Escriba el nombre del laboratorio">
                </div>
                <!-- Input oculto para el id -->

                <datalist id="datalistOptions">
                    @foreach ($laboratorios as $laboratorio)
                        <option value="{{ $laboratorio->nombre_laboratorio }}"
                            data-id="{{ $laboratorio->laboratorio_id }}">
                            {{ $laboratorio->laboratorio_id }}
                        </option>
                    @endforeach
                </datalist>

            </div>

            <div class="producto-div">

                <div class="input-group mb-3">
                    <span class="input-group-text" id="inputGroup-sizing-default">Artículo</span>

                    <input class="form-control" list="datalistOptionsProductos" id="productoDataList"
                        placeholder="Escriba el nombre del Artículo">
                    <datalist id="datalistOptionsProductos">
                    </datalist>
                </div>

            </div>


            <button type="submit" id="button-buscar" class="btn btn-secondary btn-info-modifacion">Buscar</button>
            <button type="submit" id="button-limpiar" class="btn btn-info btn-info-modifacion">Limpiar</button>

        </div>

        <div class="producto-num-div">
            <div class="input-group mb-3"  >
                <span class="input-group-text" id="inputGroup-sizing-default">Número de Factura</span>
                <input class="form-control"  name="facturaNum" id="num-factura"
                    placeholder="Escriba el número de factura">
            </div>
        </div>


        <div class="agregar-lote-div">

            <div id="agregar-lote-div" class="input-group mb-3" {{ $desactivar ? 'hidden' : '' }}>
                <span class="input-group-text" id="inputGroup-sizing-default">ID Lote</span>
                <input type="text" class="form-control" aria-label="Sizing example input"
                    aria-describedby="inputGroup-sizing-default" id="loteId" disabled>
            </div>

            <div id="agregar-almacen-div" class="input-group mb-3" {{ $desactivar ? 'hidden' : '' }}>
                <span class="input-group-text" id="inputGroup-sizing-default">Almacen</span>
                <input type="text" class="form-control" aria-label="Sizing example input"
                    aria-describedby="inputGroup-sizing-default" id="almacen" list="datalistOptionsTipoAlmacen" disabled>

                <datalist id="datalistOptionsTipoAlmacen">
                    <option value="HYO01">HYO01</option>
                    <option value="HYO02">HYO02</option>
                </datalist>

            </div>

            <div id="agregar-fecha-div" class="input-group mb-3" {{ $desactivar ? 'hidden' : '' }}>
                <span class="input-group-text" id="inputGroup-sizing-default">Fecha de creación</span>
                <input type="date" class="form-control" aria-label="Sizing example input"
                    aria-describedby="inputGroup-sizing-default" id="fechaCreacion" disabled>
            </div>

            <button type="submit" id="button-agregar" class="btn btn-success btn-info-agregar" disabled
                {{ $desactivar ? 'hidden' : '' }}>Agregar</button>

            <div id="mensajeRespuesta" class="mt-3"></div>

        </div>

        {{-- <div class="buscar-lote-div" {{ $desactivar ? 'hidden' : '' }}> --}}
            <div class="buscar-lote-div" hidden>
            <div class="input-group mb-3">
                <span class="input-group-text" id="inputGroup-sizing-default">Buscar lote</span>
                <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-success" type="submit">Buscar</button>

            </div>
        </div>


        <!-- Tabla de lotes -->
        <table class="table table-bordered border-black">
            <thead>
                <tr>
                    <th>ID Lote</th>
                    <th>Acciones archivos</th>
                    <th>Acciones lotes</th>
                </tr>
            </thead>
            <tbody id="lotTable">

            </tbody>



        </table>

        <div id="pagination" class="mt-3"></div>

    </div>

    <!-- Modal para subir archivos -->
    <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadModalLabel">Subir Archivo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="uploadForm" enctype="multipart/form-data">
                        <input type="hidden" id="loteId" name="loteId">
                        <div class="mb-3">
                            <label for="fileInput" class="form-label">Selecciona un archivo (PDF o Imagen):</label>
                            <input type="file" class="form-control" id="fileInput" name="file" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Subir</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div id="app" data-desactivar="{{ json_encode($desactivar) }}"></div>

    {{-- button chat bot --}}
    <button class="chat-toggle" onclick="toggleChat()">
        <i class="fa-brands fa-android"></i>
    </button>

     <div class="chat-window" id="chatWindow">
    <div class="chat-header">
      <div class="title">
                 <i class="fas fa-robot"></i>
        <div>
          <div><strong>Asistente Virtual BeizaNetBot</strong></div>
          <small style="color: #6ee7b7">🟢 En línea</small>
        </div>
      </div>
      <button onclick="toggleChat()" style="background:none;border:none;color:white;font-size:18px;">✖</button>
    </div>

    <div class="chat-messages" id="chatBox">
      <div class="message bot">Hola, soy tu asistente de BeizaNet Gestión de Lotes. 👋
Hasta la fecha hay 1922 lotes registrados .
Si tienes alguna duda que no pueda responder, puedes escribir a joan@gmail.com.</div>
    </div>

    <div class="chat-input">
      <input type="text" id="userInput" placeholder="Escribe tu mensaje..." onkeypress="if(event.key==='Enter') sendMessage()" />
      <button onclick="sendMessage()"><i class="fas fa-paper-plane"></i></button>
    </div>
  </div>

  {{-- fin chat bot --}}

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="./subirArchivo.js"></script>
    <script src="./scripts/chat.js"></script>



</body>

</html>
