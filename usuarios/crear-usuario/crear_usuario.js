$(document).ready(function () {
    
    var materias = [];
    var dominio = "http://chr2.hosting.cs.umss.edu.bo/usuarios/"
    
    function cargarRoles(entrada) {
        var roles = {};
        entrada.forEach(function (rol) {
            if (!roles[rol['nombre_rol']]) {
                roles[rol['nombre_rol']] = {
                    puede_tener_materias: parseInt(rol['puede_tener_materias']),
                    privilegios: []
                };
            }
            roles[rol['nombre_rol']].privilegios.push(rol['nombre_privilegio']);
        });
        
        rellenarRoles(roles);
    }
    
    function rellenarRoles(roles) {
        
        var selectRol = $('#nombre-rol').empty();
        var optionDefecto = crear('OPTION', 'Seleccionar Rol');
        optionDefecto.setAttribute('value', '');
        optionDefecto.setAttribute('selected', 'selected');
        optionDefecto.setAttribute('hidden', 'hidden');
        selectRol.append(optionDefecto);
        
        for (var nombreRol in roles) {
            var option = crear('OPTION', nombreRol);
            option.setAttribute('value', nombreRol);
            selectRol.append(option);
        }
        selectRol.off('change').change(function() {
            var rol = roles[selectRol.val()];
            actualizarPrivilegios('', rol.privilegios);
            materias = [];
            $('#lista-materias').empty();
            if (rol['puede_tener_materias']) {
                $('#seccion-materias').show('slow');
            } else {
                $('#seccion-materias').hide('slow');
            }
        });
    }
    
    function actualizarPrivilegios(seccion, privilegios) {
        
        seccion = seccion.length === 0 ? seccion: seccion + '-';
        $('#' + seccion + 'privilegio-cronograma').prop('checked', false);
        $('#' + seccion + 'privilegio-solicitudes').prop('checked', false);
        $('#' + seccion + 'privilegio-reservas').prop('checked', false);
        $('#' + seccion + 'privilegio-usuarios').prop('checked', false);
        privilegios.forEach(function (privilegio) {
            $('#' + seccion + 'privilegio-' + privilegio.toLowerCase()).prop('checked', true);
        });
    }
    
    function quitarNulos(arreglo) {
        
        var resultado = null;
        if(arreglo.length > 0){
            resultado = [];
            arreglo.forEach(function (elem) {
                if (elem !== null && elem !== undefined) {
                    resultado.push(elem);
                }
            });
        }
        return resultado;
    }
    
    function crearNuevoUsuario() {

        var usuario = obtenerDatosNuevoUsuario();
        usuario['materias'] = quitarNulos(materias);
        
        $.ajax({
            type: 'POST',
            url:'recurso_usuario.php',
            data:usuario,
        }).done(function(data){
            data = JSON.parse(data);
            if (data.exito) {
                reiniciarControles();
                materias = [];
                mostrarMensaje('alert-success', 'Se creo el usuario con éxito');
           }
            else {
                mostrarMensaje('alert-danger', data.mensaje);   
            }
        }).fail(function(data) {
                console.log(data);
              });
    }

    function obtenerDatosNuevoUsuario() {
        var matRes = "";
        var nombreRol = '';
        var boolEstado = false;
        if($('#nombre-rol').val()==null || $('#nombre-rol').val()==undefined || $('#nombre-rol').val()==''){
            nombreRol = 'Ninguno';
        }else{
            nombreRol = $('#nombre-rol').val();
        }
        if($('#select-estado').val() == 1){
            boolEstado = true;
        }
        mapRes = {
            nombres: $('#nombres').val(),
            apellidos: $('#apellidos').val(),
            telefono: $('#telefono').val(),
            correo: $('#correo').val(),
            nombre_usuario: $('#nombre-usuario').val(),
            contrasenia: $('#contrasenia').val(),
            confirmar_contrasenia: $('#confirmar-contrasenia').val(),
            estado_usuario: boolEstado,
            nombre_rol: nombreRol,
            materias: []
        };
        return mapRes;
    }

    function materiasCapturadas(){
        var lista = [];
        var i=0;
        while($('#rol-m'+i).val() == ""){
            lista.push($('#rol-m'+i).text());
            i=i+1;
        }
        return lista;
    }

    function abrirModalCrearRol() {
        
        actualizarPrivilegios('nuevo', []);
        $('#nuevo-nombre-rol').val('');
        $('#puede-tener-materias').prop('checked', false);
        $('#modalCrearRol').modal();
    }
    
    function mostrarMensajeRol(tipo, mensaje) {
        
        $('#contenedor-msg-rol').empty().append(crearAlerta(tipo, mensaje));
    }

    function mostrarMensaje(tipo, mensaje) {

        $('#contenedor-msg').empty().append(crearAlerta(tipo, mensaje));
        $('html,body').animate({scrollTop: 80}, "fast");
    }

    function crearNuevoRol() {

        var rol = obtenerDatosModalNuevoRol();
        ajaxPost('recurso_rol.php', rol, function (respuesta) {
            if (respuesta.exito) {
                actualizarPrivilegios('nuevo', []);
                $('#nuevo-nombre-rol').val('');
                $('#puede-tener-materias').prop('checked', false);
                mostrarMensajeRol('alert-success', 'Se creo el rol con éxito');
                ajaxGet('recurso_rol.php', {}, cargarRoles);
            }
            else {
                mostrarMensajeRol('alert-danger', respuesta.mensaje);
            }
        });
    }

    function obtenerDatosModalNuevoRol() {
        var privilegios = [];
        if($('#nuevo-nombre-rol').val() == ''){
            privilegios.push('Ninguno');
            res={
                nombre_rol: 'Ninguno',
                puede_tener_materias: 0,
                privilegios: privilegios
            }
        }else{
            if ($('#nuevo-privilegio-cronograma').prop('checked')) privilegios.push('Cronograma');
            if ($('#nuevo-privilegio-solicitudes').prop('checked')) privilegios.push('Solicitudes');
            if ($('#nuevo-privilegio-reservas').prop('checked')) privilegios.push('Reservas');
            if ($('#nuevo-privilegio-usuarios').prop('checked')) privilegios.push('Usuarios');
            res = { nombre_rol: $('#nuevo-nombre-rol').val(),
                    puede_tener_materias: $('#puede-tener-materias').prop('checked') ? 1 : 0,
                    privilegios: privilegios
                    };
        }
        return res;
    }
    
    function rellenarMaterias(materias) {
        
        var selectMaterias = $('#select-materias');
        materias.forEach(function (materia) {
            var option = crear('OPTION', materia['nombre_materia']);
            option.setAttribute('value', materia['codigo_materia']);
            selectMaterias.append(option);
        });
    }
     
    function crearVisualizadorMateria(nombreMateria, funcionEliminar, id) {
        var contenedor = crear('DIV', null, 'list-group-item col-xs-6', id);
        contenedor.appendChild(crear('DIV', nombreMateria, 'col-md-8 padding-boton'));
        var botonEliminar = crear('BUTTON', 'Eliminar', 'btn btn-default');
        botonEliminar.onclick = funcionEliminar;
        var divBoton = crear('DIV', null, 'col-md-4 text-right');
        divBoton.appendChild(botonEliminar);
        contenedor.appendChild(divBoton);
        return contenedor;
    }
    
    function anadirMateria() {
        
        var selectMaterias = $('#select-materias');
        var codigoMateria = selectMaterias.val();
        var nombreMateria = $('#select-materias option[value="'+codigoMateria+'"]').text();
        if (materias.includes(codigoMateria)) {
            return;
        }
        var posicion = materias.length;
        var id = 'm' + posicion;
        materias.push(codigoMateria);
        $('#lista-materias').append(crearVisualizadorMateria(nombreMateria, function() {
            $('#' + id).remove();
            delete materias[posicion];
            
        }, id));
    }
    function cancelar(){
        window.location.href = dominio + "vista-usuario";      
    }
    function reiniciarControles() {
        
        $('#nombres').val('');
        $('#apellidos').val('');
        $('#telefono').val('');
        $('#correo').val('');
        $('#nombre-usuario').val('');
        $('#contrasenia').val('');
        $('#confirmar-contrasenia').val('');
        $('#lista-materias').empty();
        $('#nombre-rol option:eq(0)').prop('selected', true);
        $('#select-materias option:eq(0)').prop('selected', true);
        actualizarPrivilegios('', []);
        $('#seccion-materias').hide('slow');
    }
    
    reiniciarControles();
    ajaxGet('recurso_rol.php', {}, cargarRoles);
    ajaxGet('recurso_materia.php', {}, rellenarMaterias);
    
    setTimeout(function(){
        $('#btn-abrir-crear-rol').click(abrirModalCrearRol);
        $('#btn-crear-nuevo-rol').click(crearNuevoRol);
        $('#btn-crear-nuevo-usuario').click(crearNuevoUsuario);
        $('#btn-anadir-materia').click(anadirMateria);
        $('#btn-cancelar').click(cancelar)
    },1000);
});