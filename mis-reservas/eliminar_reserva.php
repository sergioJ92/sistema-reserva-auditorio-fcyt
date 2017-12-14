<?php

const RAIZ = '..';

require_once RAIZ.'/interfazbd/ConexionBD.php';
require_once RAIZ.'/interfazbd/Validador.php';

function eliminarReserva($fecha, $horaInicio, $horaFin, $idAmbiente) {
    
    ConexionBD::conectar();
    $consulta = "DELETE FROM reserva WHERE fecha='$fecha' AND hora_inicio='$horaInicio' AND hora_fin='$horaFin' AND id_ambiente='$idAmbiente'";
    return pg_query(ConexionBD::getConexion(),$consulta);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    header('Content-Type: application/json');
    $entrada = $_POST;
    
    $fecha=Validador::desinfectarEntrada($entrada['fecha']);
    $horaInicio=Validador::desinfectarEntrada($entrada['hora_inicio']);
    $horaFin=Validador::desinfectarEntrada($entrada['hora_fin']);
    $idAmbiente=Validador::desinfectarEntrada($entrada['ambiente']);
   
    echo json_encode(eliminarReserva($fecha, $horaInicio, $horaFin, $idAmbiente));
} 
else {
    header('Location: index.php');
}
