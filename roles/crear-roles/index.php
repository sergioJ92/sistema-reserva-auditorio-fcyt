<?php 
const RAIZ = '../..'; 
include_once RAIZ .'/lib/sesion_store.php';
include_once RAIZ .'/lib/funciones_privilegios.php';
bloquearCrearRoles();
?>
<!DOCTYPE html>
<html>
    <head>
        <?php include RAIZ . '/cabecera.inc' ?>
    </head>
    <body>
        <?php include RAIZ . '/navegacion.inc' ?>
        <script src="crear-roles.js"></script>
        <div class="container">
            <div class="jumbotron">
                <div class="row padding-pequeno">
                    <div class="col-md-6">
                        <h3 class="inline">Crear Rol</h3>
                    </div>
                    <div class="col-md-6 text-right igualar-titulo">
                        Campos Obligatorios <span class="rojo">*</span>
                    </div>
                </div>
                <div class="row">
                            <div id="contenedor-msg-rol"></div>
                            <div class="col-md-12 form-group">
                                <label for="nuevo-nombre-rol">Nombre de Rol <span class="rojo">*</span></label>
                                <input id="nuevo-nombre-rol" type="text" class="form-control">
                            </div>
                            <div class="col-md-12 form-group">
                                <div class="checkbox">
                                    <label><input id="puede-tener-materias" type="checkbox">Puede tener materias</label>
                                </div>
                            </div>
                            <div class="col-md-12"><b>Privilegios asociados al rol</b></div>
                            <div class="col-md-12">
                                <div class="list-goup padding-lista-privilegios">
                                    <div class="checkbox list-group-iem col-xs-6 margen-privilegios">
                                        <label><input id="nuevo-privilegio-cronograma" type="checkbox">Administrar cronograma académico</label>
                                    </div>
                                    <div class="checkbox list-group-tem col-xs-6 margen-privilegios">
                                        <label><input id="nuevo-privilegio-solicitudes" type="checkbox">Administrar solicitudes de reserva</label>
                                    </div>
                                    <div class="checkbox list-group-tem col-xs-6 margen-privilegios">
                                        <label><input id="nuevo-privilegio-reservas" type="checkbox">Reservar el auditorio</label>
                                    </div>
                                    <div class="checkbox list-group-iem col-xs-6 margen-privilegios">
                                        <label><input id="nuevo-privilegio-usuarios" type="checkbox">Administrar usuarios</label>
                                    </div>
                                    <div class="checkbox list-group-iem col-xs-6 margen-privilegios">
                                        <label><input id="nuevo-privilegio-roles" type="checkbox">Administrar roles</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                	<div class="row text-center padding-pequeno sin-padding-bottom">
                    	<button type="button" class="btn btn-primary" id="btn-crear-nuevo-rol">Guardar Rol</button>
                    	<button type="button" class="btn btn-danger" id="btn-cancelar-rol">Cancelar</button>
                </div>
            </div>
        </div>
        <?php include RAIZ . '/pie.inc' ?>
    </body>
</html>