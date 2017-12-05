<?php 
const RAIZ = '../..';

require_once RAIZ . '/interfazbd/ConexionBD.php';
require_once RAIZ.'/interfazbd/ValidacionExcepcion.php';
require_once RAIZ . '/interfazbd/Validador.php';



function obtenerDatos($nombre_usuario){
	$sql = "SELECT activo, nombre_usuario FROM usuario WHERE nombre_usuario='".$nombre_usuario."'";
	$resultado = ConexionBD::getConexion();
	$resultado = pg_query($sql);
	$estado = pg_fetch_assoc($resultado);
	return $estado;	
}




if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$datos = $_POST;	
	$estado = obtenerDatos($datos['id']);
	if($estado['activo'] == 't'){
		$consulta = "UPDATE usuario SET activo='f' WHERE nombre_usuario='".$datos['id']."'";		
	}else{
		$consulta = "UPDATE usuario SET activo='t' WHERE nombre_usuario='".$datos['id']."'";	
	}

	$res = ConexionBD::getConexion();
	$res = pg_query($consulta);
	$e = pg_fetch_assoc($res);
	
	$estado=obtenerDatos($datos['id']);

	echo json_encode($estado);	
}
/*
$consulta = "UPDATE usuario SET activo='".$datos['estado_activo']."' WHERE nombre_usuario='".$datos['id']."'";

$resultado = ConexionBD::getConexion();
$resultado = pg_query($consulta);


echo json_encode($datos['id']);
*/
	
