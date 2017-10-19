<?php

const RAIZ = '..';

require_once RAIZ.'/interfazbd/ConexionBD.php';
require_once RAIZ.'/interfazbd/Validador.php';

function eliminarReserva($fecha, $horaInicio, $horaFin) {
    
    ConexionBD::conectar();
    $consulta = "DELETE FROM reserva WHERE fecha='$fecha' AND hora_inicio='$horaInicio' AND hora_fin='$horaFin'";
    return ConexionBD::getConexion()->query($consulta);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    header('Content-Type: application/json');
    $entrada = $_POST;
    
    $fecha=Validador::desinfectarEntrada($entrada['fecha']);
    $horaInicio=Validador::desinfectarEntrada($entrada['hora_inicio']);
    $horaFin=Validador::desinfectarEntrada($entrada['hora_fin']);
   
    echo json_encode(eliminarReserva($fecha, $horaInicio, $horaFin));
} 
else {
    header('Location: index.php');
}
