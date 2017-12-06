<?php 
const RAIZ = '../..'; 
include_once RAIZ .'/lib/sesion_store.php';
include_once RAIZ .'/lib/funciones_privilegios.php';
//include_once RAIZ .'/crear-usuario/vista-usuario/vista-usuario.php';
require_once RAIZ . '/interfazbd/ConexionBD.php';
bloquearCrearRoles();
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
    <script type="text/javascript" src="vista-rol.js"></script>
    <?php include RAIZ .'/navegacion.inc'; ?>
    
    <div class="container">
        <div class="jumbotron">
            <div class="row padding-pequeno">
                <div class="col-md-6">
                    <h3 class="inline">Roles</h3>
                </div>
            </div>
            <div id="contenedor-msg" class="margen-contenedor-msg"></div>
            <div class="padding-pequeno sin-padding-bottom">
                <button type="button" class="btn btn-primary" id="btn-crear-nuevo-rol">Crear Rol</button>
            </div>
            <fieldset>
                <legend>Lista Roles</legend>
                <table id="tabla-roles" class="table table-bordered">
                    <thead>
                        <tr>
                            <th scope="col-sm-2">Rol</th>
                            <th scope="col">Asigna materias</th>
                            <th scope="col">Opciones</th>
		                </tr>
                    </thead>
                    <tbody id="cuerpo-tabla">
                    </tbody>
                </table>	
            </fieldset>    
      </div>
    </div>
  </body>
</html>