<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Lotes</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Gestión de Lotes</h1>

        <!-- Tabla de lotes -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID Lote</th>
                    <th>Nombre del Lote</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="lotTable">
                <!-- Filas dinámicas -->
                <tr>
                    <td>9828</td>
                    <td>LOTE 2051833</td>
                    <td>
                        <button class="btn btn-primary btn-sm me-2" data-bs-toggle="modal" data-bs-target="#uploadModal" data-lote-id="1">Subir Archivo</button>
                        <button id="ver-archivo" class="btn btn-secondary btn-sm me-2 ver-archivo" data-lote-id="1">Ver Archivo</button>
                        <button id="eliminar-archivo" class="btn btn-danger btn-sm me-2 eliminar-archivo"  data-lote-id="1">Eliminar Archivo</button>
                        <button id="descargar-archivo" class="btn btn-success btn-sm me-2 descargar-archivo"  data-lote-id="1">Descargar Archivo</button>
                        <iframe id="iframe-mostrar" src="" width="600" height="400" style="display:none;"></iframe>
                    </td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Lote 2</td>
                    <td>
                        <button class="btn btn-primary btn-sm me-2" data-bs-toggle="modal" data-bs-target="#uploadModal" data-lote-id="2">Subir Archivo</button>
                        <button class="btn btn-secondary btn-sm me-2 ver-archivo" disabled data-lote-id="2">Ver Archivo</button>
                    </td>
                </tr>
            </tbody>
        </table>
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

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="./subirArchivo.js"></script>


</body>
</html>