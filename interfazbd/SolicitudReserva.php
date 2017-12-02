<?php

require_once 'ConexionBD.php';
require_once 'Validador.php';

class SolicitudReserva extends ConexionBD {
    
    
    public static function obtenerTodosLosAuditorios() {

        $consulta = 'SELECT id_ambiente, nombre_auditorio FROM auditorio';
        $conn = ConexionBD::getConexion();
        $consultaResultado = pg_query($conn, $consulta);
        $resultado = [];
        while ($fila = pg_fetch_assoc($consultaResultado)) {
            $idAmbiente = $fila['id_ambiente'];
            $nomAuditorio = $fila['nombre_auditorio'];
            $agregar = [$idAmbiente, $nomAuditorio];
            array_push($resultado, $agregar);
        }
        return $resultado;
    }

    public static function obtenerTodosLosDepartamentos() {

        $consulta = 'SELECT departamento FROM laboratorio group by departamento';
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

        $consulta = 'SELECT edificio FROM aula group by edificio';
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