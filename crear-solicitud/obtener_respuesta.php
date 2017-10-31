<?php

const RAIZ = './..';
include_once RAIZ.'/interfazbd/ConexionBD.php';
include_once RAIZ.'/interfazbd/Validador.php';

function obtenerRespuesta($codigo_reserva) {
    
    $consulta = "SELECT s.fecha, s.hora_inicio, s.hora_fin, r.aceptado FROM solicitud_reserva s, respuesta r WHERE s.id_solicitud_reserva='$codigo_reserva' AND s.id_solicitud_reserva=r.id_solicitud_reserva";
    $respuesta= pg_query(ConexionBD::getConexion(), $consulta);
    if(count($respuesta)>0 && ($resultado=pg_fetch_assoc($respuesta))!=null){
        return $resultado;
    }
    else{
        $consulta = "SELECT s.fecha, s.hora_inicio, s.hora_fin FROM solicitud_reserva s WHERE s.id_solicitud_reserva='$codigo_reserva'";
        $respuesta = pg_query(ConexionBD::getConexion(), $consulta);
        if (count($respuesta) > 0) {
            return pg_fetch_assoc($respuesta);
        }
        else{
            return false;
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    header('Content-Type: application/json');
    $conectado = ConexionBD::conectar();
    $entrada = $_POST;
   
    $codigo_reserva= Validador::desinfectarEntrada($entrada['codigo_reserva']);
    $codigo_reserva /= 177;
    echo json_encode(obtenerRespuesta($codigo_reserva));
    
} else {
    header('Location: index.php');
}
