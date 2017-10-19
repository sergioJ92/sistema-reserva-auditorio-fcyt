<?php

const RAIZ = '..';

include_once RAIZ . '/lib/sesion_store.php';
require_once RAIZ . '/interfazbd/ConexionBD.php';

function obtenerMateriasUsuario($nombreUsuario) {
    
    $obtenerMaterias = 'SELECT m.* FROM materia m, tiene_materia tm';
    $obtenerMaterias .= " WHERE tm.nombre_usuario = '$nombreUsuario' AND tm.codigo_materia = m.codigo_materia";
    $materias = ConexionBD::getConexion()->query($obtenerMaterias);
    $resultado = [];
    while ($fila = $materias->fetch_assoc()) {
        array_push($resultado, $fila);
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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    header('Content-Type: application/json');
    $nombreUsuario = $_SESSION['usuario'];
    
    if (!empty($nombreUsuario)) {
        $utiles = array(
            'nombre_usuario' => $nombreUsuario,
            'materias' => obtenerMateriasUsuario($nombreUsuario), 
            'asuntos' => obtenerAsuntos());

        echo json_encode($utiles);
    }
    else {
        echo json_encode(array('error' => 'nombre de usuario nulo'));
    }
}