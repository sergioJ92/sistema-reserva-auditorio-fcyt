
var actividades = [];
var tolerancias = [];
var feriadosEsp = [];
var otros = [];

var anio = getCookie('anio');
var gestion = getCookie('gestion');

$(document).ready(function () {
    
    function cargarContenido(anio, gestion, datos) {
        
        ajaxGet('obtener_periodo_cronograma.php', {
            anio: anio, gestion: gestion
        }, function (respuesta) {
            if (respuesta === null) return;
            var formateadoInicio = formatearFechaParaMostrar(respuesta.fecha_hora_inicio.split(' ')[0]);
            var formateadoFin = formatearFechaParaMostrar(respuesta.fecha_hora_fin.split(' ')[0]);
            $('#fecha_inicio_crono').text(formateadoInicio);
            $('#fecha_fin_crono').text(formateadoFin);
        });
        actividades = datos.actividades || actividades;
        tolerancias = datos.tolerancias || tolerancias;
        feriadosEsp = datos.feriadosEspeciales || feriadosEsp;
        otros = datos.otros || otros;
        rellenarPanel();
    }
    
    if (!(anio === 'null' || gestion === 'null')) {
        
        $('#selAnioGestion').val(anio + ' - ' + gestion);
        setCookie('anio', anio);
        setCookie('gestion', gestion);
        $.get('recurso_contenido.php', {anio: anio, gestion: gestion},
            function (respuesta, exito) {
                if (exito === 'success') {
                    cargarContenido(anio, gestion, respuesta);
                } else {
                    console.log("error");
                }
            }
        );
        
    }
    
    $('#selAnioGestion').change(function () {
        var _anio = $(this).val().split('-')[0].trim();
        var _gestion = $(this).val().split('-')[1].trim();
        setCookie('anio', _anio);
        setCookie('gestion', _gestion);
        anio = _anio;
        gestion = _gestion;
        ajaxGet('recurso_contenido.php', {
            anio: _anio, gestion: _gestion
        }, function (respuesta) {
            cargarContenido(_anio, _gestion, respuesta);
        });
    });
    
    inicializarFechaHoraDeActividades();

    inicializarControles();
    
    inicializarBotonesGuardar();
});

function formatearFechaParaMostrar(fecha) {

    var dia = fecha.split('-')[2];
    var mes = parseInt(fecha.split('-')[1]);
    var anio = fecha.split('-')[0];

    var formateado = rellenar(dia) + " de " + 
            mesLiteral(mes) + " del " + 
            rellenar(anio, 4);
    return formateado;
}

function inicializarFechaHoraDeActividades() {
    
    $('input[id ^=fecha]').parent().datetimepicker({
        locale: 'es',
        format: 'DD-MM-YYYY HH:mm',
        showClose: true
    });
    $('input[id ^=fecha]').click(function() {
        $(this).parent().data('DateTimePicker').toggle();
    });
}

function inicializarControles() {
    
    vaciarVista('tab-actividades');
    reiniciarBoton('tab-actividades');
    vaciarVista('tab-tolerancias');
    reiniciarBoton('tab-tolerancias');
    vaciarVista('tab-feriadosesp');
    reiniciarBoton('tab-feriadosesp');
    vaciarVista('tab-otro');
    reiniciarBoton('tab-otro');
    $('#contenedor-msg').empty();
}

function inicializarBotonesGuardar() {
    
    $('#guardar-act').click(guardar(
            obtenerCamposActividad, 'recurso_actividad.php', 'tab-actividades', 
            anadirActividad));
    $('#guardar-tol').click(guardar(
            obtenerCamposTolerancia, 'recurso_tolerancia.php', 'tab-tolerancias', 
            anadirTolerancia));
    $('#guardar-feresp').click(guardar(
            obtenerCamposFeriadoEsp, 'recurso_feriadoesp.php', 'tab-feriadosesp', 
            anadirFeriadoEsp));
    $('#guardar-otro').click(guardar(
            obtenerCamposOtro, 'recurso_otro.php', 'tab-otro', 
            anadirOtro));
}

function vaciarVista(idTab) {
    
    $("#" + idTab).find("input").val("");
    $("#" + idTab).find("textarea").val("");
    $("#" + idTab).find("select").val(0);
    $('#cierreuni-otro').prop('checked', false);
    $('#permitereserva-act').prop('checked', true);
    $("#" + idTab)
            .find('input[id ^=fecha]')
            .parent()
            .data('DateTimePicker')
            .date(null);
}

function reiniciarBoton(idTab) {
    
    $("#" + idTab).find("[id ^=guardar]").css({display: 'inline'});
}

function sacarFecha(fechaHora) {

    return fechaHora.split(' ')[0];
}

function sacarHora(fechaHora) {

    return fechaHora.split(' ')[1];
}

function rellenarPanel() {

    $('#panel').empty();
    var paneles = [];
    for (var indice = 0; indice < actividades.length; indice++) {
        
        paneles.push(crearPanel(actividades[indice], 'panel-warning', contenidoTitulo,
                eliminar(indice, obtenerIdActividad, 
                        eliminarActividad, 'tab-actividades')));
    }
    for (var indice = 0; indice < tolerancias.length; indice++) {
        
        paneles.push(crearPanel(tolerancias[indice], 'panel-success', toleranciaTitulo,
                eliminar(indice, obtenerIdTolerancia, 
                        eliminarTolerancia, 'tab-tolerancias')));
    }
    for (var indice = 0; indice < feriadosEsp.length; indice++) {
        
        paneles.push(crearPanel(feriadosEsp[indice], 'panel-danger', contenidoTitulo,
                eliminar(indice, obtenerIdFeriadoEsp, 
                        eliminarFeriadoEsp, 'tab-feriadosesp')));
    }
    for (var indice = 0; indice < otros.length; indice++) {
        
        paneles.push(crearPanel(otros[indice], 
                otros[indice].cierre_universidad > 0? 'panel-danger' : 'panel-info',
                contenidoTitulo,
                eliminar(indice, obtenerIdOtro, eliminarOtro, 'tab-otro')));
    }
    var contador = 0;
    var fila = crear('DIV', null, 'row text-left');
    $('#panel').append(fila);
    for (var indice = 0; indice < paneles.length; indice++) {
        if (contador === 3) {
            fila = crear('DIV', null, 'row text-left');
            $('#panel').append(fila);
            contador = 0;
        }
        fila.appendChild(paneles[indice]);
        contador++;
    }
}

function contenidoTitulo(contenido) {
    
    return contenido.titulo;
}

function toleranciaTitulo(tolerancia) {
    
    return 'Tolerancia';
}

function crearPanel(contenido, tipoPanel, fObtenerTitulo, fEliminar) {

    var panel = crear('DIV');
    panel.className = 'col-md-4 panel panel-contenido ' + tipoPanel;

    var cabecera = crear('DIV');
    var fila = crear('DIV');
    fila.className = 'row';
    cabecera.className = 'panel-heading';
    var titulo = crear('DIV');
    titulo.className = 'col-md-8 btn-padding';
    titulo.innerHTML = '<b>' + fObtenerTitulo(contenido) + '</b>';
    var tituloBotones = crear('DIV');
    tituloBotones.className = 'text-right col-md-4';
    var eliminar = crear('BUTTON');
    eliminar.className = 'btn btn-link';
    eliminar.innerHTML = 'Eliminar';
    eliminar.onclick = fEliminar;

    tituloBotones.appendChild(eliminar);

    fila.appendChild(titulo);
    fila.appendChild(tituloBotones);
    cabecera.appendChild(fila);

    var cuerpo = crear('DIV', null, 'panel-body panel-contenido-cuerpo');
    
    var fechas = crear('DIV');
    var fechaInicio = crear('DIV');
    fechaInicio.className = 'row';
    fechaInicio.innerHTML = '<b>Fecha inicio:</b> ' + sacarFecha(contenido.fecha_hora_inicio);
    var horaInicio = crear('DIV');
    horaInicio.className = 'row';
    horaInicio.innerHTML = '<b>Hora inicio: </b>' + sacarHora(contenido.fecha_hora_inicio);
    
    var fechaFin = crear('DIV');
    fechaFin.className = 'row';
    fechaFin.innerHTML = '<b>Fecha fin:</b> ' + sacarFecha(contenido.fecha_hora_fin);
    var horaFin = crear('DIV');
    horaFin.className = 'row';
    horaFin.innerHTML = '<b>Hora fin: </b>' + sacarHora(contenido.fecha_hora_fin);
    
    var descripcion = crear('DIV');
    descripcion.className = 'row';
    
    if (contenido.descripcion) {
        var contenidoDescripcion = crear('DIV');
        contenidoDescripcion.innerHTML = '<b>Descripcion: </b>' + contenido.descripcion;
        descripcion.appendChild(contenidoDescripcion);
    }
    
    fechas.appendChild(fechaInicio);
    fechas.appendChild(horaInicio);
    fechas.appendChild(fechaFin);
    fechas.appendChild(horaFin);

    cuerpo.appendChild(fechas);
    cuerpo.appendChild(descripcion);
    
    panel.appendChild(cabecera);
    panel.appendChild(cuerpo);
    return panel;
}

function arregloEliminarElem(arreglo, indice) {
    
    return arreglo.slice(0, indice).concat(arreglo.slice(indice + 1));
}

function eliminarActividad(indice) {
    
    actividades = arregloEliminarElem(actividades, indice);
}

function eliminarTolerancia(indice) {
    
    tolerancias = arregloEliminarElem(tolerancias, indice);
}

function eliminarFeriadoEsp(indice) {
    
    feriadosEsp = arregloEliminarElem(feriadosEsp, indice);
}

function eliminarOtro(indice) {
    
    otros = arregloEliminarElem(otros, indice);
}

function eliminar(indice, fObtenerId, fEliminarElem, idTab) {

    return function () {
        var mensaje = {
            accion: 'eliminar',
            carga: {
                id_contenido: fObtenerId(indice)
            }
        };
        ajaxPost('recurso_contenido.php', mensaje, function (respuesta) {
            
            if (respuesta.exito) {
                fEliminarElem(indice);
                rellenarPanel();
                mostrarMensaje('alert-success', 'El contenido se elimino con éxito', false);
                vaciarVista(idTab);
            } else {
                mostrarMensaje('alert-danger', respuesta.error, false);
            }
        });
    };
}

function llenarVistaActividad(indice) {
    
    var actividad = actividades[indice];
    vaciarVista('tab-actividades');
    
    $('#titulo-actividad').val(actividad.titulo);
    $('#fechahorainicio-act')
            .parent()
            .data('DateTimePicker')
            .date(new Date(actividad.fecha_hora_inicio));
    $('#fechahorafin-act')
            .parent()
            .data('DateTimePicker')
            .date(new Date(actividad.fecha_hora_fin));
    $('#descripcion-act').val(actividad.descripcion);
    $('#permitereserva-act').prop('checked', actividad.permite_reserva > 0);
}

function llenarVistaTolerancia(indice) {
    
    var tolerancia = tolerancias[indice];
    vaciarVista('tab-tolerancias');
    
    $('#fechahorainicio-tol')
            .parent()
            .data('DateTimePicker')
            .date(new Date(tolerancia.fecha_hora_inicio));
    $('#fechahorafin-tol')
            .parent()
            .data('DateTimePicker')
            .date(new Date(tolerancia.fecha_hora_fin));
    $('#descripcion-tol').val(tolerancia.descripcion);
}

function llenarVistaFeriadoEsp(indice) {
    
    var feriadoEsp = feriadosEsp[indice];
    vaciarVista('tab-feriadosesp');
    
    $('#titulo-feresp').val(feriadoEsp.titulo);
    $('#fechahorainicio-feresp')
            .parent()
            .data('DateTimePicker')
            .date(new Date(feriadoEsp.fecha_hora_inicio));
    $('#fechahorafin-feresp')
            .parent()
            .data('DateTimePicker')
            .date(new Date(feriadoEsp.fecha_hora_fin));
    $('#descripcion-feresp').val(feriadoEsp.descripcion);
}

function llenarVistaOtro(indice) {
    
    var otro = otros[indice];
    vaciarVista('tab-otro');
    
    $('#titulo-otro').val(otro.titulo);
    $('#fechahorainicio-otro')
            .parent()
            .data('DateTimePicker')
            .date(new Date(otro.fecha_hora_inicio));
    $('#fechahorafin-otro')
            .parent()
            .data('DateTimePicker')
            .date(new Date(otro.fecha_hora_fin));
    $('#descripcion-otro').val(otro.descripcion);
    $('#cierreuni-otro').prop('checked', otro.cierre_universidad > 0);
}

function mostrarMensaje(tipo, mensaje, scroll=true) {

    $('#contenedor-msg').empty().append(crearAlerta(tipo, mensaje));
    if (scroll) $('html,body').animate({scrollTop: 160}, "fast");
}

function anadirActividad(valor) {
    
    actividades.push(valor);
}

function anadirTolerancia(valor) {
    
    tolerancias.push(valor);
}

function anadirFeriadoEsp(valor) {
    
    feriadosEsp.push(valor);
}

function anadirOtro(valor) {
    
    otros.push(valor);
}

function guardar(fObtenerCampos, destino, idTab, fAnadirArreglo) {

    return function() {
        var datos = {
            accion: 'guardar',
            carga: fObtenerCampos(null)
        };
        ajaxPost(destino, datos, function (respuesta) {

            if (respuesta.exito) {
                vaciarVista(idTab);
                var carga = datos.carga;
                carga['id_contenido'] = respuesta['id_contenido'];
                fAnadirArreglo(carga);
                mostrarMensaje('alert-success', 'El contenido se creo con éxito');
                rellenarPanel();
            } else {
                mostrarMensaje('alert-danger', respuesta.error);
            }
        });
    };
}

function obtenerFecha(idInput) {
    
    return $("#" + idInput).parent().data('DateTimePicker').date();
}

function obtenerCamposActividad(id) {

    return {
        id_contenido: id,
        titulo: $('#titulo-actividad').val(),
        fecha_hora_inicio: formatearFecha(obtenerFecha("fechahorainicio-act")),
        fecha_hora_fin: formatearFecha(obtenerFecha("fechahorafin-act")),
        descripcion: $("#descripcion-act").val(),
        anio: anio,
        gestion: gestion,
        permite_reserva: $('#permitereserva-act').is(':checked') ? 1 : 0
    };
}

function obtenerCamposTolerancia(id) {

    return {
        id_contenido: id,
        fecha_hora_inicio: formatearFecha(obtenerFecha("fechahorainicio-tol")),
        fecha_hora_fin: formatearFecha(obtenerFecha("fechahorafin-tol")),
        descripcion: $("#descripcion-tol").val(),
        anio: anio,
        gestion: gestion
    };
}

function obtenerCamposFeriadoEsp(id) {

    return {
        id_contenido: id,
        titulo: $('#titulo-feresp').val(),
        fecha_hora_inicio: formatearFecha(obtenerFecha("fechahorainicio-feresp")),
        fecha_hora_fin: formatearFecha(obtenerFecha("fechahorafin-feresp")),
        descripcion: $("#descripcion-feresp").val(),
        anio: anio,
        gestion: gestion
    };
}

function obtenerCamposOtro(id) {

    return {
        id_contenido: id,
        titulo: $('#titulo-otro').val(),
        fecha_hora_inicio: formatearFecha(obtenerFecha("fechahorainicio-otro")),
        fecha_hora_fin: formatearFecha(obtenerFecha("fechahorafin-otro")),
        descripcion: $("#descripcion-otro").val(),
        cierre_universidad: $('#cierreuni-otro').is(':checked') ? 1 : 0,
        anio: anio,
        gestion: gestion
    };
}

function obtenerIdActividad(indice) {

    return actividades[indice].id_contenido;
}

function obtenerIdTolerancia(indice) {

    return tolerancias[indice].id_contenido;
}

function obtenerIdFeriadoEsp(indice) {

    return feriadosEsp[indice].id_contenido;
}

function obtenerIdOtro(indice) {

    return otros[indice].id_contenido;
}

function palanquearPanelContenido(boton) {
    
    $('#panel-contenido').toggle('slow');
    $('#formularios-creacion').toggle('slow');
    var nombre = $(boton).text() === 'Mostrar contenido' ? 
                    'Añadir contenido' : 'Mostrar contenido';
    $(boton).text(nombre);
}