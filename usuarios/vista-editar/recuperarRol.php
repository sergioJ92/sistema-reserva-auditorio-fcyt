<?php
const RAIZ = '../..';

require_once RAIZ . '/interfazbd/ConexionBD.php';
require_once RAIZ. '/interfazbd/ValidacionExcepcion.php';
require_once RAIZ . '/interfazbd/Validador.php';




if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
	 $id = $_POST["id"];

	 $sql = "SELECT r.nombre_rol, ma.nombre_materia FROM usuario u, rol r, tiene_materia tm, materia ma WHERE u.nombre_usuario = '".$id."' AND u.nombre_usuario=tm.nombre_usuario AND u.nombre_rol=r.nombre_rol AND ma.codigo_materia=tm.codigo_materia";

	 $resultado = ConexionBD::getConexion();
	 $resultado = pg_query($sql);
     $res = [];
     while ($fila = pg_fetch_assoc($resultado)) {
         array_push($res, $fila);
     }
	 echo json_encode($res);

}