<?php 
const RAIZ = '../..'; 
include_once RAIZ .'/lib/sesion_store.php';
include_once RAIZ .'/lib/funciones_privilegios.php';
//include_once RAIZ .'/crear-usuario/vista-usuario/vista-usuario.php';
require_once RAIZ . '/interfazbd/ConexionBD.php';
bloquearCrearUsuarios();
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
    <?php include RAIZ .'/cabecera.inc'; ?>
	<title></title>
</head>
<body>
	<script type="text/javascript" src="vista-editar.js"></script>
    <?php include RAIZ .'/navegacion.inc'; ?>
		<div class="container">
            <div class="jumbotron">
                <div class="row padding-pequeno">
                    <div class="col-md-6">
                        <h3 class="inline">Datos Usuario</h3>
                    </div>
                </div>
                <div id="contenedor-msg" class="margen-contenedor-msg"></div>
                <fieldset>
                    <legend>Datos de usuario</legend>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="nombres">Nombres: 
                            	<label for="nombre-recuperado" id="editar-nombres"> </label>
                            </label>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="apellidos"> Apellidos: 
                            	<label for="nombre-recuperado" id="editar-apellidos"> </label>
                            </label>
                            
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="telefono" class="padding-derecha-telefono">Teléfono: 
                            	<label for="nombre-recuperado" id="editar-telefono"> </label>
                            </label>
                            
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="correo" class="padding-derecha-correo">Correo: 
                            	<label for="nombre-recuperado" id="editar-correo"> </label>
                            </label>
                            
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="nombre-usuario">Nombre de usuario: 
                            	<label for="nombre-recuperado" id="editar-nombre-usuario"> </label>
                            </label>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="estado-usuario">Rol: 
                            	<label for="rol-usuario" id="editar-rol-usuario"> </label>
                            </label>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="estado-usuario">Estado de usuario: 
                            	<label for="estado-recuperado" id="editar-estado-usuario"> </label>
                            </label>
                        </div>
                        
                    </div>
                </fieldset>
                <button data-toggle="modal" data-target="#modal-editar-usuario" class="btn btn-primary" id="btn-editar-usuario">Editar usuario</button>
                <button data-toggle="modal" data-target="#modal-editar-contracena" class="btn btn-primary" id="btn-editar-contrasena">Editar contraseña</button>
                <button class="btn btn-danger" id="btn-cancelar">Cancelar</button>
            
                <!-- Edit Item Modal -->
	            <div class="modal fade" id="modal-editar-usuario" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" >
	              <div class="modal-dialog" role="document">
	                <div class="modal-content">
	                  <div class="modal-header">
	                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="x-datos-cerrar"><span aria-hidden="true">×</span></button>
	                    <h4 class="modal-title" id="myModalLabel">Editar usuario</h4>
	                  </div>

	                  <div class="modal-body">
	                      <form data-toggle="validator" action="editar_usuario.php" method="put">
	                      <fieldset>
	                          <legend>Datos de usuario</legend>
	                          
	                              <input type="hidden" name="id" class="edit-id">

	                              <div class="form-group">
	                                  <label class="control-label" for="title">Nombres</label>
	                                  <input type="text" name="nombres" class="form-control" data-error="Please enter title." id="modal-nombres" required />
	                                  <div class="help-block with-errors"></div>
	                              </div>

	                              <div class="form-group">
	                                  <label class="control-label" for="title">Apellidos</label>
	                                  <input type="text" name="apellidos" class="form-control" data-error="Please enter title." id="modal-apellidos" required /><div class="help-block with-errors"></div>
	                              </div>

	                              <div class="form-group">
	                                  <label class="control-label" for="title">Telefono</label>
	                                  <input type="text" name="telefonos" class="form-control" data-error="Please enter title." id="modal-telefonos" required /><div class="help-block with-errors"></div>
	                              </div>

	                              <div class="form-group">
	                                  <label class="control-label" for="title">Correo</label>
	                                  <input type="email" name="correo" class="form-control" data-error="Please enter title." id="modal-correo" required /><div class="help-block with-errors"></div>
	                              </div>
	                              <div class="form-group">
	                                  <label class="control-label" for="title">Nombre de usuario</label>
	                                  <input type="text" name="nombresDeUsuario" class="form-control" data-error="Please enter title." id="modal-nombre-usuario" required />
	                                  <div class="help-block with-errors"></div>
	                              </div>
	                              <div class="form-group">
		                              <label for="estado_usuario">Estado usuario <span class="rojo">*</span></label>
		                              <select id="select-estado" class="form-control">
		                                  <option value="1">Activo</option>
		                                  <option value="2">Inactivo</option>
		                              </select>
		                           </div>
	                          </fieldset>
	                          
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
	                                    <div id="lista-materias-modal" class="list-group lista-materias-anadidas">
										</div>
	                                </div>
	                                <div class="col-xs-12">
	                                    <div class="col-xs-6 padding-select-materia">
	                                        <select id="select-materias" class="form-control"></select>
	                                    </div>
	                                    <div class="col-xs-6">
	                                        <button type="button" id="anadir-materia-modal" class="btn btn-default ensanchar-boton">Añadir</button>
	                                    </div>
	                                </div>
	                            </div>
	                        </fieldset>    
	                          <div class="form-group">
	                              <button type="button" class="btn btn-primary crud-submit-edit" id="actualizar-datos">Guardar</button>
	                              <button type="button" class="btn btn-danger" data-dismiss="modal" id="modal-datos-cerrar">Cancelar</button>
	                          </div>
	                      </form>
	                  </div>
	                </div>
	              </div>
	            </div>
	            <!-- Modal editar contracena -->
	            <div class="modal fade" id="modal-editar-contracena" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	            <label class="control-label" id="id-contrasena-editar"></label>
				  	<div class="modal-dialog" role="document">
				    	<div class="modal-content">
				      		<div class="modal-header">
				        		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
				        		</button>
				        		<h4 class="modal-title" id="exampleModalLabel">Editar contraceña</h4>
				      		</div>
					      	<div class="modal-body">
					        	<form>
					        		<div class="form-group">
	                                    <label class="control-label" for="title">Ingrese contraseña actual</label>
	                                    <input type="password" name="telefonos" class="form-control" data-error="Please enter title." id="mod-edit-contrcena-actual" required /><div class="help-block with-errors"></div>
	                              	</div>
	                              	<div class="form-group">
	                                    <label class="control-label" for="title">Nueva contraseña</label>
	                                    <input type="password" name="telefonos" class="form-control" data-error="Please enter title." id="mod-edit-nueva-contracena" required /><div class="help-block with-errors"></div>
	                              	</div>
	                              	<div class="form-group">
	                                    <label class="control-label" for="title">Repita contraseña</label>
	                                    <input type="password" name="telefonos" class="form-control" data-error="Please enter title." id="mod-edit-reingresar-contracena" required /><div class="help-block with-errors"></div>
	                                </div>
					        	</form>
					      	</div>
					      	<div class="modal-footer">
					        	<button type="button" class="btn btn-primary" data-dismiss="modal" id="modal-guardar-contrasena">Guardar</button>
					        	<button type="button" class="btn btn-danger" data-dismiss="modal" id="modal-contrasena-cerrar">Cancelar</button>
					      	</div>
				    	</div>
				  	</div>
				</div>
			</div>    
        </div>
</body>
</html>