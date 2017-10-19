
function inicializarHoraDeCronograma(id,hora) {
    
    $('#' + id).parent().datetimepicker({
        format: 'LT',
        locale: 'es',
        defaultDate: '2017-01-01 '+hora
    });

    $('#' + id).click(function () {
        $(this).parent().data('DateTimePicker').toggle();
    });
}

function inicializarFechaDeCronograma(id, horas=true) {
    
    $('#' + id).parent().datetimepicker({
        locale: 'es',
        format: 'DD-MM-YYYY' + (horas? ' HH:mm' : ''),
        showClose: true
    });
    
    $('#' + id).click(function () {
        $(this).parent().data('DateTimePicker').toggle();
    });
}

inicializarFechaDeCronograma('fechahorainicio');
inicializarFechaDeCronograma('fechahorafin');
inicializarFechaDeCronograma('fechaactivacion', false);

inicializarHoraDeCronograma('hora_fin_jornada','21:45:00');
inicializarHoraDeCronograma('hora_inicio_jornada','06:45:00');
inicializarHoraDeCronograma('hora_fin_sabado','12:45:00');

$('#selecGestion').change(function () {
    if ($('#selecAnio').val() !== null && $('#selecGestion').val() !== null) {
        obtenerCronograma()
        var mensaje = $('#selecGestion').val() + " - " + $('#selecAnio').val();
        $('#cronograma-actual').text(mensaje);
        
        setCookie('anio', $('#selecAnio').val());
        setCookie('gestion', $('#selecGestion').val());
    }
});

$('#selecAnio').change(function () {
    if ($('#selecAnio').val() !== null && $('#selecGestion').val() !== null) {
        obtenerCronograma();
        var mensaje = $('#selecGestion').val() + " - " + $('#selecAnio').val();
        $('#cronograma-actual').text(mensaje);
        
        setCookie('anio', $('#selecAnio').val());
        setCookie('gestion', $('#selecGestion').val());
    }
});

function modificarFecha(id, fechaCadena) {
    
    $('#' + id)
            .parent()
            .data('DateTimePicker')
            .date(parseFechaHora(fechaCadena));
}

function obtenerCronograma() {

    $.ajax({
        type: "POST",
        url: "obtener_cronograma.php",
        dataType: 'json',
        data: {anio: $('#selecAnio').val(), gestion: $('#selecGestion').val()},
        success: function (data) {

            if (data !== null) {
                
                $('#contenedor-msg').empty();

                modificarFecha('fechahorainicio', data['fecha_hora_inicio']);
                modificarFecha('fechahorafin', data['fecha_hora_fin']);
                modificarFecha('fechaactivacion', data['fecha_activacion'] + ' 00:00:00');

                var list = data['duracion_periodo'].split(':');
                var hora = list[0];
                var minuto = list[1];

                $('#periodo_horas').val(hora);
                $('#periodo_minutos').val(minuto);
                var fechaStub = data['fecha_activacion'] + ' ';
                modificarFecha('hora_inicio_jornada', fechaStub + data['hora_inicio_jornada']);
                modificarFecha('hora_fin_jornada', fechaStub + data['hora_fin_jornada']);
                modificarFecha('hora_fin_sabado', fechaStub + data['hora_fin_sabado']);

                mostrarMensajeCronograma('alert-success', 'Se cargaron los datos del cronograma');
                modificarHabilitadoBotonesCronograma(true);
                
                var fechaActivacion = obtenerFecha('fechaactivacion');
                modificarHabilitadoCamposCronograma(new Date() < fechaActivacion);
                $('#btn-eliminar').attr('disabled', fechaActivacion < new Date());
                $('#guardar').attr('disabled', fechaActivacion < new Date());

            } else {
                $('#fechahorainicio').parent().data('DateTimePicker').date(null);
                $('#fechahorafin').parent().data('DateTimePicker').date(null);
                $('#fechaactivacion').parent().data('DateTimePicker').date(null);

                $('#periodo_horas').val('1');
                $('#periodo_minutos').val('30');
                var fechaStub = '2017-01-01 ';
                modificarFecha('hora_inicio_jornada', fechaStub + '06:45:00');
                modificarFecha('hora_fin_jornada', fechaStub + '21:45:00');
                modificarFecha('hora_fin_sabado', fechaStub + '12:45:00');
                mostrarMensajeCronograma('alert-danger', 'Este cronograma aun no existe');
                modificarHabilitadoBotonesCronograma(false);
                modificarHabilitadoCamposCronograma(true);
                
                $('#guardar').attr('disabled', false);
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {

            mostrarMensaje('alert-danger', textStatus);
        }
    }).always(function () {

    });
}

function obtenerFecha(idInput) {
    
    return $("#" + idInput).parent().data('DateTimePicker').date();
}

$('#guardar').click(function () {
    if ($('#selecAnio').val() !== null && $('#selecGestion').val() !== null) {
        debugger;
        var fechaInicial = formatearFecha(obtenerFecha('fechahorainicio'));
        var fechaFin = formatearFecha(obtenerFecha('fechahorafin'));
        var fechaActivacionCrudo = obtenerFecha('fechaactivacion');
        var fechaActivacion =  formatearSoloFecha(obtenerFecha('fechaactivacion'));

        var datos = {
            anio: $('#selecAnio').val(),
            gestion: $('#selecGestion').val(),
            fecha_inicio: fechaInicial,
            fecha_fin: fechaFin,
            fecha_activacion: fechaActivacion,
            periodo_horas: $('#periodo_horas').val(),
            periodo_minutos: $('#periodo_minutos').val(),
            hora_inicio_jornada: $('#hora_inicio_jornada').val(),
            hora_fin_jornada: $('#hora_fin_jornada').val(),
            hora_fin_sabado: $('#hora_fin_sabado').val()
        };
        ajaxPost('guardar_cronograma.php', datos, function (resputesta) {

            if (resputesta.exito) {
                mostrarMensaje('alert-success', 'Se guardo con exito');
                mostrarMensajeCronograma('alert-success', 'Se cargaron los datos del cronograma');
                modificarHabilitadoBotonesCronograma(true);
                modificarHabilitadoCamposCronograma(new Date() < fechaActivacionCrudo);
                $('#btn-eliminar').attr('disabled', fechaActivacionCrudo < new Date());
                $('#guardar').attr('disabled', fechaActivacionCrudo < new Date());
            } else {
                mostrarMensaje('alert-danger', resputesta.error);
            }
        });
    }
    else {
        mostrarMensaje('alert-danger', 'Debe seleccionar un Año y Gestión');
    }
});

function invertirFecha(value) {
    var base = value.split("-");
    var fecha = base[2].concat("-", base[1], "-", base[0]);
    return fecha;
}

function irActualizarContenido() {
    
    anadirCookie();
    window.location.assign('../contenido');
}

function anadirCookie() {

    setCookie('anio', $('#selecAnio').val());
    setCookie('gestion', $('#selecGestion').val());
}


function mostrarMensaje(tipo, mensaje) {
    
    $('#contenedor-msg').empty().append(crearAlerta(tipo, mensaje));
    $('html,body').animate({scrollTop: 80}, "fast");
}

function mostrarMensajeCronograma(tipo, mensaje) {
    
    $('#contenedor-msg-cronograma').empty().append(crearAlerta(tipo, mensaje));
}

function eliminarCronograma() {

    if ($('#selecAnio').val() !== null && $('#selecGestion').val() !== null) {
        $('#modalEliminar').modal();
        $('#boton-eliminar-si').click(function () {
            $.ajax({
                type: "POST",
                url: "eliminar_cronograma.php",
                data: {anio: $('#selecAnio').val(), gestion: $('#selecGestion').val()},
                success: function (respuesta, textStatus, jqXHR) {

                    if (respuesta.exito) {
                        mostrarMensaje('alert-success', 'Se eliminó con exito');
                        $('#fechahorainicio').parent().data('DateTimePicker').date(null);
                        $('#fechahorafin').parent().data('DateTimePicker').date(null);
                        $('#fechaactivacion').parent().data('DateTimePicker').date(null);
                        mostrarMensajeCronograma('alert-danger', 'Este cronograma aun no existe');
                        modificarHabilitadoBotonesCronograma(false);
                        modificarHabilitadoCamposCronograma(true);
                        $('#guardar').attr('disabled', false);
                    } else {
                        mostrarMensaje('alert-danger', respuesta.error);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {

                    mostrarMensaje('alert-danger', textStatus);
                }
            }).always(function () {

            });

        });
    } else {
        mostrarMensaje('alert-danger', "Debe seleccionar un cronograma para poder eliminarlo");
    }
}

function modificarHabilitadoCamposCronograma(habilitado) {
    
    $('input').attr('readonly', !habilitado);
}

function modificarHabilitadoBotonesCronograma(habilitado) {
    
    $('#btn-eliminar').attr('disabled', !habilitado);
    $('#btn-actualizar-contenido').attr('disabled', !habilitado);
}

$(document).ready(function () {

    mostrarMensajeCronograma('alert-info', 'Seleccione un año y gestión');
    modificarHabilitadoBotonesCronograma(false);
    modificarHabilitadoCamposCronograma(true);
});