<?php

const RAIZ = '../..';
include_once RAIZ.'/interfazbd/ConexionBD.php';
require_once RAIZ.'/interfazbd/Validador.php';
require_once RAIZ.'/interfazbd/ValidacionExcepcion.php';
require_once RAIZ.'/interfazbd/CronogramaAcademico.php';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    header('Content-Type: application/json');
    $conectado = ConexionBD::conectar();
    $entrada = $_POST;
    
    $anio = Validador::desinfectarEntrada($entrada['anio']);
    $gestion = Validador::desinfectarEntrada($entrada['gestion']);
    $fechaHoraInicio = Validador::desinfectarEntrada($entrada['fecha_inicio']);
    $fechaHoraFin = Validador::desinfectarEntrada($entrada['fecha_fin']);
    $fechaActivacion = Validador::desinfectarEntrada($entrada['fecha_activacion']);
    
    $periodoHoras = Validador::desinfectarEntrada($entrada['periodo_horas']);
    $periodoMinutos = Validador::desinfectarEntrada($entrada['periodo_minutos']);
    $horaInicioJornada = Validador::desinfectarEntrada($entrada['hora_inicio_jornada']);
    $horaFinJornada = Validador::desinfectarEntrada($entrada['hora_fin_jornada']);
    $horaFinSabado = Validador::desinfectarEntrada($entrada['hora_fin_sabado']);
 
    try {
        Validador::revisarCampoVacio($fechaHoraInicio,'Fecha y hora Inicio');
        Validador::revisarCampoVacio($fechaHoraFin, 'Fecha y hora Fin');
        Validador::revisarCampoVacio($fechaActivacion, 'Fecha de activación');
        Validador::revisarCampoVacio($periodoHoras, 'Horas de duración del periodo');
        Validador::revisarCampoVacio($periodoMinutos, 'Minutos de duración del periodo');
        Validador::revisarCampoVacio($horaInicioJornada, 'Hora Inicio de la Jornada');
        Validador::revisarCampoVacio($horaFinJornada, 'Hora Fin de la Jornada');
        Validador::revisarCampoVacio($horaFinSabado, 'Hora Fin Sabado');
        
        $duracionPeriodo = $periodoHoras.':'.$periodoMinutos;
        CronogramaAcademico::validarConfiguracionPeriodo($periodoHoras, $periodoMinutos);
        // Falta validar horas de jornada y sabado
        
        if (!CronogramaAcademico::guardarCronograma(
                $anio, $gestion, $fechaHoraInicio, $fechaHoraFin, 
                $duracionPeriodo, $horaInicioJornada,
                $horaFinJornada, $horaFinSabado, $fechaActivacion))
        {
            // La clave ya existe
            CronogramaAcademico::actualizarCronograma(
                $anio, $gestion, $fechaHoraInicio, $fechaHoraFin, $duracionPeriodo, $horaInicioJornada,
                $horaFinJornada, $horaFinSabado, $fechaActivacion);
        }

        echo json_encode(['exito' => true]);
    } catch (ValidacionExcepcion $ex) {
        echo json_encode(['exito' => false, 'error' => $ex->getMessage()]);
    }
} 
else {
    header('Location: index.php');
}
