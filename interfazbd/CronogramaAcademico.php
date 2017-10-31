<?php

require_once 'ConexionBD.php';
require_once 'Validador.php';

class CronogramaAcademico extends ConexionBD {
    
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
    
    public static function validar($anio, $gestion, 
            $fechaHoraInicio, $fechaHoraFin, $fechaActivacion) {
        
        self::validarAnioGestion($anio, $gestion);
        
        Validador::revisarCampoVacio($fechaHoraInicio, 'Fecha y Hora Inicio');
        Validador::revisarCampoVacio($fechaHoraFin, 'Fecha y Hora Fin');
        Validador::revisarCampoEsFechaHora($fechaHoraInicio, 'Fecha y Hora Inicio');
        Validador::revisarCampoEsFechaHora($fechaHoraFin, 'Fecha y Hora Fin');
        Validador::revisarCampoEsFecha($fechaActivacion, 'Fecha de activación');
        Validador::revisarFechaEsMenor($fechaActivacion, $fechaHoraFin, 'Fecha de activación', 'Fecha y hora fin');
        Validador::revisarFechaEsMenor($fechaHoraInicio, $fechaHoraFin, 'Fecha y hora Inicio', 'Fecha y hora Fin');
        if (!Validador::fechaEsMenor(date('Y-m-d'), $fechaHoraInicio)) {
            throw new ValidacionExcepcion('No se puede crear un Cronograma con fecha de inicio menor a hoy');
        }
        if (!Validador::fechaEsMenorIgual(date('Y-m-d'), $fechaActivacion)) {
            throw new ValidacionExcepcion('La fecha de activación no puede ser menor a hoy');
        }
    }
    
    public static function insertar(
            $anio, $gestion, $fechaHoraInicio, $fechaHoraFin) {

        self::validar($anio, $gestion, $fechaHoraInicio, $fechaHoraFin);
        $consulta = 'INSERT INTO cronograma_academico';
        $consulta .= ' (anio, gestion, fecha_hora_inicio, fecha_hora_fin) VALUES';
        $consulta .= " ('$anio', '$gestion', '$fechaHoraInicio', '$fechaHoraFin')";
        
        return ConexionBD::getConexion()->query($consulta);
    }

    public static function actualizar(
            $anio, $gestion, $fechaHoraInicio, $fechaHoraFin) {
        
        self::validar($anio, $gestion, $fechaHoraInicio, $fechaHoraFin);
        $consulta = 'UPDATE cronograma_academico SET';
        $consulta .= " fecha_hora_inicio='$fechaHoraInicio',";
        $consulta .= " fecha_hora_fin='$fechaHoraFin'";
        $consulta .= " WHERE anio='$anio' AND gestion='$gestion'";
        return ConexionBD::getConexion()->query($consulta);
    }
    
    public static function validarConfiguracionPeriodo($periodoHoras, $periodoMinutos) {
        
        if ($periodoHoras > 23) {
            $razon = "El periodo de horas exede a 23, que es lo establecido";
            throw new ValorIncorrectoExcepcion("Horas de duración del periodo, ", $razon);
        }
        if ($periodoHoras < 0) {
            $razon = "El periodo de horas, es un numero incorrecto ya que no puede ser negativo";
            throw new ValorIncorrectoExcepcion("Horas de duración del periodo, ", $razon);
        }
        if ($periodoMinutos > 59) {
            $razon = "El periodo de minutos exede a 59";
            throw new ValorIncorrectoExcepcion("Minutos de duración del periodo", $razon);
        }
        if ($periodoMinutos < 0) {
            $razon = "El periodo de minutos, es un numero incorrecto, ya que no puede ser negativo";
            throw new ValorIncorrectoExcepcion("Minutos de duración del periodo", $razon);
        }
        if ($periodoMinutos == 0 && $periodoHoras == 0) {
            $razon = "No pueden ser ambos 0";
            throw new ValoresIncorrectosExcepcion(["Horas", "Minutos"], $razon);
        }
    }
    
    public static function guardarCronograma(
            $anio, $gestion, $fechaHoraInicio, $fechaHoraFin, 
            $duracionPeriodo, $horaInicioJornada, 
            $horaFinJornada, $horaFinSabado, $fechaActivacion) {

        self::validar($anio, $gestion, $fechaHoraInicio, $fechaHoraFin, $fechaActivacion);
        $consulta = 'INSERT INTO cronograma_academico';
        $consulta .= ' (anio, gestion, fecha_hora_inicio, fecha_hora_fin, fecha_activacion) VALUES';
        $consulta .= " ('$anio', '$gestion', '$fechaHoraInicio', '$fechaHoraFin', '$fechaActivacion')";
        $conn = ConexionBD::getConexion();
        $conn->autocommit(false);
        
        if ($conn->query($consulta)) {
            $consultaConfig = ConexionBD::construirConsultaInsert('configuracion', 
                    ['anio', 'gestion', 'duracion_periodo', 'hora_inicio_jornada', 
                        'hora_fin_jornada', 'hora_fin_sabado'], [$anio, $gestion, 
                            $duracionPeriodo, $horaInicioJornada, $horaFinJornada, $horaFinSabado]);

            if ($conn->query($consultaConfig)) {
                $conn->commit();
                return true;
            }
            else {
                $conn->rollback();
                throw new GuardarExcepcion('Configuracion');
            }
        }
        else {
            $conn->rollback();
            return false;
        }
    }

    public static function actualizarCronograma(
            $anio, $gestion, $fechaHoraInicio, $fechaHoraFin, 
            $duracionPeriodo, $horaInicioJornada, 
            $horaFinJornada, $horaFinSabado, $nuevaFechaActivacion) {
        
        $fechaActivacion = self::obtenerFechaActivacionCronograma($anio, $gestion);
        if (Validador::fechaEsMenor($fechaActivacion, date('Y-m-d'))) {
            throw new ValidacionExcepcion('No se puede modificar un Cronograma si la fecha de activación ya se alcanzo');
        }
        self::validar($anio, $gestion, $fechaHoraInicio, $fechaHoraFin, $nuevaFechaActivacion);
        $consulta = 'UPDATE cronograma_academico SET';
        $consulta .= " fecha_hora_inicio='$fechaHoraInicio',";
        $consulta .= " fecha_hora_fin='$fechaHoraFin',";
        $consulta .= " fecha_activacion='$nuevaFechaActivacion'";
        $consulta .= " WHERE anio='$anio' AND gestion='$gestion'";
        
        $conn = ConexionBD::getConexion();
        $conn->autocommit(false);
        if ($conn->query($consulta)) {
            
            $consultaConfig=ConexionBD::construirConsultaUpdate('configuracion', 
                    ['duracion_periodo', 'hora_inicio_jornada', 'hora_fin_jornada',
                        'hora_fin_sabado'], [$duracionPeriodo, $horaInicioJornada, 
                            $horaFinJornada, $horaFinSabado], ['anio', 'gestion'], [$anio, $gestion]);

            if ($conn->query($consultaConfig)) {
                $conn->commit();
                return true;
            }else{
                $conn->rollback();
                throw new ActualizarExcepcion('Configuracion desde método actualizarCronogramaConfi');
                
                
            }
        }else{
            $conn ->rollback();
            throw new ActualizarExcepcion('Cronograma académico');
        }
    }

    public static function obtener($anio, $gestion) {

        self::validarAnioGestion($anio, $gestion);
        $consulta = "SELECT * FROM cronograma_academico WHERE anio='$anio' AND gestion='$gestion'";
        $resultado = ConexionBD::getConexion()->query($consulta);
        if ($resultado->num_rows > 0) {
            return $resultado->fetch_assoc();
        } else {
            return null;
        }
    }
    
    public static function obtenerCronoYConfi($anio, $gestion) {

        self::validarAnioGestion($anio, $gestion);
        $consulta = "SELECT CRO.*, CON.duracion_periodo, CON.hora_inicio_jornada, CON.hora_fin_jornada, CON.hora_fin_sabado "
                . "FROM cronograma_academico AS CRO, configuracion AS CON "
                . "WHERE CON.anio='$anio' AND CON.gestion='$gestion' AND CRO.anio=CON.anio AND CRO.gestion=CON.gestion";
        $resultado = ConexionBD::getConexion()->query($consulta);
        if ($resultado->num_rows > 0) {
            return $resultado->fetch_assoc();
        } else {
            return null;
        }
    }
///////////////////////////////////////////////////////////////////////////////////////////
    public static function obtenerCronoFechaFinYconfi($anio,$gestion){

        self::validarAnioGestion($anio, $gestion);
        $consulta = "SELECT * FROM cronograma_academico WHERE anio='$anio'";
        $resultado = ConexionBD::getConexion()->query($consulta);

        if($resultado->num_rows > 0){
            $res =  $resultado->fetCh_assoc();
        }else{
            $res =  null;
        }
        return $res;
    }
///////////////////////////////////////////////////////////////////////////////////////////    
    public static function obtenerFechaActivacionCronograma($anio, $gestion) {
        
        $consulta = "SELECT fecha_activacion FROM cronograma_academico WHERE anio='$anio' AND gestion='$gestion'";
        return ConexionBD::getConexion()->query($consulta)->fetch_assoc()['fecha_activacion'];
    }
    
    public static function eliminar($anio, $gestion) {
        
        self::validarAnioGestion($anio, $gestion);
        $fechaActivacion = self::obtenerFechaActivacionCronograma($anio, $gestion);
        
        if (Validador::fechaEsMenor($fechaActivacion, date('Y-m-d'))) {
            throw new ValidacionExcepcion('No se puede modificar un Cronograma si la fecha de activación ya se alcanzo');
        }
        else {
            $consulta = "DELETE FROM cronograma_academico WHERE anio=$anio AND gestion=$gestion";
            if (!ConexionBD::getConexion()->query($consulta)) {
                throw new EliminarExcepcion('Cronograma académico');
            }
        }
    }
    
    public static function obtenerCronogramasActivados() {

        $consulta = 'SELECT * FROM cronograma_academico';
        $conn = ConexionBD::getConexion();
        $consultaResultado = $conn->query($consulta);
        $resultado = [];
        
        while ($fila = $consultaResultado->fetch_assoc()) {
            $anio = $fila['anio'];
            $gestion = $fila['gestion'];
            $fechaActivacion = $fila['fecha_activacion'];
            if (Validador::fechaEsMenorIgual($fechaActivacion, date('Y-m-d'))) {
                array_push($resultado, "$anio - $gestion");
            }   
        }
        return $resultado;
    }
    
    public static function obtenerTodosCronogramas() {

        $consulta = 'SELECT anio, gestion FROM cronograma_academico';
        $conn = ConexionBD::getConexion();
        $consultaResultado = $conn->query($consulta);
        $resultado = [];
        while ($fila = $consultaResultado->fetch_assoc()) {
            $anio = $fila['anio'];
            $gestion = $fila['gestion'];
            array_push($resultado, "$anio - $gestion");
            
        }
        return $resultado;
    }
}