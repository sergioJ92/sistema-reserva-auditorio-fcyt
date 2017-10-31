<?php

const RAIZ = '../..';
require_once RAIZ.'/interfazbd/Validador.php';
require_once RAIZ.'/interfazbd/CronogramaAcademico.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $entrada = $_POST;


    $anio = Validador::desinfectarEntrada($entrada['anio']);
    $gestion = Validador::desinfectarEntrada($entrada['gestion']);
    $a = json_encode(CronogramaAcademico::obtenerCronoFechaFinYconfi($anio,$gestion));
    echo $a;

} else {
	
    header('Location: index.php');
}