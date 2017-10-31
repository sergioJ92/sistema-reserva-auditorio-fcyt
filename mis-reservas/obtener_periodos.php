<?php
const RAIZ = '..';

require_once RAIZ.'/interfazbd/ConexionBD.php';
require_once RAIZ.'/interfazbd/Validador.php';

function obtenerPeriodos($fecha) {
    $consulta = "SELECT hora_inicio,hora_fin FROM reserva WHERE fecha='$fecha'";
    $resultadoConsulta = pg_query(ConexionBD::getConexion(),$consulta);
    
    $resultado = [];
    while ($fila = pg_fetch_assoc($resultadoConsulta)) {
        $resultado []= $fila;
    }
    return $resultado;
}

function obtenerConfiguracion($idReserva) {
    
    $consulta = "SELECT duracion_periodo, hora_inicio_jornada, hora_fin_jornada, hora_fin_sabado";
    $consulta .= " FROM configuracion c, cronograma_academico ca, contenido co, actividad a, reserva_academica ra";
    $consulta .= " WHERE ra.id_reserva = $idReserva AND ra.id_contenido = a.id_contenido AND co.id_contenido = a.id_contenido AND co.anio = ca.anio AND co.gestion = ca.gestion AND c.anio = ca.anio AND c.gestion = ca.gestion AND a.permite_reserva=1";
    $resultadoConsulta = pg_query(ConexionBD::getConexion(), $consulta);
    if ($resultadoConsulta) {
        return pg_fetch_assoc($resultadoConsulta);
    }
    return null;
}

header('Content-Type: application/json');
$entrada = $_REQUEST;
if (count($entrada) == 2) {
    $idReserva= Validador::desinfectarEntrada($entrada['id_reserva']);
    $fecha= Validador::desinfectarEntrada($entrada['fecha']);

    if (!empty($idReserva) && !empty($fecha)) {
        $datos = [
            'periodos' => obtenerPeriodos($fecha),
            'configuracion' => obtenerConfiguracion($idReserva)
        ];
        echo json_encode($datos);
    }
    else {
        echo json_encode(null);
    }
} else {
    echo json_encode(null);
}