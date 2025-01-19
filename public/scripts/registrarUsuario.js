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
    var usuario = document.getElementById("form3Example1c").value;
    var correo = document.getElementById("form3Example3c").value;
    var contrasena = document.getElementById("form3Example4c").value;
    var contrasenaRepetida = document.getElementById("form3Example4cd").value;
    var aceptarTerminos = document.getElementById("form2Example3c").checked;
    var validacion = 0

    // Validar que los campos no estén vacíos
    if (!usuario || !correo || !contrasena || !contrasenaRepetida) {
        validacion = 1
        alert("Por favor complete todos los campos.");
    }

    // Validar que las contraseñas coincidan
    if (contrasena !== contrasenaRepetida) {
        validacion = 1
        alert("Las contraseñas no coinciden.");
    }

    // Validar formato de correo (Gmail o Hotmail)
    var correoRegex = /^(?:[A-Za-z0-9._%+-]+@(gmail\.com|hotmail\.com))$/;
    if (!correoRegex.test(correo)) {
        validacion = 1
        alert("Por favor ingrese un correo válido (gmail o hotmail).");
    }

    // Validar contraseña (mínimo una mayúscula, una minúscula, un número)
    var contrasenaRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/;
    if (!contrasenaRegex.test(contrasena)) {
        validacion = 1
        alert("La contraseña debe contener al menos una mayúscula, una minúscula, un número y 8 caracteres.");
    }

    // Validar que los términos estén aceptados
    if (!aceptarTerminos) {
        validacion = 1
        alert("Debe aceptar los términos y condiciones.");
    }

    if (validacion == 0) {
    // Crear los datos a enviar
    // Enviar los datos al backend con AJAX
    axios.post('/RegistrarUsuario', {
        usuario_p: usuario,
        correo_p: correo,
        contrasena_p: contrasena
    })
    .then(response => {
        alert("Se registro correctamenet") // Usuario registrado correctamente
    })
    .catch(error => {
        console.error(error.response.data.message); // Error al registrar el usuario
    });
    }
});