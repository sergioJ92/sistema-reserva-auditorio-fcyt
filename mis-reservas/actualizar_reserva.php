<?php

const RAIZ = '..';

require_once RAIZ.'/interfazbd/ConexionBD.php';
require_once RAIZ.'/interfazbd/Validador.php';
require_once RAIZ.'/interfazbd/ValidacionExcepcion.php';

function verificarModificarReserva($idReserva) {
    
    $consulta = "SELECT * FROM reserva WHERE id_reserva = '$idReserva'";
    $resConsulta = ConexionBD::getConexion()->query($consulta);
    if ($resConsulta->num_rows == 1) {
        $reserva = $resConsulta->fetch_assoc();
        $fecha = $reserva['fecha'];
        $horaInicio = $reserva['hora_inicio'];
        if (Validador::fechaEsMenorIgual("$fecha $horaInicio", date('Y-m-d H:m'))) {
            throw new ValidacionExcepcion('No se puede modificar una reserva que ya fue consumida');
        }
    }
    else {
        throw new ValidacionExcepcion('No existe la reserva que desea modificar');
    }
}

function actualizarReservaAcademica($idReserva, $codigoMateria, $idAsunto) {
   
    verificarModificarReserva($idReserva);
    $consulta = "UPDATE reserva_academica SET codigo_materia='$codigoMateria', id_asunto='$idAsunto' WHERE id_reserva='$idReserva'";
    if (ConexionBD::getConexion()->query($consulta)) {
        return true;
    }else{
        return false;
    }
    
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    header('Content-Type: application/json');
    $entrada = $_POST;
    
    $idReserva=Validador::desinfectarEntrada($entrada['id_reserva']);
    $codigoMateria=Validador::desinfectarEntrada($entrada['nom_materia']);
    $asunto=Validador::desinfectarEntrada($entrada['asunto']);
    
    try {
        
        $actualizado= actualizarReservaAcademica($idReserva, $codigoMateria, $asunto);
        echo json_encode(["exito" => $actualizado]);   
        
    } catch (ValidacionExcepcion $ex) {
        
        echo json_encode(['exito' => false, 'error' => $ex->getMessage()]);
    }
} 
else {
    header('Location: index.php');
}
