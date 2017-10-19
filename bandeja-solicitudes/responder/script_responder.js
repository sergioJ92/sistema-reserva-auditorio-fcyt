var institucion = getCookie('institucion');
var responsable = getCookie('responsable');
var correo = getCookie('correo');
var telefono = getCookie('telefono');
var fecha = getCookie('fecha');
var horaInicio = getCookie('hora_inicio');
var horaFin = getCookie('hora_fin');
var evento = getCookie('evento');
var descripcion = getCookie('descripcion');
var idSolicitudReserva = getCookie('id_solicitud_reserva');
var conflictoAcademicas = false;
var conflictoSolicitadas = false;
var listaConflictosAcademicas = [];
var listaConflictosSolicitadas = [];

$(document).ready(function () {
    if (institucion === 'null' || responsable === 'null' || correo === 'null' ||
            telefono === 'null' || fecha === 'null' || horaInicio === 'null' ||
            horaFin === 'null' || evento === 'null' || idSolicitudReserva === 'null') {
        redirigir();
    }
    $('#institucion').html(" " + institucion);
    $('#responsable').html(" " + responsable);
    $('#fecha-solicitud').html(fecha);
    $('#hora_inicio').html(horaInicio);
    $('#hora_fin').html(horaFin);
    $('#evento').html(evento);
    $('#fecha').text(fecha);

    $.ajax({
        type: 'POST',
        dataType: 'json',
        data: {fecha: fecha},
        url: "./obtener_eventos_por_fecha.php",
        success: function (data) {
            llenarEventosAcademicosFecha(data[0]);
            llenarEventosSolicitadosFecha(data[1]);
            llenarMensajeDeEventosVacios(data[0],data[1]);
            var datos = {id_solicitud_reserva: idSolicitudReserva,
                responsable: responsable,
                fecha: fecha,
                hora_inicio: horaInicio,
                hora_fin: horaFin,
                evento: evento,
                descripcion: descripcion,
                institucion: institucion
            };
            listaConflictosAcademicas = hayConflictos(datos, data[0],true);
            listaConflictosSolicitadas = hayConflictos(datos, data[1],false);
        },
        error: function () {
            alert('Error al realizar la busqueda de eventos para la fecha ' + fecha);
        }
    });


    $('#nombre-representante').keyup(function () {
        var nombreRepresentantePresionando = $(this).val();
        $('#representante-univ').text(nombreRepresentantePresionando);
        if (nombreRepresentantePresionando === '') {
            $('#representante-univ').text('--> Nombre Representante');
        }
    });

    $('#btn-enviar-mensaje').click(function () {
        var contenidoMensaje = crearContenido();
        if (listaConflictosAcademicas.length === 0) {
            listaConflictosAcademicas = "vacio";
        }
        if (listaConflictosSolicitadas.length === 0) {
            listaConflictosSolicitadas = "vacio";
        }
        var envio = {correo: correo,
            content: contenidoMensaje,
            id_solicitud_reserva: idSolicitudReserva,
            aceptado_rechazado: $('#aceptado-rechazado').val(),
            representante: $('#nombre-representante').val(),
            lista_academicas: listaConflictosAcademicas,
            lista_solicitadas: listaConflictosSolicitadas,
            cargo_representante: $('#cargo-representante').val(),
            fecha: fecha,
            hora_inicio: horaInicio,
            hora_fin: horaFin,
            evento: evento,
            responsable: responsable,
            descripcion: descripcion,
            institucion: institucion
        };
        ajaxPost("./send_mail.php", envio, function (data) {
            $('#contenedor-msg').empty();
            if (data.exito) {
                alert('El mensaje fue procesado para su envio');
                redirigir();
            } else {
                if (data.error) {
                    mostrarMensaje('alert-danger', 'No fue posible enviar el mensaje ' + data.error);
                } else {
                    mostrarMensaje('alert-danger', data+': No hay conexión a internet.');
                }
            }
        }
        );
    });
    
    $('#enviar-mensaje').click(function () {
        $('#body-modal').empty();
        if ($('#nombre-representante').val() === "" || $('#cargo-representante').val() === "") {
            mostrarMensaje('alert-danger', 'Debes llenar todos los datos');
        } else {
            var reservaAcetadoRechazado = $('#aceptado-rechazado').val();
            if (reservaAcetadoRechazado === 'ACEPTADO') {
                reservaAcetadoRechazado = 'ACEPTANDO';
                $('#titulo-modal').css({"background-color": "#337ab7", "color": "white"});
                $('#btn-enviar-mensaje').css({"background-color": "#337ab7", "color": "white"});
                if (conflictoSolicitadas) {
                    var add = '<div id="cuerpo-modal" class="modal-body"><div class="form-group row"><div id="modal-mensaje" class="col-md-12 text-center">Existen '+listaConflictosSolicitadas.length+' Conflictos con Reservas Solicitadas <br>No se puede ACEPTAR si el periodo ya fue ocupado por otra RESERVA SOLICITADA<br></div></div></div>';
                    $('#body-modal').append(add);
                    $('#btn-enviar-mensaje').attr('disabled',conflictoSolicitadas);
                } else {
                    if(conflictoAcademicas){
                        var add = '<div id="cuerpo-modal" class="modal-body"><div class="form-group row"><div id="modal-mensaje" class="col-md-12 text-center">Existen '+listaConflictosAcademicas.length+' Conflictos con otras Reservas <br>Las reservas en conflicto seran ELIMINADAS<br> <b>Si deseas continuar presiona ENVIAR</b></div></div></div>';
                        $('#body-modal').append(add);
                    }
                }
            } else {
                reservaAcetadoRechazado = 'RECHAZANDO';
                $('#body-modal').empty();
                $('#titulo-modal').css({"background-color": "#d9534f", "color": "white"});
                $('#btn-enviar-mensaje').css({"background-color": "#d9534f", "color": "white"});
                
            }
            $('#descision-reserva').text(reservaAcetadoRechazado);
            $('#myModal').modal();
        }
    });
});

function redirigir() {
    window.location.replace("../index.php");
}

function hayConflictos(datosCartaActual, listaEventos, tipoAcademicoSolicitado){
    var res=[];
    for (var i = 0; i < listaEventos.length; i++) {
        if(esConflicto(listaEventos[i],datosCartaActual)){
            res.push(listaEventos[i]);
            if(tipoAcademicoSolicitado){
                conflictoAcademicas=true;
            }else{
                conflictoSolicitadas=true;
            }
            
        }
    }
    return res;
}

function esConflicto(eventoBasico, listaEventos){
    var ini1 = getMinutos(eventoBasico['hora_inicio']);
    var fin1 = getMinutos(eventoBasico['hora_fin']);
    var ini2 = getMinutos(listaEventos['hora_inicio']); 
    var fin2 = getMinutos(listaEventos['hora_fin']); 
    
    if ((ini1> ini2 && ini1<fin2)||(fin1>ini2 && fin1<fin2)||(ini1===ini2 && fin1 ===fin2)
            ||(ini1<=ini2&&fin1>=fin2)) {
        return true;
    }
    return false;
}

function mostrarMensaje(tipo, mensaje) {

    $('#contenedor-msg').empty().append(crearAlerta(tipo, mensaje));
    $('html,body').animate({scrollTop: 80}, "fast");
}

function crearContenido() {
    var respuesta = '<div>';
    respuesta += $('#nombre-representante').val() + '<br>';
    respuesta +=$('#cargo-representante').val() + '<br>';
    respuesta +=  $('#provincia').text() + '<br>';
    respuesta +=  $('#departamento').text() + '<br>';
    respuesta +=   '<br>';
    respuesta += '<p>' + $('#fecha-mensaje').text() + '</p>';
    respuesta +=   '<br>';
    respuesta += $('#primer-bloque').text() + '<br>';
    respuesta +=  $('#segundo-bloque').text() + '<br>';
    respuesta +=   '<br>';
    respuesta += '<p>' + $('#tercer-bloque').text() + '</p>';
    respuesta += '<p>' + $('#cuarto-bloque').text() + '</p>';
    respuesta += '<p>' + $('#aceptado-rechazado').val() + '</p>';
    respuesta += '<p>' + $('#sexto-bloque').text() + '</p>';
    respuesta +=   '<br>';
    respuesta += '<p>' + $('#septimo-bloque').text() + '</p>';
    respuesta +=   '<br>';
    respuesta +=   '<br>';
    respuesta += $('#octavo-bloque').text() + '<br>';
    respuesta += $('#noveno-bloque').text() ;

    respuesta += '</div>';
    return respuesta;
}

function llenarEventosAcademicosFecha (datosAcademicos){
    
    for (var fila = 0; fila < datosAcademicos.length ; fila++) {
        var filaContenido = '<li class="list-group-item " >';

            filaContenido +=   '<div class="no-select">';

            filaContenido +=       '<div ><span><b>Asunto:</b> </span>';
            filaContenido +=               datosAcademicos[fila]['asunto'];
            filaContenido +=       '</div>';
            filaContenido +=       '<div ><span><b>Hora Inicio:</b> </span>';
            filaContenido +=               datosAcademicos[fila]['hora_inicio'];
            filaContenido +=       '</div>';

            filaContenido +=       '<div ><span><b>Hora Fin:</b> </span>';
            filaContenido +=               datosAcademicos[fila]['hora_fin'];
            filaContenido +=       '</div>';

            filaContenido +=   '</div>';

            filaContenido+= '</li>';

        $('#eventos-fecha').append(filaContenido);
    }
}

function llenarEventosSolicitadosFecha (datosSolicitados){
    if (datosSolicitados.length!==0) {
        for (var fila = 0; fila < datosSolicitados.length ; fila++) {
            var filaContenido = '<li class="list-group-item " >';
                
                filaContenido +=   '<div class="no-select">';
                
                filaContenido +=       '<div ><span><b>Evento:</b> </span>';
                filaContenido +=               datosSolicitados[fila]['evento'];
                filaContenido +=       '</div>';
                
                filaContenido +=       '<div ><span><b>Hora Inicio:</b> </span>';
                filaContenido +=               datosSolicitados[fila]['hora_inicio'];
                filaContenido +=       '</div>';

                filaContenido +=       '<div ><span><b>Hora Fin:</b> </span>';
                filaContenido +=               datosSolicitados[fila]['hora_fin'];
                filaContenido +=       '</div>';

                filaContenido +=   '</div>';

                filaContenido+= '</li>';
                
            $('#eventos-fecha').append(filaContenido);
        }
    }
}
function llenarMensajeDeEventosVacios (listaAcademica,listaSolicitada){
    if (listaAcademica.length === 0 && listaSolicitada.length ===0) {
        var text = '<li class="list-group-item empty" >Aun no existen reservas para esta fecha</li>';
        $('#eventos-fecha').append(text);
    }
}