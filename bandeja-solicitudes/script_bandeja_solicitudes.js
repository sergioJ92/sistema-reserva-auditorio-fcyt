var datos = [];

$(document).ready(function () {
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: "./obtener_solicitud_reserva.php",
        success: function (data) {
            datos = data;
            llenarDatos(data);
            $(".fila-solicitud").click(function () {
                llenarModal(this.id);
                $('#myModal').modal();
            });
            $(".boton-responder").click(function () {
                llenarModal(this.id);
                setCookie('id_solicitud_reserva', datos[this.id]['id_solicitud_reserva']);
                setCookie('institucion', $('#modalInstitucion').text());
                setCookie('responsable', $('#modalResponsable').text());
                setCookie('correo', $('#modalCorreo').text());
                setCookie('telefono', $('#modalTelefono').text());
                setCookie('fecha', $('#modalFecha').text());
                setCookie('hora_inicio', $('#modalHoraInicio').text());
                setCookie('hora_fin', $('#modalHoraFin').text());
                setCookie('evento', $('#modalEvento').text());
                setCookie('descripcion', $('#modalDescripccion').text());
            });

        },
        error: function (jqXHR) {
            alert('error cargando los datos');
        }
    });
});

function llenarModal(idLista) {
    $('#modalInstitucion').text("");
    $('#modalResponsable').text("");
    $('#modalCorreo').text("");
    $('#modalTelefono').text("");
    $('#modalFecha').text("");
    $('#modalHoraInicio').text("");
    $('#modalHoraFin').text("");
    $('#modalEvento').text("");
    $('#modalDescripccion').text("");


    $('#modalInstitucion').text(datos[idLista]['institucion']);
    $('#modalResponsable').text(datos[idLista]['responsable']);
    $('#modalCorreo').text(datos[idLista]['correo']);
    $('#modalTelefono').text(datos[idLista]['telefono']);
    $('#modalFecha').text(datos[idLista]['fecha']);
    $('#modalHoraInicio').text(datos[idLista]['hora_inicio']);
    $('#modalHoraFin').text(datos[idLista]['hora_fin']);
    $('#modalEvento').text(datos[idLista]['evento']);
    $('#modalDescripccion').text(datos[idLista]['descripcion']);
}

function llenarDatos(valores) {
    if (valores.length === 0) {
        var fila_vacia = '<li class="list-group-item empty" >No tienes Mensajes</li>';
        $('#notificaciones').append(fila_vacia);
    } else {
        for (var fila = 0; fila < valores.length; fila++) {
            var leido = 'antiguo';
            if (valores[fila]['leido'] === '0') {
                leido = 'nuevo';
            }
            var filaContenido = '<li class="list-group-item lista-foco ' + leido + '" >';

            if (fechaValida(valores[fila])) {
                filaContenido += '<div class"resto-lista"><a class="btn btn-default resto-lista boton-responder" type="button" id=' + fila + ' href="./responder/">Responder</a></div>';
            } else {
                filaContenido += '<div class"resto-lista"><a class="btn btn-default resto-lista" type="button" id=' + fila + ' href="#" disabled>Responder</a></div>';
            }

            filaContenido += '<div class="no-select fila-solicitud " id=' + fila + '>';

            filaContenido += '<div ><span>Solicitud de Reserva</span></div>';

            filaContenido += '<div ><span>De: </span>';
            filaContenido += valores[fila]['responsable'];
            filaContenido += '</div>';

            filaContenido += '<div ><span>Asunto: </span>';
            filaContenido += valores[fila]['evento'];
            filaContenido += '</div>';

            filaContenido += '<div ><span>Fecha: </span>';
            filaContenido += valores[fila]['fecha'];
            filaContenido += '</div>';

            filaContenido += '</div>';

            filaContenido += '</li>';
            $('#notificaciones').append(filaContenido);
        }
    }
}

function fechaValida(fechaReserva) {
    var fecha = parseFechaHora(fechaReserva['fecha'] + ' ' + fechaReserva['hora_inicio'] + ':00');
    return fecha >= new Date();
}