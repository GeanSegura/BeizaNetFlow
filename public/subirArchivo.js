
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

