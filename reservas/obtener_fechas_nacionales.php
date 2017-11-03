<?php

const RAIZ = '..';

require_once RAIZ.'/interfazbd/ConexionBD.php';

function obtenerFechasNacionales() {
    
    $consulta = 'SELECT * FROM fechas_nacionales';
    $respuesta = pg_query(ConexionBD::getConexion(), $consulta);
    $resultado = [];
    while ($fila = pg_fetch_assoc($respuesta)) {
        array_push($resultado, $fila);
    }
    return $resultado;
}

header('Content-Type: application/json');
echo json_encode(obtenerFechasNacionales());