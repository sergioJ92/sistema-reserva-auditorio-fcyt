<?php
const RAIZ = '../..';

require_once RAIZ . '/interfazbd/ConexionBD.php';
require_once RAIZ. '/interfazbd/ValidacionExcepcion.php';
require_once RAIZ . '/interfazbd/Validador.php';




if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
	 $id = $_POST["id"];
	 $sql = "SELECT u.nombres, u.apellidos, u.activo, u.nombre_usuario, cu.correo, tu.telefono, tr.nombre_rol, r.puede_tener_materias FROM usuario u, telefono_usuario tu, correo_usuario cu, tiene_rol tr, rol r WHERE u.nombre_usuario = '".$id."' AND u.nombre_usuario=tu.nombre_usuario AND u.nombre_usuario=cu.nombre_usuario AND u.nombre_usuario=tr.nombre_usuario AND r.nombre_rol=tr.nombre_rol";

	// $sql = "SELECT * FROM usuario u, telefono_usuario tu, correo_usuario cu ,rol r, tiene_materia tm, materia ma WHERE u.nombre_usuario = '".$id."' AND u.nombre_usuario=tu.nombre_usuario AND u.nombre_usuario=cu.nombre_usuario AND u.nombre_usuario=tm.nombre_usuario AND u.nombre_rol=r.nombre_rol AND ma.codigo_materia=tm.codigo_materia";

	 $resultado = ConexionBD::getConexion();
	 $resultado = pg_query($sql);
     $res = [];
     while ($fila = pg_fetch_assoc($resultado)) {
         array_push($res, $fila);
     }
	 echo json_encode($res);
}
?>