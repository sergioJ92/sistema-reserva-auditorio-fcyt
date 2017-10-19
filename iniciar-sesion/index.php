<?php 
const RAIZ = '..'; 
include_once RAIZ .'/lib/sesion_store.php';
include_once RAIZ .'/lib/funciones_privilegios.php';

bloquearIniciarSesion();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <?php include(RAIZ . "/cabecera.inc"); ?>
        <link href="estilo_login.css" rel="stylesheet">
    </head>
    <body>
        <?php include RAIZ . '/navegacion.inc'; ?>
        <div class="container">
            <div id="contenedor-msg"></div>
            <div class="content">
                <div class="row content-superior">
                    <div class="col-md-5">
                        <fieldset class="espaciar-login">
                            <legend><b> Iniciar Sesión </b></legend>
                            <div class="espacio-inferior">
                                <div class="form-group">
                                    <label for="id-usuario"> Nombre de Usuario: </label>
                                    <input class="form-control" name="username" id="id-usuario" type="text" placeholder="Nombre de Usuario">
                                </div>
                                <div class="form-group">
                                    <label for="id-contrasenia">Contraseña: </label>
                                    <input class="form-control" name="password" id="id-contrasenia" type="password" placeholder="Contraseña">
                                </div>
                                <button id="boton-login" type="button" class="btn btn-primary form-control espacio-superior">Iniciar Sesion</button>
                            </div>
                        </fieldset>
                    </div>
                    <div class="col-md-7">
                        <h2> Sistema Para La Reserva Del Auditorio FCYT </h2>
                        <p> 
                            El Sistema Para La Reserva Del Auditorio FCYT ofrece a la comunidad docente la administración
                            y uso controlado del auditorio ubicado en el edificio nuevo de la UMSS 
                        </p>
                        <p> 
                            El Sistema ofrece las siguientes funcionalidades
                        </p>
                        <ul>
                            <li>
                                Realizar y Ver Reservas
                            </li>
                            <li>
                                Administración del Cronograma
                            </li>
                            <li>
                                Solicitar Reserva
                            </li>
                        </ul>
                        <p>
                            Para contactar al administrador dirijirse a oficinas de la UMSS
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <script src="script_login.js"></script>
        <?php include RAIZ . '/pie.inc'; ?>
    </body>
</html>