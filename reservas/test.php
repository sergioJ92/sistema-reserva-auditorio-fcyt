<?php

const RAIZ = '..';

require_once(RAIZ . '/interfazbd/ConexionBD.php');
require_once(RAIZ . '/interfazbd/Validador.php');
require_once(RAIZ . '/interfazbd/ValidacionExcepcion.php');

function validarFormatoCampos(
        $fecha, $horaInicio, $horaFin, $idAsunto, 
        $codigoMateria, $idContenido, $nombreUsuario) {
    
    Validador::revisarCampoVacio($fecha, 'Fecha');
    Validador::revisarCampoVacio($horaInicio, 'Hora inicio');
    Validador::revisarCampoVacio($horaFin, 'Hora fin');
    Validador::revisarCampoVacio($idAsunto, 'Asunto');
    Validador::revisarCampoVacio($codigoMateria, 'Materia');
    Validador::revisarCampoVacio($idContenido, 'Actividad reservable');
    Validador::revisarCampoVacio($nombreUsuario, 'Responsable');
    
    Validador::revisarCampoEsFecha($fecha, 'Fecha');
    Validador::revisarCampoEsHoraMinuto($horaInicio, 'Hora inicio');
    Validador::revisarCampoEsHoraMinuto($horaFin, 'Hora fin');
}

function validarReserva($fecha, $horaInicio, $horaFin, 
        $codigoMateria, $idContenido, $idAsunto, $nombreUsuario) {
    
    validarFormatoCampos($fecha, $horaInicio, $horaFin, $idAsunto, $codigoMateria, $idContenido, $nombreUsuario);
    try {
        Validador::revisarCampoEsNumeroEnteroPositivo($idAsunto, 'Asunto');
    } catch (ValidacionExcepcion $ex) {
        throw new ValidacionExcepcion('Debe seleccionar un asunto');
    }
    try {
        Validador::revisarCampoEsNumeroEnteroPositivo($codigoMateria, 'Materia');
    } catch (ValidacionExcepcion $ex) {
        throw new ValidacionExcepcion('Debe seleccionar una materia');
    }
    try {
        Validador::revisarCampoEsNumeroEnteroPositivo($idContenido, 'Actividad reservable');
    } catch (ValidacionExcepcion $ex) {
        throw new ValidacionExcepcion('La nueva reserva debe estar asociada a una Actividad que permite reservas');
    }
    
    Validador::revisarCampoEsAlfaNumerico($nombreUsuario, 'Responsable');
    Validador::revisarHoraEsMenor($horaInicio, $horaFin, 'Hora inicio', 'Hora fin');
    Validador::revisarFechaEsMenor(date('Y-m-d H:i'), "$fecha $horaInicio", 'Ahora', 'Fecha y hora inicio');
}

function guardarReserva($fecha, $horaInicio, $horaFin, 
        $codigoMateria, $idContenido, $idAsunto, $nombreUsuario) {
    
    validarReserva($fecha, $horaInicio, $horaFin, 
            $codigoMateria, $idContenido, $idAsunto, $nombreUsuario);
    
    $evento = 'Reserva académica';
    $conn = ConexionBD::getConexion();
    //HECHO
    pg_query($conn, "BEGIN");
    
    $insertarReserva = 'INSERT INTO reserva (fecha, hora_inicio, hora_fin, evento)';
    $insertarReserva .= " VALUES ('$fecha', '$horaInicio', '$horaFin', '$evento')";
    if (pg_query($conn, $insertarReserva)) {
        return guardarReservarAcademica($conn, $codigoMateria, $idContenido, $idAsunto, $nombreUsuario);
    }
    else {
        //HECHO
        pg_query($conn, "ROLLBACK");
        throw new ValidacionExcepcion('Ya existe una reserva en esa hora y fecha');
    }
}

function guardarReservarAcademica($conn, $codigoMateria, $idContenido, $idAsunto, $nombreUsuario) {
    
    $consulta_insercion = pg_query($conn, "SELECT lastval();");
    $idReserva = pg_fetch_row($consulta_insercion)[0];
    $insertarAcademica = 'INSERT INTO reserva_academica (id_reserva, codigo_materia, id_contenido, id_asunto)';
    $insertarAcademica .= " VALUES ('$idReserva', '$codigoMateria', '$idContenido', '$idAsunto')";

    if (pg_query($conn, $insertarAcademica)) {
        $insertarResponsable = 'INSERT INTO responsable_reserva (id_reserva, nombre_usuario)';
        $insertarResponsable .= " VALUES ('$idReserva', '$nombreUsuario')";

        if (pg_query($conn, $insertarResponsable)) {
            //HECHO
            pg_query($conn, "COMMIT");
            return $idReserva;
        }
        else {
            //HECHO
            pg_query($conn, "ROLLBACK");
            throw new GuardarExcepcion('Responsable reserva');
        }
    }
    else {
        //HECHO
        pg_query($conn, "ROLLBACK");
        throw new GuardarExcepcion('Reserva académica');
    }
}

function obtenerReservaAcademica($idReserva) {
    
    $consulta = "SELECT r.fecha, r.hora_inicio, r.hora_fin, r.evento, asu.asunto, ma.nombre_materia materia, u.nombres responsable FROM reserva r, reserva_academica ra, asunto asu, materia ma, responsable_reserva rr, usuario u WHERE r.id_reserva = ra.id_reserva AND asu.id_asunto = ra.id_asunto AND ma.codigo_materia = ra.codigo_materia AND ra.id_reserva = rr.id_reserva AND rr.nombre_usuario = u.nombre_usuario AND r.id_reserva = '$idReserva'";
    $resConsulta = pg_query(ConexionBD::getConexion(), $consulta);
    if (pg_num_rows($resConsulta) == 1) {
        return pg_fetch_assoc($resConsulta);
    }
    else {
        throw new Exception("No se encontro la reserva con el id '$idReserva'");
    }
}

    $fecha = '2017-11-13';
    $horaInicio = '18:45';
    $horaFin = '19:45';
    $codigoMateria = 2010024;
    $idContenido = 10;
    $idAsunto = 2;
    $nombreUsuario = 'docente';
    
    try {
        $idReserva = guardarReserva($fecha, $horaInicio, $horaFin, 
                $codigoMateria, $idContenido, $idAsunto, $nombreUsuario);
        $reserva = obtenerReservaAcademica($idReserva);
        echo "exito";
    }
    catch (ValidacionExcepcion $ex) {
        echo "fallo";
    }