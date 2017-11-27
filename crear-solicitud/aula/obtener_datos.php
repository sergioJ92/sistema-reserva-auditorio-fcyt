<?php

const RAIZ = './../..';
require_once RAIZ.'/interfazbd/ConexionBD.php';
require_once RAIZ.'/interfazbd/Validador.php';
require_once RAIZ.'/interfazbd/ValidacionExcepcion.php';

function obtenerPisos($edificio){
    $conn = ConexionBD::getConexion();

    $consultaObtenerPisos = "select piso from aula where edificio='$edificio'";
    $res = pg_query($conn, $consultaObtenerPisos);
    if($res){

    }

}

function obtenerAulas($edificio, $piso){
    $conn = ConexionBD::getConexion();

    $consultaObtenerAulas = "select nombre_aula from aula where edificio='$edificio' and piso='$piso'";
    $res = pg_query($conn, $consultaObtenerAulas);
    if($res){
        
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    header('Content-Type: application/json');
    $conectado = ConexionBD::conectar();
    $entrada = $_POST;
    
    $tipo = Validador::desinfectarEntrada($entrada['tipo']);
    
    try {
        if($tipo == 1){
            $edificio = Validador::desinfectarEntrada($entrada['edificio']);
            obtenerPisos($edificio);
        }else{
            if($tipo == 2){
                $edificio = Validador::desinfectarEntrada($entrada['edificio']);
                $piso = Validador::desinfectarEntrada($entrada['piso']);
                obtenerAulas($edificio, $piso);
            }
        }

    }
    catch (ValidacionExcepcion $ex) {
        echo json_encode(['exito' => false, 'error' => $ex->getMessage()]);
    }
}
else {
    header('Location: index.php');
}