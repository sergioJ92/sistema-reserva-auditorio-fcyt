<?php

const RAIZ = '..';

require_once RAIZ . '/interfazbd/ConexionBD.php';
require_once RAIZ . '/lib/sesion_store.php';
header('Content-Type: application/json');

function obtenerReservasAcademicas($usuario) {
    $consulta = 'SELECT ASU.asunto, RESER.fecha,ACADE.id_asunto, RESER.hora_inicio,RESER.id_reserva, RESER.hora_fin, MAT.nombre_materia, CRON.anio, CRON.gestion ';
    $consulta .= 'FROM usuario AS USER, responsable_reserva AS RESP, asunto AS ASU, reserva AS RESER, reserva_academica AS ACADE, materia AS MAT, actividad AS ACTI, contenido AS CONT, cronograma_academico AS CRON ';
    $consulta .= "WHERE USER.nombre_usuario = '$usuario' AND CONT.id_contenido = ACTI.id_contenido AND CONT.anio = CRON.anio AND CONT.gestion = CRON.gestion AND ACTI.id_contenido = ACADE.id_contenido ";
    $consulta .= 'AND USER.nombre_usuario = RESP.nombre_usuario AND RESP.id_reserva = ACADE.id_reserva AND ACADE.id_reserva = RESER.id_reserva AND ACADE.id_asunto = ASU.id_asunto AND ACADE.codigo_materia = MAT.codigo_materia ';
    $consulta .= 'ORDER BY RESER.fecha';
    
    $resultadoConsulta = ConexionBD::getConexion()->query($consulta);
    
    $resultado = [];
    while ($fila = $resultadoConsulta->fetch_assoc()) {
        $resultado []= $fila;
    }
    return $resultado;
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombreUsuario = $_SESSION['usuario'];
    echo json_encode(obtenerReservasAcademicas($nombreUsuario));
} 
else {
    header('Location: index.php');
}