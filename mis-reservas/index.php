<?php
const RAIZ = '..';
include_once RAIZ . '/lib/sesion_store.php';
include_once RAIZ . '/lib/funciones_privilegios.php';
bloquearCalendarioYMisReservas();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <?php include(RAIZ . "/cabecera.inc"); ?>
        <title>Reservas</title>
        <script src="<?php echo RAIZ; ?>/lib/moment-with-locales.js"></script>
        <script src="<?php echo RAIZ; ?>/lib/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
        <link rel="stylesheet" href="<?php echo RAIZ; ?>/lib/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css">
    </head>
    <?php include RAIZ . '/navegacion.inc'; ?>
    <body>
        <div class="container">
            <div class="row">
                <div id="contenedor-mensaje" class="col-md-12"></div>
            </div>
            <div class="row">
                <ul class="list-group-item-text col-md-12">
                    <li class="list-group-item filaTitulo no-select"><h4>Mis Reservas</h4></li>
                    <div  id="respuestas"></div>
                </ul>
            </div>
        </div>
        <div class="modal fade" id="modalContenido" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header estilo-modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Detalle Reserva</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <div class="col-md-3"><b>Fecha:</b></div>
                            <div class="col-md-9" id="modalFecha"></div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-3"><b>Asunto:</b></div>
                            <div class="col-md-9" id="modalEvento"></div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-3"><b>Materia:</b></div>
                            <div class="col-md-9" id="modalMateria"></div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-3"><b>Hora Inicio:</b></div>
                            <div class="col-md-3" id="modalHoraInicio"></div>
                            <div class="col-md-3"><b>Hora Fin:</b></div>
                            <div class="col-md-3" id="modalHoraFin"></div>
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
        <div class="modal fade" id="modalEliminar" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header estilo-modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Reserva</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <div class="col-md-12 text-center"><b>¿Está seguro de eliminar la reserva?</b></div>
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
        <div class="modal fade" id="modalModificar" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header estilo-modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title"> Modificar Reserva </h4>
                    </div>
                    <div class="modal-body">

                        <div class="form-group row" id="idmodifmensaje">
                            <div id="contenedor-modificar" class="col-md-12"></div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-2"><b>Asunto:</b></div>
                            <div class="col-md-10">
                                <select id="modalModificarAsunto" class="selectpicker form-control" >
                                    <option hidden disabled selected value="null"> Seleccione Asunto </option>
                                    <option value="Clases"> Clases </option>
                                    <option value="Exámen"> Exámen </option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-2"><b>Materia:</b></div>
                            <div class="col-md-10">
                                <select id="modalModificarSelectMateria" class="selectpicker form-control" >
                                </select>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer padding-pequeno estilo-modal-footer">
                        <div class="col-md-12">
                            <center>
                                <button type="button" class="btn btn-default" data-dismiss="modal" id="boton-modal-cancelar"> Cancelar </button>
                                <button type="button" class="btn btn-default" id="boton-modal-modificar"> Modificar </button>
                            </center>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="mis_reservas.js"></script>
        <?php include RAIZ . '/pie.inc'; ?>
    </body>
</html>