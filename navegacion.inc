<nav class="navbar navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed enlace-dropdown-menu" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="<?php echo DOMINIO?>/index.php">
                <span class="glyphicon glyphicon-home"></span>
                <!--img alt="" src="<?php echo DOMINIO;?>/icono.png" width="25" heigth="25"-->
            </a>
        </div>
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                
                <?php 
                    //Comprobacion principal si existe usuario
                    if (isset($_SESSION['nombres'])) {
                        addCalendario();
                        addCronograma();
                        addBandejaSolicitudes();
                        addCrearUsuarios();
                    }
                ?>
                <?php  

                $a = 'auditorio';
                $c = 'aula';
                $b = 'laboratorio';

                ?>
                <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#">Solicitar Reserva<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                      <li><a href="<?php echo DOMINIO;?>/crear-solicitud/index.php?var=<?php echo $a ?>">Solicitar Reserva Auditorio</a></li>
                      <li><a href="<?php echo DOMINIO;?>/crear-solicitud/index.php?var=<?php echo $b ?>">Solicitar Reserva Laboratorio</a></li>
                      <li><a href="<?php echo DOMINIO;?>/crear-solicitud/index.php?var=<?php echo $c ?>">Solicitar Reserva Aula</a></li>
                    </ul>
                </li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <?php 
                    if (isset($_SESSION['nombres'])) {
                ?>  
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#"><span class="glyphicon glyphicon-user">&nbsp</span><?php echo isset($_SESSION['nombres']) ? $_SESSION['nombres'] : 'Usuario'; ?><span class="caret"></span></a>
                    <ul class="dropdown-menu dropdown-control">
                        <?php 
                            //Comprobacion opciones de usuario
                            if (isset($_SESSION['nombres'])) {
                                addMisReservas();
                                addMisRespuestas();
                            }
                        ?>
                        <li><a class="enlace-dropdown-menu" href="<?php echo DOMINIO;?>/iniciar-sesion/cerrar_sesion.php">Salir</a></li>
                    </ul>
                </li>
                <?php 
                    }else{
                ?>
                <li>
                    <a href="<?php echo DOMINIO?>/iniciar-sesion/index.php"><span class="glyphicon glyphicon-user">&nbsp</span> Acceder</a>
                </li>
                <?php 
                    }
                ?>
            </ul>
            
        </div>
    </div>
</nav>