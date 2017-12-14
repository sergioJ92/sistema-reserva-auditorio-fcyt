<?php
const RAIZ = '../..';

require_once RAIZ . '/interfazbd/ConexionBD.php';
require_once RAIZ. '/interfazbd/ValidacionExcepcion.php';
require_once RAIZ . '/interfazbd/Validador.php';

function compararDatos($actual,$contrasena,$reContrasena,$id){
	validarDatosUsuario($actual,$contrasena,$reContrasena);
	$res = ConexionBD::getConexion();
	$sql = "select contrasenia from usuario where nombre_usuario='".$id."'";
	$res = pg_query($sql);
	$fila = pg_fetch_assoc($res);
	if (!password_verify($actual, $fila['contrasenia'])) {
        throw new ValidacionExcepcion('La contraseña actual es incorrecta');
    }
    if(strcmp($contrasena,$reContrasena) != 0){
    	throw new ValidacionExcepcion('Verifique que la nueva contraseña sea correcta');
    }
}

function validarDatosUsuario($actual,$contrasena,$reContrasena){
	Validador::revisarCampoVacio($actual, 'Contraseña actual');
	Validador::revisarCampoVacio($contrasena, 'Contraseña');
	Validador::revisarCampoVacio($reContrasena, 'Reingresar contraseña');

	//falta comparacion de la contracena actual
	if (strcmp($contrasena, $reContrasena) != 0) {
        throw new ValidacionExcepcion('El campo de Reingrsar contraseña '
                . 'no empareja con la contraseña');
    }
}
function guardarDatos($contrasena,$nombreUsuario){
	$res = array('exito' => false, 'mensaje' => "No se pudo guardar la contraseña" );
	$hashContraseña = password_hash($contrasena, PASSWORD_DEFAULT);
	if (!password_verify($contrasena, $hashContraseña)) {
        throw new ValidacionExcepcion('La contraseña actual es incorrecta');
    }

    $sql = "UPDATE usuario SET contrasenia='".$hashContraseña."' WHERE nombre_usuario = '".$nombreUsuario."'";
    $conn = ConexionBD::getConexion();

    if(pg_query($conn, $sql)){
    	$res = array('exito' => true);
    }

    return $res;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
     $entrada = $_POST ;
	 $actual = Validador::desinfectarEntrada($entrada['actual']);
	 $contrasena = Validador::desinfectarEntrada($entrada['contrasena']);
	 $reContrasena = Validador::desinfectarEntrada($entrada['reContrasena']);
	 compararDatos($actual,$contrasena,$reContrasena,$entrada['id']);
	 $res = guardarDatos($contrasena,$entrada['id']);
	 echo json_encode($res);
}