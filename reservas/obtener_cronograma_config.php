<?php

const RAIZ = '..';

require_once RAIZ.'/interfazbd/ConexionBD.php';
require_once RAIZ.'/interfazbd/Validador.php';

$entrada = $_REQUEST;

$anio = Validador::desinfectarEntrada($entrada['anio']);
$gestion = Validador::desinfectarEntrada($entrada['gestion']);

header('Content-Type: application/json');

function obtenerCronogramaYConfig($anio, $gestion) {
    
    $consulta = "SELECT fecha_hora_inicio, fecha_hora_fin,";
    $consulta .= " duracion_periodo, hora_inicio_jornada, hora_fin_jornada, hora_fin_sabado";
    $consulta .= " FROM configuracion c, cronograma_academico ca";
    $consulta .= " WHERE ca.anio='$anio' AND ca.gestion='$gestion' AND c.anio = ca.anio AND c.gestion = ca.gestion";
    $resConsulta = ConexionBD::getConexion()->query($consulta);
    if ($resConsulta) {
        return $resConsulta->fetch_assoc();
    }
    return null;
}

if (!empty($anio) && !empty($gestion)) {
    
    echo json_encode(obtenerCronogramaYConfig($anio, $gestion));
}