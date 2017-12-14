<?php
include_once 'ConexionBD.php';

class InterfazReservasCalendario {
    public static function obternerReservasSimple($fecha, $idAmbiente) {
        $conexion = ConexionBD::getConexion();
        $consulta = "SELECT RES.*, ASUN.asunto FROM reserva AS RES, reserva_academica AS ACADE, asunto AS ASUN WHERE RES.fecha = '$fecha' AND RES.id_reserva = ACADE.id_reserva AND ASUN.id_asunto = ACADE.id_asunto AND RES.id_ambiente = $idAmbiente";
        return self::obtenerEnLista(pg_query($conexion, $consulta));
    }
    private static function obtenerEnLista($resultado_consulta) {
        $lista = [];
         while ($fila = pg_fetch_assoc($resultado_consulta)) {
            array_push($lista, $fila);
        }
        return $lista;
    }
    public static function obternerReservasSolicitada($fecha, $idAmbiente) {
        $conexion = ConexionBD::getConexion();
        $consulta = "SELECT RES.* ,SOLIC.id_respuesta FROM reserva AS RES, reserva_solicitada AS SOLIC WHERE RES.fecha = '$fecha' AND RES.id_reserva = SOLIC.id_reserva AND RES.id_ambiente=$idAmbiente";
        return self::obtenerEnLista(pg_query($conexion, $consulta));
    }
    public static function obtenerTodasLasReservas ($fecha, $idAmbiente){
        $lista1 = self::obternerReservasSimple($fecha, $idAmbiente);
        $lista2 = self::obternerReservasSolicitada($fecha, $idAmbiente);
        $respuesta = [];
        array_push($respuesta, $lista1);
        array_push($respuesta, $lista2);
        return $respuesta;
    }
    public static function obtenerTodasLasReservasAmbiente($ambiente, $fecha){
        $conexion = ConexionBD::getConexion();
        $consulta = "SELECT hora_inicio, hora_fin FROM reserva AS res WHERE id_ambiente=$ambiente AND fecha='$fecha'";
        return self::obtenerEnLista(pg_query($conexion, $consulta));
    }

}
