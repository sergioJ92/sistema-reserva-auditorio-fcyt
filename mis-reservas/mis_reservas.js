
var datos = [];

ajaxPost('obtener_reserva.php', {}, function (data) {
    datos = data;
    llenarDatos();
});

function arregloEliminarElemento(arreglo, indice) {
    return arreglo.slice(0, indice).concat(arreglo.slice(indice + 1));
}

function llenarModal(idLista) {

    $('#modalFecha').text("");
    $('#modalEvento').text("");
    $('#modalMateria').text("");
    $('#modalHoraInicio').text("");
    $('#modalHoraFin').text("");

    $('#modalFecha').text(datos[idLista]['fecha']);
    $('#modalEvento').text(datos[idLista]['asunto']);
    $('#modalMateria').text(datos[idLista]['nombre_materia']);
    $('#modalHoraInicio').text(datos[idLista]['hora_inicio']);
    $('#modalHoraFin').text(datos[idLista]['hora_fin']);
}

function llenarDatos() {

    $('#respuestas').empty();
    if (datos.length === 0) {
        var filaVacia = '<li class="list-group-item empty" >No tienes Reservas</li>';
        $('#respuestas').append(filaVacia);
    } else {
        for (var fila = 0; fila < datos.length; fila++) {
            var leido = 'antiguo';
            if (datos[fila]['leido'] === '0') {
                leido = 'nuevo';
            }
            var filaContenido = '<li class="list-group-item lista-foco  ' + leido + '" >';
            filaContenido +=       '<div class="row no-select">';
            filaContenido +=           '<div class="col-sm-9">';
            filaContenido +=               '<div class="row" onclick="verReservaDeFila(' + fila + ')" id=' + fila + '>';
            filaContenido +=                   '<div class="col-sm-3">';
            filaContenido +=                       '<span><b>Fecha: </b></span>';
            filaContenido +=                       '<span>' + datos[fila]['fecha'] + '</span>';
            filaContenido +=                       '</div>';
            filaContenido +=                   '<div class="col-sm-3">';
            filaContenido +=                       '<span><b>Asunto: </b></span>';
            filaContenido +=                       '<span>' + datos[fila]['asunto'] + '</span>';
            filaContenido +=                   '</div>';
            filaContenido +=                   '<div class="col-sm-3">';
            filaContenido +=                       '<span><b>Materia: </b></span>';
            filaContenido +=                       '<span>' + datos[fila]['nombre_materia'] + '</span>';
            filaContenido +=                   '</div>';
            filaContenido +=                   '<div class="col-sm-3">';
            filaContenido +=                       '<span><b>Año: </b></span>';
            filaContenido +=                       '<span>' + datos[fila]['anio'] + '</span>';
            filaContenido +=                       '<div style="display:inline-block; width:20px"></div>';
            filaContenido +=                       '<span><b> Gestion: </b></span>';
            filaContenido +=                       '<span>' + datos[fila]['gestion'] + '</span>';
            filaContenido +=                   '</div>';
            filaContenido +=               '</div>';
            filaContenido +=           '</div>';
            filaContenido +=           '<div class="col-sm-3">';
            filaContenido +=               '<div class="text-right">';
            if (fechaValida(datos[fila])) {
                filaContenido +=                   '<button onclick="eliminarReserva(this)" class="btn btn-default boton-eliminar" type="button" id="' + fila + 'b">Eliminar</button>';
                filaContenido +=                   '<div style="display:inline-block; width:20px"></div>';
                filaContenido +=                   '<button onclick="modificarReserva(this)" class="btn btn-default boton-modificar" type="button" id="' + fila + 'c">Modificar</button>';
                filaContenido +=               '</div>';
            } else {
                filaContenido +=                   '<button onclick="eliminarReserva(this)" class="btn btn-default boton-eliminar" disabled="disabled" type="button" id="' + fila + 'b">Eliminar</button>';
                filaContenido +=                   '<div style="display:inline-block; width:20px"></div>';
                filaContenido +=                   '<button onclick="modificarReserva(this)" class="btn btn-default boton-modificar" disabled="disabled" type="button" id="' + fila + 'c" >Modificar</button>';
            }
            filaContenido +=               '</div>';
            filaContenido +=           '</div>';
            filaContenido +=       '</div>';
            filaContenido +=    '</li>';
            $('#respuestas').append(filaContenido);
        }
    }
}

function eliminarReserva(boton) {

    $('#contenedor-mensaje').empty();
    var ideliminar = parseInt(boton.id.substring(0, boton.id.length - 1));

    $('#modalEliminar').modal();
    $('#boton-eliminar-si').off('click').click(function () {
        var datosReserva = {
            materia: datos[ideliminar]['materia'],
            asunto: datos[ideliminar]['asunto'],
            fecha: datos[ideliminar]['fecha'],
            hora_inicio: datos[ideliminar]['hora_inicio'],
            hora_fin: datos[ideliminar]['hora_fin']
        };
        ajaxPost('eliminar_reserva.php', datosReserva, function (respuesta) {
            if (respuesta === true) {
                datos = remover(datos, ideliminar);
                llenarDatos();
            }
        });
    });
}

function modificarReserva(boton) {

    $('#contenedor-mensaje').empty();
    var idmodificar = parseInt(boton.id.substring(0, boton.id.length - 1));
    $('#modalModificar').modal();

    function cargarMaterias(materiasUsuario) {
        var selectMateria = document.getElementById('modalModificarSelectMateria');
        $(selectMateria).empty();

        for (var i = 0; i < materiasUsuario.length; i++) {
            var materia = materiasUsuario[i]['nombre_materia'];
            var codigoMateria = materiasUsuario[i]['codigo_materia'];
            var optionMateria = document.createElement('option');
            optionMateria.setAttribute('value', codigoMateria);
            optionMateria.innerHTML = materia;
            selectMateria.appendChild(optionMateria);
        }
    }
    
    function cargarAsuntos(todosAsuntos) {
        
        var selectAsunto = document.getElementById('modalModificarAsunto');
        $(selectAsunto).empty();
        
        todosAsuntos.forEach(function (asuntoActual) {
            var option = crear('OPTION', asuntoActual['asunto']);
            option.setAttribute('value', asuntoActual['id_asunto']);
            selectAsunto.appendChild(option);
        });
    }
    
    function cargarMateriasYAsuntos(datos) {
        
        var materias = datos.materias;
        var asuntos = datos.asuntos;
        
        cargarMaterias(materias);
        cargarAsuntos(asuntos);
    }

    ajaxPost('obtener_materias_asuntos.php', {}, cargarMateriasYAsuntos);

    $('#boton-modal-modificar').off('click').click(function () {

        var selectAsunto = document.getElementById('modalModificarAsunto');
        var selectMateria = document.getElementById('modalModificarSelectMateria');
        if ($(selectAsunto).val() === null || $(selectMateria).val() === null) {
            mostrarMensajeModal('alert-danger', 'Debe seleccionar asunto y materia');
        } else {
            var datosReserva = {
                id_reserva: datos[idmodificar]['id_reserva'],
                nom_materia: $(selectMateria).val(),
                asunto: $(selectAsunto).val()
            };
            ajaxPost('actualizar_reserva.php', datosReserva, function (respuesta) {
                if (respuesta.exito) {
                    $('#contenedor-modificar').empty();
                    $('#modalModificar').modal("hide");
                    var nombreMateria = $('#modalModificarSelectMateria option[value="' + datosReserva['nom_materia'] + '"]').text();
                    datos[idmodificar]['nombre_materia'] = nombreMateria;
                    var nombreAsunto = $('#modalModificarAsunto option[value="' + datosReserva['asunto'] + '"]').text();
                    datos[idmodificar]['asunto'] = nombreAsunto;
                    $('#respuestas').empty();
                    llenarDatos();
                    mostrarMensaje('alert-success', 'Se modifico correctamente los datos de reserva');
                } else {
                    mostrarMensajeModal('alert-danger', respuesta.error || 'Error en el servidor');
                }
            });
        }
    });

    $('#boton-modal-cancelar').off('click').click(function () {
        $('#contenedor-modificar').empty();
    });
}

function verReservaDeFila(fila) {
    llenarModal(fila);
    $('#modalContenido').modal();
}

function mostrarMensajeModal(tipo, mensaje) {

    $('#contenedor-modificar').empty().append(crearAlerta(tipo, mensaje));
}

function mostrarMensaje(tipo, mensaje) {

    $('#contenedor-mensaje').empty().append(crearAlerta(tipo, mensaje));
    $('html,body').animate({scrollTop: 80}, "fast");
}

function fechaValida(reserva) {

    var fecha = parseFechaHora(reserva['fecha'] + ' ' + reserva['hora_inicio'] + ':00');
    return fecha >= new Date();
}