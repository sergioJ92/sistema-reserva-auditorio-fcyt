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
			fila = fila[0];
			$('#editar-nombres').text(fila['nombres']);
			$('#editar-apellidos').text(fila['apellidos']);	
			$('#editar-correo').text(fila['correo']);
			$('#editar-telefono').text(fila['telefono']);
			$('#editar-nombre-usuario').text(fila['nombre_usuario']);
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
		console.log("lllllll");
		console.log(selectMaterias);		
		//return false;
        var codigoMateria = selectMaterias.val();
        var nombreMateria = $('#select-materias option[value="'+codigoMateria+'"]').text();
        if (materias.includes(codigoMateria)) {
            return;
        }
        var posicion = materias.length;
        var id = 'm' + posicion;
        materias.push(codigoMateria);
        console.log("fin");
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

    function editarContracena(actual,contrasena,reContrasena){
    	$.ajax({
			type: 'POST',
			url:'editar_contracena.php',
			data:{actual:actual,contrasena:contrasena,reContrasena:reContrasena}
		}).done(function(materias){
			console.log("entro");	
			//mostrar mensaje de actualizacion
		}).fail(function(error){
			console.log("error");
			console.log(error);

			//mostrar mensaje de no actualizacion
		});
    }

    function cargarRolDeUsuario(){
    	console.log(datojson);
    	$.ajax({
    		type: 'POST',
    		url: 'recuperarRol.php',
    		data: {id:dato}
    	}).done(function(dat){
    		dat = JSON.parse(dat)[0];
    		console.log(dat);
    		debugger;
    		if(dat['nombre_rol'] == 'Ninguno'){
    			$('#nombre-rol').val("selected");
    		}else{
    			console.log(dat['nombre_rol']);
    			$('#nombre-rol').val(dat['nombre_rol']);
    		}

    	}).fail(function(error){});
    	//verificar si es ninguno
    	//	si : que hacer
    	//	no : que hacer
    	//cargar el rol en el selected
    	//Si es docente 
    	//cargar sus materias si las tiene
    	//otros
    }

	function cancelarEdicion(){
		window.location.href = dominio + "vista-usuario";	 	
	}
	/////
	cargarDatos();
	setTimeout(function(){
	////////////////////////////////////////////////////////////
	$('#btn-cancelar').click(cancelarEdicion);
	$('body').on("click","#btn-editar-usuario",function(){
		cargarDatosModal();
		crearOpcionesRoles();
		crearAgregarMateria();
		cargarRolDeUsuario();
	});
	/*
	$('body').on("click","#anadir-materia-modal",function(){
		
		console.log("entre")
		insertarMateria();
		console.log("termina");

	});
	*/
	$('#anadir-materia-modal').click(function(e){
		e.preventDefault();
		console.log("entre")
		insertarMateria();
		console.log("termina");
		//e.stopPropagation();
	});


	$('body').on("click",".modal-editar-contracena",function(){
		var actual = $('#mod-edit-contrcena-actual').val();
		var contracena = $('#mod-edit-nueva-contracena').val();
		var reContracena = $('#mod-edit-reingresar-contracena').val();
		editarContracena(actual,contracena,reContracena);
	});

	},1000);
	////////////////////////////////////////////////////////////
});