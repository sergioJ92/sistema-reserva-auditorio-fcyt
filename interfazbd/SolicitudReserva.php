<?php

require_once 'ConexionBD.php';
require_once 'Validador.php';

class SolicitudReserva extends ConexionBD {
    
    
    public static function obtenerTodosLosAuditorios() {

        $consulta = 'SELECT nombre_auditorio FROM auditorio';
        $conn = ConexionBD::getConexion();
        $consultaResultado = pg_query($conn, $consulta);
        $resultado = [];
        while ($fila = pg_fetch_assoc($consultaResultado)) {
            $nomAuditorio = $fila['nombre_auditorio'];
            array_push($resultado, $nomAuditorio);
        }
        return $resultado;
    }

    public static function obtenerTodosLosDepartamentos() {

        $consulta = 'SELECT departamento FROM laboratorio';
        $conn = ConexionBD::getConexion();
        $consultaResultado = pg_query($conn, $consulta);
        $resultado = [];
        while ($fila = pg_fetch_assoc($consultaResultado)) {
            $departamento = $fila['departamento'];
            array_push($resultado, $departamento);
        }
        return $resultado;
    }

    public static function obtenerTodosLosEdificios() {

        $consulta = 'SELECT edificio FROM aula';
        $conn = ConexionBD::getConexion();
        $consultaResultado = pg_query($conn, $consulta);
        $resultado = [];
        while ($fila = pg_fetch_assoc($consultaResultado)) {
            $edificio = $fila['edificio'];
            array_push($resultado, $edificio);
        }
        return $resultado;
    }
    
}