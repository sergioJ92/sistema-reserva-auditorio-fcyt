$(document).ready(function () {
	var dominio = "http://localhost/sistema-reserva-auditorio-fcyt/roles/"
	var rol = '';
	var privilegios = [];
	var privRol = [];
	function obtenerNombreUsuarioUrl(){
		var url = location.href;
		rol = url.split('?next=')[1];
        rol = habilitarEspacios(rol);
	}

    function habilitarEspacios(cad){
        var cadenaRes = '';
        var cadenas = cad.split("%20");
        if(cadenas.length > 0){
            cadenaRes = cadenaRes + cadenas[0];
            for (var i=1 ;i < cadenas.length; ++i) {
                cadenaRes = cadenaRes+" "+cadenas[i];
            }
        }    
        return cadenaRes;
    }

	function cargarVentana(){
		var cadenaRes = "";
		var cadenaModalRes = "";
		$.ajax({
			type:"GET",
			url:"cargar-ventana.php",
			data:{}
		}).done(function(datos){
			datos.forEach(function(data){
				cadenaRes = cadenaRes + cargarPrivilegio(data['nombre_privilegio'],true);
				cadenaModalRes = cadenaModalRes + cargarPrivilegio(data['nombre_privilegio'],false);
				privilegios.push(data['nombre_privilegio']);
			});
			$('#campo-privilegios').append(cadenaRes);
			$('#modal-campo-privilegios').append(cadenaModalRes);
		}).fail(function(error){
			console.log("error");
		});
	}

	function cargarPrivilegio(privilegio,bloqueo){
		var res = "";
		if(bloqueo == true){
			res = '<div class="checkbox list-group-item col-xs-6 margen-privilegios">'
			res = res + '<label><input id="id-privilegio-'+privilegio+'" type="checkbox" disabled="">Acceder '+privilegio+'</label>'
		    res = res + '</div>'
		}else{
			res = '<div class="checkbox list-group-item col-xs-6 margen-privilegios">'
			res = res + '<label><input id="id-modal-privilegio-'+privilegio+'" type="checkbox">Acceder '+privilegio+'</label>'
		    res = res + '</div>'
		}
		
	    return res;
	}
    function cargarDatosRolNinguno(){
        $.ajax({
            type:"GET",
            url:"editarRol.php",
            data:{nombre_usuario:rol}
            }).done(function(dato){
                $('#nombre-rol-recuperado').text(dato['nombre_rol']);
                $('#tiene-materias-recuperado').text("No");
                document.getElementById("btn-editar-rol").disabled = true;
            }).fail(function(error){
                console.log("falla");
                console.log(error);
            });
    }
	function cargarDatosRol(){
		$.ajax({
			type:"GET",
			url:"editarRol.php",
			data:{nombre_usuario:rol}
			}).done(function(dato){
				var data = dato[0];
				$('#nombre-rol-recuperado').text(data['nombre_rol']);
				$('#modal-nombre-rol').val(data['nombre_rol']);
				if(data['puede_tener_materias'] == 1){
					$('#tiene-materias-recuperado').text("si");
					$('#modal-tiene-materias-rol').val(1);
				}else{
					$('#tiene-materias-recuperado').text("No");	
					$('#modal-tiene-materias-rol').val(2);
				}
				privRol = dato;
				marcarPrivilegios(dato);
			}).fail(function(error){
				console.log("falla");
				console.log(error);
			});
		}
	function marcarPrivilegios(datos){
		datos.forEach(function(dato){
			$('#id-privilegio-'+dato['nombre_privilegio']).prop('checked',true);
			$('#id-modal-privilegio-'+dato['nombre_privilegio']).prop('checked',true);
		});
	}


    function cancelarCrearRol(){
        document.getElementById("btn-editar-rol").disabled = false;
    	window.location.href = dominio;
    }

    function modalEditarRol(){
    	var listaAgregar = privilegiosAAgregar();
    	var listaEliminar = privilegiosAEliminar();//
    	var nombreRol = null;
    	var tieneMateria = 0;
    	if($('#modal-tiene-materias-rol').val() == 1){
    		tieneMateria = 1;
    	}
    	if(!verificarCampoVacio($('#modal-nombre-rol').val())) {
    		nombreRol = $('#modal-nombre-rol').val();
    	}
    	
    	$.ajax({
            type:"POST",
            url:"editarRol.php",
            data:{rol_actual:$('#nombre-rol-recuperado').text(),
            	  nombre_rol:nombreRol, 
            	  tiene_materias:tieneMateria, 
            	  lista_eliminar:listaEliminar, 
            	  lista_agregar:listaAgregar}
        }).done(function(datos){
        	window.location.href = dominio;
        }).fail(function(error){
        	console.log(error);
        });

    	// controlar que los datos no este  vacios o nulos
    	// controlar campos con letras numeros caracteres para evitar fallos
    	// cargar los datos de privilegios
    	// mostrar mensages de edicion correcta
    	//limpiar lista
    }
    function verificarCampoVacio(campo){
    	var res = false;
    	if(campo==null || campo==undefined || campo==''){
    		res = true;
    	}
    	return res;
    }

    function privilegiosAAgregar(){
    	var privilegiosMarcados = capturarPrivilegiosMarcados();
    	privRol.forEach(function(dato){
    		var posicion = privilegiosMarcados.indexOf(dato['nombre_privilegio']);
    		if(posicion >= 0){
    			privilegiosMarcados.splice(posicion,1);
    		}
    	});
    	if(privilegiosMarcados.length == 0){
    		privilegiosMarcados=null;
    	}
    	return privilegiosMarcados;
    }

    function privilegiosAEliminar(){
    	var privilegiosNoMarcados = capturarPrivilegiosNoMarcados();
    	var listaRes = [];
    	privRol.forEach(function(dato){
    		var posicion = privilegiosNoMarcados.indexOf(dato['nombre_privilegio']);
    		if(posicion >= 0){
    			listaRes.push(privilegiosNoMarcados[posicion]);
    		}
    	});
    	if(listaRes.length == 0){
    		listaRes=null;
    	}
    	return listaRes;
    }

    function capturarPrivilegiosMarcados(){
    	var listaPrivilegios=[];
    	privilegios.forEach(function(privilegio){
    		if($('#id-modal-privilegio-'+privilegio).prop('checked')){
    			listaPrivilegios.push(privilegio);
    		}
    	});
    	return listaPrivilegios;
    }

    function capturarPrivilegiosNoMarcados(){
    	var listaPrivilegios=[];
    	privilegios.forEach(function(privilegio){
    		if(!$('#id-modal-privilegio-'+privilegio).prop('checked')){
    			listaPrivilegios.push(privilegio);
    		}
    	});
    	return listaPrivilegios;	
    }

    function limpiarPrivilegios(){
    	privilegios.forEach(function(privilegio){
    		$('#id-modal-privilegio-'+privilegio).prop('checked',false);
    	});
    }

    function cargarDatosModal(){
    	limpiarPrivilegios();
    	marcarPrivilegios(privRol);
    	var nom = $('#nombre-rol-recuperado').text();
    	var tieneMateria = $('#modal-tiene-materias-rol').val();
		$('#modal-nombre-rol').val(nom);	
    	$('#modal-tiene-materias-rol').val(tieneMateria);
    }
    cargarVentana();
    obtenerNombreUsuarioUrl();

	setTimeout(function(){
        if(rol == 'Ninguno'){
            cargarDatosRolNinguno();   
        }else{
            cargarDatosRol();
        }
		$('#btn-editar-rol').click(cargarDatosModal);
		$('#actualizar-datos-rol').click(modalEditarRol);
		$('#btn-cancelar').click(cancelarCrearRol);
    },100);
});
