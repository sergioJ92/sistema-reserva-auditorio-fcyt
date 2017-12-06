
$(document).ready(function() {
    
    var valor = $('#selAnioGestion').val();
    if (valor !== 'null') {
        var anio = parseInt(valor.split('-')[0]);
        var gestion = parseInt(valor.split('-')[1]);
        cargarCalendario(anio, gestion);
    }
});

var auditorio = '';

$('#selAnioGestion').attr('disabled', 'disabled');

$('#selAuditorio').change(function () {
    var _auditorio = $(this).val();
    console.log(_auditorio);
    auditorio = _auditorio;
    $("#selAnioGestion").removeAttr("disabled");
});

function crearCalendario(
        anio, gestion, fechaHoraInicio, fechaHoraFin, 
        horaInicioJornada, horaFinJornada, duracionPeriodo, 
        horaFinSabado, utiles) {
    
    var cronograma = {
        anio: anio, 
        gestion: gestion, 
        inicio: fechaHoraInicio, 
        fin: fechaHoraFin, 
        configuracion: {
            horaInicioJornada: horaInicioJornada, 
            horaFinJornada: horaFinJornada, 
            duracionPeriodo: duracionPeriodo, 
            horaFinSabado: horaFinSabado
        }
    };
    return new Calendario(cronograma, utiles);
}

function cargarCalendario(anio, gestion, id_ambiente) {
    
    cargarDatosCronograma(anio, gestion, function(cronograma) {
        
        if (cronograma !== null) {
            cargarUtilesParaReservar(function (utiles) {
                                        var calendario = crearCalendario(anio, gestion, 
                                            cronograma['fecha_hora_inicio'], cronograma['fecha_hora_fin'],
                                            cronograma['hora_inicio_jornada'], cronograma['hora_fin_jornada'], 
                                            cronograma['duracion_periodo'], cronograma['hora_fin_sabado'], 
                                            utiles);

                                        $('#calendario').empty().append(calendario.getDOM());
                                        cargarContenidoEnCalendario(anio, gestion, id_ambiente, calendario);
                                        }
            );
        } else {
            alert('Error fatal de servidor');
        }
    });
}

function cargarContenidoEnCalendario(anio, gestion, id_ambiente, calendario) {
    
    var argumentos = {anio: anio, gestion: gestion, ambiente: id_ambiente, calendario: calendario};
    var funciones = [
        anadirActividades,
        anadirTolerancias,
        anadirFeriadosEspeciales,
        anadirOtros,
        anadirReservas,
        anadirFechasNacionales,
        anadirAccionReservar
    ];
    ejecutarSecuencialmente(funciones, argumentos);
}

function anadirActividades(argumentos, siguiente) {
            
    var anio = argumentos.anio;
    var gestion = argumentos.gestion;
    var calendario = argumentos.calendario;

    cargarContenidoCronograma(anio, gestion, 'actividades', function (respuesta) {

        respuesta.forEach((actividad) => {
            if (actividad['permite_reserva'] === '1') {
                calendario.anadirEvento(crearEventoPermiteReserva(actividad));
            } 
            else {
                calendario.anadirEvento(crearEventoOtro(actividad));
            }
        });
        siguiente(argumentos);
    });
}

function anadirTolerancias(argumentos, siguiente) {
            
    var anio = argumentos.anio;
    var gestion = argumentos.gestion;
    var calendario = argumentos.calendario;

    cargarContenidoCronograma(anio, gestion, 'tolerancias', function (respuesta) {

        respuesta.forEach((tolerancia) => {
            calendario.anadirEvento(crearEventoTolerancia(tolerancia));
        });
        siguiente(argumentos);
    });
}

function anadirFeriadosEspeciales(argumentos, siguiente) {
            
    var anio = argumentos.anio;
    var gestion = argumentos.gestion;
    var calendario = argumentos.calendario;

    cargarContenidoCronograma(anio, gestion, 'feriados-especiales', function (respuesta) {

        respuesta.forEach((feriado) => {
            calendario.anadirEvento(crearEventoCierreUnivsersidad(feriado));
        });
        siguiente(argumentos);
    });
}

function anadirOtros(argumentos, siguiente) {
            
    var anio = argumentos.anio;
    var gestion = argumentos.gestion;
    var calendario = argumentos.calendario;

    cargarContenidoCronograma(anio, gestion, 'otros', function(respuesta) {

        respuesta.forEach((otro) => {
            if (otro['cierre_universidad'] === '1') {
                calendario.anadirEvento(crearEventoCierreUnivsersidad(otro));
            } 
            else {
                calendario.anadirEvento(crearEventoOtro(otro));
            }
        });
        siguiente(argumentos);
    });
}

function anadirReservas(argumentos, siguiente) {
            
    var anio = argumentos.anio;
    var gestion = argumentos.gestion;
    var ambiente = argumentos.ambiente;
    var calendario = argumentos.calendario;

    cargarReservas(anio, gestion, ambiente, function(respuesta) {

        respuesta.academicas.forEach((reserva) => {
            calendario.anadirEvento(crearEventoReservaAcademica(reserva));
        });
        respuesta.solicitadas.forEach((reserva) => {
            calendario.anadirEvento(crearEventoReservaSolicitada(reserva));
        });
        siguiente(argumentos);
    });
}

function anadirFechasNacionales(argumentos, siguiente) {
            
    var calendario = argumentos.calendario;

    cargarFechasNacionales(function(respuesta) {

        respuesta.forEach((fechaNacional) => {
            if (fechaNacional['feriado'] === '1') {
                calendario.anadirEvento(crearEventoParaFeriado(fechaNacional));
            }
            else {
                calendario.anadirEvento(crearEventoParaNoFeriado(fechaNacional));
            }
        });
        siguiente(argumentos);
    });
}

function anadirAccionReservar(argumentos, siguiente) {
            
    var calendario = argumentos.calendario;

    calendario.setAccionReservar(function (
            fecha, horaInicio, horaFin, 
            materia, asunto, idContenido, nombreUsuario) {

            var fecha_bloq = formatearSoloFecha(fecha);
            $.ajax({
            type: "POST",
            dataType: 'json',
            url: "../bandeja-solicitudes/bloquear_fecha.php",
            data: {'fecha': fecha_bloq},
            success : function(msm)  {
                if  (msm.exito)
                {
                    reservar(fecha, horaInicio, horaFin, 
                    materia, asunto, idContenido, 
                    nombreUsuario, calendario);
                
                }else {        
                    mostrarMensaje('alert-danger', msm.error);
                }
            },
            error: function (xhr, desc, err){
                    console.log("error"+err);
                }
            });  
    });
    calendario.forzarActualizar();
    siguiente(argumentos);
}

function crearEventoPermiteReserva(contenido) {
    
    return new EventoPermiteReserva(
            parseFechaHora(contenido['fecha_hora_inicio']),
            parseFechaHora(contenido['fecha_hora_fin']), 
            contenido['titulo'], contenido['descripcion']);
}

function crearEventoTolerancia(contenido) {
    
    return new EventoTolerancia(
            parseFechaHora(contenido['fecha_hora_inicio']),
            parseFechaHora(contenido['fecha_hora_fin']), contenido['descripcion']);
}

function crearEventoCierreUnivsersidad(contenido) {
    
    return new EventoCierreUniversidad(
            parseFechaHora(contenido['fecha_hora_inicio']),
            parseFechaHora(contenido['fecha_hora_fin']), 
            contenido['titulo'], contenido['descripcion']);
}

function crearEventoPermiteReserva(contenido) {
    
    return new EventoPermiteReserva(
            parseFechaHora(contenido['fecha_hora_inicio']),
            parseFechaHora(contenido['fecha_hora_fin']), 
            contenido['titulo'], contenido['descripcion'], contenido['id_contenido']);
}

function crearEventoOtro(contenido) {
    
    return new EventoOtro(
            parseFechaHora(contenido['fecha_hora_inicio']),
            parseFechaHora(contenido['fecha_hora_fin']), 
            contenido['titulo'], contenido['descripcion']);
}

function crearEventoReservaAcademica(reserva) {
    
    return new EventoReservaAcademica(
                parseFechaHora(reserva['fecha'] + ' ' + reserva['hora_inicio'] + ':00'), 
                parseFechaHora(reserva['fecha'] + ' ' + reserva['hora_fin'] + ':00'), 
                reserva['evento'], reserva['responsable'],
                reserva['materia'], reserva['asunto']);
}

function crearEventoReservaSolicitada(reserva) {
    
    return new EventoReservaSolicitada(
                parseFechaHora(reserva['fecha'] + ' ' + reserva['hora_inicio'] + ':00'), 
                parseFechaHora(reserva['fecha'] + ' ' + reserva['hora_fin'] + ':00'), 
                reserva['evento'], reserva['responsable'], reserva['institucion'], 
                reserva['descripcion']);
}

function crearEventoParaFeriado(fechaNacional) {
    
    var anio = new Date().getFullYear();
    var mes = parseInt(fechaNacional['mes']) -1;
    var dia = parseInt(fechaNacional['dia']);
    var fechaInicio = new Date(anio, mes, dia, 0, 0, 0, 0);
    var fechaFin = new Date(anio, mes, dia, 23, 59, 0, 0);
    if (fechaInicio.getDay() === 0) {
        fechaInicio = new Date(fechaInicio.getTime() + dias2Milisecs(1));
        fechaFin = new Date(fechaFin.getTime() + dias2Milisecs(1));
    }
    return new EventoCierreUniversidad(fechaInicio, fechaFin, fechaNacional['titulo']);
}

function crearEventoParaNoFeriado(fechaNacional) {
    
    var anio = new Date().getFullYear();
    var mes = parseInt(fechaNacional['mes']) -1;
    var dia = parseInt(fechaNacional['dia']);
    var fechaInicio = new Date(anio, mes, dia, 0, 0, 0, 0);
    var fechaFin = new Date(anio, mes, dia, 23, 59, 0, 0);
    return new EventoOtro(fechaInicio, fechaFin, fechaNacional['titulo']);
}

function cuandoCambiaAnioGestion(evento) {
    var valor = $('#selAnioGestion').val();
    var anio = parseInt(valor.split('-')[0]);
    var gestion = parseInt(valor.split('-')[1]);
    if (anio !== '' && gestion !== '' && auditorio !== '') {
        cargarCalendario(anio, gestion, auditorio);
    }
}

function cargarDatosCronograma(anio, gestion, callback) {
    
    ajaxPost('obtener_cronograma_config.php', {
        anio: anio, gestion: gestion
    }, callback);
}

function cargarContenidoCronograma(anio, gestion, peticion, callback) {
    
    ajaxPost('obtener_contenido_cronograma.php', {
        anio: anio, gestion: gestion, peticion:peticion
    }, callback);
}

function cargarReservas(anio, gestion, id_ambiente, callback) {
    
    ajaxPost('obtener_reservas.php', {anio: anio, gestion: gestion, ambiente:id_ambiente}, callback);
}

function cargarFechasNacionales(callback) {
    
    ajaxPost('obtener_fechas_nacionales.php', {}, callback);
}

function cargarUtilesParaReservar(callback) {

    ajaxPost('obtener_utiles_reservar.php', {}, callback);
}

function reservar(fecha, horaInicio, horaFin, 
        codigoMateria, idAsunto, idContenido, 
        nombreUsuario, calendario) {
    
    var reserva = {
        ambiente: auditorio,
        fecha: formatearSoloFecha(fecha),
        hora_inicio: horaInicio,
        hora_fin: horaFin,
        codigo_materia: codigoMateria,
        id_asunto: idAsunto,
        id_contenido: idContenido,
        nombre_usuario: nombreUsuario
    };
    ajaxPost('guardar_reserva_academica.php', reserva, function(respuesta) {
        
        if (respuesta.exito) {
            calendario.anadirEvento(crearEventoReservaAcademica(respuesta.reserva));
            calendario.forzarActualizar();
        }
        else {
            mostrarMensaje('alert-danger', respuesta.error);
        }
    });
}

function mostrarMensaje(tipo, mensaje) {

    $('#contenedor-msg').empty().append(crearAlerta(tipo, mensaje));
    $('html,body').animate({scrollTop: 80}, "fast");
}