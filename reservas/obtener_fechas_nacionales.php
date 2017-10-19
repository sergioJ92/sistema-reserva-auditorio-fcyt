<?php

const RAIZ = '..';

require_once RAIZ.'/interfazbd/ConexionBD.php';

function obtenerFechasNacionales() {
    
    $consulta = 'SELECT * FROM fechas_nacionales';
    $respuesta = ConexionBD::getConexion()->query($consulta);
    $resultado = [];
    while ($fila = $respuesta->fetch_assoc()) {
        array_push($resultado, $fila);
    }
    return $resultado;
}

header('Content-Type: application/json');
echo json_encode(obtenerFechasNacionales());