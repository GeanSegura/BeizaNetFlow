document.getElementById('laboratorio').addEventListener('input', function () {

    var articulosList1 = document.getElementById('productoDataList');
    articulosList1.innerHTML = ''; // Limpiar el datalist de artículos
    var articulosList = document.getElementById('datalistOptionsProductos');
    articulosList.innerHTML = ''; // Limpiar el datalist de artículos

    var laboratorioNombre = this.value; // Obtener el nombre del laboratorio seleccionado
    var laboratorioId = null;

    // Buscar la opción en el datalist correspondiente al nombre del laboratorio
    var options = document.querySelectorAll('#datalistOptions option');
    options.forEach(function (option) {
        if (option.value === laboratorioNombre) {
            laboratorioId = option.getAttribute('data-id'); // Obtener el laboratorio_id
        }
    });

    if (laboratorioId) {
        // Establecer el valor del input oculto con el laboratorio_id
        document.getElementById('laboratorio_id').value = laboratorioId;

        // Realizar la solicitud para obtener los artículos de ese laboratorio usando Axios
        axios.get(`/GestionLotesLaboratorio/${laboratorioId}`)
            .then(function (response) {
                // Llenar el datalist de artículos con los datos obtenidos
                var articulosList = document.getElementById('datalistOptionsProductos');
                articulosList.innerHTML = ''; // Limpiar el datalist de artículos

                response.data.forEach(function (articulo) {
                    var option = document.createElement('option');
                    option.value = articulo.nombre_articulo;
                    option.textContent = articulo.id_laboratorio;
                    articulosList.appendChild(option); // Añadir el artículo al datalist
                });
            })
            .catch(function (error) {
                console.error('Error al obtener los artículos:', error);
            });
    }
});



let loteId = null;

// Capturar loteId al abrir el modal
document.querySelectorAll('[data-bs-target="#uploadModal"]').forEach(button => {
    button.addEventListener('click', () => {
        loteId = button.getAttribute('data-lote-id');
        document.getElementById('loteId').value = loteId;
    });
});

// Subir archivo
document.getElementById('uploadForm').addEventListener('submit', function (e) {
    e.preventDefault();

    const formData = new FormData(this);
    axios.post('/Subir', formData)
        .then(response => {
            alert(response.data.message);

            // Habilitar el botón de Ver Archivo
            document.querySelector(`.ver-archivo[data-lote-id="${loteId}"]`).disabled = false;

            // Cerrar el modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('uploadModal'));
            modal.hide();

            // Limpiar el formulario
            this.reset();
        })
        .catch(error => {
            console.error('Error al subir el archivo:', error);
            alert('Hubo un problema al subir el archivo');
        });
});

document.getElementById('ver-archivo').addEventListener('click', function (e) {
    e.preventDefault();

    const fileName = "1736522160_2051833-DILOVET 250MG.pdf";

    axios.get(`/VerArchivo/${fileName}`, { responseType: 'blob' })
        .then(response => {
            const url = URL.createObjectURL(response.data);
            const iframe = document.getElementById('iframe-mostrar');
            if (iframe) {
                // Actualiza el contenido de un iframe en tu vista Blade con la URL del archivo
                iframe.src = url;
                iframe.style.display = 'block'; // Muestra el iframe
            } else {
                console.error('El elemento iframe no se encontró en el DOM.');
            }
        })
        .catch(error => {
            console.error('Error al obtener el archivo:', error);
            alert('No se pudo cargar el archivo.');
        });
});

document.querySelectorAll('.descargar-archivo').forEach(button => {
    button.addEventListener('click', function (e) {
        e.preventDefault();

        const fileName = "1736522160_2051833-DILOVET 250MG.pdf";

        axios.get(`/VerArchivo/${fileName}`, { responseType: 'blob' })
            .then(response => {
                const url = URL.createObjectURL(response.data);
                const a = document.createElement('a');
                a.href = url;
                a.download = fileName;
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
            })
            .catch(error => {
                console.error('Error al descargar el archivo:', error);
                alert('No se pudo descargar el archivo.');
            });
    });
});

