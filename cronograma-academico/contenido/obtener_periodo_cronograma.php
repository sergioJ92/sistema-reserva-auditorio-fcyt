<?php

const RAIZ = '../..';
        
require_once RAIZ . '/interfazbd/Validador.php';
require_once RAIZ . '/interfazbd/CronogramaAcademico.php';

header('Content-Type: application/json');

$entrada = $_REQUEST;

$anio = Validador::desinfectarEntrada($entrada['anio']);
$gestion = Validador::desinfectarEntrada($entrada['gestion']);

echo json_encode(CronogramaAcademico::obtener($anio, $gestion));