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


    <body>
        <?php include RAIZ . '/navegacion.inc'; ?>
        <div class="container">
            <div class="row padding-pequeno sin-padding-bottom">
                <div class="col-md-12">
                    <h3 class="inline">Reservas</h3>
                </div>
                <div class="row-center  " >
                    <div id='auditorio-oculto' style="display: none;">
                        <div  class="col-md-4 form-group" id="auditorio">
                            <label>Seleccionar Auditorio <span class="rojo">*</span></label>
                            <select class="form-control" id="selAuditorio">
                                <option selected="" value="null" hidden="">Nombre Auditorio</option>
                                <?php array_map(crearOptionConIndices, SolicitudReserva::obtenerTodosLosAuditorios()); ?>
                            </select>
                        </div>
                    </div>

                    <div id='aula-oculto' style="display: none;">
                        <div  class="col-md-4 form-group" id="edificio">
                            <label>Seleccionar Edificio <span class="rojo">*</span></label>
                            <select name="selEdificio" class="form-control" id="selEdificio">
                                <option selected="" value="null" hidden="">Nombre Edificio</option>
                                <?php array_map(crearOption, SolicitudReserva::obtenerTodosLosEdificios()); ?>

                            </select>
                        </div>
                        
                        <div  class="col-md-4 form-group" id="piso">
                            <label>Seleccionar Piso <span class="rojo">*</span></label>
                            <select name="selPiso" class="form-control" id="selPiso">
                            </select>
                        </div>

                        <div  class="col-md-4 form-group" id="aula">
                            <label>Seleccionar Aula <span class="rojo">*</span></label>
                            <select name="selAula" class="form-control" id="selAula">
                            </select>
                        </div>    
                    </div>
                    
                     <div id='laboratorio-oculto' style="display: none;">  
                        <div  class="col-md-4 form-group" id="departamento">
                            <label>Seleccionar Departamento <span class="rojo">*</span></label>
                            <select name="selDepartamento" class="form-control" id="selDepartamento">
                                <option selected="" value="null" hidden="">Nombre Departamento</option>
                                <?php array_map(crearOption, SolicitudReserva::obtenerTodosLosDepartamentos()); ?>

                            </select>
                        </div>
                        <div  class="col-md-4 form-group" id="laboratorio">
                            <label>Seleccionar Laboratorio <span class="rojo">*</span></label>
                            <select name="selLaboratorio" class="form-control" id="selLaboratorio">
                            </select>
                        </div>    
                    </div>

                    <div class="col-md-4 form-group">
                        <label for="selAnioGestion">
                            <span>Seleccione el Cronograma Académico</span>
                        </label>
                        <select id="selAnioGestion" class="form-control" onchange="cuandoCambiaAnioGestion()">
                            <option selected="" hidden="" value="null">Año y Gestion</option>
                            <?php array_map(crearOption, CronogramaAcademico::obtenerCronogramasActivados()); ?>
                        </select>
                    </div>
                </div>
            </div>
            <hr class="separador-pequeno">
            <div id="contenedor-msg"></div>
            <div id="calendario">
                <div class="alert alert-info">
                    <label for="selAnioGestion">Seleccione un Cronograma Académico y un Auditorio</label> para visualizar su contenido en el Calendario
                </div>
            </div>
        </div>
        <link href="calendario.css" type="text/css" rel="stylesheet">
        <script src="calendario.js"></script>
        <?php  
            $val = ($_GET['var']);

            if($val == 'auditorio')
            {
                //include  "reservas-auditorio/calendario_auditorio.php";
                echo '<script src="reservas-auditorio/auditorio.js"></script>';
            }
            elseif($val == 'laboratorio')
            {
                //include  "reservas-laboratorio/calendario_laboratorio.php";
                echo '<script src="reservas-laboratorio/laboratorio.js"></script>';
            }
            elseif($val == 'aula')
            {
                //include  "reservas-aula/calendario_aula.php";
                echo '<script src="reservas-aula/aula.js"></script>';
            }
            else
            {
                header('Location: ./index.php');
            }
            echo '<script src="reservas.js"></script>';
        ?>
        <?php include RAIZ . '/pie.inc'; ?>
    </body>
</html>
