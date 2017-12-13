<?php

const RAIZ = './..';
require_once RAIZ.'/interfazbd/ConexionBD.php';
require_once RAIZ.'/interfazbd/Validador.php';
require_once RAIZ.'/interfazbd/ValidacionExcepcion.php';

function obtenerPisos($edificio){
    $conn = ConexionBD::getConexion();
    $edificio = str_replace("_", " ", $edificio);
    $consultaObtenerPisos = "select piso from aula where edificio='$edificio' group by piso";
    $res = pg_query($conn, $consultaObtenerPisos);
    echo '<option selected="" value="null" hidden="">Numero de Piso</option>';
    while ($fila = pg_fetch_assoc($res)) {
        $piso = $fila['piso'];
        echo '<option value="'.$piso.'">'.$piso.'</option>';
    }

}

function obtenerAulas($edificio, $piso){
    $conn = ConexionBD::getConexion();

    $edificio = str_replace("_", " ", $edificio);
    $piso = str_replace("_", " ", $piso);
    $consultaObtenerAulas = "select id_ambiente, nombre_aula from aula where edificio='$edificio' and piso='$piso'";
    $res = pg_query($conn, $consultaObtenerAulas);
    echo '<option selected="" value="null" hidden="">Nombre de Aula</option>';
    while ($fila = pg_fetch_assoc($res)) {
        $id_ambiente = $fila['id_ambiente'];
        $aula = $fila['nombre_aula'];
        echo "<option value='$id_ambiente'>$aula</option>";
    }
}


function obtenerLaboratorios($departamento){
    $conn = ConexionBD::getConexion();
    $departamento = str_replace("_", " ", $departamento);
    $consultaObtenerPisos = "select id_ambiente, nombre_laboratorio from laboratorio where departamento='$departamento'";
    $res = pg_query($conn, $consultaObtenerPisos);
    echo '<option selected="" value="null" hidden="">Nombre de laboratorio</option>';
    while ($fila = pg_fetch_assoc($res)) {
        $id_ambiente = $fila['id_ambiente'];
        $nombre_laboratorio = $fila['nombre_laboratorio'];
        echo '<option value="'.$id_ambiente.'">'.$nombre_laboratorio.'</option>';
    }

}

$tipo = $_GET['tipo'];

if($tipo == '1'){
    obtenerPisos($_GET['nombre']);
}else{
    if($tipo == '2'){
        obtenerAulas($_GET['nombre'], $_GET['piso']);
    }else{
        if($tipo == '3'){
            obtenerLaboratorios($_GET['nombre']);
        }
    }
}
