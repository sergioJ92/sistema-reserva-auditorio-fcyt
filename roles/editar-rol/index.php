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
	<script type="text/javascript" src="editar-rol.js"></script>
    <?php include RAIZ .'/navegacion.inc'; ?>
		<div class="container">
            <div class="jumbotron">
                <div class="row padding-pequeno">
                    <div class="col-md-6">
                        <h3 class="inline">Datos Rol</h3>
                    </div>
                </div>
                <div id="contenedor-msg" class="margen-contenedor-msg"></div>
                <fieldset>
                    <legend>Datos de rol</legend>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="nombre-rol">Nombre del Rol: 
                            	<label for="nombre-recuperado" id="nombre-rol-recuperado"> </label>
                            </label>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="rol-tiene-materias"> Puede tener materias: 
                            	<label for="nombre-recuperado" id="tiene-materias-recuperado"> </label>
                            </label>
                        </div>
                    </div>
                </fieldset>
                <fieldset>
                	<legend>Privilegios del rol</legend>
                	<div class="col-xs-12">
	                    <div class="list-group padding-lista-privilegios lista-privilegios-desabilitado" id="campo-privilegios">
	                        
	                    </div>
	                </div>
                </fieldset>
                <button data-toggle="modal" data-target="#modal-editar-usuario" class="btn btn-primary" id="btn-editar-rol">Editar rol</button>
                <button class="btn btn-danger" id="btn-cancelar">Cancelar</button>
            
                <!-- Edit Item Modal -->
	            <div class="modal fade" id="modal-editar-usuario" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" >
	              <div class="modal-dialog" role="document">
	                <div class="modal-content">
	                  <div class="modal-header">
	                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="x-datos-cerrar"><span aria-hidden="true">×</span></button>
	                    <h4 class="modal-title" id="myModalLabel">Editar rol</h4>
	                  </div>

	                  <div class="modal-body">
	                      <form data-toggle="validator" action="editar_usuario.php" method="put">
	                      <fieldset>
	                          <legend>Datos de rol</legend>    
	                              
	                              <input type="hidden" name="id" class="edit-id">

	                              <div class="form-group">
	                                  <label class="control-label" for="title">Nombre del rol</label>
	                                  <input type="text" name="nombres" class="form-control" data-error="Please enter title." id="modal-nombre-rol" required />
	                                  <div class="help-block with-errors"></div>
	                              </div>
	                              <div class="row">
	                                  <div class="form-inline col-xs-6 form-group">
	                                      <label for="nombre-rol" class="padding-derecha-roles">Puede tener materias</label>
	                                      <select id="modal-tiene-materias-rol" class="form-control agrandar-combo-box">
	                                      	<option value="1">Si</option>
	                                      	<option value="2">No</option>
	                                      </select>
	                                  </div>
	                              </div>
	                        </fieldset>
	                        <fieldset>
	                       	    <legend>Privilegios del rol</legend>
				                 	<div class="col-xs-12">
					                    <div class="list-group padding-lista-privilegios lista-privilegios-desabilitado" id="modal-campo-privilegios">
					                        
					                    </div>
					                </div>
				            </fieldset>
	                          <div class="form-group">
	                              <button type="button" class="btn btn-primary crud-submit-edit" id="actualizar-datos-rol">Guardar</button>
	                              <button type="button" class="btn btn-danger" data-dismiss="modal" id="modal-datos-cerrar">Cancelar</button>
	                          </div>
	                      </form>
	                  </div>
	                </div>
	              </div>
	            </div>
			</div>    
        </div>
</body>
</html>