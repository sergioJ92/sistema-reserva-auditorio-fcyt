<?php
const RAIZ = './..';
include_once RAIZ . '/lib/sesion_store.php';
include_once RAIZ . '/lib/funciones_privilegios.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <?php include(RAIZ . "/cabecera.inc"); ?>

        <script src="<?php echo RAIZ; ?>/lib/moment.js"></script>
        <script src="<?php echo RAIZ; ?>/lib/moment-with-locales.js"></script>
        <script src="<?php echo RAIZ; ?>/lib/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
        <link rel="stylesheet" href="<?php echo RAIZ; ?>/lib/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css">
        <link rel="stylesheet" href="crear_solicitud.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    </head>
    <?php  
        $val = ($_GET['variable1']);

        if($val == 'auditorio')
        {
            include  "auditorio.php";

        }
        elseif($val == 'laboratorio')
        {
            include  "laboratorio.php";
        }
        else
        {
            include  "aula.php";
        }
        
    ?>
</html>