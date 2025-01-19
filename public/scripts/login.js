const iptUsuario = document.getElementById('input-usuario');
const iptContrasena = document.getElementById('input-contrasena');
const iptBtnIngresar = document.getElementById('btn-ingresar');
const divMensajeValidacion = document.getElementById('div-mensaje-validacion');
    
iptUsuario.addEventListener('click', function() {
    divMensajeValidacion.hidden = true;
});

iptContrasena.addEventListener('click', function() {
    divMensajeValidacion.hidden = true;
});

iptBtnIngresar.addEventListener('click', function() {
    divMensajeValidacion.hidden = true;
});