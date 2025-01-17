  // Mostrar/Ocultar contraseña
  document.getElementById("togglePassword").addEventListener("click", function () {
    var passwordField = document.getElementById("form3Example4c");
    var passwordFieldRepeat = document.getElementById("form3Example4cd");
    var type = passwordField.type === "password" ? "text" : "password";
    passwordField.type = type;
    passwordFieldRepeat.type = type;
});

document.getElementById("registerBtn").addEventListener("click", function (e) {
    e.preventDefault();  // Previene que el formulario se envíe automáticamente

    // Comprobamos si el proceso ya está en ejecución para evitar múltiples clics
    if (this.disabled) {
        return; // Si el botón está deshabilitado, no hacemos nada
    }

    var usuario = document.getElementById("form3Example1c").value;
    var correo = document.getElementById("form3Example3c").value;
    var contrasena = document.getElementById("form3Example4c").value;
    var contrasenaRepetida = document.getElementById("form3Example4cd").value;
    var aceptarTerminos = document.getElementById("form2Example3c").checked;

    // Validar que los campos no estén vacíos
    if (!usuario || !correo || !contrasena || !contrasenaRepetida) {
        alert("Por favor complete todos los campos.");
        return;
    }

    // Validar que las contraseñas coincidan
    if (contrasena !== contrasenaRepetida) {
        alert("Las contraseñas no coinciden.");
        return;
    }

    // Validar formato de correo (Gmail o Hotmail)
    var correoRegex = /^(?:[A-Za-z0-9._%+-]+@(gmail\.com|hotmail\.com))$/;
    if (!correoRegex.test(correo)) {
        alert("Por favor ingrese un correo válido (gmail o hotmail).");
        return;
    }

    // Validar contraseña (mínimo una mayúscula, una minúscula, un número)
    var contrasenaRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/;
    if (!contrasenaRegex.test(contrasena)) {
        alert("La contraseña debe contener al menos una mayúscula, una minúscula, un número y 8 caracteres.");
        return;
    }

    // Validar que los términos estén aceptados
    if (!aceptarTerminos) {
        alert("Debe aceptar los términos y condiciones.");
        return;
    }

    // Desactivar el botón al inicio para prevenir múltiples clics
    var registerBtn = document.getElementById("registerBtn");
    registerBtn.disabled = true;

    if (!usuario && !correo && !contrasena ) {
    // Crear los datos a enviar
    var formData = {
        usuario: usuario,
        correo: correo,
        contrasena: contrasena
    };

    // Enviar los datos al backend con AJAX
    axios.post('/registrar-usuario', formData)
        .then(function (response) {
            if (response.data.success) {
                alert("Usuario registrado correctamente");
            } else {
                alert("Error al registrar el usuario.");
            }
        })
        .catch(function (error) {
            console.error('Error:', error);
            alert("Hubo un problema al registrar al usuario.");
        })
        .finally(function() {
            // Asegurarse de que el botón se reactiva después de la petición, por si hubo un error
            registerBtn.disabled = false;
        });
    }
});