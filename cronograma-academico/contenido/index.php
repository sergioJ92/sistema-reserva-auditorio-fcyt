<?php
const RAIZ = '../..';
include_once RAIZ . '/lib/sesion_store.php';
include_once RAIZ . '/lib/funciones_privilegios.php';
require_once RAIZ . '/interfazbd/CronogramaAcademico.php';
bloquearCronograma();

function crearOption($elemento) {

    echo "<option value=\"$elemento\">$elemento</option>";
}
?>
<!DOCTYPE html>
<html>
    <head>
        <?php include(RAIZ . "/cabecera.inc"); ?>
        <script src="<?php echo RAIZ; ?>/lib/moment.js"></script>
        <script src="<?php echo RAIZ; ?>/lib/moment-with-locales.js"></script>
        <script src="<?php echo RAIZ; ?>/lib/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
        <link rel="stylesheet" href="<?php echo RAIZ; ?>/lib/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css">
    </head>
    <body>
        <script src="contenido.js"></script>
        <?php include(RAIZ . "/navegacion.inc") ?>
        <div class="container">
            <div class="row padding-pequeno-top">
                <div class="col-md-6">
                    <h3 class="inline">Contenido del cronograma académico</h3>
                </div>
                <div class="col-md-6 text-right form-inline">
                    <label for="selAnioGestion">
                        <span>Seleccione el Cronograma Académico</span>
                    </label>
                    <select id="selAnioGestion" class="form-control">
                        <option selected="" hidden="" value="null">Año y Gestion</option>
                        <?php array_map(crearOption, CronogramaAcademico::obtenerTodosCronogramas()); ?>
                    </select>
                </div>
            </div>
            <hr>

            <div class="row padding-pequeno sin-padding-top">
                <div class="col-md-12 text-right">
                    <button class="btn btn-default" onclick="palanquearPanelContenido(this)">Mostrar contenido</button>
                    <a href="../crear/" class="btn btn-default">Volver al Cronograma académico</a>
                </div>
            </div>
            <div class="jumbotron">
                <div id="panel-contenido" class="panel-mostrador" style="display: none">
                    <div id="panel" class="row"></div>
                </div>
                <div id="formularios-creacion">
                    <div class="row padding-pequeno">
                        <div class="col-md-8">
                            <h4 class="inline"><b>Desde</b> <span id="fecha_inicio_crono">[fecha inicio]</span> <b>hasta</b> <span id="fecha_fin_crono">[fecha fin]</span></h4>
                        </div>
                        <div class="col-md-4 text-right igualar-titulo">
                            Campos Obligatorios <span class="rojo">*</span>
                        </div>
                    </div>
                    <ul class="nav nav-tabs">
                        <li class="active"><a data-toggle="tab" href="#tab-actividades">Actividades</a></li>
                        <li><a data-toggle="tab" href="#tab-tolerancias">Tolerancias</a></li>
                        <li><a data-toggle="tab" href="#tab-feriadosesp">Feriados Especiales</a></li>
                        <li><a data-toggle="tab" href="#tab-otro">Otro</a></li>
                    </ul>
                    <div class="tab-content">
                        <div id="contenedor-msg"></div>
                        <div id="tab-actividades" class="tab-pane active fade in">
                            <div class=" padding-pequeno">
                                <label for="titulo-actividad">Actividad <span class="rojo">*</span></label>
                                <input class="form-control" id="titulo-actividad" type="text">
                            </div>
                            <div class="row padding-pequeno sin-padding-top">
                                <div class="col-md-6 date">
                                    <label for="fechahorainicio-act">Fecha y hora Inicio <span class="rojo">*</span></label>
                                    <div class="input-group date">
                                        <input id="fechahorainicio-act" class="form-control" type="text">
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="fechahorafin-act">Fecha y hora Fin <span class="rojo">*</span></label>
                                    <div class="input-group date">
                                        <input id="fechahorafin-act" class="form-control" type="text">
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="padding-pequeno sin-padding-top">
                                <label for="descripcion-act">Descripción</label>
                                <textarea id="descripcion-act" class="form-control" maxlength="6000"></textarea>
                            </div>
                            <div class="padding-pequeno sin-padding-top">
                                <input type="checkbox" id="permitereserva-act">
                                <label for="permitereserva-act" class="ancho-normal"> Permite Reserva</label>
                            </div>
                            <div class="row padding-pequeno-bottom">
                                <div class="col-md-12">
                                    <span class="glyphicon glyphicon-info-sign"></span> 
                                    Las actividades indican periodos de actividad académica dentro un cronograma. 
                                    Estas actividades pueden ser periodos de tiempo en los que se permite reservar 
                                    solo si selecciona el checkbox <b>Permite Reservas</b>
                                </div>
                            </div>
                            <div class="padding-pequeno text-center">
                                <button id="guardar-act" class="btn btn-primary">Guardar</button>
                            </div>
                        </div>
                        <div id="tab-tolerancias" class="tab-pane fade">
                            <div class="row padding-pequeno">
                                <div class="col-md-6">
                                    <label for="fechahorainicio-tol">Fecha y hora Inicio <span class="rojo">*</span></label>
                                    <div class="input-group date">
                                        <input id="fechahorainicio-tol" class="form-control" type="text">
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="fechahorafin-tol">Fecha y hora Fin <span class="rojo">*</span></label>
                                    <div class="input-group date">
                                        <input id="fechahorafin-tol" class="form-control" type="text">
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="padding-pequeno sin-padding-top">
                                <label for="descripcion-tol">Descripción</label>
                                <textarea id="descripcion-tol" class="form-control" maxlength="6000"></textarea>
                            </div>
                            <div class="row padding-pequeno-bottom">
                                <div class="col-md-12">
                                    <span class="glyphicon glyphicon-info-sign"></span> 
                                    Las tolerancias indican periodos de tiempo en los que las actividades académicas 
                                    ya no tienen lugar. Pero no imposibilita reservas.
                                </div>
                            </div>
                            <div class="padding-pequeno  text-center">
                                <button id="guardar-tol" class="btn btn-primary">Guardar</button>
                            </div>
                        </div>
                        <div id="tab-feriadosesp" class="tab-pane fade">
                            <div class=" padding-pequeno">
                                <label for="titulo-feresp">Feriado <span class="rojo">*</span></label>
                                <input class="form-control" id="titulo-feresp" type="text">
                            </div>
                            <div class="row padding-pequeno sin-padding-top">
                                <div class="col-md-6">
                                    <label for="fechahorainicio-feresp">Fecha y hora Inicio <span class="rojo">*</span></label>
                                    <div class="input-group date">
                                        <input id="fechahorainicio-feresp" class="form-control" type="text">
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="fechahorafin-feresp">Fecha y hora Fin <span class="rojo">*</span></label>
                                    <div class="input-group date">
                                        <input id="fechahorafin-feresp" class="form-control" type="text">
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="padding-pequeno sin-padding-top">
                                <label for="descripcion-feresp">Descripción</label>
                                <textarea id="descripcion-feresp" class="form-control" maxlength="6000"></textarea>
                            </div>
                            <div class="row padding-pequeno-bottom">
                                <div class="col-md-12">
                                    <span class="glyphicon glyphicon-info-sign"></span> 
                                    Los feriados son periodos de tiempo en los que cesa toda activdiad académica, y por
                                    tanto la universidad cierra. Este tipo de eventos <b>Imposibilitan la reserva</b>.
                                </div>
                            </div>
                            <div class="padding-pequeno text-center">
                                <button id="guardar-feresp" class="btn btn-primary">Guardar</button>
                            </div>
                        </div>
                        <div id="tab-otro" class="tab-pane fade">
                            <div class=" padding-pequeno">
                                <label for="titulo-otro">Titulo <span class="rojo">*</span></label>
                                <input class="form-control" id="titulo-otro" type="text">
                            </div>
                            <div class="row padding-pequeno sin-padding-top">
                                <div class="col-md-6">
                                    <label for="fechahorainicio-otro">Fecha y hora Inicio <span class="rojo">*</span></label>
                                    <div class="input-group date">
                                        <input id="fechahorainicio-otro" class="form-control" type="text">
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="fechahorafin-otro">Fecha y hora Fin <span class="rojo">*</span></label>
                                    <div class="input-group date">
                                        <input id="fechahorafin-otro" class="form-control" type="text">
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="padding-pequeno sin-padding-top">
                                <label for="descripcion-otro">Descripción</label>
                                <textarea id="descripcion-otro" class="form-control" maxlength="6000"></textarea>
                            </div>
                            <div class="padding-pequeno sin-padding-top">
                                <input id="cierreuni-otro" type="checkbox"> <label for="cierreuni-otro" class="ancho-normal">Cierre de la Universidad</label>
                            </div>
                            <div class="row padding-pequeno-bottom">
                                <div class="col-md-12">
                                    <span class="glyphicon glyphicon-info-sign"></span>
                                    El tipo de evento Otro indica una actividad que no tiene que ver con la académia pero 
                                    que puede ser necesario que figure dentro el cronograma. Otro caso de este evento es
                                    un acontencimiento ajeno a la universidad que niegue el ingreso, en cuyo caso se debe 
                                    seleccionar el checkbox <b>Cierre de la Universidad</b> para indicar que no se pueden 
                                    realizar actividades académicas con normalidad y marcar el periodo de tiempo como un <b>Imposibilitador de reservas</b>
                                </div>
                            </div>
                            <div class="padding-pequeno text-center">
                                <button id="guardar-otro" class="btn btn-primary" >Guardar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <?php include(RAIZ . "/pie.inc"); ?>
    </body>
</html>
