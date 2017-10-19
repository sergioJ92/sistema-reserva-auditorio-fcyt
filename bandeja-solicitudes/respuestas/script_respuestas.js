var datos = [];

$(document).ready(function () {
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: "./obtener_respuestas.php",
        success: function (data) {
            datos = data;
            llenarDatos(data);
            $(".fila-respuesta").click(function () {
                llenarModal(this.id);
                $('#modalVerRespuesta').modal();
            });
        },
        error: function (jqXHR) {
            alert('error cargando los datos');
        }
    });
    $('#botonModalMensaje').click(function () {
        $('#modalMensaje').toggle('slow');
    });
});

function llenarModal(valor) {
    $('#modalResponsable').text("");
    $('#modalCorreo').text("");
    $('#modalTelefono').text("");
    $('#modalHoraInicio').text("");
    $('#modalHoraFin').text("");
    $('#modalEvento').text("");
    $('#modalMensaje').text("");

    $('#modalResponsable').text(datos[valor]['responsable']);
    $('#modalCorreo').text(datos[valor]['correo']);
    $('#modalTelefono').text(datos[valor]['telefono']);
    $('#modalHoraInicio').text(datos[valor]['hora_inicio']);
    $('#modalHoraFin').text(datos[valor]['hora_fin']);
    $('#modalEvento').text(datos[valor]['evento']);
    $('#modalMensaje').append(datos[valor]['mensaje']);
    $('#modalMensaje').hide();
}

function llenarDatos(valores) {
    
    if (valores.length === 0) {
        var filaVacia = '<li class="list-group-item empty" >No tienes Mensajes</li>';
        $('#respuestas').append(filaVacia);
    } else {
        for (var fila = 0; fila < valores.length; fila++) {
            var leido = 'antiguo';
            if (valores[fila]['leido'] === '0') {
                leido = 'nuevo';
            }
            var filaContenido='<li class="list-group-item lista-foco ' + leido + '" >';

            filaContenido +=   '<div class="row contenedor-informacion no-select fila-respuesta" id=' + fila + '>';

            filaContenido +=       '<div class="col-md-3">';
                            
            if (valores[fila]['aceptado'] === '1') {
                filaContenido += 'Aceptado';
            } else {
                filaContenido += 'Rechazado';
            }

            filaContenido +=       '</div>';

            filaContenido +=       '<div class="col-md-3">\n\
                                        <span>Para:</span>';
            filaContenido += valores[fila]['responsable'];
            filaContenido +=       '</div>';

            filaContenido +=       '<div class="col-md-3">\n\
                                        <span>Evento:</span>';
            filaContenido += valores[fila]['evento'];
            filaContenido +=       '</div>';



            filaContenido +=   '</div>';

            filaContenido +=   '</li>';
            $('#respuestas').append(filaContenido);
        }
    }
}