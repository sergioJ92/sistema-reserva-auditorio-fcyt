<?php
const RAIZ = '../..';
include_once RAIZ .'/lib/sesion_store.php';
include_once RAIZ .'/lib/funciones_privilegios.php';
bloquearCronograma();
require_once RAIZ . '/interfazbd/ConexionBD.php';

function obtenerMayorMenorAnioCalenAcademico() {

    $consulta = 'SELECT max(anio) AS mayoranio, min(anio) AS menoranio FROM cronograma_academico';
    $resConsulta = ConexionBD::getConexion()->query($consulta);
    if ($resConsulta->num_rows > 0) {
        return $resConsulta->fetch_assoc();
    }
    return null;
}

$mayorMenor = obtenerMayorMenorAnioCalenAcademico();
if ($mayorMenor != null) {
    $menorAnio = $mayorMenor['menoranio'] ? $mayorMenor['menoranio'] : date('Y');
    $mayorAnio = $mayorMenor['mayoranio'] ? $mayorMenor['mayoranio'] : date('Y');
} else {
    $mayorAnio = date('Y');
    $menorAnio = date('Y');
}
$mayorAnio++;
$diferencia = $mayorAnio - $menorAnio;
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <?php include RAIZ . '/cabecera.inc'; ?>


        <script src="<?php echo RAIZ; ?>/lib/moment.js"></script>
        <script src="<?php echo RAIZ; ?>/lib/moment-with-locales.js"></script>
        <script src="<?php echo RAIZ; ?>/lib/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
        <link rel="stylesheet" href="<?php echo RAIZ; ?>/lib/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css">
        <link rel="stylesheet" href="crear_estilo.css">
    </head>
    <body>
        <?php include RAIZ . '/navegacion.inc'; ?>
        <div class="container">
            <div class="jumbotron">
                <div class="row padding-pequeno">
                    <div class="col-md-6">
                        <h3 class="inline">Cronograma Académico <span id="cronograma-actual"></span></h3>
                    </div>
                    <div class="col-md-6 text-right igualar-titulo">
                        Campos Obligatorios <span class="rojo">*</span>
                    </div>
                </div>
                <div id="contenedor-msg" class="margen-contenedor-msg"></div>
                <fieldset>
                    <legend>Seleccionar Año y Gestion</legend>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="selecAnio">Año <span class="rojo">*</span></label>
                                <select id="selecAnio" class="selectpicker form-control" >
                                    <option hidden disabled selected value="null">Seleccione Año</option>
                                    <?php for ($i = 0; $i <= $diferencia; $i++) { ?>
                                        <option value="<?php echo $menorAnio + $i; ?>"><?php echo $menorAnio + $i; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="selecGestion">Gestion <span class="rojo">*</span></label>
                                <select name="gestion" id="selecGestion" class="selectpicker form-control">
                                    <option hidden disabled selected value="null">Seleccione Gestion</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row text-center">
                        <div id="contenedor-msg-cronograma" class="col-sm-12"></div>
                    </div>
                </fieldset>
                <fieldset>
                    <legend>Duracion del Cronograma</legend>
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label for="fechahorainicio">Fecha y hora Inicio <span class="rojo">*</span></label>
                            <div class="input-group date">
                                <input id="fechahorainicio" class="form-control" type="text">
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="fechahorafin">Fecha y hora Fin <span class="rojo">*</span></label>
                            <div class="input-group date">
                                <input id="fechahorafin" class="form-control" type="text">
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="fechaactivacion">Fecha de activación <span class="rojo">*</span></label>
                            <div class="input-group date">
                                <input id="fechaactivacion" class="form-control" type="text">
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>
                    </div>
                </fieldset>
                <fieldset>
                    <legend>Configurar Horario</legend>
                    <fieldset>
                        <legend>Duracion Periodo Diario</legend>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="periodo_horas">Horas <span class="rojo">*</span></label>
                                <input value="1" class="form-control" id="periodo_horas" type="number" min="0" max="23">
                            </div>
                            <div class="col-md-6 form-group"> 
                                <label for="periodo_minutos">Minutos <span class="rojo">*</span></label>
                                <input value="30" class="form-control" id="periodo_minutos"  type="number" min="0" max="59">
                            </div>
                        </div>
                    </fieldset>
                    <fieldset>
                        <legend>Inicio y Fin de la Jornada</legend>
                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label for="hora_inicio_jornada"> Hora inicio de jornada <span class="rojo">*</span></label>
                                <div class="input-group date">
                                    <input id="hora_inicio_jornada" type="text" class="form-control" autocomplete="off">
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-time"></span>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="hora_fin_jornada"> Hora fin de jornada <span class="rojo">*</span></label>
                                <div class="input-group date">
                                    <input id="hora_fin_jornada" type="text" class="form-control" autocomplete="off">
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-time"></span>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="hora_fin_sabado"> Hora fin día sábado <span class="rojo">*</span></label>
                                <div class="input-group date">
                                    <input id="hora_fin_sabado" type="text" class="form-control" autocomplete="off">
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-time"></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </fieldset>
                <div class="row">
                    <div class="col-xs-12 text-center">
                        <div class="form-group">
                            <button id="guardar" type="button" class="btn btn-primary padding-boton">Guardar</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-xs-12 text-right">
                    <button id="btn-actualizar-contenido" href="../contenido/" class="btn btn-default" onclick="irActualizarContenido()">Actualizar Contenido</button>
                    <button id="btn-eliminar" class="btn btn-warning" onclick="eliminarCronograma()">Eliminar Cronograma</button>
                </div>
            </div>
            <script type="text/javascript" src="crear.js"></script>
        </div>
         <div class="modal fade" id="modalEliminar" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header estilo-modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Cronograma</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <div class="col-md-12 text-center"><b>¿Está seguro de eliminar el cronograma?</b></div>
                        </div>
                    </div>
                    <div class="modal-footer estilo-modal-footer">
                        <div class="col-md-12">
                            <center>
                                <button type="button" class="btn btn-default" data-dismiss="modal" id="boton-eliminar-si">Si</button>
                                <button type="button" class="btn btn-default" data-dismiss="modal" id="boton-eliminar-no">No</button>
                            </center>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include RAIZ . '/pie.inc'; ?>
    </body>
</html>
