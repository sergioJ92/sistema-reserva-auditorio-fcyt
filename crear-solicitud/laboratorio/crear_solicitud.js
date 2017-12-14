var departamento = '';
var nombre_laboratorio = '';

$('#laboratorio-oculto').css("display", "block");

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

$( "#selLaboratorio" ).attr('disabled', 'disabled');

$('#selDepartamento').change(function () {
    nombre_laboratorio = '';
    departamento = $(this).val();
    console.log(departamento);
    var _departamento = departamento.replace(/\s/g,"_");
    $("#selLaboratorio").load("obtener_datos.php?tipo="+"3"+"&nombre="+_departamento);
    $("#selLaboratorio").removeAttr("disabled");
});

$("#selLaboratorio").change(function(){
    nombre_laboratorio = $(this).val();
    console.log(nombre_laboratorio);
});

$('#enviar').click(function () {
    $('#body-modal').empty();
    var datos = {
        id_ambiente: nombre_laboratorio,
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
    if (datos['id_ambiente'] === '' || datos['responsable'] === '' || datos['telefono'] === '' || datos['correo'] === '' ||
            datos['evento'] === '' || datos['fecha'] === null || datos['hora_inicio'] === '' || 
            datos['hora_fin'] === '') {
        mostrarMensaje('alert-danger', 'Debe rellenar todos los campos obligatorios');
    }else {
        datos.fecha = formatearFecha(datos.fecha).split(' ')[0];
        var datos ={id_ambiente:datos['id_ambiente'],
                    fecha: datos['fecha'],
                    hora_inicio: datos['hora_inicio'],
                    hora_fin: datos['hora_fin']};
        verificarConflictos(datos);   
    }
});

$('#btn-enviar-mensaje').click(function () {
    
    $('#ico-enviando').append('Enviando ... <i class="fa fa-spinner fa-spin" style="font-size:24px"></i>');
    $('#enviar').attr("disabled", true);
    
    var datos = {
        id_ambiente: nombre_laboratorio,
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
        datos.fecha = formatearFecha(datos.fecha).split(' ')[0];
        ajaxPost('guardar_solicitud.php', datos, manejarGuardarSolicitud);
});

function verificarConflictos(datos){
    var res = 0;
    $.ajax({
        type: 'POST',
        data: datos,
        url: "obtener_conflictos.php",
        success: function (resultado) {
            resultado = JSON.parse(resultado);
            if(resultado.exito){
                res = parseInt(resultado.conflictos);
                console.log(res);
                $('#titulo-modal').css({"background-color": "#337ab7", "color": "white"});
                $('#btn-enviar-mensaje').css({"background-color": "#337ab7", "color": "white"});
                console.log("pasaaaa");
                if (res>0) {
                    console.log("entra");
                    var add = '<div id="cuerpo-modal" class="modal-body"><div class="form-group row"><div id="modal-mensaje" class="col-md-12 text-center">Existen '+res+' conflictos con otras Reservas <b>Si deseas continuar presiona ENVIAR</b></div></div></div>';
                    $('#body-modal').append(add);
                }
                $('#myModal').modal();
            }else{
                console.log("entra error");
            }
        },
        error: function(resultado){
            console.log("no entra");
        }
    });   
}

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
        $('#selDepartamento').val("null");
        $('#selLaboratorio').html("");
        $('#selLaboratorio').attr('disabled', 'disabled');
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
