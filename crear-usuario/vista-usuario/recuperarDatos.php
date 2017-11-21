<?php
const RAIZ = '../..';

require_once RAIZ . '/interfazbd/ConexionBD.php';
require_once RAIZ. '/interfazbd/ValidacionExcepcion.php';
require_once RAIZ . '/interfazbd/Validador.php';


 $id  = $_POST["id"];

 $sql = "SELECT * FROM usuario u, telefono_usuario tu, correo_usuario cu  WHERE u.nombre_usuario = '".$id."AND u.nombre_usuario=tu.nombre_usuario AND u.nombre_usuario=u.nombre_usuario";

 ConexionBD::getConexion()->query($sql);

 $result = $mysqli->query($sql);

$resultado = [];
    while ($fila = $materias->fetch_assoc()) {
        array_push($resultado, $fila);
    }


 echo json_encode([$id]);

?>