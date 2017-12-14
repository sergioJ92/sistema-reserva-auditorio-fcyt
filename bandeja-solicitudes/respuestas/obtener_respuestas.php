<?php

const RAIZ = '../.';

require_once RAIZ.'./interfazbd/ConexionBD.php';

function obtenerRespuestas() {
    
    $consulta = 'SELECT aceptado, mensaje, hora_inicio, hora_fin, s.responsable, evento, correo1, telefono1 ';
    $consulta .= 'FROM respuesta as r, solicitud_reserva as s, correo as c, telefono as t ';
    $consulta .= 'WHERE r.id_solicitud_reserva=s.id_solicitud_reserva AND s.id_solicitud_reserva= c.id_solicitud_reserva AND s.id_solicitud_reserva=t.id_solicitud_reserva';

    $resultadoConsulta = pg_query(ConexionBD::getConexion(), $consulta);
    $respuesta = [];
    while ($fila = pg_fetch_assoc($resultadoConsulta)) {
        $respuesta []= $fila;
    }
    return $respuesta;
}

header('Content-Type: application/json');

echo json_encode(obtenerRespuestas());