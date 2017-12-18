<?php 
const RAIZ = '../..'; 
include_once RAIZ .'/lib/sesion_store.php';
include_once RAIZ .'/lib/funciones_privilegios.php';
bloquearCrearUsuarios();
?>
<!DOCTYPE html>
<html>
    <head>
        <?php include RAIZ . '/cabecera.inc' ?>
        <link rel="stylesheet" href="crear_usuario.css">
    </head>
    <body>
        <?php include RAIZ . '/navegacion.inc' ?>
        <script src="crear_usuario.js"></script>
        <div class="container">
            <div class="jumbotron">
                <div class="row padding-pequeno">
                    <div class="col-md-6">
                        <h3 class="inline">Crear usuario</h3>
                    </div>
                    <div class="col-md-6 text-right igualar-titulo">
                        Campos Obligatorios <span class="rojo">*</span>
                    </div>
                </div>
                <div id="contenedor-msg" class="margen-contenedor-msg"></div>
                <fieldset>
                    <legend>Datos de usuario</legend>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="nombres">Nombres <span class="rojo">*</span></label>
                            <input id="nombres" type="text" placeholder="Ingrese los nombres" class="form-control">
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="apellidos">Apellidos <span class="rojo">*</span></label>
                            <input id="apellidos" type="text" placeholder="Ingrese los apellidos" class="form-control">
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="telefono" class="padding-derecha-telefono">Teléfono <span class="rojo">*</span></label>
                            <input id="telefono" type="text" placeholder="Ingrese el teléfono" class="form-control">
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="correo" class="padding-derecha-correo">Correo <span class="rojo">*</span></label>
                            <input id="correo" type="text" placeholder="Ingrese el correo" class="form-control">
                        </div>
                    </div>
                </fieldset>
                <fieldset>
                    <legend>Datos de cuenta</legend>
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label for="nombre-usuario">Nombre de usuario <span class="rojo">*</span></label>
                            <input id="nombre-usuario" type="text" placeholder="Ingrese el nombre de usuario" class="form-control">
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="contrasenia">Contraseña <span class="rojo">*</span></label>
                            <input id="contrasenia" type="password" placeholder="Ingrese la contraseña" class="form-control">
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="confirmar-contrasenia">Confirmar contraseña <span class="rojo">*</span></label>
                            <input id="confirmar-contrasenia" type="password" placeholder="Confirme la contraseña" class="form-control">
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="estado_usuario">Estado usuario <span class="rojo">*</span></label>
                            <select id="select-estado" class="form-control">
                                <option value="1">Activo</option>
                                <option value="2">Inactivo</option>
                            </select>
                        </div>
                    </div>
                </fieldset>
                <fieldset>
                    <legend>Rol del usuario</legend>
                    <div class="row">
                        <div class="form-inline col-xs-6 form-group">
                            <label for="nombre-rol" class="padding-derecha-roles">Rol <span class="rojo"> * </span></label>
                            <select id="nombre-rol" class="form-control agrandar-combo-box"></select>
                        </div>
                        <div class="col-xs-12"><b>Privilegios asociados al rol</b></div>
                        <div class="col-xs-12">
                            <div class="list-group padding-lista-privilegios lista-privilegios-desabilitado">
                                <div class="checkbox list-group-item col-xs-6 margen-privilegios">
                                    <label><input id="privilegio-cronograma" type="checkbox" disabled="">Administrar el cronograma académico</label>
                                </div>
                                <div class="checkbox list-group-item col-xs-6 margen-privilegios">
                                    <label><input id="privilegio-solicitudes" type="checkbox" disabled="">Administrar solicitudes de reserva</label>
                                </div>
                                <div class="checkbox list-group-item col-xs-6 margen-privilegios">
                                    <label><input id="privilegio-reservas" type="checkbox" disabled="">Reservar el auditorio</label>
                                </div>
                                <div class="checkbox list-group-item col-xs-6 margen-privilegios">
                                    <label><input id="privilegio-usuarios" type="checkbox" disabled="">Administrar usuarios</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>
                <fieldset hidden="" id="seccion-materias">
                    <legend>Materias</legend>
                    <div class="row">
                        <div class="col-xs-12 form-group"><b>Añadir materias al usuario</b></div>
                        <div class="col-xs-12 form-group">
                            <div id="lista-materias" class="list-group lista-materias-anadidas">
                            </div>
                        </div>
                        <div class="col-xs-12">
                            <div class="col-xs-6 padding-select-materia">
                                <select id="select-materias" class="form-control"></select>
                            </div>
                            <div class="col-xs-6">
                                <button id="btn-anadir-materia" class="btn btn-default ensanchar-boton">Añadir</button>
                            </div>
                        </div>
                    </div>
                </fieldset>
                <div class="row text-center padding-pequeno sin-padding-bottom">
                    <button class="btn btn-primary" id="btn-crear-nuevo-usuario">Guardar Usuario</button>
                    <button class="btn btn-danger" id="btn-cancelar">Cancelar</button>
                </div>
            </div>
        </div>
        
        <?php include RAIZ . '/pie.inc' ?>
    </body>
</html>