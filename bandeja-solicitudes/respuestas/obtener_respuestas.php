<?php

const RAIZ = '../.';

require_once RAIZ.'./interfazbd/ConexionBD.php';

function obtenerRespuestas() {
    
    $consulta = 'SELECT aceptado, mensaje, hora_inicio, hora_fin, responsable, evento, correo, telefono FROM respuesta r, solicitud_reserva s, correo c, telefono t WHERE r.id_solicitud_reserva=s.id_solicitud_reserva AND s.id_solicitud_reserva= c.id_solicitud_reserva AND s.id_solicitud_reserva=t.id_solicitud_reserva';
    $resultadoConsulta = ConexionBD::getConexion()->query($consulta);
    $respuesta = [];
    while ($fila = $resultadoConsulta->fetch_assoc()) {
        $respuesta []= $fila;
    }
    return $respuesta;
}

header('Content-Type: application/json');

echo json_encode(obtenerRespuestas());