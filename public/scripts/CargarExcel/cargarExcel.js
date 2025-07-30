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
