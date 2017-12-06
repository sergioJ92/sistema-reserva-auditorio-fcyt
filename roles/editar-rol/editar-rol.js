$(document).ready(function () {
	var dominio = "http://localhost/sistema-reserva-auditorio-fcyt/roles/"

    function cancelarCrearRol(){
    	window.location.href = dominio;
    }

	setTimeout(function(){

		$('#btn-editar-rol').click(crearNuevoRol);
		$('#btn-cancelar').click(cancelarCrearRol);
    },1000);
});
