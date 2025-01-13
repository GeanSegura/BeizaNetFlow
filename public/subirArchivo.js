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
        document.getElementById('overlay').style.display = 'flex';

        // Realizar la solicitud para obtener los artículos de ese laboratorio usando Axios
        axios.get(`/GestionLotesLaboratorio/${laboratorioId}`)
            .then(function (response) {
                // Limpiar el datalist de artículos
                articulosList.innerHTML = '';

                // Llenar el datalist de artículos con los datos obtenidos
                response.data.forEach(function (articulo) {
                    var option = document.createElement('option');
                    option.value = articulo.nombre_articulo;
                    option.textContent = articulo.articulo_id;
                    option.setAttribute('data-articulo-id', articulo.articulo_id);
                    articulosList.appendChild(option); // Añadir el artículo al datalist
                });

                // Ocultar el loader una vez que los datos han sido cargados
                document.getElementById('overlay').style.display = 'none';
            })
            .catch(function (error) {
                console.error('Error al obtener los artículos:', error);

                // Ocultar el loader en caso de error
                document.getElementById('overlay').style.display = 'none';
            });
    }
});

// document.getElementById('laboratorio').addEventListener('input', function () {

//     var articulosList1 = document.getElementById('productoDataList');
//     articulosList1.innerHTML = ''; // Limpiar el datalist de artículos
//     var articulosList = document.getElementById('datalistOptionsProductos');
//     articulosList.innerHTML = ''; // Limpiar el datalist de artículos

//     var laboratorioNombre = this.value; // Obtener el nombre del laboratorio seleccionado
//     var laboratorioId = null;

//     // Buscar la opción en el datalist correspondiente al nombre del laboratorio
//     var options = document.querySelectorAll('#datalistOptions option');
//     options.forEach(function (option) {
//         if (option.value === laboratorioNombre) {
//             laboratorioId = option.getAttribute('data-id'); // Obtener el laboratorio_id
//         }
//     });

//     if (laboratorioId) {
//         // Establecer el valor del input oculto con el laboratorio_id
//         document.getElementById('laboratorio_id').value = laboratorioId;

//         // Realizar la solicitud para obtener los artículos de ese laboratorio usando Axios
//         axios.get(`/GestionLotesLaboratorio/${laboratorioId}`)
//             .then(function (response) {
//                 // Llenar el datalist de artículos con los datos obtenidos
//                 var articulosList = document.getElementById('datalistOptionsProductos');
//                 articulosList.innerHTML = ''; // Limpiar el datalist de artículos

//                 response.data.forEach(function (articulo) {
//                     var option = document.createElement('option');
//                     option.value = articulo.nombre_articulo;
//                     option.textContent = articulo.articulo_id;
//                     option.setAttribute('data-articulo-id', articulo.articulo_id);
//                     articulosList.appendChild(option); // Añadir el artículo al datalist
//                 });
//             })
//             .catch(function (error) {
//                 console.error('Error al obtener los artículos:', error);
//             });
//     }
// });



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


//LIMPIAR BOTON
document.getElementById('button-limpiar').addEventListener('click', function (e) {
    e.preventDefault();

    var articulosList1 = document.getElementById('productoDataList');
    articulosList1.innerHTML = ''; // Limpiar el datalist de artículos
    articulosList1.value = '';

    var articulosList = document.getElementById('datalistOptionsProductos');
    articulosList.innerHTML = ''; // Limpiar el datalist de artículos

    var inputLaboratorios = document.getElementById('laboratorio');
    inputLaboratorios.value = '';

    var tblBodyLotes = document.getElementById('lotTable');
    tblBodyLotes.innerHTML = '';

    const paginationContainer = document.getElementById('pagination');
    if (paginationContainer) {
        paginationContainer.innerHTML = '';  // Elimina los botones de paginación
    }

});


let articuloId = 0; // Mover la declaración a un alcance global

document.getElementById('button-buscar').addEventListener('click', function () {
    // Capturar el valor del artículo seleccionado en el datalist
    var articuloSeleccionado = document.querySelector('#datalistOptionsProductos option[value="' + document.getElementById('productoDataList').value + '"]');
    var buttonAgregar = document.getElementById('button-agregar');

    buttonAgregar.disabled = false;

    if (articuloSeleccionado) {
        articuloId = articuloSeleccionado.getAttribute('data-articulo-id');
        // Llamada AJAX para obtener los datos del artículo
        axios.get(`/GestionLotesArticulo/${articuloId}`)
            .then(response => {
                actualizarTabla(response.data.data); // Acceder a los datos paginados
                manejarPaginacion(response.data); // Manejar la paginación
            })
            .catch(error => {
                console.error('Error al buscar el artículo:', error);
            });
    } else {
        alert('Seleccione un artículo válido.');
    }
});

function actualizarTabla(data) {
    // Limpiar la tabla antes de actualizarla
    const tabla = document.getElementById('lotTable');
    tabla.innerHTML = ''; // Limpiar tabla



    data.forEach(lote => {
        const fila = `
            <tr id="lote-${lote.lote_id}"> <!-- Agregar un id único para cada fila -->
                <td>${lote.lote_id}</td>
                <td>
                    <button class="btn btn-secondary btn-sm me-2" onclick="mostrarArchivo('${lote.lote_id}')">
            Ver Archivo
                    </button>
                    <button class="btn btn-success btn-sm me-2 descargar-archivo" data-lote-id="${lote.lote_id}">
                        Descargar Archivo
                    </button>
                    <button class="btn btn-primary btn-sm me-2" data-bs-toggle="modal" data-bs-target="#uploadModal" data-lote-id="${lote.lote_id}">
                        Subir Archivo
                    </button>
                    <button class="btn btn-danger btn-sm me-2 eliminar-archivo" data-lote-id="${lote.lote_id}">
                        Eliminar Archivo
                    </button>
                </td>
                <td>
                    <button class="btn btn-warning btn-sm me-2 modificar-lote" data-lote-id="${lote.lote_id}">
                        Modificar lote
                    </button>
                    <button class="btn btn-success btn-sm me-2 guardar-lote" data-lote-id="${lote.lote_id}">
                        Guardar lote
                    </button>
                    <button class="btn btn-danger btn-sm me-2 eliminar-lote" data-lote-id="${lote.lote_id}">
                        Eliminar lote
                    </button>
                </td>
            </tr>
        `;
        tabla.insertAdjacentHTML('beforeend', fila);
    });
}

function manejarPaginacion(paginacion) {
    const paginationContainer = document.getElementById('pagination');
    paginationContainer.innerHTML = ''; // Limpiar el contenedor de paginación

    // Mostrar cantidad de registros totales
    const totalDatos = document.createElement('p');
    totalDatos.textContent = `Total de registros: ${paginacion.total}`;
    totalDatos.className = 'text-muted';
    paginationContainer.appendChild(totalDatos);

    const ul = document.createElement('ul');
    ul.className = 'pagination justify-content-center';

    // Botón "Anterior"
    const prevItem = document.createElement('li');
    prevItem.className = `page-item ${paginacion.current_page === 1 ? 'disabled' : ''}`;
    prevItem.innerHTML = `
        <a class="page-link" href="#" tabindex="-1" onclick="cambiarPagina(${paginacion.current_page - 1})">Anterior</a>
    `;
    ul.appendChild(prevItem);

    // Números de página
    for (let i = 1; i <= paginacion.last_page; i++) {
        const pageItem = document.createElement('li');
        pageItem.className = `page-item ${i === paginacion.current_page ? 'active' : ''}`;
        pageItem.innerHTML = `
            <a class="page-link" href="#" onclick="cambiarPagina(${i})">${i}</a>
        `;
        ul.appendChild(pageItem);
    }

    // Botón "Siguiente"
    const nextItem = document.createElement('li');
    nextItem.className = `page-item ${paginacion.current_page === paginacion.last_page ? 'disabled' : ''}`;
    nextItem.innerHTML = `
        <a class="page-link" href="#" onclick="cambiarPagina(${paginacion.current_page + 1})">Siguiente</a>
    `;
    ul.appendChild(nextItem);

    paginationContainer.appendChild(ul);
}

function cambiarPagina(page) {
    // Simulación de solicitud de datos para la nueva página
    axios.get(`/GestionLotesArticulo/${articuloId}?page=${page}`)
        .then(response => {
            actualizarTabla(response.data.data);
            manejarPaginacion(response.data);
        })
        .catch(error => {
            console.error('Error al cambiar de página:', error);
        });
}

// Botones AWS
function mostrarArchivo(loteId) {

    const fileName = "1736522160_2051833-DILOVET 250MG.pdf"; // Aquí puedes reemplazarlo por una variable si es dinámico

    axios.get(`/VerArchivo/${fileName}`, { responseType: 'blob' })
        .then(response => {

            const url = URL.createObjectURL(response.data); // Crea un objeto URL a partir de la respuesta binaria

            // Encuentra la fila correspondiente al loteId
            const fila = document.querySelector(`#lote-${loteId}`);

            if (!fila) {
                console.error('Fila no encontrada');
                return;
            }

            // Encuentra la segunda celda (columna) dentro de la fila
            const segundaCelda = fila.querySelectorAll('td')[1]; // Obtén la segunda celda (índice 1)

            if (!segundaCelda) {
                console.error('Segunda celda no encontrada');
                return;
            }

            // Verificar si ya existe un iframe
            const existingIframe = fila.querySelector('.iframe-container');
            if (existingIframe) {
                existingIframe.style.display = 'block'; // Muestra el iframe si ya existe
                return;
            }

            // Crear el iframe
            const iframe = document.createElement('iframe');
            iframe.src = url;
            iframe.style.width = '100%';  // Asegura que el iframe ocupe el 100% de la celda
            iframe.style.height = '400px';  // Ajustar la altura según sea necesario
            iframe.style.border = 'none';   // Opcional: eliminar el borde del iframe

            // Crear el contenedor del iframe y agregarlo a la celda
            const iframeContainer = document.createElement('div');
            iframeContainer.style.marginTop = '8px';
            iframeContainer.classList.add('iframe-container');
            iframeContainer.style.position = 'relative';
            iframeContainer.style.width = '100%';  // Asegura que el contenedor ocupe el 100% de la celda
            iframeContainer.style.height = '400px'; // Ajustar la altura según sea necesario
            iframeContainer.style.overflow = 'hidden';  // Evita el desbordamiento si el contenido del iframe es mayor

            const closeButton = document.createElement('button');
            closeButton.type = 'button';
            closeButton.classList.add('btn', 'btn-danger', 'custom-close'); // Usamos 'btn-danger' para un botón rojo
            closeButton.style.position = 'absolute';
            closeButton.style.top = '10px';
            closeButton.style.right = '10px';
            closeButton.onclick = function () {
                iframeContainer.style.display = 'none'; // Ocultar el iframe al hacer clic en el botón cerrar
            };
            closeButton.innerHTML = 'X'; // Agregar la "X" dentro del botón

            iframeContainer.appendChild(closeButton);
            iframeContainer.appendChild(iframe);

            // Agregar el iframe dentro de la segunda celda de la fila correspondiente
            segundaCelda.appendChild(iframeContainer);
        })
        .catch(error => {
            console.error('Error al obtener el archivo:', error);
            alert('No se pudo cargar el archivo.');
        });
}

// inicio agregar lote

document.getElementById('button-agregar').addEventListener('click', function () {
    const loteId = document.getElementById('loteId').value;
    const almacen = document.getElementById('almacen').value;
    const fechaCreacion = document.getElementById('fechaCreacion').value;

    var articuloSeleccionado = document.querySelector('#datalistOptionsProductos option[value="' + document.getElementById('productoDataList').value + '"]');


    if (articuloSeleccionado) {
        articuloId = articuloSeleccionado.getAttribute('data-articulo-id');
    }

    // Validación básica
    if (!loteId || !almacen || !fechaCreacion) {
        alert('Por favor, complete todos los campos.');
        return;
    }

    // Enviar datos al servidor
    axios.post('/AgregarObtenerLote', {
        lote_id: loteId,
        almacen: almacen,
        fecha_creacion: fechaCreacion,
        articulo_id: articuloId
    })
        .then(response => {
            document.getElementById('mensajeRespuesta').textContent = 'Lote agregado exitosamente.';
            document.getElementById('mensajeRespuesta').classList.add('text-success');

            // Limpiar los campos
            document.getElementById('loteId').value = '';
            document.getElementById('almacen').value = '';
            document.getElementById('fechaCreacion').value = '';
        })
        .catch(error => {
            console.error('Error al agregar el lote:', error);
            document.getElementById('mensajeRespuesta').textContent = 'Error al agregar el lote.';
            document.getElementById('mensajeRespuesta').classList.add('text-danger');
        });


    if (articuloSeleccionado) {
        articuloId = articuloSeleccionado.getAttribute('data-articulo-id');
        // Llamada AJAX para obtener los datos del artículo
        axios.get(`/GestionLotesArticulo/${articuloId}`)
            .then(response => {
                actualizarTabla(response.data.data); // Acceder a los datos paginados
                manejarPaginacion(response.data); // Manejar la paginación
            })
            .catch(error => {
                console.error('Error al buscar el artículo:', error);
            });
    } else {
        alert('Seleccione un artículo válido.');
    }

});


// Fin agregar lote
