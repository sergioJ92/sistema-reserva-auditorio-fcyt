<?php

const RAIZ = '..';

require_once RAIZ . '/interfazbd/ConexionBD.php';
require_once RAIZ . '/interfazbd/Validador.php';

function obtenerMaterias() {
    
    $consulta = 'SELECT * FROM materia';
    $materias = ConexionBD::getConexion()->query($consulta);
    $resultado = [];
    while ($fila = $materias->fetch_assoc()) {
        array_push($resultado, $fila);
    }
    return $resultado;
}

header('Content-Type: application/json');

echo json_encode(obtenerMaterias());