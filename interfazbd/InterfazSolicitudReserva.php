<?php

include_once 'ConexionBD.php';

class InterfazSolicitudReserva {

    public static function obtenerSolicitudesReserva() {
        $conexion = ConexionBD::getConexion();
        $consulta = 'SELECT S.* , C.correo , T.telefono FROM solicitud_reserva AS S, correo as C, telefono as T WHERE S.id_solicitud_reserva= C.id_solicitud_reserva AND S.leido = 0 AND S.id_solicitud_reserva=T.id_solicitud_reserva ORDER BY S.id_solicitud_reserva ASC';
        return self::obtenerEnLista($conexion->query($consulta));
    }

    private static function obtenerEnLista($resultado_consulta) {
        $lista = [];
        while ($fila = $resultado_consulta->fetch_assoc()) {
            array_push($lista, $fila);
        }
        return $lista;
    }
}
