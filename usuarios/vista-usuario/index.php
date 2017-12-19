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
        <link rel="stylesheet" type="text/css" href="vista-usuario.css">
        <title></title>
    </head>
  <body>
    <script type="text/javascript" src="vista-usuario.js"></script>
    <?php include RAIZ .'/navegacion.inc'; ?>
    
    <div class="container">
        <!-- <div class="jumbotron"> -->
            <div class="row padding-pequeno">
                <h3 class="inline">Lista de Usuarios</h3>
            </div>
            <div class="row">
                <div id="contenedor-msg" class="margen-contenedor-msg"></div>
                <div class="form-group">
                    <button class="btn btn-success" id="btn-crear-nuevo-usuario">Crear Usuario</button>
                </div>
                <div class="table-responsive">
                    <table id="tabla-usuarios" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th scope="col-sm-2">Nombre</th>
                                <th scope="col-sm-2">Apellido</th>
                                <th scope="col-sm-2">Rol</th>
                                <th scope="col-sm-2">Estado</th>
                                <th scope="col-sm-2">Opciones</th>
                            </tr>
                        </thead>
                        <tbody id="cuerpo-tabla">
                                
                        </tbody>
                    </table>
                </div>
            </div> 
            <!-- </fieldset>     -->
        </div>
    </div>
  </body>
</html>