<?php

include_once 'ConexionBD.php';

class InterfazSolicitudReserva {

    public static function obtenerSolicitudesReservaAuditorio() {
        $conexion = ConexionBD::getConexion();
        $consulta = 'SELECT S.* , C.correo1 , T.telefono1, Au.nombre_auditorio FROM solicitud_reserva AS S, correo as C, telefono as T, ambiente as A, auditorio as Au WHERE A.id_ambiente=S.id_ambiente AND A.id_ambiente=Au.id_ambiente AND S.id_solicitud_reserva= C.id_solicitud_reserva AND S.leido = 0 AND S.id_solicitud_reserva=T.id_solicitud_reserva ORDER BY S.id_solicitud_reserva ASC';
        return self::obtenerEnLista(pg_query($conexion,$consulta));
    }

    public static function obtenerSolicitudesReservaLaboratorio() {
        $conexion = ConexionBD::getConexion();
        $consulta = 'SELECT S.* , C.correo1 , T.telefono1, L.departamento, L.nombre_laboratorio FROM solicitud_reserva AS S, correo as C, telefono as T, laboratorio as L, ambiente as A WHERE A.id_ambiente=S.id_ambiente AND A.id_ambiente=L.id_ambiente AND  S.id_solicitud_reserva= C.id_solicitud_reserva AND S.leido = 0 AND S.id_solicitud_reserva=T.id_solicitud_reserva ORDER BY S.id_solicitud_reserva ASC';
        return self::obtenerEnLista(pg_query($conexion,$consulta));
    }

    public static function obtenerSolicitudesReservaAula() {
        $conexion = ConexionBD::getConexion();
        $consulta = 'SELECT S.* , C.correo1 , T.telefono1, Au.edificio, Au.piso, Au.nombre_aula FROM solicitud_reserva AS S, correo as C, telefono as T, ambiente as A, aula as Au WHERE A.id_ambiente=S.id_ambiente AND A.id_ambiente=Au.id_ambiente AND S.id_solicitud_reserva= C.id_solicitud_reserva AND S.leido = 0 AND S.id_solicitud_reserva=T.id_solicitud_reserva ORDER BY S.id_solicitud_reserva ASC';
        return self::obtenerEnLista(pg_query($conexion,$consulta));
    }

    private static function obtenerEnLista($resultado_consulta) {
        $lista = [];
        while ($fila = pg_fetch_assoc($resultado_consulta)) {
            array_push($lista, $fila);
        }
        return $lista;
    }


}