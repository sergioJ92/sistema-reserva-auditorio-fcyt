<?php

const RAIZ = '..';

require_once RAIZ.'/interfazbd/ConexionBD.php';
require_once RAIZ.'/interfazbd/Validador.php';

function obtenerReservasAcademicas($anio, $gestion, $ambiente) {
    
    $consulta = "SELECT r.fecha, r.hora_inicio, r.hora_fin, r.evento, asu.asunto, ma.nombre_materia materia, u.nombres responsable FROM reserva r, reserva_academica ra, actividad a, contenido c, cronograma_academico ca, asunto asu, materia ma, responsable_reserva rr, usuario u WHERE r.id_reserva = ra.id_reserva AND a.id_contenido = ra.id_contenido AND c.id_contenido = a.id_contenido AND c.anio = ca.anio AND asu.id_asunto = ra.id_asunto AND c.gestion = ca.gestion AND ma.codigo_materia = ra.codigo_materia AND ra.id_reserva = rr.id_reserva AND rr.nombre_usuario = u.nombre_usuario  AND ca.anio = '$anio' AND ca.gestion = '$gestion' AND id_ambiente='$ambiente'";
    $resConsulta = pg_query(ConexionBD::getConexion(), $consulta);
    $resultado = [];
    while ($fila = pg_fetch_assoc($resConsulta)) {
        $resultado []= $fila;
    }
    return $resultado;
}

function obtenerReservasSolicitadas($ambiente) {
    
    $consulta = "SELECT r.*, rs.* FROM reserva r, reserva_solicitada rs WHERE r.id_reserva = rs.id_reserva AND id_ambiente='$ambiente'";
    $resConsulta = pg_query(ConexionBD::getConexion(), $consulta);
    $resultado = [];
    while ($fila = pg_fetch_assoc($resConsulta)) {
        $resultado []= $fila;
    }
    return $resultado;
}

header('Content-Type: application/json');

$entrada = $_REQUEST;
$anio = Validador::desinfectarEntrada($entrada['anio']);
$gestion = Validador::desinfectarEntrada($entrada['gestion']);
$ambiente = Validador::desinfectarEntrada($entrada['ambiente']);

echo json_encode(array(
    'academicas' => obtenerReservasAcademicas($anio, $gestion, $ambiente), 
    'solicitadas' => obtenerReservasSolicitadas($ambiente)));
