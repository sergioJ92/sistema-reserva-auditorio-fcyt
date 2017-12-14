<?php

const RAIZ = '../..';
require_once RAIZ. '/interfazbd/Validador.php';
require_once RAIZ. '/interfazbd/ValidacionExcepcion.php';
require_once RAIZ. '/interfazbd/ContenidoCronograma.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $entrada = $_POST['carga'];
    $accion = Validador::desinfectarEntrada($_POST['accion']);
    header('Content-Type: application/json');
    if ($accion === 'guardar') {
        
        $fechaHoraInicio = Validador::desinfectarEntrada($entrada['fecha_hora_inicio']);
        $fechaHoraFin = Validador::desinfectarEntrada($entrada['fecha_hora_fin']);
        $descripcion = Validador::desinfectarEntrada($entrada['descripcion']);
        $anio = Validador::desinfectarEntrada($entrada['anio']);
        $gestion = Validador::desinfectarEntrada($entrada['gestion']);
        
        try {
            $idtolerancia = ContenidoCronograma::insertarTolerancia(
                    $fechaHoraInicio, $fechaHoraFin, 
                    $descripcion, $anio, $gestion);
            
            echo json_encode(['exito' => true, 'id_contenido' => $idtolerancia]);
        } 
        catch (ValidacionExcepcion $ex) {
            echo json_encode(['exito' =>false, 'error' => $ex->getMessage()]);
        }
    }
    else if ($accion === 'eliminar') {
        header('Location: recursocontenido.php');
    } 
    else {
        header('Location: index.php');
    }
} 
else {
    header('Location: index.php');
}