<?php
const RAIZ = './..';
include_once RAIZ . '/lib/sesion_store.php';
include_once RAIZ . '/lib/funciones_privilegios.php';
bloquearBandejaSolicitudes();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <?php include ('../cabecera.inc'); ?>
        <title>Lista</title>
        <link rel="stylesheet" href="bandeja_solicitudes.css">
    </head>
    <?php  
        $val = ($_GET['var']);

        if($val == 'auditorio')
        {
            include  "bandeja-auditorios/solicitudes_auditorio.php";

        }
        elseif($val == 'laboratorio')
        {
            include  "bandeja-laboratorios/solicitudes_laboratorio.php";
        }
        elseif($val == 'aula')
        {
            include  "bandeja-aulas/solicitudes_aula.php";
        }
        else
        {
            header('Location: ./index.php');
        }
    ?>

</html>