var laboratorioId = "";
// modal de configuracion
function toggleModal() {
    const modal = document.getElementById('modal-config');
    modal.style.display = modal.style.display === 'none' || modal.style.display === '' ? 'block' : 'none';
}

function activateModal() {
    const modal = document.getElementById('modal-config');
    modal.style.display = 'block';
}
// fin modal de configuracion

function mostrarTabla() {
    const tablaBody = document.getElementById("tabla-body");
    tablaBody.innerHTML = `
      <tr>
        <td>Laboratorio Alpha</td>
        <td><input type="number" value="10" class="form-control form-control-sm"></td>
        <td>
          <select class="form-select form-select-sm">
            <option value="suma">Suma</option>
            <option value="resta">Resta</option>
          </select>
        </td>
      </tr>
      <tr>
        <td>Laboratorio Beta</td>
        <td><input type="number" value="5" class="form-control form-control-sm"></td>
        <td>
          <select class="form-select form-select-sm">
            <option value="suma">Suma</option>
            <option value="resta">Resta</option>
          </select>
        </td>
      </tr>
    `;
    document.getElementById("tabla-resultados").classList.remove("d-none");
}

function enviarPregunta() {
    const input = document.getElementById("user-input");
    const mensaje = input.value.trim();
    if (!mensaje) return;

    const chat = document.getElementById("chat-messages");
    chat.innerHTML += `<div><strong>Tú:</strong> ${mensaje}</div>`;

    chat.innerHTML += `
      <div><strong>BeizaNetFlow:</strong> Resultados del laboratorio "${mensaje}":</div>
      <table class="table table-bordered table-sm mt-2">
        <thead>
          <tr>
            <th>ID</th><th>Nombre</th><th>Porcentaje %</th><th>Stock</th><th>Estado</th><th>Precio</th>
          </tr>
        </thead>
        <tbody>
          <tr><td>1</td><td>${mensaje} A</td><td>10%</td><td>50</td><td>Activo</td><td>S/ 35.00</td></tr>
          <tr><td>2</td><td>${mensaje} B</td><td>5%</td><td>20</td><td>Inactivo</td><td>S/ 45.00</td></tr>
        </tbody>
      </table>
    `;
    chat.scrollTop = chat.scrollHeight;
    input.value = "";
}

document.getElementById("user-input").addEventListener("keydown", function (event) {
    if (event.key === "Enter") {
        event.preventDefault();
        enviarPregunta();
    }
});

function validarYMostrarLoader() {
    const inputArchivo = document.getElementById('excel-upload');

    if (!inputArchivo.value) {
        alert('Por favor, seleccione un archivo Excel antes de enviar.');
        return false; // Detiene el envío del formulario
    }

    // Aquí puedes mostrar el loader si tienes alguna función para eso
    mostrarLoader();

    return true; // Permite enviar el formulario
}

// LOADER
function mostrarLoader() {
    document.getElementById('loader').style.display = 'flex';
}

function ocultarLoader() {
    document.getElementById('loader').style.display = 'none';
}
// FIN LOADER

const inputBusqueda = document.getElementById('busqueda');
const awesomplete = new Awesomplete(inputBusqueda, {
    minChars: 1,
    maxItems: 15,
    autoFirst: true
});

// 🌀 Cargar sugerencias para el autocompletado una sola vez
let cacheLaboratorios = [];

function cargarSugerencias() {
    if (cacheLaboratorios.length > 0) {
        const lista = cacheLaboratorios
            .map(l => l.laboratorio)
            .filter((v, i, a) => v && a.indexOf(v) === i);
        awesomplete.list = lista;
        return;
    }

    mostrarLoader();

    $.ajax({
        url: RUTA_LISTA_LABORATORIOS,
        method: "GET",
        success: function (data) {
            cacheLaboratorios = data;

            const lista = data
                .map(l => l.laboratorio)
                .filter((v, i, a) => v && a.indexOf(v) === i);

            awesomplete.list = lista;

            ocultarLoader();
        },
        error: function () {
            ocultarLoader();
            alert('Error al cargar autocompletado');
        }
    });
}

// Trigger cuando el input gana foco o cambia
inputBusqueda.addEventListener('focus', cargarSugerencias);
inputBusqueda.addEventListener('input', cargarSugerencias);

$('#btnBuscar').on('click', function () {
    const texto = $('#busqueda').val().trim().toLowerCase();
    const body = $('#tabla-body');
    body.empty();

    mostrarLoader();

    function filtrarYMostrar(data) {
        let resultados = data;

        if (texto !== "") {
            resultados = data.filter(lab =>
                (lab.laboratorio && lab.laboratorio.toLowerCase().includes(texto)) ||
                (lab.porcentaje && lab.porcentaje.toString().toLowerCase().includes(texto))
            );
        }

        if (resultados.length === 0) {
            $('#tabla-resultados').addClass('d-none');
            $('#mensaje-vacio').removeClass('d-none');
        } else {
            $('#mensaje-vacio').addClass('d-none');
            $('#tabla-resultados').removeClass('d-none');

            resultados.forEach((lab, index) => {
                const porcentaje = parseFloat(lab.porcentaje) || 0;
                const operacion = lab.ch_tipo_operacion || 1;

                body.append(`
                    <tr>
                        <td>${lab.laboratorio || ''}</td>
                        <td>
                            <input type="number" class="form-control porcentaje-input"
                                value="${porcentaje}"
                                data-index="${index}"
                                step="0.01" min="0" max="100">
                        </td>
                        <td>
                             <select class="form-select operacion-select" data-index="${index}">
                                <option value="1" ${operacion == 1 ? 'selected' : ''}>+</option>
                                <option value="2" ${operacion == 2 ? 'selected' : ''}>-</option>
                            </select>
                        </td>
                    </tr>
                `);
            });
        }

        ocultarLoader();
    }


    $.ajax({
        url: RUTA_LISTA_LABORATORIOS,
        method: "GET",
        success: function (data) {
            cacheLaboratorios = data;
            filtrarYMostrar(data);
        },
        error: function () {
            ocultarLoader();
            alert('Error al consultar los laboratorios.');
        }
    });

});

// GUARDAR CONFIGURACION

$('#btnGuardar').on('click', function () {
    const configuraciones = [];

    $('#tabla-body tr').each(function () {
        const laboratorio = $(this).find('td').eq(0).text().trim();
        console.log(laboratorio);
        const porcentaje = parseFloat($(this).find('.porcentaje-input').val()) || 0;
        const operacion = $(this).find('.operacion-select').val();

        configuraciones.push({
            laboratorio: laboratorio,
            porcentaje: porcentaje,
            operacion: operacion
        });
    });

    mostrarLoader();

    $.ajax({
        url: RUTA_GUARDAR_CONFIGURACION, // Define esta ruta como constante en tu JS
        method: 'POST',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            configuraciones: configuraciones
        },
        success: function (response) {
            ocultarLoader();
            alert('Configuración guardada correctamente.');
            activateModal();
        },
        error: function () {
            ocultarLoader();
            alert('Error al guardar la configuración.');
            activateModal();
        }
    });
});

// FIN GUARDAR CONFIGURACION
// INICIO CHAT BOT LABORATORIOS


const inputBusqueda2 = document.getElementById('user-input');
const chatMessages2 = document.getElementById('chat-messages');
const btnEnviar2 = document.querySelector('button[onclick="enviarPregunta()"]');

let estadoActual2 = 'laboratorio';
let laboratorioSeleccionado2 = null;

let yaCargoLaboratorios = false;

inputBusqueda2.addEventListener('focus', function () {
    if (!yaCargoLaboratorios) {
        yaCargoLaboratorios = true;
        cargarLaboratorios2();
    }
});

let awesomplete2 = new Awesomplete(inputBusqueda2, { minChars: 1, maxItems: 10, autoFirst: true });

// Mostrar loader simple (puedes adaptar)
function mostrarLoader2() {
    mostrarLoader();
}

function ocultarLoader2() {
    ocultarLoader();
}

function cargarLaboratorios2() {
    mostrarLoader2();
    estadoActual2 = 'articulo';
    $.ajax({
        url: RUTA_LISTA_LABORATORIOS,
        method: "GET",
        success: function (data) {
            ocultarLoader2();

            const lista = data
                .filter(l => l.laboratorio && l.id_laboratorio)
                .map(l => ({ label: l.laboratorio, value: l.id_laboratorio }));

            awesomplete2.list = lista;

            const awesompleteList = document.querySelector('.awesomplete ul');
                if (awesompleteList) {
                    awesompleteList.style.maxHeight = '360px';
                    awesompleteList.style.overflowY = 'auto';
                }

        },
        error: function () {
            ocultarLoader2();
            alert('Error al cargar laboratorios');
        }
    });

}

function cargarArticulos2(laboratorioId) {
    mostrarLoader2();
    $.ajax({
        url: RUTA_LISTA_ARTICULOS,
        method: "GET",
        data: { laboratorio_id: laboratorioId },
        success: function (data) {
            ocultarLoader2();

            const lista = data
                .filter(a => a.articulo && a.id)
                .map(a => ({ label: a.articulo, value: a.id }));

            awesomplete2.list = lista;

            const awesompleteList = document.querySelector('.awesomplete ul');
                if (awesompleteList) {
                    awesompleteList.style.maxHeight = '360px';
                    awesompleteList.style.overflowY = 'auto';
                }

        },
        error: function () {
            ocultarLoader2();
            alert('Error al cargar artículos');
        }
    });
}

// Configurar Awesomplete para que muestre label pero guarde value
awesomplete2.data = function (item, input) {
    return item.label;
};

awesomplete2.filter = function (text, input) {
    return Awesomplete.FILTER_CONTAINS(text.label, input);
};

awesomplete2.item = function (item, input) {
    return Awesomplete.ITEM(item.label, input);
};

awesomplete2.replace = function (item) {
    this.input.value = item.label;
    this.input.dataset.idSeleccionado = item.value; // guardamos id seleccionado
};

function limpiarChat() {
    chatMessages2.innerHTML = `<div><strong>BeizaNetFlow:</strong> Hola 👋 ¿En qué puedo ayudarte?</div>`;
    laboratorioSeleccionado2 = null;
    estadoActual2 = 'laboratorio';
    inputBusqueda2.placeholder = 'Buscar laboratorio...';
    inputBusqueda2.value = '';
    cargarLaboratorios2();
}

function reiniciarProceso2() {
    laboratorioSeleccionado2 = null;
    estadoActual2 = 'laboratorio';
    inputBusqueda2.placeholder = 'Buscar laboratorio...';
    inputBusqueda2.value = '';
    chatMessages2.insertAdjacentHTML('beforeend', `<div><strong>BeizaNetFlow:</strong> Selección reiniciada. Por favor busca un laboratorio.</div>`);
    cargarLaboratorios2();
}

function enviarPregunta2() {
    if (estadoActual2 === 'laboratorio') {
        mostrarLoader2();
        $.ajax({
            url: RUTA_LISTA_LABORATORIOS,
            method: "GET",
            success: function (data) {
                ocultarLoader2();

                const lab = data.find(l => l.laboratorio.toLowerCase() === texto.toLowerCase() || (inputBusqueda2.dataset.idSeleccionado && inputBusqueda2.dataset.idSeleccionado == l.id_laboratorio));
                if (!lab) {
                    alert('Laboratorio no encontrado. Intenta de nuevo.');
                    return;
                }

                laboratorioSeleccionado2 = lab;
                chatMessages2.insertAdjacentHTML('beforeend', `<div><strong>Usuario:</strong> ${texto}</div>`);
                chatMessages2.insertAdjacentHTML('beforeend', `<div><strong>BeizaNetFlow:</strong> Laboratorio seleccionado: <em>${lab.laboratorio}</em>. Ahora ingresa el artículo.</div>`);
                chatMessages2.scrollTop = chatMessages2.scrollHeight;

                inputBusqueda2.value = '';
                inputBusqueda2.placeholder = 'Buscar artículo...';
                estadoActual2 = 'articulo';
                cargarArticulos2(laboratorioSeleccionado2.id_laboratorio);
            },
            error: function () {
                ocultarLoader2();
                alert('Error al validar laboratorio');
            }
        });
    } else if (estadoActual2 === 'articulo') {
        var texto = inputBusqueda2.value.trim();

        mostrarLoader2();
        $.ajax({
            url: RUTA_DATOS_ARTICULO_ALL,
            method: "GET",
            data: { laboratorio_id: texto },
            success: function (resp) {
                ocultarLoader2();

                if (resp && resp.length > 0) {
                    let tablaHTML = `
  <div style="overflow-x:auto;">
    <table class="table table-bordered mt-3" style="width:100%; table-layout:auto;">
      <thead class="table-primary">
        <tr>
          <th>Artículo</th>
          <th>Precio Lista</th>
          <th>Precio sin IGV PL1</th>
          <th>Precio Contado</th>
          <th>Precio sin IGV PL2</th>
          <th>Laboratorio</th>
          <th>Stock</th>
          <th>Costo Proveedor</th>
          <th>Costo Proveedor con IGV</th>
          <th>Adicional 1</th>
          <th>Adicional 2</th>
          <th>Precio mínimo</th>
        </tr>
      </thead>
      <tbody>`;

                    resp.forEach(item => {
                        tablaHTML += `
    <tr>
      <td>${item.articulo || ''}</td>
      <td>${item.precio_lista || ''}</td>
      <td>${item.prec_list_sin_igv_pl1 || ''}</td>
      <td>${item.precio_contado || ''}</td>
      <td>${item.prec_list_sin_IGV_pl2 || ''}</td>
      <td>${item.laboratorio || ''}</td>
      <td>${item.stock || ''}</td>
      <td>${item.costo_proveedor || ''}</td>
      <td>${item.costo_proveedor_con_igv || ''}</td>
      <td>${item.adicional1 || ''}</td>
      <td>${item.adicional2 || ''}</td>
      <td>${item.precio_final || ''}</td>
    </tr>`;
                    });

                    tablaHTML += `
      </tbody>
    </table>
  </div>`;
                    // Suponiendo que chatMessages2 es el div donde quieres mostrar
                    const chatMessages2 = document.getElementById('chat-messages');
                    chatMessages2.insertAdjacentHTML('beforeend', tablaHTML);
                } else {
                    alert("No hay datos para mostrar.");
                }
            },
            error: function () {
                ocultarLoader2();
                alert('Error al cargar laboratorios');
            }
        });




        inputBusqueda2.value = '';
        inputBusqueda2.placeholder = 'Buscar artículo...';
        estadoActual2 = 'datosArticulos';
        laboratorioId = texto;
        mostrarLoader2();
        $.ajax({
            url: RUTA_LISTA_ARTICULOS,
            method: "GET",
            data: { laboratorio_id: texto },
            success: function (data) {

                ocultarLoader2();

                const listaArticulos = data.map(a => ({ label: a.articulo, value: a.id }));

                awesomplete2.list = listaArticulos;



                const awesompleteList = document.querySelector('.awesomplete ul');
                if (awesompleteList) {
                    awesompleteList.style.maxHeight = '360px';
                    awesompleteList.style.overflowY = 'auto';
                }

                chatMessages2.insertAdjacentHTML('beforeend', `<div><strong>Usuario:</strong> ${texto}</div>`);
                chatMessages2.insertAdjacentHTML('beforeend', `<div><strong>BeizaNetFlow:</strong> Procesando artículo <em>${"artículo"}</em>...</div>`);
                chatMessages2.scrollTop = chatMessages2.scrollHeight;
            },
            error: function () {
                ocultarLoader2();
                alert('Error al validar artículo');
            }
        });
    }

    else if (estadoActual2 === 'datosArticulos') {
        var textoArticulo = inputBusqueda2.value.trim();
        alert(laboratorioId);
        alert(textoArticulo);
        mostrarLoader2();
        $.ajax({
            url: RUTA_DATOS_ARTICULO,
            method: 'GET',
            data: {
                laboratorio_id: laboratorioId,
                articulo_id: textoArticulo
            },
            success: function (resp) {
                ocultarLoader2();
                console.log(resp)
                if (resp && resp.length > 0) {
                    let tablaHTML = `
  <div style="overflow-x:auto;">
    <table class="table table-bordered mt-3" style="width:100%; table-layout:auto;">
      <thead class="table-primary">
        <tr>
          <th>Artículo</th>
          <th>Precio Lista</th>
          <th>Precio sin IGV PL1</th>
          <th>Precio Contado</th>
          <th>Precio sin IGV PL2</th>
          <th>Laboratorio</th>
          <th>Stock</th>
          <th>Costo Proveedor</th>
          <th>Costo Proveedor con IGV</th>
          <th>Adicional 1</th>
          <th>Adicional 2</th>
          <th>Precio mínimo</th>
        </tr>
      </thead>
      <tbody>`;

                    resp.forEach(item => {
                        tablaHTML += `
    <tr>
      <td>${item.articulo || ''}</td>
      <td>${item.precio_lista || ''}</td>
      <td>${item.prec_list_sin_igv_pl1 || ''}</td>
      <td>${item.precio_contado || ''}</td>
      <td>${item.prec_list_sin_IGV_pl2 || ''}</td>
      <td>${item.laboratorio || ''}</td>
      <td>${item.stock || ''}</td>
      <td>${item.costo_proveedor || ''}</td>
      <td>${item.costo_proveedor_con_igv || ''}</td>
      <td>${item.adicional1 || ''}</td>
      <td>${item.adicional2 || ''}</td>
      <td>${item.precio_final || ''}</td>
    </tr>`;
                    });

                    tablaHTML += `
      </tbody>
    </table>
  </div>`;
                    // Suponiendo que chatMessages2 es el div donde quieres mostrar
                    const chatMessages2 = document.getElementById('chat-messages');
                    chatMessages2.insertAdjacentHTML('beforeend', tablaHTML);
                } else {
                    alert("No hay datos para mostrar.");
                }

                chatMessages2.insertAdjacentHTML('beforeend', `<button id="btnReiniciar2" class="btn btn-warning mt-3">Reiniciar selección</button>`);
                chatMessages2.scrollTop = chatMessages2.scrollHeight;

                // Asignar evento para reiniciar proceso
                document.getElementById('btnReiniciar2').onclick = reiniciarProceso2;
            },
            error: function () {
                ocultarLoader2();
                alert('Error al procesar artículo');
            }
        });

    }
    inputBusqueda2.value = '';
    inputBusqueda2.dataset.idSeleccionado = ''; // limpiar id seleccionado
}

// Evento para botón enter en el input
inputBusqueda2.addEventListener('keydown', function (e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        enviarPregunta2();
    }
});

// Inicializar cargando laboratorios
cargarLaboratorios2();

// Botón para limpiar chat (puedes agregar en tu HTML y hacer referencia)
const btnLimpiarChat = document.createElement('button');
btnLimpiarChat.textContent = 'Limpiar Chat';
btnLimpiarChat.className = 'btn btn-secondary mt-3';
btnLimpiarChat.onclick = limpiarChat;
document.querySelector('.chat-card').appendChild(btnLimpiarChat);

// cargarLaboratorios2();

