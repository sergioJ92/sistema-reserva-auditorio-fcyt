$(document).ready(function () {
	var url = location.href;
	var dato = url.split('?next=')[1];
	var dominio = 'http://localhost/sistema-reserva-auditorio-fcyt/usuarios/';
	var datojson = '';
	var materias = [];

	function cargarDatos(){
		$.ajax({
			type: 'POST',
			url:'recuperarDatos.php',
			data:{id:dato}
		}).done(function(entrada){
			var fila = JSON.parse(entrada);
			var estado ='inactivo';
			if(fila['activo'] == 't'){
				estado = 'activo';
			}
			fila = fila[0];
			$('#editar-nombres').text(fila['nombres']);
			$('#editar-apellidos').text(fila['apellidos']);	
			$('#editar-correo').text(fila['correo']);
			$('#editar-telefono').text(fila['telefono']);
			$('#editar-nombre-usuario').text(fila['nombre_usuario']);
			$('#editar-rol-usuario').text(fila['nombre_rol']);
			$('#editar-estado-usuario').text(estado);
			datojson = fila;

		}).fail(function(error){
		});
	}
	function cargarDatosModal(){
		$('#modal-nombres').val(datojson['nombres']);
		$('#modal-apellidos').val(datojson['apellidos']);	
		$('#modal-correo').val(datojson['correo']);
		$('#modal-telefonos').val(datojson['telefono']);
		$('#modal-nombre-usuario').val(datojson['nombre_usuario']);
			
	}
	function crearOpcionesRoles(){
		$.ajax({
			type:'GET',
			url:'../crear-usuario/recurso_rol.php',
			data:{},
		}).done(function(entrada){
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
		}).fail(function(error){
			console.log(error);
		});
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
            $('#lista-materias-modal').empty();
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

    function crearAgregarMateria(){
		$.ajax({
			type: 'GET',
			url:'../crear-usuario/recurso_materia.php',
			data:{}
		}).done(function(materias){
			var selectRol = $('#select-materias').empty();
			var selectMaterias = $('#select-materias');
        	materias.forEach(function (materia) {
	            var option = crear('OPTION', materia['nombre_materia']);
	            option.setAttribute('value', materia['codigo_materia']);
	            selectMaterias.append(option);
        	});
		});
	}

	function insertarMateria(){
		var selectMaterias = $('#select-materias');
		var codigoMateria = selectMaterias.val();
        var nombreMateria = $('#select-materias option[value="'+codigoMateria+'"]').text();
        if (materias.includes(codigoMateria)) {
            return;
        }
        var posicion = materias.length;
        var id = 'm' + posicion;
        materias.push(codigoMateria);
        $('#lista-materias-modal').append(crearVisualizadorMateria(nombreMateria, function() {
            $('#' + id).remove();
            delete materias[posicion];
            
        }, id));	
		        
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

    function editarContrasena(actual,contrasena,reContrasena,nombreUsuario){
    	$.ajax({
			type: 'POST',
			url:'editar_contracena.php',
			data:{actual:actual,contrasena:contrasena,reContrasena:reContrasena,id:nombreUsuario}
		}).done(function(dato){
			JSON.parse(dato);	
			//mostrar mensaje de actualizacion
		}).fail(function(error){
			console.log("error");
			console.log(error);

			//mostrar mensaje de no actualizacion
		});
    }

    function cargarRolDeUsuario(){
    	$.ajax({
    		type: 'POST',
    		url: 'recuperarRol.php',
    		data: {id:dato}
    	}).done(function(dat){
    		dat = JSON.parse(dat);
    		if(datojson['nombre_rol'] == 'Ninguno'){
    			$('#nombre-rol').val("selected");
    		}else{
    			$('#nombre-rol').val(datojson['nombre_rol']);
    			if(datojson['puede_tener_materias'] == 1){
    				var n = 0;
    				$('#seccion-materias').show('slow');
					dat.forEach(function(materia){
						var id = 'm'+n;
						materias.push(materia['codigo_materia']);
						var mat = $('#lista-materias-modal')
						mat.append(agregarMateria(materia['nombre_materia'],function(){
								$('#' + id).remove();
	            				delete materias[n];
	            			},id));
	            		n = n+1;
    				});
				}else{
					$('#seccion-materias').hide('slow');
				}	
    		}
    	}).fail(function(error){
    		console.log('No se an podido cargar los datos');
    	});
    }

    function agregarMateria(nombreMateria, funcionEliminar, id) {
        var contenedor = crear('DIV', null, 'list-group-item col-xs-6', id);
        contenedor.appendChild(crear('DIV', nombreMateria, 'col-md-8 padding-boton'));
        var botonEliminar = crear('BUTTON', 'Eliminar', 'btn btn-default');
        botonEliminar.onclick = funcionEliminar;
        var divBoton = crear('DIV', null, 'col-md-4 text-right');
        divBoton.appendChild(botonEliminar);
        contenedor.appendChild(divBoton);
        return contenedor;
    }

    function actualizarDatos(){
    	var nombre = $('#modal-nombres').val();
    	var apellido = $('#modal-apellidos').val();
    	var telefonos = $('#modal-telefonos').val();
    	var correos = $('#modal-correo').val();
    	var nombre_usuario = $('#modal-nombre-usuario').val();
    	var estado = false;//activo 1  -  inactivo 2
    	if($('#select-estado').val()==1){
			estado = true;
    	}
    	//cambiar rol es 
    	guardarDatosNuevos(nombre, apellido, telefonos, correos, nombre_usuario, estado);
    }

    function guardarDatosNuevos(nombres,apellidos,telefonos,correos,nombreUsuario,estado){
    	$.ajax({
    		type:'POST',
    		url:'editar-datos.php',
    		data:{usuario_actual:datojson['nombre_usuario'],
    			 nombre:nombres,
    			 apellido:apellidos,
    			 telefonos:telefonos,
    			 correos:correos,
    			 nombre_usuario:
    			 nombreUsuario,
    			 estado:estado}
    	}).done(function(dato){
    		var a = JSON.parse(dato);
    		window.location.href = dominio+a.id_get;

    	}).fail(function(error){
    		var e = JSON.parse(error);
    		console.log(e['mensaje']);
    	});
    }

    function limpiarModal(){
    	$('#lista-materias-modal').empty();
    }

	function cancelarEdicion(){
		window.location.href = dominio + "vista-usuario";	 	
	}
	
	cargarDatos();
	setTimeout(function(){
	
	$('#btn-cancelar').click(cancelarEdicion);
	$('#modal-datos-cerrar').click(limpiarModal);
	$('#x-datos-cerrar').click(limpiarModal);
	$('#modal-editar-usuario').on('hidden.bs.modal', limpiarModal);

	$('body').on("click","#btn-editar-usuario",function(){
		cargarDatosModal();
		crearOpcionesRoles();
		crearAgregarMateria();
		cargarRolDeUsuario();
	});
	$('#actualizar-datos').click(actualizarDatos);

	$('#modal-guardar-contrasena').click(function(){
		var actual = $('#mod-edit-contrcena-actual').val();
		var contracena = $('#mod-edit-nueva-contracena').val();
		var reContracena = $('#mod-edit-reingresar-contracena').val();
		var nombre_usuario = $('#editar-nombre-usuario').text();
		editarContrasena(actual,contracena,reContracena,nombre_usuario);
		$('#mod-edit-contrcena-actual').val('');
		$('#mod-edit-nueva-contracena').val('');
		$('#mod-edit-reingresar-contracena').val('');
	});
	$('#modal-contrasena-cerrar').click(function(){
		$('#mod-edit-contrcena-actual').val('');
		$('#mod-edit-nueva-contracena').val('');
		$('#mod-edit-reingresar-contracena').val('');
	});

	$('#anadir-materia-modal').click(function(e){
		e.preventDefault();
		insertarMateria();
		//e.stopPropagation();
	});


	},1000);
});