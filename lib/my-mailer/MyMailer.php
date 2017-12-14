<?php
class MyMailer {
    private static $asunto = "Respuesta Solicitud Reserva";
    private static $domain_username = "sincpros@gmail.com";
    
    private static $host_smpt = "smtp.gmail.com";
    private static $domain_password = "sincpro123";
    private static $smtp_secure = "ssl";
    private static $smtp_port = 465;

    public static function sendMailPhpMailer($direccion_correo,$mensaje_contenido){
        //PHPMAILER
        $mail = new PHPMailer();
        //indico a la clase que use SMTP
        $mail->IsSMTP();
        //indico el servidor de Gmail para SMTP
        $mail->Host = self::$host_smpt;

        //permite modo debug para ver mensajes de las cosas que van ocurriendo
        //$mail­>SMTPDebug = 2;
        //Debo de hacer autenticacion SMTP
        $mail->SMTPAuth = true;

        $mail->Username = self::$domain_username;
        $mail->Password = self::$domain_password;

        $mail->SMTPSecure = self::$smtp_secure;

        //indico el puerto que usa el smpt seleccionado
        $mail->Port = self::$smtp_port;

        $mail->Subject = self::$asunto;
        $mail->msgHTML($mensaje_contenido);

        $mail->SetFrom(self::$domain_username, 'Sistema de reservas: Auditorio-FCYT');

        $mail->AddAddress( $direccion_correo );
        return $mail->Send();
    }
    public static function sendMailSMTP($direccion_correo,$mensaje_contenido){
        $to = $direccion_correo;
        $subject = self::$asunto;
        $message = $mensaje_contenido;
        $from = self::$domain_username;
        $header = 'MIME-Version: 1.0' . "\r\n";
        $header .= 'Content-type: text/html; charset=utf-8' . "\r\n";
        $header .= "From:" . $from;
        
        return mail($to, $subject, $message, $header);
    }
}