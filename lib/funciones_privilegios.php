<?php
    const CRONOGRAMA = 'Cronograma'; //administracion del cronograma
    const CALENDARIO = 'Reservas'; //vista calendario y realizar reservas
    const SOLICITUDES = 'Solicitudes'; //administrar bandeja de solicitudes y ver respuestas
    const USUARIOS = 'Usuarios'; //crear usuarios
    const ROLES = 'Roles'; //crear roles
    
    function buscarPrivilegio($lista, $elemento){
        for ($i=0 ; $i<count($lista);$i++){
            if($lista[$i]['nombre_privilegio']==$elemento){
                return true;
            }
        }
        return false;
    }
    function tienePrivilegio($constante){
        if(isset($_SESSION['nombres']) && isset($_SESSION['privilegios'])){
            if(buscarPrivilegio($_SESSION['privilegios'],$constante)){
                return true;
            }
        }
        return false;
    }
    function addCalendario(){
        if(tienePrivilegio(CALENDARIO)){
            echo '<li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#">Calendario<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                      <li><a href="'.DOMINIO.'/reservas/index.php?var=auditorio">Calendario Auditorio</a></li>
                      <li><a href="'.DOMINIO.'/reservas/index.php?var=laboratorio">Calendario Laboratorio</a></li>
                      <li><a href="'.DOMINIO.'/reservas/index.php?var=aula">Calendario Aula</a></li>
                    </ul>
                </li>';
            //echo '<li><a href="'.DOMINIO.'/reservas/">Calendario</a></li>';
        }
    }
    function addCronograma(){
        if(tienePrivilegio(CRONOGRAMA)){
            echo '<li><li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">Cronograma<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                      <li><a class="enlace-dropdown-menu" href="'.DOMINIO.'/cronograma-academico/crear/">Crear</a></li></li>
                      <li><a class="enlace-dropdown-menu" href="'.DOMINIO.'/cronograma-academico/contenido/">Contenido</a></li></li>
                    </ul>
                  </li>
            . ';
        }
    }
    function addBandejaSolicitudes(){
        if(tienePrivilegio(SOLICITUDES)){
           echo '<li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#">Solicitudes<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                      <li><a href="'.DOMINIO.'/bandeja-solicitudes/index.php?var=auditorio">Solicitudes Auditorio</a></li>
                      <li><a href="'.DOMINIO.'/bandeja-solicitudes/index.php?var=laboratorio">Solicitudes Laboratorio</a></li>
                      <li><a href="'.DOMINIO.'/bandeja-solicitudes/index.php?var=aula">Solicitudes Aula</a></li>
                    </ul>
                </li>';
            //echo '<li><a href="'.DOMINIO.'/bandeja-solicitudes/">Solicitudes</a></li>';
        }
    }
    function addCrearUsuarios(){
        if(tienePrivilegio(USUARIOS)){
            echo '<li><a href="'.DOMINIO.'/usuarios/">Usuarios</a></li>';
        }
    }
    function addCrearRoles(){
        if(tienePrivilegio(ROLES)){
            echo '<li><a href="'.DOMINIO.'/roles/">Roles</a></li>';
        }
    }         
    function addMisReservas(){
        if(tienePrivilegio(CALENDARIO)){
            echo '<li><a class="enlace-dropdown-menu" href="'.DOMINIO.'/mis-reservas/">Mis reservas</a></li>';
        }      
    }
    function addMisRespuestas(){
        if(tienePrivilegio(SOLICITUDES)){
            echo '<li><a class="enlace-dropdown-menu" href="'.DOMINIO.'/bandeja-solicitudes/respuestas">Respuestas</a></li>';
        }      
    }
    
    function bloquearCalendarioYMisReservas(){
        if (isset($_SESSION['nombres'])||isset($_SESSION['privilegios'])) {
            if (!buscarPrivilegio($_SESSION['privilegios'], CALENDARIO)) {
                header('Location: ../index.php');
                die();
            }
        }else{
            header('Location: ../index.php');
            die();
        }
    }
    function bloquearIniciarSesion() {
        if (isset($_SESSION['nombres'])) {
            header('Location: ../index.php');
            die();
        }
    }
    function bloquearCronograma(){
        if (isset($_SESSION['nombres'])||isset($_SESSION['privilegios'])) {
            if (!buscarPrivilegio($_SESSION['privilegios'], CRONOGRAMA)) {
                header('Location: ../../index.php');
                die();
            }
        }else{
            header('Location: ../../index.php');
            die();
        }
    }
    
    function bloquearCrearUsuarios(){
        if (isset($_SESSION['nombres'])||isset($_SESSION['privilegios'])) {
            if (!buscarPrivilegio($_SESSION['privilegios'], USUARIOS)) {
                header('Location: ../../index.php');
                die();
            }
        }else{
            header('Location: ../../index.php');
            die();
        }
    }

    function bloquearCrearRoles(){
        if (isset($_SESSION['nombres'])||isset($_SESSION['privilegios'])) {
            if (!buscarPrivilegio($_SESSION['privilegios'], ROLES)) {
                header('Location: ../../index.php');
                die();
            }
        }else{
            header('Location: ../../index.php');
            die();
        }
    }
    
    function bloquearBandejaSolicitudes(){
        if (isset($_SESSION['nombres'])||isset($_SESSION['privilegios'])) {
            if (!buscarPrivilegio($_SESSION['privilegios'], SOLICITUDES)) {
                header('Location: ../index.php');
                die();
            }
        }else{
            header('Location: ../index.php');
            die();
        }
    }
    function bloquearBandejaRespuestas(){
        if (isset($_SESSION['nombres'])||isset($_SESSION['privilegios'])) {
            if (!buscarPrivilegio($_SESSION['privilegios'], SOLICITUDES)) {
                header('Location: ../../index.php');
                die();
            }
        }else{
            header('Location: ../../index.php');
            die();
        }
    }
    function bloquearResponderSolicitud(){
        if (isset($_SESSION['nombres'])||isset($_SESSION['privilegios'])) {
            if (!buscarPrivilegio($_SESSION['privilegios'], SOLICITUDES)) {
                header('Location: ../../index.php');
                die();
            }
        }else{
            header('Location: ../../index.php');
            die();
        }
    }


