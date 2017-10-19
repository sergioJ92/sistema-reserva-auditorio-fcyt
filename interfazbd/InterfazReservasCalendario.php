<?php
include_once 'ConexionBD.php';

class InterfazReservasCalendario {
    public static function obternerReservasSimple($fecha) {
        $conexion = ConexionBD::getConexion();
        $consulta = "SELECT RES.*, ASUN.asunto FROM reserva AS RES, reserva_academica AS ACADE, asunto AS ASUN WHERE RES.fecha = '$fecha' AND RES.id_reserva = ACADE.id_reserva AND ASUN.id_asunto = ACADE.id_asunto";
        return self::obtenerEnLista($conexion->query($consulta));
    }
    private static function obtenerEnLista($resultado_consulta) {
        $lista = [];
        while ($fila = $resultado_consulta->fetch_assoc()) {
            array_push($lista, $fila);
        }
        return $lista;
    }
    public static function obternerReservasSolicitada($fecha) {
        $conexion = ConexionBD::getConexion();
        $consulta = "SELECT RES.* ,SOLIC.id_respuesta FROM reserva AS RES, reserva_solicitada AS SOLIC WHERE RES.fecha = '$fecha' AND RES.id_reserva = SOLIC.id_reserva ";
        return self::obtenerEnLista($conexion->query($consulta));
    }
    public static function obtenerTodasLasReservas ($fecha){
        $lista1 = self::obternerReservasSimple($fecha);
        $lista2 = self::obternerReservasSolicitada($fecha);
        $respuesta = [];
        array_push($respuesta, $lista1);
        array_push($respuesta, $lista2);
        return $respuesta;
    }
}
