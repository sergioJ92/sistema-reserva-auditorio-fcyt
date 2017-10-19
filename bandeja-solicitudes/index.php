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
    <?php include ('../navegacion.inc'); ?>
    <body>
        <div class="container">
            <!--h2>Solicitudes de Reserva</h2-->
            <ul class="list-group" id="notificaciones">
                <li class="list-group-item filaTitulo no-select"><h4>Bandeja de Solicitudes</h4></li>
            </ul>        
        </div>
        <div class="modal fade" id="myModal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header estilo-modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Descripccion</h4>
                    </div>

                    <div class="modal-body">
                        <div class="form-group row">
                            <div class="col-md-4"><b>Institucion:</b></div>
                            <div class="col-md-8" id="modalInstitucion"></div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-4"><b>Responsable:</b></div>
                            <div class="col-md-8" id="modalResponsable"></div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-4"><b>Correo:</b></div>
                            <div class="col-md-8" id="modalCorreo"></div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-4"><b>Telefono:</b></div>
                            <div class="col-md-8" id="modalTelefono"></div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-4"><b>Fecha:</b></div>
                            <div class="col-md-8" id="modalFecha"></div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-4"><b>Hora Inicio:</b></div>
                            <div class="col-md-8" id="modalHoraInicio"></div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-4"><b>Hora Fin:</b></div>
                            <div class="col-md-8" id="modalHoraFin"></div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-4"><b>Evento:</b></div>
                            <div class="col-md-8" id="modalEvento"></div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-4"><b>Descripccion:</b></div>
                            <div class="col-md-8" id="modalDescripccion"></div>
                        </div>
                    </div>

                    <div class="modal-footer estilo-modal-footer">
                        <div class="row text-center">
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
                        <h4 class="modal-title">Solicitud</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <div class="col-md-12 text-center"><b>¿Está seguro de eliminar la solicitud?</b></div>
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
        <script src="script_bandeja_solicitudes.js"></script>
        <?php include ('../pie.inc'); ?>
    </body>
</html>