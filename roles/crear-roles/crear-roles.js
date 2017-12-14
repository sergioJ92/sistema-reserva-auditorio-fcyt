$(document).ready(function () {
	var dominio = "http://localhost/sistema-reserva-auditorio-fcyt/roles/"

	function crearNuevoRol() {

        var rol = obtenerDatosModalNuevoRol();
        ajaxPost('crearRol.php', rol, function (respuesta) {
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
            if ($('#nuevo-privilegio-roles').prop('checked')) privilegios.push('Roles');
            res = { nombre_rol: $('#nuevo-nombre-rol').val(),
                    puede_tener_materias: $('#puede-tener-materias').prop('checked') ? 1 : 0,
                    privilegios: privilegios
                    };
        }
        return res;
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

    function mostrarMensajeRol(tipo, mensaje) {
        
        $('#contenedor-msg-rol').empty().append(crearAlerta(tipo, mensaje));
    }

    function cancelarCrearUsuario(){
    	window.location.href = dominio;
    }

	setTimeout(function(){

		$('#btn-crear-nuevo-rol').click(crearNuevoRol);
		$('#btn-cancelar-rol').click(cancelarCrearUsuario);
    },1000);
});
