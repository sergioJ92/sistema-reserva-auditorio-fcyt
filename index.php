<?php   
    
    include_once './lib/sesion_store.php';
    include_once './lib/funciones_privilegios.php';
?>
<!DOCTYPE html>
<html>
<head>
    <?php include("cabecera.inc");?>
    <link rel="stylesheet" href="estilo_pagina_principal.css">
</head>
    <?php include("navegacion.inc"); ?>
<body>
    <div class="container caja-global">
        <div class="row">
            <div class="col-sm-12" id="image-inicial">
                <div class="text-center mi-texto">
                    <h1 class="espacio-titulo estilo-principal">Sistema Para La Reserva Del Auditorio De La FCYT</h1>
                </div>
            </div>

        </div>
        <div class="row fondo-contenido">
            <h1 class="text-center estilo-post-principal">Utilidades</h1>
            <div class="col-sm-6">
                <div class="row text-center">
                    <div class="col-sm-3">
                        <a href="<?php echo DOMINIO?>/crear-solicitud/"><img src="./lib/imagen/solicitud.png" alt="Smiley face" height="100" width="100"></a>
                    </div>
                    <div class="col-sm-9">
                        <a class="estilo-secundario" href="<?php echo DOMINIO?>/crear-solicitud/">Solicitar Reserva</a>
                        <p class="estilo-contenido">Puedes solicitar una reserva del auditorio</p>
                    </div>
                </div>
                
            </div>
            
            <?php 
                if (isset($_SESSION['nombres'])||isset($_SESSION['privilegios'])) {
                    if (buscarPrivilegio($_SESSION['privilegios'], CALENDARIO)) {
            ?>  
                        <div class="col-sm-6">
                            <div class="row text-center">
                                <div class="col-sm-3">
                                    <a href="<?php echo DOMINIO;?>/reservas/index.php"><img src="./lib/imagen/calendario.jpg" alt="Smiley face" height="100" width="100"></a>
                                </div>
                                <div class="col-sm-9">
                                    <a class="estilo-secundario" href="<?php echo DOMINIO;?>/reservas/index.php">Calendario</a>
                                    <p class="estilo-contenido">Visita el Calendario Academico</p>
                                </div>
                            </div>

                        </div>
            <?php 
                    }
                }else{
            ?>
            <div class="col-sm-6">
                <div class="row text-center">
                    <div class="col-sm-3">
                        <a href="<?php echo DOMINIO?>/iniciar-sesion/index.php"><img src="./lib/imagen/no-user.png" alt="Smiley face" height="100" width="100"></a>
                    </div>
                    <div class="col-sm-9">
                        <a class="estilo-secundario" href="<?php echo DOMINIO?>/iniciar-sesion/index.php">Acceder</a>
                        <p class="estilo-contenido">Ingresa para empezar a planificar tus horarios</p>
                    </div>
                </div>
                
            </div>
            <?php 
                }
            ?>
        </div>
    </div>
    
    <?php include("pie.inc"); ?>
</body>
</html>