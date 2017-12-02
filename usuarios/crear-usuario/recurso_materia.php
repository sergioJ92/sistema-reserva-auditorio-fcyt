<?php

const RAIZ = '../..';

require_once RAIZ . '/interfazbd/ConexionBD.php';
require_once RAIZ . '/interfazbd/Validador.php';

function obtenerMaterias() {
    
    $consulta = 'SELECT * FROM materia';
    $materias = pg_query(ConexionBD::getConexion(), $consulta);
    $resultado = [];
    while ($fila = pg_fetch_assoc($materias)) {
        array_push($resultado, $fila);
    }
    return $resultado;
}

header('Content-Type: application/json');

echo json_encode(obtenerMaterias());