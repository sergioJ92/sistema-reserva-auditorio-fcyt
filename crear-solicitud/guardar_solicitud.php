<?php

const RAIZ = '..';
require_once RAIZ.'/lib/phpmailer/PHPMailerAutoload.php';
require_once RAIZ.'/lib/my-mailer/MyMailer.php';
require_once RAIZ.'/interfazbd/ConexionBD.php';
require_once RAIZ.'/interfazbd/Validador.php';
require_once RAIZ.'/interfazbd/ValidacionExcepcion.php';

function validarCamposVacios(
        $fecha, $horaInicio, $horaFin, $responsable, $evento, $telefono, $correo) {
    
    Validador::revisarCampoVacio($fecha, 'Fecha');
    Validador::revisarCampoVacio($horaInicio, 'Hora inicio');
    Validador::revisarCampoVacio($horaFin, 'Hora fin');
    Validador::revisarCampoVacio($responsable, 'Responsable');
    Validador::revisarCampoVacio($evento, 'Evento');
    Validador::revisarCampoVacio($telefono, 'Télefono');
    Validador::revisarCampoVacio($correo, 'Correo');
}

function validarSolicitud(
        $fecha, $horaInicio, $horaFin, $responsable, 
        $evento, $telefono, $correo, $institucion) {
    
    validarCamposVacios($fecha, $horaInicio, $horaFin, $responsable, $evento, $telefono, $correo);
    
    Validador::revisarCampoEsFecha($fecha, 'Fecha');
    if (Validador::fechaEsMenor($fecha, date('Y-m-d'))) {
        throw new ValidacionExcepcion('No se puede solicitar reserva con fecha menor a la de hoy');
    }
    
    Validador::revisarCampoEsNombre($responsable, 'Responsable');
    Validador::revisarCampoEsHoraMinuto($horaInicio, 'Hora inicio');
    Validador::revisarCampoEsHoraMinuto($horaFin, 'Hora fin');
    Validador::revisarHoraEsMenor($horaInicio, $horaFin, 'Hora inicio', 'Hora fin');
    
    if (!empty($institucion)) {
        Validador::revisarCampoEsAlfaNumerico($institucion, 'Institucion');
    }
    
    Validador::revisarCampoEsAlfaNumerico($evento, 'Evento');
    Validador::revisarCampoEsCorreo($correo, 'Correo');
    Validador::revisarCampoEsNumeroEnteroPositivo($telefono, 'Teléfono');
}

function insertarSolicitudDeReserva(
        $fecha, $horaInicio, $horaFin, $responsable, 
        $institucion, $telefono, $correo, $evento, $descripcion) {
    
    validarSolicitud($fecha, $horaInicio, $horaFin, $responsable, $evento, $telefono, $correo, $institucion);
    
    $conn = ConexionBD::getConexion();
    $conn->autocommit(false);
    $insertarSolicitud = 'INSERT INTO solicitud_reserva';
    $insertarSolicitud .= ' (id_solicitud_reserva, leido, fecha, hora_inicio, hora_fin, responsable, institucion, evento, descripcion) VALUES';
    $insertarSolicitud .= " ('null', '0', '$fecha', '$horaInicio', '$horaFin', '$responsable', '$institucion', '$evento', '$descripcion')";
    
    if ($conn->query($insertarSolicitud)) {
        return insertarTelefonoYCorreo($conn, $telefono, $correo);
    }
    else {
        $conn->rollback();
        throw new GuardarExcepcion('Solicitud de reserva');
    }
}

function insertarTelefonoYCorreo($conn, $telefono, $correo) {
    
    $get_id = $conn->insert_id;
    $insertarTelefono = "INSERT INTO telefono (id_solicitud_reserva, telefono) VALUES ('$get_id', '$telefono')";

    if ($conn->query($insertarTelefono)) {

        $insertarCorreo = "INSERT INTO correo (id_solicitud_reserva, correo) VALUES ('$get_id', '$correo')";
        if ($conn->query($insertarCorreo)) {
            $conn->commit();
            return $get_id;
        }
        else {
            $conn->rollback();
            throw new GuardarExcepcion('Correo');
        }
    }
    else {
        $conn->rollback();
        throw new GuardarExcepcion('Telefono');
    }
}

function obtenerSolicitudDeReserva() {
    
    $consulta = "SELECT * FROM solicitud_reserva";
    $resultado = ConexionBD::getConexion()->query($consulta);
    
    if ($resultado->num_rows > 0) {
        return $resultado->fetch_assoc();
    }
    else {
        return null;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    header('Content-Type: application/json');
    $conectado = ConexionBD::conectar();
    $entrada = $_POST;
    
    $fecha = Validador::desinfectarEntrada($entrada['fecha']);
    $horaInicio= Validador::desinfectarEntrada($entrada['hora_inicio']);
    $horaFin= Validador::desinfectarEntrada($entrada['hora_fin']);
    $responsable=Validador::desinfectarEntrada($entrada['responsable']);
    $institucion= Validador::desinfectarEntrada($entrada['institucion']);
    $evento= Validador::desinfectarEntrada($entrada['evento']);
    $descripcion= Validador::desinfectarEntrada($entrada['descripcion']);
    $telefono= Validador::desinfectarEntrada($entrada['telefono']);
    $direccionCorreo= Validador::desinfectarEntrada($entrada['correo']);
    
    try {
        $mensajeContenido = 'El Código de su Solicitud de Reserva es: ';
        $asunto =  'Solicitud de Reserva';
        
        $insertado = insertarSolicitudDeReserva($fecha, $horaInicio, $horaFin, $responsable, $institucion, $telefono, $direccionCorreo, $evento, $descripcion);
        
        $smtpMailer = false;
        if ($smtpMailer) {
            if ($insertado != null) {
                $codigoGenerado = $insertado * 177;
                $insertado = true;
                $message = $mensajeContenido.$codigoGenerado;
                $mensajeEnviado = MyMailer::sendMailSMTP($direccionCorreo, $message);
                echo json_encode(["exito" => true, "mail"=> $mensajeEnviado]);
            }
            else {
                echo json_encode(["exito" => false, "mail"=> false]);
            }
        }
        else {
            if ($insertado != null) {
                $codigoGenerado = $insertado * 177;
                $insertado = true;
                $message = $mensajeContenido.$codigoGenerado;
                $mensajeEnviado = MyMailer::sendMailPhpMailer($direccionCorreo, $message);
                echo json_encode(["exito" => true, "mail"=> $mensajeEnviado]);
            }
            else {
                echo json_encode(["exito" => false, "mail"=> false]);
            }
        }
    }
    catch (ValidacionExcepcion $ex) {
        
        echo json_encode(['exito' => false, 'error' => $ex->getMessage()]);
    }
}
else {
    header('Location: index.php');
}