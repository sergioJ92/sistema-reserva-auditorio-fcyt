<?php

const RAIZ = './..';
include_once RAIZ.'/interfazbd/ConexionBD.php';
include_once RAIZ.'/interfazbd/Validador.php';

function obtenerRespuesta($codigo_reserva) {
    
    $consulta = "SELECT s.fecha, s.hora_inicio, s.hora_fin, r.aceptado FROM solicitud_reserva s, respuesta r WHERE s.id_solicitud_reserva='$codigo_reserva' AND s.id_solicitud_reserva=r.id_solicitud_reserva";
    $respuesta= ConexionBD::getConexion()->query($consulta);
    if(count($respuesta)>0 && ($resultado=$respuesta->fetch_assoc())!=null){
        return $resultado;
    }
    else{
        $consulta = "SELECT s.fecha, s.hora_inicio, s.hora_fin FROM solicitud_reserva s WHERE s.id_solicitud_reserva='$codigo_reserva'";
        $respuesta = ConexionBD::getConexion()->query($consulta);
        if (count($respuesta) > 0) {
            return $respuesta->fetch_assoc();
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
