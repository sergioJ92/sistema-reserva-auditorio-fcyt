<?php
const RAIZ = '..';

require_once RAIZ . '/interfazbd/ConexionBD.php';
require_once RAIZ . '/interfazbd/Validador.php';
require_once RAIZ . '/lib/sesion_store.php';

function obtenerMaterias($nombreUsuario) {
    $consulta = "SELECT MATER.nombre_materia, MATER.codigo_materia FROM usuario AS USER, tiene_materia AS LIST,materia AS MATER WHERE ";
    $consulta .= "USER.nombre_usuario = ";
    $consulta .= " '";
    $consulta .= $nombreUsuario;
    $consulta .= "' ";
    $consulta .= " AND USER.nombre_usuario = LIST.nombre_usuario AND ";
    $consulta .= " LIST.codigo_materia = MATER.codigo_materia";
    
    $resultadoConsulta = ConexionBD::getConexion()->query($consulta);
    
    $resultado = [];
    while ($fila = $resultadoConsulta->fetch_assoc()) {
        $resultado []= $fila;
    }
    return $resultado;
}

function obtenerAsuntos() {
    
    $obtenerAsuntos = 'SELECT * FROM asunto';
    $asuntos = ConexionBD::getConexion()->query($obtenerAsuntos);
    $resultado = [];
    while ($fila = $asuntos->fetch_assoc()) {
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