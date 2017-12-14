<?php

const RAIZ = '..';

require_once RAIZ . '/interfazbd/ContenidoCronograma.php';
require_once RAIZ . '/interfazbd/Validador.php';

$peticion = Validador::desinfectarEntrada($_REQUEST['peticion']);
$anio = Validador::desinfectarEntrada($_REQUEST['anio']);
$gestion = Validador::desinfectarEntrada($_REQUEST['gestion']);

header('Content-Type: application/json');

if (empty($peticion) || empty($anio) || empty($gestion)) {
    echo json_encode(null);
}
else {
    switch ($peticion) {
        case 'actividades':
            $activdiades = ContenidoCronograma::obtenerActividades($anio, $gestion);
            echo json_encode($activdiades);
            break;

        case 'tolerancias':
            $tolerancias = ContenidoCronograma::obtenerTolerancias($anio, $gestion);
            echo json_encode($tolerancias);
            break;

        case 'feriados-especiales':
            $feriadosesp = ContenidoCronograma::obtenerFeriadosEspeciales($anio, $gestion);
            echo json_encode($feriadosesp);
            break;

        case 'otros':
            $otros = ContenidoCronograma::obtenerOtros($anio, $gestion);
            echo json_encode($otros);
            break;

        default:
            echo json_encode(null);
    }
}