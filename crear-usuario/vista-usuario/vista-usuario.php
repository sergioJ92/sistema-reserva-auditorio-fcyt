<?php 
//error_reporting(0);
const RAIZ = '../..';

require_once RAIZ . '/interfazbd/ConexionBD.php';
require_once RAIZ . '/interfazbd/Validador.php';

function obtenerDatosUsuario(){

	$consulta = "SELECT nombres, apellidos, nombre_rol, nombre_usuario FROM usuario";
	$resultado = ConexionBD::getConexion();
    $resultado = pg_query($consulta);
    $resultadoLista = [];
    while ($fila = pg_fetch_assoc($resultado)) {
        array_push($resultadoLista, $fila);
    }
    return $resultadoLista;

}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    
    header('Content-Type: application/json');
	$resConsulta = obtenerDatosUsuario();
	
    echo json_encode($resConsulta);

}

function llenarDatos(){
    $consulta = "SELECT nombres, apellidos, nombre_rol, nombre_usuario FROM usuario";
    $resultado = ConexionBD::getConexion()->query($consulta);
    $resultadoLista = [];
    while ($fila = $resultado->fetch_assoc()) {
        array_push($resultadoLista, $fila);
    }

    return $resultadoLista;
}

?>

