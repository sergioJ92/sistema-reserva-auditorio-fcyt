<?php
const RAIZ = '..';

require_once RAIZ . '/interfazbd/ConexionBD.php';
require_once RAIZ . '/interfazbd/ValidacionExcepcion.php';

function bloquearFecha($fecha) {

    $conexion = ConexionBD::getConexion();
    $consulta = "select * from bloquear('".$fecha."')";

    $resultado = pg_query($conexion, $consulta);
    
    if ($resultado) {
        $row = pg_fetch_row($resultado)[0];
        if(!($row==1)){
            throw new ValidacionExcepcion('Esta fecha esta bloqueada momentaneamente, intente mas tarde');
        }
    } else {
        throw new ValidacionExcepcion('Esta fecha esta bloqueada momentaneamente, intente mas tarde');
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $form_data = array();
    $entrada = $_POST;
    $fecha = $entrada['fecha'];
    try {
        bloquearFecha($fecha);
        $form_data['exito'] = true;
    }
    catch (ValidacionExcepcion $ex) {
        $form_data['exito'] = false;
        $form_data['error'] = $ex->getMessage();
    }
    echo json_encode($form_data);
    //exit;
}
else {
    header('Location: index.php');
}