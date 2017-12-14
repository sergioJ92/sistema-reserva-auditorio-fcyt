<?php
const RAIZ = '../..';

require_once RAIZ . '/interfazbd/ConexionBD.php';
require_once RAIZ. '/interfazbd/ValidacionExcepcion.php';
require_once RAIZ . '/interfazbd/Validador.php';




if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
	 $id = $_POST["id"];

	 $sql = "SELECT r.nombre_rol, ma.nombre_materia, r.puede_tener_materias, ma.codigo_materia FROM usuario as u, rol as r, tiene_materia as tm, materia as ma, tiene_rol as tr WHERE u.nombre_usuario = '".$id."' AND u.nombre_usuario=tm.nombre_usuario AND u.nombre_usuario=tr.nombre_usuario AND ma.codigo_materia=tm.codigo_materia AND tr.nombre_rol=r.nombre_rol";

	 $resultado = ConexionBD::getConexion();
	 $resultado = pg_query($sql);
     $res = [];
     while ($fila = pg_fetch_assoc($resultado)) {
         array_push($res, $fila);
     }
	 echo json_encode($res);

}