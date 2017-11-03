<?php

const RAIZ = '../..';
require_once RAIZ.'/lib/phpmailer/PHPMailerAutoload.php';
require_once RAIZ.'/lib/my-mailer/MyMailer.php';
include_once RAIZ.'/interfazbd/ConexionBD.php';
include_once RAIZ.'/interfazbd/Validador.php';
include_once RAIZ.'/interfazbd/ValidacionExcepcion.php';

$idRespuesta = null;
$idReserva = null;

function marcarLeido($idSolicitudReserva) {
        
    $consulta = 'UPDATE solicitud_reserva SET';
    $consulta .= " leido=1";
    $consulta .= " WHERE solicitud_reserva.id_solicitud_reserva='$idSolicitudReserva'";
     return pg_query(ConexionBD::getConexion(), $consulta);
}

function insertarMensaje (
    $mensaje,$idSolicitudReserva,$aceptadoRechazado, $representante,$cargoRepresentante){
    global $idRespuesta;
    $consulta = 'INSERT INTO respuesta';
    $consulta .= ' (aceptado, mensaje, id_solicitud_reserva, representante, cargo_representante) VALUES';
    $consulta .= " ('$aceptadoRechazado', '$mensaje', '$idSolicitudReserva', '$representante', '$cargoRepresentante')";
    $resultadoConsulta = pg_query(ConexionBD::getConexion(), $consulta);
    $consulta_insercion = pg_query(ConexionBD::getConexion(), "SELECT lastval();");
    $idRespuesta = pg_fetch_row($consulta_insercion)[0];
    return $resultadoConsulta;
}

function eliminarReservas($listaConflictos){
    if ($listaConflictos!="vacio") {
        for($indice = 0 ; $indice<count($listaConflictos);$indice++){
            $idEliminar = $listaConflictos[$indice]['id_reserva'];
            $consulta = "DELETE FROM reserva WHERE reserva.id_reserva = '$idEliminar'";
            
            if (!pg_query(ConexionBD::getConexion(), $consulta)) {
                return false;
            }
        }
    }
    return true;
}

function crearReserva($fecha,$horaInicio,$horaFin,$evento){
    global $idReserva;
    $consulta = 'INSERT INTO reserva';
    $consulta .= ' (fecha, hora_inicio, hora_fin, evento) VALUES';
    $consulta .= " ('$fecha', '$horaInicio', '$horaFin', '$evento')";
    $resultadoConsulta = pg_query(ConexionBD::getConexion(), $consulta);
    $consulta_insercion = pg_query(ConexionBD::getConexion(), "SELECT lastval();");
    $idReserva = pg_fetch_row($consulta_insercion)[0];
    return $resultadoConsulta;
}

function crearReservaSolicitada($responsable,$descripcion,$institucion){
    global $idReserva,$idRespuesta;
    $consulta = 'INSERT INTO reserva_solicitada';
    $consulta .= ' (id_reserva,responsable, descripcion, institucion, id_respuesta) VALUES';
    $consulta .= " ( '$idReserva','$responsable', '$descripcion', '$institucion', '$idRespuesta')";
    $resultadoConsulta = pg_query(ConexionBD::getConexion(), $consulta);
    return $resultadoConsulta;
}

function realizarReservaCompleta($mensaje,$idSolicitudReserva,$aceptadoRechazado, $representante,$cargoRepresentante,$listaConflictos,
        $fecha,$horaInicio,$horaFin,$evento,$responsable,$descripcion,$institucion){
    //pg_query(ConexionBD::getConexion(), "BEGIN work");
    //pg_query(ConexionBD::getConexion(), "lock table reserva in exclusive mode");
    if (insertarMensaje($mensaje, $idSolicitudReserva, $aceptadoRechazado, $representante, $cargoRepresentante)) {
        if (marcarLeido($idSolicitudReserva)) {
            if ($aceptadoRechazado == 1) {
                if (eliminarReservas($listaConflictos)){
                    if (crearReserva($fecha,$horaInicio,$horaFin,$evento)) {
                        if (crearReservaSolicitada($responsable, $descripcion, $institucion)) {
                            pg_query(ConexionBD::getConexion(), "COMMIT work");
                            return true;
                        }else{
                            pg_query(ConexionBD::getConexion(), "ROLLBACK work");
                            return false;
                        }
                    }else{
                        pg_query(ConexionBD::getConexion(), "ROLLBACK work");
                        return false;
                    }
                }else{
                    pg_query(ConexionBD::getConexion(), "ROLLBACK work");
                    return false;
                }
            }else{
                pg_query(ConexionBD::getConexion(), "COMMIT work");
                return true;
            }
        }else{
            pg_query(ConexionBD::getConexion(), "ROLLBACK work");
            return false;
        }
    }else{
        pg_query(ConexionBD::getConexion(), "ROLLBACK work");
        return false;
    }
}

function enviarMailsConflictos($listaAcademicas,$mails){
    
    if ($listaAcademicas!="vacio") {
        $res = true;
        $dentroWhile = false;
        for($i = 0 ; $i<count($listaAcademicas);$i++){
            
            $mensajeEliminacion = "<p>";
            $mensajeEliminacion .= "Notificamos que su reserva de asunto: ".$listaAcademicas[$i]['asunto'] ;
            $mensajeEliminacion .= " con fecha ".$listaAcademicas[$i]['fecha'];
            $mensajeEliminacion .= " que comenzara a horas ".$listaAcademicas[$i]['hora_inicio'];
            $mensajeEliminacion .= " y terminara a horas ".$listaAcademicas[$i]['hora_fin'];
            $mensajeEliminacion .= " ha sido eliminada por motivo de Aceptacion de una Reserva Solicitada. ";
            $mensajeEliminacion .= "</p><br><p>";
            $mensajeEliminacion .= " Agradecemos su comprension.";
            $mensajeEliminacion .= "</p><br><p>";
            $mensajeEliminacion .= " Para obtener mas informacion puede comunicarse con el administrador de la pagina del Sistema Para la reserva del auditorio.";
            $mensajeEliminacion .= "</p>";
            
            $res = $res && MyMailer::sendMailPhpMailer($mails[$i]['correo'], $mensajeEliminacion);
            $dentroWhile = true;
            
        }
    
        return $res && $dentroWhile ;
    }
    return false;
}

function obtenerCorreos($listaAcademicas){
    $conexion = ConexionBD::getConexion();
    $lista = [];
    for($i = 0 ; $i<count($listaAcademicas);$i++){
        $idBuscarCorreo = $listaAcademicas[$i]['id_reserva'];
        $consulta = "SELECT correo_usuario.correo FROM responsable_reserva, correo_usuario WHERE responsable_reserva.id_reserva = '$idBuscarCorreo' AND correo_usuario.nombre_usuario = responsable_reserva.nombre_usuario ";
        $resultadoConsulta = pg_query($conexion, $consulta);
        while ($fila = pg_fetch_assoc($resultadoConsulta)) {
            array_push($lista, $fila);
        }
    }
    return $lista;
}

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $direccionCorreo = $_POST['correo'];
    $mensajeContenido = $_POST['content'];
    $idSolicitudReserva = $_POST['id_solicitud_reserva'];
    $aceptadoRechazado = $_POST['aceptado_rechazado'];
    $representante = $_POST['representante'];
    $cargoRepresentante = $_POST['cargo_representante'];
    $listaAcademicas = $_POST['lista_academicas'];
    $listaSolicitadas = $_POST['lista_solicitadas'];

    $fecha= $_POST['fecha'];
    $horaInicio= $_POST['hora_inicio'];
    $horaFin= $_POST['hora_fin'];
    $evento= $_POST['evento'];
    $responsable= $_POST['responsable'];
    $descripcion= $_POST['descripcion'];
    $institucion= $_POST['institucion'];
    
    
    try {
        Validador::revisarCampoEsNombre($representante, 'Representante');
        Validador::revisarCampoEsNombre($cargoRepresentante, 'Cargo Representante');

        if ($aceptadoRechazado == 'ACEPTADO') {
            $aceptadoRechazado = 1;
        } else {
            $aceptadoRechazado = 0;
        }
        
        $smtpMailer = false;

        $listaCorreosEliminacion = obtenerCorreos($listaAcademicas);
        
        $crearReserva = realizarReservaCompleta($mensajeContenido, $idSolicitudReserva, 
                    $aceptadoRechazado, $representante, $cargoRepresentante, 
                    $listaAcademicas, $fecha, $horaInicio, $horaFin, $evento,
                    $responsable, $descripcion, $institucion);
        
        if($smtpMailer){
            if ($crearReserva) {

                $mailEliminaciones = enviarMailsConflictos($listaAcademicas,$listaCorreosEliminacion);
                
                $envioMail = MyMailer::sendMailSMTP($direccionCorreo,$mensajeContenido);
                
                echo json_encode(["exito" => true,"mail"=>$envioMail,"mail_eliminacion"=>$mailEliminaciones]);

            }else {
                echo json_encode(["exito" => false, "mail"=> false]);
            }
        }else{
            if ($crearReserva) {

                $mailEliminaciones = enviarMailsConflictos($listaAcademicas,$listaCorreosEliminacion);
                
                $envioMail = MyMailer::sendMailPhpMailer($direccionCorreo,$mensajeContenido);
                
                echo json_encode(["exito" => true,"mail"=>$envioMail,"mail_eliminacion"=>$mailEliminaciones]);

            }else {
                echo json_encode(["exito" => false,"mail"=>false]) ;
            }
        }
    } catch (ValidacionExcepcion $ex) {
        echo json_encode(['exito' => false,'mail'=>false, 'error' => $ex->getMessage()]);
    }

    
}else {
    header('Location: index.php');
}

