<?php
const RAIZ = '..';
include_once RAIZ . '/lib/sesion_store.php';
include_once RAIZ . '/lib/funciones_privilegios.php';
require_once RAIZ . '/interfazbd/CronogramaAcademico.php';
require_once RAIZ . '/interfazbd/SolicitudReserva.php';

bloquearCalendarioYMisReservas();

function crearOption($elemento) {

    echo "<option value=\"$elemento\">$elemento</option>";
}
function crearOptionConIndices($elemento) {

    echo "<option value=\"$elemento[0]\">$elemento[1]</option>";
}
?>
<!DOCTYPE html>
<html>
    <head>
        <?php include RAIZ . '/cabecera.inc'; ?>
        <script src="<?php echo RAIZ; ?>/lib/moment.js"></script>
        <script src="<?php echo RAIZ; ?>/lib/moment-with-locales.js"></script>
        <script src="<?php echo RAIZ; ?>/lib/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
        <link rel="stylesheet" href="<?php echo RAIZ; ?>/lib/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css">
    </head>
    <?php  
        $val = ($_GET['var']);

        if($val == 'auditorio')
        {
            include  "reservas-auditorio/calendario_auditorio.php";

        }
        elseif($val == 'laboratorio')
        {
            include  "reservas-laboratorio/calendario_laboratorio.php";
        }
        elseif($val == 'aula')
        {
            include  "reservas_aula/calendario_aula.php";
        }
        else
        {
            header('Location: ./index.php');
        }
    ?>

</html>