$(document).ready(function () {

	var dominio = "http://localhost/sistema-reserva-auditorio-fcyt/roles/";
	function cargarRoles(roles){
		var filas = "";
		for (var i = 0; i < roles.length; ++i) {
			filas = anadirNuevaFila(roles[i],i);
			$('#cuerpo-tabla').append(filas);
			$('#primaryllave-'+i).hide();
		}
	}

	function anadirNuevaFila(data,id){
		var estado = 'No';
		if(data['puede_tener_materias'] == 1){
			estado = "Si";
		}
		var fila = '';
		fila = fila + '<tr>';
	  	fila = fila + '<td data-id="rol-'+id+'">'+data['nombre_rol']+'</td>';
	  	fila = fila + '<td data-id="tiene-materias'+id+'">'+estado+'</td>';
	  	fila = fila + '<td data-id="llave-'+id+'">';
        fila = fila + '<button data-toggle="modal" data-target="#edit-item" class="btn btn-primary edit-item">Acceder</button> ';
        //fila = fila + '<button id="boton-eliminar-llave-'+id+'" class="btn btn-danger remove-item">Cambiar estado</button>';
        fila = fila + '</td>';
        fila = fila + '<td id="primaryllave-'+id+'">'+data['nombre_rol']+'</td>';
	  	fila = fila + '</tr>';
	  	return fila;
	}

	function redireccionarCrearRol(){
		var dom = "crear-roles/index.php";
		window.location.href = dominio + dom;
	}

	function redireccionarEditar(llave){
		var dato = 'editar-rol/?next='+llave;
		window.location.href = dominio + dato;
	}


	ajaxGet('recuperarDatos.php',{},cargarRoles);
	$('#btn-crear-nuevo-rol').click(redireccionarCrearRol);
    $("body").on("click",".edit-item",function(){
    	var id = $(this).parent("td").data("id");
    	var llave = document.getElementById("primary"+id).innerHTML;
    	console.log(llave);
    	redireccionarEditar(llave);
    	//cargarVentana();
    	//var tablaDatos = recuperarDatos(llave);
	});
});