
$('#boton-login').click(function () {
    var contrasenia = $('#id-contrasenia').val();
    var usuario = $('#id-usuario').val();
    var captcha = $('#id-captcha').val();
    var captcha2 = $('#id-captcha-2').val();
    if (contrasenia === '' || usuario === '' || captcha === '' || captcha2 === '') {
        mostrarMensaje('alert-danger', 'Debes llenar todos los datos');
    } else {
        if (captcha == captcha2) {
            var datos = {usuario:usuario,contrasenia:contrasenia};
            $.ajax({
                type: 'POST',
                dataType: 'json',
                data:datos,
                url: "./iniciar_sesion.php",
                success: function (data, textStatus, jqXHR) {
                    console.log(data);
                    if (data.exito) {
                        window.location.href = "../index.php";
                    } else {
                        mostrarMensaje('alert-danger', data['mensaje']);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                    //alert(errorThrown+jqXHR+textStatus);
                }
            }).always();
        }
        else {
            mostrarMensaje('alert-danger', 'EL codigo de Captcha NO coinciden');
        }
    }

});

function mostrarMensaje(tipo, mensaje) {
    $('#contenedor-msg').empty().append(crearAlerta(tipo, mensaje));
    $('html,body').animate({scrollTop: 80}, "fast");
}
$(function(){
            $("#recargar").click(function(){
                document.location.reload();
                return false;
            });
        });