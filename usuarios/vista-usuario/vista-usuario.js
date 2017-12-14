 $(document).ready(function () {
	
	var dominio = "http://localhost/sistema-reserva-auditorio-fcyt/usuarios/";
	var materias = [];
    
    function mostrarUsuarios(usuarios){
    	var filas = "";
		for (var i = 0; i < usuarios.length; ++i) {
			filas = anadirNuevaFila(usuarios[i],i);
			$('#cuerpo-tabla').append(filas);
			$('#primaryllave-'+i).hide();
		}
		
		
	}

	function anadirNuevaFila(data,id){
		var estado = 'Inactivo';
		if(data['activo'] == 't'){
			estado = "Activo";
		}
		var fila = '';
		fila = fila + '<tr>';
	  	fila = fila + '<td data-id="nombre-'+id+'">'+data['nombres']+'</td>';
	  	fila = fila + '<td data-id="apeleidos'+id+'">'+data['apellidos']+'</td>';
	  	fila = fila + '<td>'+data['nombre_rol']+'</td>';
	  	fila = fila + '<td id="estado-activo-llave-'+id+'">'+estado+'</td>';
	  	fila = fila + '<td data-id="llave-'+id+'">';
        fila = fila + '<button data-toggle="modal" data-target="#edit-item" class="btn btn-primary edit-item">Acceder</button> ';
        fila = fila + '<button id="boton-eliminar-llave-'+id+'" class="btn btn-danger remove-item">Cambiar estado</button>';
        fila = fila + '</td>';
        fila = fila + '<td id="primaryllave-'+id+'">'+data['nombre_usuario']+'</td>';
	  	fila = fila + '</tr>';
	  	return fila;
	}

	function redireccionarCrearUsuario(){
		window.location.href = dominio + "crear-usuario";	 	
	}

	function redireccionarEditar(llave){
		var dato = 'vista-editar/?next='+llave;
		window.location.href = dominio + dato;
	}

	function mostrarDatosUsuario(lista){
		$('#nombres').val(lista['nombres']);
		$('#apellidos').val(lista['apellidos']);
		$('#telefonos').val(lista['telefono']);
		$('#correo').val(lista['correo']);
		$('#nombreDeUsuario').val(lista['nombre_usuario']);	
	}

	function cargarVentana(){
		crearOpcionesRoles();
		crearAgregarMateria();
	}

	function crearOpcionesRoles(){
		$.ajax({
			type: 'GET',
			url:'../recurso_rol.php',
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

	function crearAgregarMateria(){
		$.ajax({
			type: 'GET',
			url:'../recurso_materia.php',
			data:{}
		}).done(function(materias){
			var selectMaterias = $('#select-materias');
        	materias.forEach(function (materia) {
	            var option = crear('OPTION', materia['nombre_materia']);
	            option.setAttribute('value', materia['codigo_materia']);
	            selectMaterias.append(option);
        	});
		});
	}

	function crearVisualizadorMateria(nombreMateria, funcionEliminar, id) {
		debugger;
		var cadena = '<div class="list-group-item col-xs-6" id="'+id+'"></div>';
		cadena = cadena + '<div class="col-md-8 padding-boton">'+nombreMateria+'</div>';
		cadena = cadena + '<div class="col-md-4 text-right">';
		cadena = cadena + '<button type="button" class="btn btn-default">Eliminar</button>';
		cadena = cadena + '</div>';
        //var contenedor = crear('DIV', null, 'list-group-item col-xs-6', id);
        //contenedor.appendChild(crear('DIV', nombreMateria, 'col-md-8 padding-boton'));
        //var botonEliminar = crear('BUTTON', 'Eliminar', 'btn btn-default');
        //botonEliminar.onclick = funcionEliminar;
        //var divBoton = crear('DIV', null, 'col-md-4 text-right');
        //divBoton.appendChild(botonEliminar);
        //contenedor.appendChild(divBoton);
        return cadena;
    }

	function anadirMateria() {
        debugger;
        var selectMaterias = $('#select-materias');
        var codigoMateria = selectMaterias.val();
        var nombreMateria = $('#select-materias option[value="'+codigoMateria+'"]').text();
        if (materias.includes(codigoMateria)) {
            return;
        }
        var posicion = materias.length;
        var id = 'm' + posicion;
        materias.push(codigoMateria);
        var a =crearVisualizadorMateria(nombreMateria, function() {
            $('#' + id).remove();
            delete materias[posicion];
            
        }, id);
        console.log(a);
        console.log('cccccc');
        $('#lista-materias-modal').append(a);
		console.log($('#lista-materias-modal').html());
    }

	function cargarRoles(lista){
		alert(lista['nombre_rol']);
		document.getElementById("nombre-rol").value = lista['nombre_rol'];
		//$('#nombre-rol').val(lista['nombre_rol']);
	}

	function cargarMaterias(lista){

	}	

	function recuperarDatos(llave){
		var res = null;
		$.ajax({
			type: 'POST',
			url:'recuperarDatos.php',
			data:{id:llave},
		}).done(function(res){
			var fila = JSON.parse(res);
			mostrarDatosUsuario(fila[0]);
			cargarRoles(fila[0]);
			cargarMaterias(fila[0]);
			//////
			console.log(fila);
			console.log("recupere datos");
			//////
		});
		return res;
	}

	function cambiarEstadoUsuario(dato, id, estado){
    	$.ajax({
		    type:'POST',
		    url:'eliminar_usuario.php',
		    data:{id:dato,estado_activo:estado}
		}).done(function(data){
			var dat = JSON.parse(data);
			var cadena = '';
			if(dat['activo'] == "t"){
				$('#estado-activo-'+id).text('Activo');
				cadena = 'Activo';
			}else{
				$('#estado-activo-'+id).text('Inactivo');
				cadena = 'Inactivo';
			}
		    console.log("Se a cambiado el estado del usuario: " + dat['nombre_usuario']+ " a "+cadena);
		    //Falta mostrar el mensaje de la actualizacion en la pantalla
		    }).fail(function(error){

		    });	
	}

	function parcearIdTag(id){
	var cadena = id.split('-');
	return cadena[1];
	}

	ajaxGet('vista-usuario.php',{},mostrarUsuarios);

	setTimeout(function(){
		$('#btn-crear-nuevo-usuario').click(redireccionarCrearUsuario);
    	//eliminar usuario
    	$("body").on("click",".remove-item",function(){
    		var id = $(this).parent("td").data('id');
    		var columna = $(this).parents("tr");
    		var dato = document.getElementById("primary"+id).innerHTML;
    		if($('#estado-activo-'+id).text() == 'Activo'){
    			cambiarEstadoUsuario(dato,id,true);	
    		}else{
    			cambiarEstadoUsuario(dato,id,false);	
    		}	
    		//mostrarAlerta('alert-success',"Se elimino el usuariocorrectamente")
    	});
    	//editar usuario
        $("body").on("click",".edit-item",function(){
    		var id = $(this).parent("td").data("id");
    		var llave = document.getElementById("primary"+id).innerHTML;
    		redireccionarEditar(llave);
    		//cargarVentana();
    		//var tablaDatos = recuperarDatos(llave);
		});
		$('#btn-anadir-materia-modal').click(anadirMateria);
	},1000)
});