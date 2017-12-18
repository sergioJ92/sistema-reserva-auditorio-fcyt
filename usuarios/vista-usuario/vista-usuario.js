 $(document).ready(function () {
	
	var dominio = "http://chr2.hosting.cs.umss.edu.bo/usuarios/";
    
    function mostrarUsuarios(usuarios){							///////
    	var filas = "";
		for (var i = 0; i < usuarios.length; ++i) {
			filas = anadirNuevaFila(usuarios[i],i);
			$('#cuerpo-tabla').append(filas);
			$('#primaryllave-'+i).hide();
		}
	}

	function anadirNuevaFila(data,id){							///////
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

	function redireccionarCrearUsuario(){							//////
		window.location.href = dominio + "crear-usuario";	 	
	}

	function redireccionarEditar(llave){							/////
		var dato = 'vista-editar/?next='+llave;
		window.location.href = dominio + dato;
	}

	function cambiarEstadoUsuario(dato, id, estado){			///////
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
		    }).fail(function(error){

		    });	
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
    	});
    	//editar usuario
        $("body").on("click",".edit-item",function(){
    		var id = $(this).parent("td").data("id");
    		var llave = document.getElementById("primary"+id).innerHTML;
    		redireccionarEditar(llave);
		});
	},1000)
});