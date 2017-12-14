<?php

const RAIZ = '../..';
require_once RAIZ.'/interfazbd/Validador.php';
require_once RAIZ.'/interfazbd/ValidacionExcepcion.php';
require_once RAIZ.'/interfazbd/CronogramaAcademico.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $entrada = $_POST;
    header('Content-Type: aplication/json');

    $anio = Validador::desinfectarEntrada($entrada['anio']);
    $gestion = Validador::desinfectarEntrada($entrada['gestion']);
    
    try {
        CronogramaAcademico::eliminar($anio, $gestion);
        echo json_encode(['exito' => true]);
    } 
    catch (ValidacionExcepcion $ex) {
        echo json_encode(['exito' => false, 'error' => $ex->getMessage()]);
    }
} else {
    header('Location: index.php');
}