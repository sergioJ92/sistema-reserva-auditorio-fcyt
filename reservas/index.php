<?php
const RAIZ = '..';
include_once RAIZ . '/lib/sesion_store.php';
include_once RAIZ . '/lib/funciones_privilegios.php';
require_once RAIZ . '/interfazbd/CronogramaAcademico.php';

bloquearCalendarioYMisReservas();

function crearOption($elemento) {

    echo "<option value=\"$elemento\">$elemento</option>";
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
    <body>
        <?php include RAIZ . '/navegacion.inc'; ?>
        <div class="container">
            <div class="row padding-pequeno sin-padding-bottom">
                <div class="col-md-4">
                    <h3 class="inline">Reservas</h3>
                </div>
                <div class="col-md-8 text-right form-inline">
                    <label for="selAnioGestion">
                        <span>Seleccione el Cronograma Académico</span>
                    </label>
                    <select id="selAnioGestion" class="form-control" onchange="cuandoCambiaAnioGestion()">
                        <option selected="" hidden="" value="null">Año y Gestion</option>
                        <?php array_map(crearOption, CronogramaAcademico::obtenerCronogramasActivados()); ?>
                    </select>
                </div>
            </div>
            <hr class="separador-pequeno">
            <div id="contenedor-msg"></div>
            <div id="calendario">
                <div class="alert alert-info">
                    <label for="selAnioGestion">Seleccione un Cronograma Académico</label> para visualizar su contenido en el Calendario
                </div>
            </div>
        </div>
        <link href="calendario.css" type="text/css" rel="stylesheet">
        <script src="calendario.js"></script>
        <script src="reservas.js"></script>
        <?php include RAIZ . '/pie.inc'; ?>
    </body>
</html>