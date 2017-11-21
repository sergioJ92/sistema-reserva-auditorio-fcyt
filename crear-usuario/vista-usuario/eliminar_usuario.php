<?php 
const RAIZ = '../..';

require_once RAIZ . '/interfazbd/ConexionBD.php';
require_once RAIZ.'/interfazbd/ValidacionExcepcion.php';
require_once RAIZ . '/interfazbd/Validador.php';

$id = $_POST["id"];

$consulta = "DELETE FROM usuario WHERE nombre_usuario='".$id."'";

$resultado = ConexionBD::getConexion();
$resultado = pg_query($consulta);

echo json_encode([$id]);


	
