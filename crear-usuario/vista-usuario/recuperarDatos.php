<?php
const RAIZ = '../..';

require_once RAIZ . '/interfazbd/ConexionBD.php';
require_once RAIZ. '/interfazbd/ValidacionExcepcion.php';
require_once RAIZ . '/interfazbd/Validador.php';




if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
	 $id  = $_POST["id"];

	 $sql = "SELECT * FROM usuario u, telefono_usuario tu, correo_usuario cu ,rol r, tiene_materia tm WHERE u.nombre_usuario = '".$id."' AND u.nombre_usuario=tu.nombre_usuario AND u.nombre_usuario=cu.nombre_usuario AND u.nombre_usuario=tm.nombre_usuario AND u.nombre_rol=r.nombre_rol";

	 $resultado = ConexionBD::getConexion();
	 $resultado = pg_query($sql);
     $res = [];
     while ($fila = pg_fetch_assoc($resultado)) {
         array_push($res, $fila);
     }
	 echo json_encode($res);
}
?>