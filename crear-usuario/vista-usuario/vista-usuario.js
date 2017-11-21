$(document).ready(function () {
	
	var dominio = "http://localhost/sistema-reserva-auditorio-fcyt/"
    
    function mostrarUsuarios(usuarios){
    	var filas = "";
		for (var i = 0; i < usuarios.length; ++i) {
			filas = filas + anadirNuevaFila(usuarios[i],i);
		}
		$('#cuerpo-tabla').html(filas);
		
	}

	function anadirNuevaFila(data,id){
	
		var fila = '';
		fila = fila + '<tr>';
	  	fila = fila + '<td data-id="nombre-'+id+'">'+data['nombres']+'</td>';
	  	fila = fila + '<td data-id="apeleidos'+id+'">'+data['apellidos']+'</td>';
	  	fila = fila + '<td>'+data['nombre_rol']+'</td>';
	  	fila = fila + '<td data-id="llave-'+id+'">';
        fila = fila + '<button data-toggle="modal" data-target="#edit-item" class="btn btn-primary edit-item">Editar</button> ';
        fila = fila + '<button id="boton-eliminar-llave-'+id+'" class="btn btn-danger remove-item">Eliminar</button>';
        fila = fila + '</td>';
        fila = fila + '<td id="primaryllave-'+id+'">'+data['nombre_usuario']+'</td>';
	  	fila = fila + '</tr>';
	  	return fila;
	}

	function redireccionarCrearUsuario(){
		window.location.href = dominio + "crear-usuario/";	 	
	}

	function editarUsuario(){

	}

	function recuperarDatos(llave){
		var res = null;
		$.ajax({
			dataType: 'json',
			type: 'POST',
			url:'recuperarDatos.php',
			data:{id:llave},
		}).done(function(data){
			debugger;
			console.log(termine);
		});
		return res;
	}

	function eliminarUsuario(dato,columna){
    	debugger;
    	$.ajax({
//		    dataType: 'json',
		    type:'POST',
		    url:'eliminar_usuario.php',
		    data:{id:dato}
		}).done(function(data){
		  	columna.remove();
		    console.log(data);
		    console.log("ya esta eliminado: " + data);
		    //getPageData();
		    });	
	}

	function parcearIdTag(id){
	var cadena = id.split('-');
	return cadena[1];
	}
	//function mostrarAlerta(tipoAlerta,mensaje){
	//	$('#contenedor-msg').empty().append(crearAlerta(tipo, mensaje));
	//}

	ajaxGet('vista-usuario.php',{},mostrarUsuarios);

	setTimeout(function(){
		$('#btn-crear-nuevo-usuario').click(redireccionarCrearUsuario);
    	$('#botonEditar').click(editarUsuario);
    	
    	//eliminar usuario
    	$("body").on("click",".remove-item",function(){
   			
    		var id = $(this).parent("td").data('id');
    		var columna = $(this).parents("tr");
    		var dato = document.getElementById("primary"+id).innerHTML;
    		eliminarUsuario(dato,columna)
    		//mostrarAlerta('alert-success',"Se elimino el usuariocorrectamente")
    	});
    	//editar usuario
    	$("body").on("click",".edit-item",function(){
    		
    		var id = $(this).parent("td").data("id");
    		//var nombre = $(this).parent("td").prev("td").prev("td").prev("td").text();
    		//var apellido = $(this).parent("td").prev("td").prev("td").text();
    		//var rol = $(this).parent("td").prev("td").text();
    		var llave = document.getElementById("primary"+id).innerHTML;
    		var tablaDatos = recuperarDatos(llave);
    		console.log(llave);

    		debugger;
    		$("#nombres").val();
    		$("#apellidos").val();
    		$("#telefonos").val();
    		$("#correo").val();
		});
	},1000)
});



     