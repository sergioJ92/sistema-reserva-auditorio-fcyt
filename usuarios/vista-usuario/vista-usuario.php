<?php 
//error_reporting(0);
const RAIZ = '../..';

require_once RAIZ . '/interfazbd/ConexionBD.php';
require_once RAIZ . '/interfazbd/Validador.php';

function obtenerDatosUsuario(){

	$consulta = "SELECT u.nombres, u.apellidos, tr.nombre_rol, u.nombre_usuario, u.activo FROM usuario as u, tiene_rol as tr where u.nombre_usuario=tr.nombre_usuario";
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

?>

