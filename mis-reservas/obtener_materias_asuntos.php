<?php
const RAIZ = '..';

require_once RAIZ . '/interfazbd/ConexionBD.php';
require_once RAIZ . '/interfazbd/Validador.php';
require_once RAIZ . '/lib/sesion_store.php';

function obtenerMaterias($nombreUsuario) {
    $consulta = "SELECT MATER.nombre_materia, MATER.codigo_materia FROM usuario AS us, tiene_materia AS LIST,materia AS MATER WHERE ";
    $consulta .= "us.nombre_usuario = ";
    $consulta .= " '";
    $consulta .= $nombreUsuario;
    $consulta .= "' ";
    $consulta .= " AND us.nombre_usuario = LIST.nombre_usuario AND ";
    $consulta .= " LIST.codigo_materia = MATER.codigo_materia";
    
    $resultadoConsulta = pg_query(ConexionBD::getConexion(), $consulta);
    
    $resultado = [];
    while ($fila = pg_fetch_assoc($resultadoConsulta)) {
        $resultado []= $fila;
    }
    return $resultado;
}

function obtenerAsuntos() {
    
    $obtenerAsuntos = 'SELECT * FROM asunto';
    $asuntos = pg_query(ConexionBD::getConexion(),$obtenerAsuntos);
    $resultado = [];
    while ($fila = pg_fetch_assoc($asuntos)) {
        array_push($resultado, $fila);
    }
    return $resultado;
}

header('Content-Type: application/json');
$nombreUsuario = $_SESSION['usuario'];
if (!empty($nombreUsuario)) {
    $datos = [
        'materias' => obtenerMaterias($nombreUsuario),
        'asuntos' => obtenerAsuntos()
    ];
    echo json_encode($datos);
} else {
    echo json_encode(null);
}