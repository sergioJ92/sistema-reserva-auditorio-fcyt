
$('#boton-login').click(function () {
    var contrasenia = $('#id-contrasenia').val();
    var usuario = $('#id-usuario').val();
    if (contrasenia === '' || usuario === '') {
        mostrarMensaje('alert-danger', 'Debes llenar todos los datos');
    } else {
        var datos = {usuario:usuario,contrasenia:contrasenia};
        $.ajax({
            type: 'POST',
            dataType: 'json',
            data:datos,
            url: "./iniciar_sesion.php",
            success: function (data, textStatus, jqXHR) {
                if (data.exito) {
                    window.location.href = "../index.php";
                } else {
                    mostrarMensaje('alert-danger', 'El nombre de usuario o contraseña son incorrectos');
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert(errorThrown+jqXHR+textStatus);
            }
        }).always();
    }

});

function mostrarMensaje(tipo, mensaje) {
    $('#contenedor-msg').empty().append(crearAlerta(tipo, mensaje));
    $('html,body').animate({scrollTop: 80}, "fast");
}

