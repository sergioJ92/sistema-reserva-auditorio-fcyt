<?php

require_once 'ConexionBD.php';
require_once 'Validador.php';
require_once 'ValidacionExcepcion.php';

class ContenidoCronograma {
    
    private static function insertarContenido(
            $fechaHoraInicio, $fechaHoraFin, 
            $descripcion, $anio, $gestion) {
        
        $consultaCont = 'INSERT INTO contenido';
        $consultaCont .= ' (fecha_hora_inicio, fecha_hora_fin, descripcion, anio, gestion) ';
        $consultaCont .= 'VALUES';
        $consultaCont .= " ('$fechaHoraInicio','$fechaHoraFin',"
                . "'$descripcion','$anio','$gestion')";
        
        return pg_query(ConexionBD::getConexion(), $consultaCont);
    }
    
    public static function insertarActividad(
            $titulo, $fechaHoraInicio, $fechaHoraFin, 
            $descripcion, $anio, $gestion, $permiteReserva) {

        ContenidoCronograma::validarAnioGestion($anio, $gestion);
        ContenidoCronograma::validarActividad(
                $fechaHoraInicio, $fechaHoraFin, 
                $descripcion, $titulo, $permiteReserva);
        ContenidoCronograma::validarPeriodoDentroCronograma(
                $fechaHoraInicio, $fechaHoraFin, $anio, $gestion);
        
        $conn = ConexionBD::getConexion();
        pg_query($conn, "BEGIN");

        if (self::insertarContenido($fechaHoraInicio, $fechaHoraFin, 
                $descripcion, $anio, $gestion)) {
            
            $consulta_insercion = pg_query($conn, "SELECT lastval();");
            $idcontenido = pg_fetch_row($consulta_insercion)[0];
            $consultaAct = 'INSERT INTO actividad (id_contenido, titulo, permite_reserva) ';
            $consultaAct .= "VALUES ('$idcontenido','$titulo', '$permiteReserva')";

            if (pg_query($conn, $consultaAct)) {
                pg_query($conn, "COMMIT");
                return $idcontenido;
            } 
            else {
                pg_query($conn, "ROLLBACK");
                throw new GuardarExcepcion('Actividad');
            }
        } 
        else {
            pg_query($conn, "ROLLBACK");
            throw new GuardarExcepcion('Contenido');
        }
    }
    
    public static function insertarTolerancia(
            $fechaHoraInicio, $fechaHoraFin, 
            $descripcion, $anio, $gestion) {

        ContenidoCronograma::validarAnioGestion($anio, $gestion);
        ContenidoCronograma::validarContenido(
                $fechaHoraInicio, $fechaHoraFin, $descripcion);
        ContenidoCronograma::validarPeriodoDentroCronograma(
                $fechaHoraInicio, $fechaHoraFin, $anio, $gestion);

        $conn = ConexionBD::getConexion();
        pg_query($conn, "BEGIN");

        if (self::insertarContenido($fechaHoraInicio, $fechaHoraFin, 
                $descripcion, $anio, $gestion)) {
            
            $consulta_insercion = pg_query($conn, "SELECT lastval();");
            $idcontenido = pg_fetch_row($consulta_insercion)[0];
            $consultaTol = 'INSERT INTO tolerancia (id_contenido) ';
            $consultaTol .= "VALUES ('$idcontenido')";

            if (pg_query($conn, $consultaTol)) {
                pg_query($conn, "COMMIT");
                return $idcontenido;
            } 
            else {
                pg_query($conn, "ROLLBACK");
                throw new GuardarExcepcion('Tolerancia');
            }
        } 
        else {
            pg_query($conn, "ROLLBACK");
            throw new GuardarExcepcion('Contenido');
        }
    }
    
    public static function insertarFeriadoEspecial(
            $titulo, $fechaHoraInicio, $fechaHoraFin, 
            $descripcion, $anio, $gestion) {
        
        ContenidoCronograma::validarAnioGestion($anio, $gestion);
        ContenidoCronograma::validarFeriadoEsp(
                $fechaHoraInicio, $fechaHoraFin, $descripcion, $titulo);
        ContenidoCronograma::validarPeriodoDentroCronograma(
                $fechaHoraInicio, $fechaHoraFin, $anio, $gestion);
            
        $conn = ConexionBD::getConexion();
        pg_query($conn, "BEGIN");
        
        if (self::insertarContenido($fechaHoraInicio, $fechaHoraFin, 
                $descripcion, $anio, $gestion)) {
            
            $consulta_insercion = pg_query($conn, "SELECT lastval();");
            $idcontenido = pg_fetch_row($consulta_insercion)[0];
            $consultaFerEsp = 'INSERT INTO feriado_especial (id_contenido, titulo)';
            $consultaFerEsp .= " VALUES ('$idcontenido', '$titulo')";
            
            if (pg_query($conn, $consultaFerEsp)) {
                pg_query($conn, "COMMIT");
                return $idcontenido;
            } 
            else {
                pg_query($conn, "ROLLBACK");
                throw new GuardarExcepcion('FeriadoEspecial');
            }
        } else {
            pg_query($conn, "ROLLBACK");
            throw new GuardarExcepcion('Contenido');
        }
    }
    
    public static function insertarOtro(
                $titulo, $fechaHoraInicio, $fechaHoraFin, 
                $descripcion, $anio, $gestion, $cierreuniversidad) {
        
        ContenidoCronograma::validarAnioGestion($anio, $gestion);
        ContenidoCronograma::validarOtro(
                $fechaHoraInicio, $fechaHoraFin, $descripcion, $titulo,
                $cierreuniversidad);
        ContenidoCronograma::validarPeriodoDentroCronograma(
                $fechaHoraInicio, $fechaHoraFin, $anio, $gestion);
        
        $conn = ConexionBD::getConexion();
        pg_query($conn, "BEGIN");
        
        if (self::insertarContenido($fechaHoraInicio, $fechaHoraFin, 
                $descripcion, $anio, $gestion)) {
            
            $consulta_insercion = pg_query($conn, "SELECT lastval();");
            $idcontenido = pg_fetch_row($consulta_insercion)[0]; 
            $consultaOtro = 'INSERT INTO otro';
            $consultaOtro .= ' (id_contenido, titulo, cierre_universidad)';
            $consultaOtro .= " VALUES ('$idcontenido', '$titulo', '$cierreuniversidad')";
            
            if (pg_query($conn,$consultaOtro)) {
                pg_query($conn, "COMMIT");
                return $idcontenido;
            } 
            else {
                pg_query($conn, "ROLLBACK");
                throw new GuardarExcepcion('Otro');
            }
        } else {
            pg_query($conn, "ROLLBACK");
            throw new GuardarExcepcion('Contenido');
        }
    }
    
    private static function actualizarContenido(
            $idcontenido, $fechaHoraInicio, 
            $fechaHoraFin, $descripcion) {
        
        $consultaCont = 'UPDATE contenido SET';
        $consultaCont .= " fecha_hora_inicio='$fechaHoraInicio',";
        $consultaCont .= " fecha_hora_fin='$fechaHoraFin',";
        $consultaCont .= " descripcion='$descripcion'";
        $consultaCont .= " WHERE id_contenido='$idcontenido'";
        
        return pg_query(ConexionBD::getConexion(), $consultaCont);
    }

    public static function actualizarActividad($idcontenido, 
            $titulo, $fechaHoraInicio, $fechaHoraFin, 
            $descripcion, $permiteReserva) {

        Validador::revisarCampoEsNumeroEntero(
                $idcontenido, 'Llave Contenido');
        ContenidoCronograma::validarActividad(
                $fechaHoraInicio, $fechaHoraFin, 
                $descripcion, $titulo, $permiteReserva);
            
        $conn = ConexionBD::getConexion();
        pg_query($conn, "BEGIN");

        if (self::actualizarContenido($idcontenido, $fechaHoraInicio, 
                $fechaHoraFin, $descripcion)) {
            
            $consultaAct = "UPDATE actividad SET titulo='$titulo',";
            $consultaAct .= "permite_reserva='$permiteReserva'";
            $consultaAct .= " WHERE id_contenido='$idcontenido'";

            if (pg_query($conn, $consultaAct)) {
                pg_query($conn, "COMMIT");
                return true;
            } 
            else {
                pg_query($conn, "ROLLBACK");
                throw new ActualizarExcepcion('Actividad', $idcontenido);
            }
        } 
        else {
            pg_query($conn, "ROLLBACK");
            throw new ActualizarExcepcion('Contenido', $idcontenido);
        }
        return false;
    }
    
    public static function actualizarTolerancia(
            $idcontenido, $fechaHoraInicio, $fechaHoraFin, $descripcion) {

        Validador::revisarCampoEsNumeroEntero(
                $idcontenido, 'Llave Tolerancia');
        ContenidoCronograma::validarContenido(
                $fechaHoraInicio, $fechaHoraFin, $descripcion);

        $conn = ConexionBD::getConexion();
        pg_query($conn, "BEGIN");

        if (self::actualizarContenido($idcontenido, $fechaHoraInicio, 
                $fechaHoraFin, $descripcion)) {
            pg_query($conn, "COMMIT");
            return true;
        } 
        else {
            pg_query($conn, "ROLLBACK");
            throw new ActualizarExcepcion('Contenido', $idcontenido);
        }
        return false;
    }
    
    public static function actualizarFeriadoEspecial(
                    $idcontenido, $titulo, 
                    $fechaHoraInicio, $fechaHoraFin, $descripcion) {
        
        Validador::revisarCampoEsNumeroEntero(
                $idcontenido, 'Llave Contenido');
        ContenidoCronograma::validarFeriadoEsp(
                $fechaHoraInicio, $fechaHoraFin, $descripcion, $titulo);

        $conn = ConexionBD::getConexion();
        pg_query($conn, "BEGIN");

        if (self::actualizarContenido($idcontenido, $fechaHoraInicio, 
                $fechaHoraFin, $descripcion)) {
            
            $consultaFerEsp = "UPDATE feriado_especial SET titulo='$titulo'";
            $consultaFerEsp .= " WHERE id_contenido='$idcontenido'";

            if (pg_query($conn, $consultaFerEsp)) {
                pg_query($conn, "COMMIT");
                return true;
            } 
            else {
                pg_query($conn, "ROLLBACK");
                throw new ActualizarExcepcion('FeriadoEspecial', $idcontenido);
            }
        } 
        else {
            pg_query($conn, "ROLLBACK");
            throw new ActualizarExcepcion('Contenido', $idcontenido);
        }
        return false;
    }
    
    public static function actualizarOtro(
                    $idcontenido, $titulo, 
                    $fechaHoraInicio, $fechaHoraFin, $descripcion, $cierreUniversidad) {
        
        Validador::revisarCampoEsNumeroEntero(
                $idcontenido, 'Llave Contenido');

        ContenidoCronograma::validarOtro(
                $fechaHoraInicio, $fechaHoraFin, $descripcion, $titulo, 
                $cierreUniversidad);
        
        $conn = ConexionBD::getConexion();
        pg_query($conn, "BEGIN");

        if (self::actualizarContenido($idcontenido, $fechaHoraInicio, 
                $fechaHoraFin, $descripcion)) {
            
            $consultaOtro = "UPDATE otro SET titulo='$titulo', ";
            $consultaOtro .= "cierre_universidad='$cierreUniversidad'";
            $consultaOtro .= " WHERE id_contenido='$idcontenido'";

            if (pg_query($conn, $consultaOtro)) {
                pg_query($conn, "COMMIT");
                return true;
            } 
            else {
                pg_query($conn, "ROLLBACK");
                throw new ActualizarExcepcion('Otro', $idcontenido);
            }
        } 
        else {
            pg_query($conn, "ROLLBACK");
            throw new ActualizarExcepcion('Contenido', $idcontenido);
        }
        return false;
    }

    public static function obtenerActividades($anio, $gestion) {

        $conn = ConexionBD::getConexion();
        $consulta = 'SELECT c.id_contenido AS id_contenido, fecha_hora_inicio, fecha_hora_fin, descripcion, titulo, permite_reserva';
        $consulta .= ' FROM contenido c, actividad a';
        $consulta .= " WHERE anio='$anio' AND gestion='$gestion' AND c.id_contenido=a.id_contenido";
        return self::obtenerEnLista(pg_query($conn, $consulta));
    }
    
    public static function obtenerTolerancias($anio, $gestion) {
    
        $conn = ConexionBD::getConexion();
        $consulta = 'SELECT c.id_contenido AS id_contenido, fecha_hora_inicio, fecha_hora_fin, descripcion';
        $consulta .= ' FROM contenido c, tolerancia t';
        $consulta .= " WHERE anio='$anio' AND gestion='$gestion' AND c.id_contenido=t.id_contenido";
        return self::obtenerEnLista(pg_query($conn, $consulta));
    }

    public static function obtenerFeriadosEspeciales($anio, $gestion) {

        $conn = ConexionBD::getConexion();
        $consulta = 'SELECT c.id_contenido AS id_contenido, fecha_hora_inicio, fecha_hora_fin, descripcion, titulo';
        $consulta .= ' FROM contenido c, feriado_especial f';
        $consulta .= " WHERE anio='$anio' AND gestion='$gestion' AND c.id_contenido=f.id_contenido";
        return self::obtenerEnLista(pg_query($conn, $consulta));
    }

    public static function obtenerOtros($anio, $gestion) {

        $conn = ConexionBD::getConexion();
        $consulta = 'SELECT c.id_contenido AS id_contenido, fecha_hora_inicio, fecha_hora_fin, descripcion, titulo, cierre_universidad';
        $consulta .= ' FROM contenido c, otro o';
        $consulta .= " WHERE anio='$anio' AND gestion='$gestion' AND c.id_contenido=o.id_contenido";
        return self::obtenerEnLista(pg_query($conn, $consulta));
    }

    public static function obtenerEnLista($consultaResultado) {

        $lista = [];
        while ($fila = pg_fetch_assoc($consultaResultado)) {
            array_push($lista, $fila);
        }
        return $lista;
    }
    
    public static function verificarEliminarContenido($idContenido) {
        
        $consulta = "SELECT * FROM reserva_academica WHERE id_contenido = '$idContenido'";
        if (!pg_num_rows(pg_query(ConexionBD::getConexion(), $consulta)) == 0) {
            throw new ValidacionExcepcion('No se puede eliminar un Contenido del cronograma si ya tiene reservas dentro');
        }
    }

    public static function eliminarContenido($idContenido) {

        self::verificarEliminarContenido($idContenido);
        $conn = ConexionBD::getConexion();
        $consulta = "DELETE FROM contenido WHERE id_contenido='$idContenido'";
        if (pg_query($conn, $consulta)) { 

        } else {
            throw new EliminarExcepcion('Contenido', $idContenido);
        }
    }
    
    public static function obtenerPeriodoCronograma($anio, $gestion) {
        
        $consulta = 'SELECT fecha_hora_inicio, fecha_hora_fin';
        $consulta .= ' FROM cronograma_academico';
        $consulta .= " WHERE anio='$anio' AND gestion='$gestion'";
        
        $resultado = pg_query(ConexionBD::getConexion(), $consulta);
        if (pg_num_rows($resultado) > 0) {
            return pg_fetch_assoc($resultado);
        } else {
            throw new ValorIncorrectoExcepcion('Año y Gestion', 'No existen');
        }
    }
    
    public static function validarAnioGestion($anio, $gestion) {
        
        Validador::revisarCampoVacio($anio, 'Año');
        Validador::revisarCampoVacio($gestion, 'Gestion');
        Validador::revisarCampoEsNumeroEntero($anio, 'Año');
        Validador::revisarCampoEsNumeroEntero($gestion, 'Gestion');
        
        if ($gestion != 1 && $gestion != 2) {
            throw new ValorIncorrectoExcepcion('Gestion', 'Debe ser 1 o 2');
        }
        if ($anio < 1900 || $anio > 9999) {
            throw new ValorIncorrectoExcepcion(
                    'Año', 'No puede ser menor a 1900 o mayor a 9999');
        }
    }
    
    public static function validarPeriodoDentroCronograma(
            $fechainicio, $fechafin, $anio, $gestion) {
        
        $calendario = self::obtenerPeriodoCronograma($anio, $gestion);
        
        Validador::revisarFechaEsMenorIgual(
                $calendario['fecha_hora_inicio'], $fechainicio, 
                'Inicio del cronograma', 'Inicio del contenido');
        Validador::revisarFechaEsMenorIgual(
                $fechafin, $calendario['fecha_hora_fin'], 
                'Fin del contenido', 'Fin del cronograma');
    }
    
    public static function validarContenido(
            $fechaHoraInicio, $fechaHoraFin, $descripcion) {
        
        Validador::revisarCampoVacio($fechaHoraInicio, 'Fecha y Hora inicio');
        Validador::revisarCampoVacio($fechaHoraFin, 'Fecha y Hora fin');
        
        Validador::revisarCampoEsFechaHora($fechaHoraInicio, 'Fecha y Hora Inicio');
        Validador::revisarCampoEsFechaHora($fechaHoraFin, 'Fecha y Hora Fin');
        if (strlen($descripcion) > 6000) {
            throw new ValorIncorrectoExcepcion(
                    "Descripcion", "No puede superar los 6000 caracteres");
        }
        Validador::revisarFechaEsMenor(
                $fechaHoraInicio, $fechaHoraFin, 
                'Fecha y Hora Inicio', 'Fecha y Hora Fin');
    }
    
    public static function validarActividad(
            $fechaHoraInicio, $fechaHoraFin, 
            $descripcion, $titulo, $permitereserva) {
        
        Validador::revisarCampoVacio($titulo, 'Titulo');
        Validador::revisarCampoEsAlfaNumerico($titulo, 'Titulo');
        self::validarContenido($fechaHoraInicio, $fechaHoraFin, $descripcion);
        Validador::revisarCampoEsNumeroEnteroPositivo($permitereserva, 'Permite Reserva');
    }
    
    
    public static function validarFeriadoEsp(
            $fechaHoraInicio, $fechaHoraFin, $descripcion, $titulo) {
        
        Validador::revisarCampoVacio($titulo, 'Titulo');
        Validador::revisarCampoEsAlfaNumerico($titulo, 'Titulo');
        self::validarContenido($fechaHoraInicio, $fechaHoraFin, $descripcion);
    }
    
    
    public static function validarOtro(
            $fechaHoraInicio, $fechaHoraFin, $descripcion,
            $titulo, $cierreuniversidad) {
        
        Validador::revisarCampoVacio($titulo, 'Titulo');
        Validador::revisarCampoEsAlfaNumerico($titulo, 'Titulo');
        self::validarContenido($fechaHoraInicio, $fechaHoraFin, $descripcion);
        Validador::revisarCampoEsNumeroEnteroPositivo($cierreuniversidad, 'Cierre Universidad');
    }
}