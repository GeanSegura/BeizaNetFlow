const iptUsuario = document.getElementById('input-usuario');
const iptContrasena = document.getElementById('input-contrasena');
const iptBtnIngresar = document.getElementById('btn-ingresar');
const divMensajeValidacion = document.getElementById('div-mensaje-validacion');

iptUsuario.addEventListener('click', function () {
    divMensajeValidacion.hidden = true;
});

iptContrasena.addEventListener('click', function () {
    divMensajeValidacion.hidden = true;
});


document.getElementById('btn-ingresar').addEventListener('click', function (e) {
    e.preventDefault();
    $('#modalInicio').modal('show');

});

function seleccionarOpcion(opcion) {
    document.getElementById('opcionFuncionalidad').value = opcion;
    console.log(document.getElementById('opcionFuncionalidad').value)
    $('#modalInicio').modal('hide');
    document.getElementById('form-login').submit();
}
