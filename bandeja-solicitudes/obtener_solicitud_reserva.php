<?php

include_once '../interfazbd/InterfazSolicitudReserva.php';
include_once '../interfazbd/ValidacionExcepcion.php';

header('Content-Type: application/json');

$tipo = $_POST['tipo'];
try {
    if($tipo == 'auditorio'){
    	$respuesta = InterfazSolicitudReserva::obtenerSolicitudesReservaAuditorio();
		echo json_encode($respuesta);
    }else{
    	if($tipo == 'laboratorio'){
    		$respuesta = InterfazSolicitudReserva::obtenerSolicitudesReservaLaboratorio();
    		echo json_encode($respuesta);
    	}else{
    		if($tipo == 'aula'){
    			$respuesta = InterfazSolicitudReserva::obtenerSolicitudesReservaAula();
    			echo json_encode($respuesta);
    		}
    	}
    }
}
catch (ValidacionExcepcion $ex) {
	echo json_encode([]);
}
