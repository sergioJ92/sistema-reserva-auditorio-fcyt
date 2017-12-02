 <?php 
const RAIZ = '../..'; 
include_once RAIZ .'/lib/sesion_store.php';
include_once RAIZ .'/lib/funciones_privilegios.php';
//include_once RAIZ .'/crear-usuario/vista-usuario/vista-usuario.php';
require_once RAIZ . '/interfazbd/ConexionBD.php';

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
	      <?php include RAIZ .'/cabecera.inc'; ?>
        <link rel="stylesheet" type="text/css" href="vista-usuario.css">
        <title></title>
    </head>
  <body>
    <script type="text/javascript" src="vista-usuario.js"></script>
    <?php include RAIZ .'/navegacion.inc'; ?>
    
    <div class="container">
        <div class="jumbotron">
            <div class="row padding-pequeno">
                <div class="col-md-6">
                    <h3 class="inline">Usuarios</h3>
                </div>
            </div>
            <div id="contenedor-msg" class="margen-contenedor-msg"></div>
            <div class="padding-pequeno sin-padding-bottom">
                <button class="btn btn-primary" id="btn-crear-nuevo-usuario">Crear Usuario</button>
            </div>
            <fieldset>
                <legend>Lista Usuarios</legend>
                <table id="tabla-usuarios" class="table table-bordered">
                    <thead>
                        <tr>
                            <th scope="col-sm-2">Nombre</th>
                            <th scope="col">Apellido</th>
                            <th scope="col">Rol</th>
                            <th scope="col">Opciones</th>
                        </tr>
                    </thead>
                    <tbody id="cuerpo-tabla">
                            
                    </tbody>
                </table>
                      
            </fieldset>
            <!-- Edit Item Modal -->
            <div class="modal fade" id="edit-item" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4 class="modal-title" id="myModalLabel">Editar usuario</h4>
                  </div>

                  <div class="modal-body">
                      <form data-toggle="validator" action="editar_usuario.php" method="put">
                      <fieldset>
                          <legend>Datos de usuario</legend>
                          
                              <input type="hidden" name="id" class="edit-id">

                              <div class="form-group">
                                  <label class="control-label" for="title">Nombres<span class="rojo">*</span></label>
                                  <input type="text" name="nombres" class="form-control" data-error="Please enter title." id="nombres" required />
                                  <div class="help-block with-errors"></div>
                              </div>

                              <div class="form-group">
                                  <label class="control-label" for="title">Apellidos<span class="rojo">*</span></label>
                                  <input type="text" name="apellidos" class="form-control" data-error="Please enter title." id="apellidos" required /><div class="help-block with-errors"></div>
                              </div>

                              <div class="form-group">
                                  <label class="control-label" for="title">Telefono<span class="rojo">*</span></label>
                                  <input type="text" name="telefonos" class="form-control" data-error="Please enter title." id="telefonos" required /><div class="help-block with-errors"></div>
                              </div>

                              <div class="form-group">
                                  <label class="control-label" for="title">Correo<span class="rojo">*</span></label>
                                  <input type="ema" name="correo" class="form-control" data-error="Please enter title." id="correo" required /><div class="help-block with-errors"></div>
                              </div>
                          </fieldset>
                          <fieldset>
                              <legend>Datos de cuenta</legend>
                              <div class="form-group">
                                  <label class="control-label" for="title">Nombre de usuario<span class="rojo">*</span></label>
                                  <input type="text" name="nombresDeUsuario" class="form-control" data-error="Please enter title." id="nombreDeUsuario" required />
                                  <div class="help-block with-errors"></div>
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
                                        <button id="btn-anadir-materia-modal" class="btn btn-default ensanchar-boton">Añadir</button>
                                    </div>
                                </div>
                            </div>
                        </fieldset>    
                          <div class="form-group">
                              <button type="submit" class="btn btn-success crud-submit-edit">Guardar</button>
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