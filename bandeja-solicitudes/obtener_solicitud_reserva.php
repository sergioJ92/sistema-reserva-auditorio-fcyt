<?php

include_once '../interfazbd/InterfazSolicitudReserva.php';

header('Content-Type: application/json');

$respuesta = InterfazSolicitudReserva::obtenerSolicitudesReserva();
echo json_encode($respuesta);
