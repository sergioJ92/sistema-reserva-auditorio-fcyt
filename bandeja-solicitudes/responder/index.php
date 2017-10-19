<?php
const RAIZ = '../..';
include_once RAIZ . '/lib/sesion_store.php';
include_once RAIZ . '/lib/funciones_privilegios.php';
bloquearResponderSolicitud();

$dias = array("Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado");
$meses = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");

function obtenerFechaActual() {
    global $dias, $meses;
    return $dias[date('w')] . " " . date('d') . " de " . $meses[date('n') - 1] . " del " . date('Y');
}
?>
<html>
    <head>
        <?php include RAIZ . '/cabecera.inc' ?>
        <link rel="stylesheet" href="responder.css">
    </head>
    <body>
        <?php include RAIZ . '/navegacion.inc' ?>
        <div class="container">
            <div id="contenedor-msg"></div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="container-fluid">
                        <div id="contenido-mensaje" class="contenedor-principal row" >

                            <div class="cabecera-mensaje row-sm-12 ">
                                <div >
                                    <div class="pequenio-espacio">
                                        <input id="nombre-representante" class="form-control" type="text" placeholder="Nombre del Responsable">
                                    </div>
                                    <div class="pequenio-espacio">
                                        <input id="cargo-representante" class="form-control" type="text" placeholder="Cargo del Responsable">
                                    </div>
                                    <div class="pequenio-espacio">
                                        <span id="provincia">Cercado</span>
                                    </div>
                                    <div class="pequenio-espacio">
                                        <span id="departamento">Cochabamba</span>
                                    </div>
                                </div>
                            </div>
                            <div class="inicio-mensaje row-sm-12 espacio-top" >
                                <div>
                                    <span id="fecha-mensaje"> <?php echo obtenerFechaActual(); ?></span>
                                </div>
                                <div id="primer-bloque" class="espacio-top">
                                    Sr.<span id="responsable"></span>
                                </div>
                                <div id="segundo-bloque">
                                    Representante de: <span id="institucion"></span>
                                </div>


                            </div>
                            <div class="cuerpo-mensaje row-sm-12 espacio-top">
                                <p id="tercer-bloque">Muy Señores Mios:</p>
                                <p id="cuarto-bloque">
                                    En respuesta a la solicitud de 
                                    reserva del AUDITORIO que se solicita 
                                    para realizar el Evento 
                                    <span id="evento"></span>
                                    con fecha 
                                    <span id="fecha-solicitud"></span> 
                                    de 
                                    <span id="hora_inicio"></span>
                                    a 
                                    <span id="hora_fin"></span>
                                    ,en respuesta a su solicitud, informamos que 
                                    ha sido: 
                                </p>

                                <p id="quinto-bloque">
                                    <span class="form-group">
                                        <select class="mi-input form-control" id="aceptado-rechazado">
                                            <option>RECHAZADO</option>
                                            <option>ACEPTADO</option>
                                        </select>
                                    </span>
                                </p>

                                <p id="sexto-bloque">
                                    para el uso del AUDITORIO en dicha fecha.
                                </p>
                            </div>
                            <div  class="fin-mensaje row-sm-12 espacio-top">
                                <div id="septimo-bloque" class="pequenio-espacio">
                                    <span>Atentamente:</span>
                                </div>
                                <div id="octavo-bloque" class="normal-espacio">
                                    <span>Firma</span>
                                </div>
                                <div id="noveno-bloque" class="pequenio-espacio">
                                    <span id="representante-univ">--> Nombre Representante</span>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="espacio-boton-enviar texto-center">
                        <button id="enviar-mensaje" class="btn btn-primary padding-boton">Enviar</button>
                    </div>
                </div>
                <!-- Eventos Existentes en fecha-->
                <div class="col-sm-6">
                    <ul class="list-group" id="eventos-fecha">
                        <li class="list-group-item filaTitulo no-select text-center">Eventos en Fecha: <b><span id="fecha"></span></b></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="modal fade" id="myModal" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header estilo-modal-header" id="titulo-modal">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <p>Usted esta  
                            <span class="center negrita" id="descision-reserva"></span>
                            la reserva.
                        </p>
                    </div>
                    <div id="body-modal"></div>
                    <div class="modal-footer text-center estilo-modal-footer">
                        <button id="btn-enviar-mensaje" type="button" class="btn btn-primary boton-centreado " data-dismiss="modal">Enviar</button>
                    </div>
                </div>

            </div>
        </div>

        <script src="script_responder.js"></script>
        <?php include RAIZ . '/pie.inc' ?>
    </body>
</html>