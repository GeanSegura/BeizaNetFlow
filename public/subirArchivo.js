var desactivar = JSON.parse(document.getElementById('app').getAttribute('data-desactivar'));

document.getElementById('laboratorio').addEventListener('input', function () {

    var articulosList1 = document.getElementById('productoDataList');
    articulosList1.value = '';
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
        mostrarLoader()
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
                ocultarLoader();
            })
            .catch(function (error) {
                console.error('Error al obtener los artículos:', error);

                // Ocultar el loader en caso de error
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




//LIMPIAR BOTON
document.getElementById('button-limpiar').addEventListener('click', function (e) {
    e.preventDefault();

    var buttonAgregar = document.getElementById('button-agregar');
    var iptLote = document.getElementById('loteId');
    var iptAlmacen = document.getElementById('almacen');
    var iptFecha = document.getElementById('fechaCreacion');

    buttonAgregar.disabled = true;
    iptLote.disabled = true;
    iptAlmacen.disabled = true;
    iptFecha.disabled = true;

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

// inicio boton buscar
document.getElementById('button-buscar').addEventListener('click', function () {
    // Capturar el valor del artículo seleccionado en el datalist
    var articuloSeleccionado = document.querySelector('#datalistOptionsProductos option[value="' + document.getElementById('productoDataList').value + '"]');
    var buttonAgregar = document.getElementById('button-agregar');
    var iptLote = document.getElementById('loteId');
    var iptAlmacen = document.getElementById('almacen');
    var iptFecha = document.getElementById('fechaCreacion');

    buttonAgregar.disabled = false;
    iptLote.disabled = false;
    iptAlmacen.disabled = false;
    iptFecha.disabled = false;



    if (articuloSeleccionado) {
        articuloId = articuloSeleccionado.getAttribute('data-articulo-id');

        mostrarLoader();
        // Llamada AJAX para obtener los datos del artículo
        axios.get(`/GestionLotesArticulo/${articuloId}`)

            .then(response => {
                actualizarTabla(response.data.data); // Acceder a los datos paginados
                manejarPaginacion(response.data); // Manejar la paginación
            })
            .catch(error => {
                console.error('Error al buscar el artículo:', error);
            })
            .finally(() => {
                ocultarLoader();
            });



    } else {
        alert('Seleccione un artículo válido.');
    }
});



// agregar botones
function actualizarTabla(data) {
    // Limpiar la tabla antes de actualizarla
    const tabla = document.getElementById('lotTable');
    tabla.innerHTML = ''; // Limpiar tabla

    mostrarLoader();

    data.forEach(lote => {
        // Comprobamos si 'is_archivo' está vacío o tiene un valor
        const isArchivoVacio = !lote.is_archivo || lote.is_archivo === '';

        const fila = `
            <tr id="lote-${lote.lote_id}"> <!-- Agregar un id único para cada fila -->
                <td>${lote.lote_id}</td>
                <td>
                    <button class="btn btn-secondary btn-sm me-2" onclick="mostrarArchivo('${lote.lote_id}')"
                        ${isArchivoVacio ? 'disabled' : ''}>
                        Ver Archivo
                    </button>

                    <button class="btn btn-primary btn-sm me-2" data-bs-toggle="modal" data-bs-target="#uploadModal" onclick="subirArchivo('${lote.lote_id}')"
                        ${isArchivoVacio ? '' : 'disabled'} ${desactivar ? 'hidden' : ''}>
                        Subir Archivo
                    </button>

                    <button class="btn btn-success btn-sm me-2 descargar-archivo" onclick="descargarArchivo('${lote.lote_id}')"
                        ${isArchivoVacio ? 'disabled' : ''}>
                        Descargar Archivo
                    </button>

                    <button id="${lote.lote_id}" class="btn btn-danger btn-sm me-2 eliminar-archivo"  onclick="eliminarArchivo('${lote.lote_id}')"
                        ${isArchivoVacio ? 'disabled' : ''} ${desactivar ? 'hidden' : ''}>
                        Eliminar Archivo
                    </button>
                </td>
                <td>
                    <button class="btn btn-warning btn-sm me-2 modificar-lote" data-lote-id="${lote.lote_id}" style="display:none;">
                        Modificar lote
                    </button>

                    <button class="btn btn-success btn-sm me-2 guardar-lote" data-lote-id="${lote.lote_id}" style="display:none;">
                        Guardar lote
                    </button>

                    <button class="btn btn-danger btn-sm me-2 eliminar-lote" onclick="eliminarLote('${lote.lote_id}')" ${desactivar ? 'hidden' : ''}>
                        Eliminar lote
                    </button>

                     <button class="btn btn-warning btn-sm me-2 eliminar-lote" onclick="limpiarLote()">
                        Limpiar Resultados
                    </button>

                </td>
            </tr>
        `;
        tabla.insertAdjacentHTML('beforeend', fila);
        ocultarLoader();

    });

}


function manejarPaginacion(paginacion) {

    mostrarLoader();
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

    ocultarLoader();


}

function cambiarPagina(page) {
    // Simulación de solicitud de datos para la nueva página
    mostrarLoader();
    axios.get(`/GestionLotesArticulo/${articuloId}?page=${page}`)
        .then(response => {
            actualizarTabla(response.data.data);
            manejarPaginacion(response.data);
        })
        .catch(error => {
            console.error('Error al cambiar de página:', error);
        })
        .finally(() => {
            ocultarLoader();
        });

}

// Boton mostrar archivo
function mostrarArchivo(loteId) {
    mostrarLoader
    axios.get(`/VerArchivo/${loteId}`, { responseType: 'blob' })
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
        })
        .finally(()=>{
             ocultarLoader(); // Ocultar el loader
        });
}
// INICIO BOTONES AWS

// boton subir archivo 

function subirArchivo(loteId) {
    document.getElementById('uploadForm').addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(this);

        formData.append('lote_id', loteId); // Agrega loteId al FormData
        mostrarLoader();
        axios.post('/Subir', formData)
            .then(response => {
                alert(response.data.mensaje);

                buscar()
                    .then(() => {
                        console.log('Datos obtenidos con éxito.');
                    })
                    .catch(error => {
                        console.error('Error al obtener datos:', error);
                    })
                    .finally(() => {
                        ocultarLoader
                    });
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

    }, { once: true }); // Escuchar solo una vez para evitar múltiples manejadores

}

// fin boton subir archivo

// INICIO DESCARGAR ARCHIVO
function descargarArchivo(loteId) {
    axios.get(`/DescargarArchivo/${loteId}`, { responseType: 'blob' })
        .then(response => {
            // Obtener el nombre del archivo desde los headers
            const contentDisposition = response.headers['content-disposition'];
            let fileName = 'archivo_desconocido'; // Nombre por defecto

            if (contentDisposition && contentDisposition.includes('filename=')) {
                // Extraer el nombre del archivo del header
                const fileNameMatch = contentDisposition.match(/filename="(.+)"/);
                if (fileNameMatch.length > 1) {
                    fileName = fileNameMatch[1];
                }
            }

            // Crear un objeto URL para el blob
            const url = URL.createObjectURL(response.data);
            const a = document.createElement('a');
            a.href = url;
            a.download = fileName;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);

            // Liberar el objeto URL
            URL.revokeObjectURL(url);
        })
        .catch(error => {
            console.error('Error al descargar el archivo:', error);
            alert('No se pudo descargar el archivo.');
        });
}
// FIN DESCARGAR ARCHIVO
// inicio eliminar archivo

// inicio eliminar archivo
async function eliminarArchivo(loteId) {
    
    document.getElementById(loteId).disabled = true;

    try {
        mostrarLoader(); // Mostrar el loader
        axios.delete(`/EliminarArchivoAWS/${loteId}`)
            .then(response => {

                alert(response.data.mensaje); // Operación exitosa

                buscar()
                    .then(() => {
                        console.log('Datos obtenidos con éxito.');
                    })
                    .catch(error => {
                        console.error('Error al obtener datos:', error);
                    })
                    .finally(() => {
                        ocultarLoader(); // Ocultar el loader
                    });
            })
            .catch(error => {
                console.error('Error al eliminar el archivo:', error);
                alert('Ocurrió un error al intentar eliminar el archivo.'); // Manejo de error
            })
            .finally(() => {
            });

    } catch (error) {
        console.error('Error al eliminar el archivo:', error);
        alert('Ocurrió un error al intentar eliminar el archivo.');
        ocultarLoader(); // Ocultar el loader
    } finally {
        ocultarLoader(); // Ocultar el loader
        document.getElementById(loteId).disabled = false;
    }
}
// Fin eliminar archivo

// inicio agregar lote
document.getElementById('button-agregar').addEventListener('click', function () {
    const loteId = document.getElementById('loteId').value;
    const almacen = document.getElementById('almacen').value;
    const fechaCreacion = document.getElementById('fechaCreacion').value;

    if (!loteId || !almacen || !fechaCreacion) {
        alert('Por favor, complete todos los campos.');
        return;
    }

    mostrarLoader();

    axios.post('/AgregarObtenerLote', {
        lote_id: loteId,
        almacen: almacen,
        fecha_creacion: fechaCreacion,
        articulo_id: articuloId
    })
        .then(response => {
            var rptaS = response.data.mensaje;
            var mjsArticulo = response.data.articulo;
            var mjsLaboratario = response.data.laboratorio;

            if (rptaS == '1') {
                document.getElementById('mensajeRespuesta').textContent = 'El Lote ha sido agregado exitosamente.';
                document.getElementById('mensajeRespuesta').classList.add('text-success');
            } else {
                document.getElementById('mensajeRespuesta').textContent = 'El lote ya existe' + ' Articulo: ' + mjsArticulo + ' Laboratorio:' + mjsLaboratario;
                document.getElementById('mensajeRespuesta').classList.add('text-danger');
            }

            // Limpiar los campos
            document.getElementById('loteId').value = '';
            document.getElementById('almacen').value = '';
            document.getElementById('fechaCreacion').value = '';

            buscar()
            .then(() => {
                console.log('Datos obtenidos con éxito.');
            })
            .catch(error => {
                console.error('Error al obtener datos:', error);
            })
            .finally(() => {
                ocultarLoader(); // Ocultar el loader
            });

        })
        .catch(error => {
            console.error('Error al agregar el lote:', error);
            document.getElementById('mensajeRespuesta').textContent = 'Error al agregar el lote.';
            document.getElementById('mensajeRespuesta').classList.add('text-danger');
        })
        .finally(() => {
            ocultarLoader

        });

});

// fin agregar lote

// Inicio eliminar lote
function eliminarLote(loteId) {
    

    if (!loteId) {
        alert('ID del lote no válido.');
        return;
    }

    // Mostrar loader mientras se procesa la eliminación
    document.getElementById('overlay').style.display = 'flex';

    axios.delete(`/EliminarLote/${loteId}`)
        .then(response => {
            document.getElementById('mensajeRespuesta').textContent = response.data.mensaje;
            document.getElementById('mensajeRespuesta').classList.add('text-success');

            buscar()
            .then(() => {
                console.log('Datos obtenidos con éxito.');
            })
            .catch(error => {
                console.error('Error al obtener datos:', error);
            })
            .finally(() => {
                ocultarLoader(); // Ocultar el loader
            });

        })
        .catch(error => {
            console.error('Error al eliminar el lote:', error);
            alert('No se pudo eliminar el lote.');
        })
        .finally(() => {
            // Ocultar loader siempre
        });



}

function mostrarLoader() {
    // Muestra el overlay
    document.getElementById('overlay').style.display = 'flex';
    // Agrega la clase 'loading' al body para bloquear interacciones
    document.body.classList.add('loading');
}

function ocultarLoader() {
    // Oculta el overlay
    document.getElementById('overlay').style.display = 'none';
    // Remueve la clase 'loading' del body para habilitar interacciones
    document.body.classList.remove('loading');
}
// Fin eliminar lote

async function buscar() {

    var articuloSeleccionado = document.querySelector('#datalistOptionsProductos option[value="' + document.getElementById('productoDataList').value + '"]');
    if (articuloSeleccionado) {
        articuloId = articuloSeleccionado.getAttribute('data-articulo-id');

        mostrarLoader();
        // Llamada AJAX para obtener los datos del artículo
        axios.get(`/GestionLotesArticulo/${articuloId}`)

            .then(response => {
                actualizarTabla(response.data.data); // Acceder a los datos paginados
                manejarPaginacion(response.data); // Manejar la paginación
            })
            .catch(error => {
                console.error('Error al buscar el artículo:', error);
            })
            .finally(() => {
                ocultarLoader();
            });



    } else {
        alert('Seleccione un artículo válido.');
    }

}

//inicio limpiar lote
function limpiarLote() {

    var buttonAgregar = document.getElementById('button-agregar');
    var iptLote = document.getElementById('loteId');
    var iptAlmacen = document.getElementById('almacen');
    var iptFecha = document.getElementById('fechaCreacion');
    var iptLimpiarLote = document.getElementById('num-factura');

    buttonAgregar.disabled = true;
    iptLote.disabled = true;
    iptAlmacen.disabled = true;
    iptFecha.disabled = true;

    var articulosList1 = document.getElementById('productoDataList');
    articulosList1.innerHTML = ''; // Limpiar el datalist de artículos
    articulosList1.value = '';

    var articulosList = document.getElementById('datalistOptionsProductos');
    articulosList.innerHTML = ''; // Limpiar el datalist de artículos

    var inputLaboratorios = document.getElementById('laboratorio');
    inputLaboratorios.value = '';

    var tblBodyLotes = document.getElementById('lotTable');
    tblBodyLotes.innerHTML = '';

    iptLimpiarLote.value = '';

    const paginationContainer = document.getElementById('pagination');
    if (paginationContainer) {
        paginationContainer.innerHTML = '';  // Elimina los botones de paginación
    }


}


// fin limpiar lote

