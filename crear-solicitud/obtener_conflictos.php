<?php

const RAIZ = './..';
require_once RAIZ.'/interfazbd/Validador.php';
require_once RAIZ.'/interfazbd/ValidacionExcepcion.php';
include_once RAIZ.'/interfazbd/InterfazReservasCalendario.php';

//header('Content-Type: application/json');

$id_ambiente = $_POST['id_ambiente'];
$fecha = $_POST['fecha'];
$hora_inicio = $_POST['hora_inicio'];
$hora_fin = $_POST['hora_fin'];

/*$id_ambiente = 1;
$fecha = '2017-12-04';
$hora_inicio = '6:45';
$hora_fin = '20:15';
*/

$form_data = array();
try{
    $id_ambiente = Validador::desinfectarEntrada($id_ambiente);
    $fecha = Validador::desinfectarEntrada($fecha);
    $hora_inicio = Validador::desinfectarEntrada($hora_inicio);
    $hora_fin = Validador::desinfectarEntrada($hora_fin);
    
    $respuesta = InterfazReservasCalendario::obtenerTodasLasReservasAmbiente($id_ambiente, $fecha);

    $res = hayConflictos($respuesta, $hora_inicio, $hora_fin);
    $form_data['exito'] = true;
    $form_data['conflictos'] = $res;
}catch (ValidacionExcepcion $ex) {
    $form_data['exito'] = false;
    $form_data['error'] = $ex->getMessage();
}

echo json_encode($form_data);

function hayConflictos($eventos, $hora_inicio, $hora_fin){
	$res = 0;
	$hora_inicio = strtotime($hora_inicio);
	$hora_fin = strtotime($hora_fin);
	$tam = sizeof($eventos);
	for($i = 0; $i<$tam; $i++){
		$fila = $eventos[$i];
		$ini = strtotime($fila['hora_inicio']);
		$fin = strtotime($fila['hora_fin']);
		if(esConflicto($hora_inicio, $hora_fin, $ini, $fin)){
			$res = $res+1;
		}
	}
    
    return $res;
}

function esConflicto($ini1, $fin1, $ini2, $fin2){
    if (($ini1> $ini2 && $ini1<$fin2)||($fin1>$ini2 && $fin1<$fin2)||($ini1===$ini2 && $fin1 ===$fin2)
            ||($ini1<=$ini2&&$fin1>=$fin2)) {
        return true;
    }
    return false;
}