<?php
const RAIZ = '../..';
include_once RAIZ . '/lib/sesion_store.php';
include_once RAIZ . '/lib/funciones_privilegios.php';
bloquearBandejaRespuestas()
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <?php include(RAIZ . "/cabecera.inc"); ?>
    </head>
    <?php include RAIZ . '/navegacion.inc'; ?>
    <body>
        <div class="container">
            <div class="row">
                <ul class="list-group-item-text col-md-12" id="respuestas">
                    <li class="list-group-item filaTitulo no-select"><h4>Bandeja de Respuestas</h4></li>
                </ul>
            </div>
        </div>
        <div class="modal fade" id="modalVerRespuesta" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header estilo-modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Detalle Respuesta</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <div class="col-md-3"><b>Responsable:</b></div>
                            <div class="col-md-9" id="modalResponsable"></div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-3"><b>Correo:</b></div>
                            <div class="col-md-9" id="modalCorreo"></div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-3"><b>Telefono:</b></div>
                            <div class="col-md-9" id="modalTelefono"></div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-3"><b>Evento:</b></div>
                            <div class="col-md-9" id="modalEvento"></div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-3"><b>Hora Inicio:</b></div>
                            <div class="col-md-3" id="modalHoraInicio"></div>
                            <div class="col-md-3"><b>Hora Fin:</b></div>
                            <div class="col-md-3" id="modalHoraFin"></div>
                        </div>
                        <div class="form-group">
                            <label for="botonModalMensaje">Mensaje:</label>
                            <button id="botonModalMensaje" class="btn btn-default" type="button">Mostrar</button>
                            <div  id="modalMensaje" ></div>
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
        <script src="script_respuestas.js"></script>
        <?php include RAIZ . '/pie.inc'; ?>
    </body>
</html>