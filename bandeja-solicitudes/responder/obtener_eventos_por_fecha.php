<?php
include_once '../../interfazbd/InterfazReservasCalendario.php';
include_once '../../interfazbd/Validador.php';
include_once '../../interfazbd/ValidacionExcepcion.php';
header('Content-Type: application/json');

$fecha = $_POST['fecha'];
try{
    Validador::desinfectarEntrada($fecha);
    $respuesta = InterfazReservasCalendario::obtenerTodasLasReservas($fecha);

    echo json_encode($respuesta);
}catch(ValidacionExcepcion $excepcion){
    echo json_encode([]);
}


