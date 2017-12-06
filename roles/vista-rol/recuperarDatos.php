<?php 
//error_reporting(0);
const RAIZ = '../..';

require_once RAIZ . '/interfazbd/ConexionBD.php';
require_once RAIZ . '/interfazbd/Validador.php';

function obtenerDatosRoles(){

	$consulta = "SELECT * from rol";
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
	$resConsulta = obtenerDatosRoles();

    echo json_encode($resConsulta);

}   

?>