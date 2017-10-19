<?php

const RAIZ = '../..';
require_once RAIZ.'/interfazbd/Validador.php';
require_once RAIZ.'/interfazbd/ValidacionExcepcion.php';
require_once RAIZ.'/interfazbd/ContenidoCronograma.php';

$metodo = $_SERVER['REQUEST_METHOD'];

if ($metodo == 'GET') {
    
    header('Content-Type: application/json');
    
    $entrada = $_GET;
    $anio = Validador::desinfectarEntrada($entrada['anio']);
    $gestion = Validador::desinfectarEntrada($entrada['gestion']);
    
    $paquete = [
        'actividades' => null, 
        'tolerancias' => null,
        'feriadosEspeciales' => null,
        'otros' => null];
    
    $paquete['actividades'] = ContenidoCronograma::obtenerActividades($anio, $gestion);
    $paquete['tolerancias'] = ContenidoCronograma::obtenerTolerancias($anio, $gestion);
    $paquete['feriadosEspeciales'] = ContenidoCronograma::obtenerFeriadosEspeciales($anio, $gestion);
    $paquete['otros'] = ContenidoCronograma::obtenerOtros($anio, $gestion);
    
    echo json_encode($paquete);
} 
else if ($metodo === 'POST') {
    
    $entrada = $_POST['carga'];
    $accion = Validador::desinfectarEntrada($_POST['accion']);
    header('Content-Type: application/json');
    
    if ($accion === 'eliminar') {
    
        $idcontenido = Validador::desinfectarEntrada($entrada['id_contenido']);
        try {
            ContenidoCronograma::eliminarContenido($idcontenido);
            echo json_encode(['exito' => true]);
        } 
        catch (ValidacionExcepcion $ex) {
            echo json_encode(['exito' => false, 'error' => $ex->getMessage()]);
        }
    }
}
else {
    header('Location: contenidocalendario.php');
}