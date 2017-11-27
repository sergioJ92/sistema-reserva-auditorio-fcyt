var edificio = '';
var piso = '';
var aula = '';

$('input[id ^=fecha]').parent().datetimepicker({
    format: 'DD-MM-YYYY',
    locale: 'es',
    showClose: true
});

$('input[id ^=hora_inicio]').parent().datetimepicker({
    locale: 'es',
    format: 'LT'
});

$('input[id ^=hora_fin]').parent().datetimepicker({
    locale: 'es',
    format: 'LT'
});

$('input[id ^=fecha]').click(function () {
    $(this).parent().data('DateTimePicker').toggle();
});

$('input[id ^=hora_inicio]').click(function () {
    $(this).parent().data('DateTimePicker').toggle();
});

$('input[id ^=hora_fin]').click(function () {
    $(this).parent().data('DateTimePicker').toggle();
});

$( "#selPiso" ).attr('disabled', 'disabled');

$( "#selAula" ).attr('disabled', 'disabled');

$('#selEdificio').change(function () {
    edificio = $(this).val();
    var _edificio = edificio.replace(/\s/g,"_");
    $("#selPiso").load("obtener_datos.php?tipo="+"1"+"&nombre="+_edificio);
    $("#selPiso").removeAttr("disabled");
});

$('#selPiso').change(function () {
    piso = $(this).val();
    var _edificio = edificio.replace(/\s/g,"_");
    var _piso = piso.replace(/\s/g,"_");
    console.log(edificio);
    console.log(piso);
    $("#selAula").load("obtener_datos.php?tipo="+"2"+"&nombre="+_edificio+"&piso="+_piso);
    $("#selAula").removeAttr("disabled");
});

$('#selAula').change(function () {
    aula = $(this).val();
    console.log(aula);
});

$('#enviar').click(function () {
    
    $('#ico-enviando').append('Enviando ... <i class="fa fa-spinner fa-spin" style="font-size:24px"></i>');
    $('#enviar').attr("disabled", true);
    
    var datos = {
        edificio: edificio,
        piso: piso,
        nombre_aula: aula,
        responsable: $('#responsable').val(),
        institucion: $('#institucion').val(),
        telefono: $('#telefono').val(),
        correo: $('#correo').val(),
        evento: $('#evento').val(),
        fecha: $('#fecha').parent().data("DateTimePicker").date(),
        hora_inicio: $('#hora_inicio').val(),
        hora_fin: $('#hora_fin').val(),
        descripcion: $('#descripcion').val()
    };
    
    if (datos['edificio'] === '' || datos['piso'] === '' || datos['nombre_aula'] === '' || datos['responsable'] === '' || datos['telefono'] === '' || datos['correo'] === '' ||
            datos['evento'] === '' || datos['fecha'] === null || datos['hora_inicio'] === '' || 
            datos['hora_fin'] === '') {
        
        mostrarMensaje('alert-danger', 'Debe rellenar todos los campos obligatorios');
    }
    else {
        datos.fecha = formatearFecha(datos.fecha).split(' ')[0];
        
        ajaxPost('aula/guardar_solicitud.php', datos, manejarGuardarSolicitud);
    }
});

function cargarDatos(respuesta){
    if(respuesta.exito){

    }else{
        
    }
}

function manejarGuardarSolicitud(respuesta) {
            
    if (respuesta.exito) {
        var msgExito ='La solicitud de reserva fue enviada con exito. \n';
        msgExito += 'Acabamos de enviar un codigo de solicitud a su correo, puede consultar el estado de su solicitud en cualquier momento';
        mostrarMensaje('alert-success', msgExito );
        $('#responsable').val("");
        $('#institucion').val("");
        $('#evento').val("");
        $('#fecha').parent().data('DateTimePicker').date(null);
        $('#hora_inicio').parent().data('DateTimePicker').date(null);
        $('#hora_fin').parent().data('DateTimePicker').date(null);
        $('#descripcion').val("");
        $('#telefono').val("");
        $('#correo').val("");
        $('#ico-enviando').empty();
        $('#enviar').attr("disabled", false);
    }
    else {
        mostrarMensaje('alert-danger', respuesta.error);
        $('#ico-enviando').empty();
        $('#enviar').attr("disabled", false);
    }
}

function mostrarMensaje(tipo, mensaje) {

    $('#contenedor-msg').empty().append(crearAlerta(tipo, mensaje));
    $('html,body').animate({scrollTop: 80}, "fast");
}

$('#consultar_solicitud').click(function () {
    
    $('#contenedor-consulta').empty();
    $('#codigo_reserva').val("");
    $('#codigo_respuesta').text("");
    $('#modalConsulta').modal();
    
    $('#consultar').click(function () {
        
        var datos = {
            codigo_reserva: $('#codigo_reserva').val()
        };
        
        if (datos.codigo_reserva === '') {
            $('#codigo_respuesta').text("");
            mostrarMensajeModal('alert-danger', 'Ingrese un codigo de solicitud por favor');
        }
        else {
            $('#contenedor-consulta').empty();
            
            ajaxPost('obtener_respuesta.php', datos, manejarRespuestaConsulta);
        }
    });
});

function manejarRespuestaConsulta(respuesta) {
                
    if (respuesta !== null) {
        var texto='La solicitud de reserva con '
                       +'Fecha: '+respuesta.fecha
                       +', Hora Inicio: '+respuesta.hora_inicio
                       +', Hora Fin: '+respuesta.hora_fin;
        if (respuesta.aceptado && fechaValida(respuesta.fecha)) { 
            if (respuesta.aceptado === '1') {
                $('#codigo_respuesta').text(texto + ' fue ACEPTADO');
            }
            else{
                $('#codigo_respuesta').text(texto + ' fue RECHAZADO');
            }
        }
        else {
            if(fechaValida(respuesta.fecha)) {
                $('#codigo_respuesta').text(texto + ' está PENDIENTE');  
            }
            else {
                $('#codigo_respuesta').text(texto + ' a EXPIRADO antes de su evaluación');
            }
        }
    } else {
        $('#codigo_respuesta').text("");
        mostrarMensajeModal('alert-danger', 'No se encuentra el código de la solicitud');
    }

}

function mostrarMensajeModal(tipo, mensaje) {

    $('#contenedor-consulta').empty().append(crearAlerta(tipo, mensaje));
}

function fechaValida(fecha){
    
    // dia+1 debido a que new Date hace dia-1
    var splitFecha= fecha.split('-');
    var anio= splitFecha[0];
    var mes= splitFecha[1];
    var dia= parseInt(splitFecha[2])+1;
    var fecha_parametro= new Date(anio+'-'+mes+'-'+dia);
    var fecha_actual= new Date();
    return fecha_parametro > fecha_actual;
}