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
    <body>
        <?php include RAIZ . '/navegacion.inc'; ?>
        <div class="container">
            <div class="jumbotron">
                <div class="row padding-pequeno">
                    <div class="col-md-6">
                        <h3 class="inline"> Solicitar reserva </h3>
                    </div>
                    <div class="col-md-6 text-right igualar-titulo">
                        <input type="submit" value="Consultar Solicitud" id="consultar_solicitud" class="btn btn-primary">
                        <div style="display: inline-block; width: 20px"></div>
                        Campos Obligatorios <span class="rojo">*</span>
                    </div>
                </div>
                <div id="contenedor-msg" class="margen-contenedor-msg"></div>
                <fieldset>
                    <legend>Datos de la reserva</legend>
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label for="fecha"> Fecha <span class="rojo">*</span></label>
                            <div class="input-group date">
                                <input id="fecha" type="text" class="form-control" autocomplete="off" placeholder="Ingresar fecha"/>
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="hora_inicio"> Hora inicio <span class="rojo">*</span></label>
                            <div class="input-group date">
                                <input id="hora_inicio" type="text" class="form-control" autocomplete="off" placeholder="Ingresar hora inicio"/>
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-time"></span>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="hora_fin"> Hora fin <span class="rojo">*</span></label>
                            <div class="input-group date">
                                <input id="hora_fin" type="text" class="form-control" autocomplete="off" placeholder="Ingresar hora fin"/>
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-time"></span>
                                </span>
                            </div>
                        </div>
                    </div>
                </fieldset>
                <fieldset>
                    <legend>Datos del solicitante</legend>
                    <div class="row">
                        <div class="col-md-6 form-group"> 
                            <label for="responsable"> Responsable <span class="rojo">*</span></label>
                            <input type="text" class="form-control" autocomplete="off" id="responsable" placeholder="Ingresar responsable">
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="institucion"> Institución </label>
                            <input type="text" class="form-control" autocomplete="off" id="institucion" placeholder="Ingresar institución">
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="correo"> Correo <span class="rojo">*</span></label>
                            <input type="text" class="form-control" autocomplete="off" id="correo" placeholder="Ingresar correo electrónico">
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="telefono"> Teléfono <span class="rojo">*</span></label>
                            <input type="text" class="form-control" autocomplete="off" id="telefono" placeholder="Ingresar teléfono">                 
                        </div>
                    </div>
                </fieldset>
                <fieldset>
                    <legend>Datos del evento</legend>
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label for="evento"> Evento <span class="rojo">*</span></label>
                            <input type="text" class="form-control" autocomplete="off" id="evento" placeholder="Ingresar evento">
                        </div>
                        <div class="col-md-12 form-group">
                            <label for="descripcion"> Descripción </label>
                            <textarea class="form-control" autocomplete="off" id="descripcion" placeholder="Ingresar descripción"></textarea>
                        </div>
                    </div>
                </fieldset>
                <div class="row padding-pequeno-bottom">
                    <div class="col-md-12">
                        <span class="glyphicon glyphicon-info-sign"></span> 
                        Su solicitud será revisada por un agente encargado de las reservas. 
                        Se le hará llegar la respuesta a uno de los medios de contacto que proporcionó. 
                        Tenga en cuenta que su solicitud podria ser aprobada o rechazada según a la disponibilidad del auditorio
                    </div>
                </div>
                <div class="text-center row">
                    <input type="submit" value="Enviar Solicitud" id="enviar" class="btn btn-primary">
                </div>
                <div id="ico-enviando" class="text-center row">
                    
                </div>
            </div>
        </div>
        <div class="modal fade" id="modalConsulta" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header estilo-modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Consulta Solicitud Reserva</h4>
                    </div>
                    <div class="modal-body">

                        <div class="form-group row">
                            <div id="contenedor-consulta" class="col-md-12"></div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-3"><b>Código Reserva:</b></div>
                            <div class="col-md-6"><input type="text" id="codigo_reserva" class="form-control" placeholder="Ingrese Código Reserva"></div>
                            <div class="col-md-3"><input type="submit" id="consultar" value="Consultar" class="btn btn-default form-control"></div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12" id="codigo_respuesta"></div>
                        </div>

                    </div>
                    <div class="modal-footer estilo-modal-footer">
                        <div class="row boton-centreado">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script type="text/javascript" src="crear_solicitud.js"></script>
        <?php include RAIZ . '/pie.inc'; ?>
    </body>
</html>