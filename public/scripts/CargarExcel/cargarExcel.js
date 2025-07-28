function toggleModal() {
    const modal = document.getElementById('modal-config');
    modal.style.display = modal.style.display === 'none' || modal.style.display === '' ? 'block' : 'none';
}

function guardarConfiguracion() {
    alert("Configuración guardada");
    toggleModal();
}

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

// FUNCION SUBIR EXCEL
document.getElementById("excel-upload").addEventListener("change", function () {
    const form = document.getElementById("formExcel");
    const formData = new FormData(form);

    fetch("/subir-excel", {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
        },
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            alert("Archivo procesado correctamente");
            console.log(data);
            // Aquí puedes actualizar tu tabla, etc.
        })
        .catch(error => {
            alert("Ocurrió un error al subir el archivo.");
            console.error(error);
        });
});
