<?php

const RAIZ = '../..';
require_once RAIZ.'/interfazbd/Validador.php';
require_once RAIZ.'/interfazbd/CronogramaAcademico.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $entrada = $_POST;
    header('Content-Type: aplication/json');

    $anio = Validador::desinfectarEntrada($entrada['anio']);
    $gestion = Validador::desinfectarEntrada($entrada['gestion']);
    
    echo json_encode(CronogramaAcademico::obtenerCronoYConfi($anio, $gestion));
} else {
    header('Location: index.php');
}