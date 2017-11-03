<?php
    
    $time = 5; // una hora en mili-segundos 
     
    // verificamos si existe la sesión 
    // el nombre "session_name" es como ejemplo
    session_start(); 
    if(isset($_SESSION["nombres"])) 
    { 
          // verificamos si existe la sesión que se encarga del tiempo 
          // si existe, y el tiempo es mayor que una hora, expiramos la sesión  
            if(isset($_SESSION["expire"]) && time() > $_SESSION["expire"] + $time) 
            { 
                echo'<script type="text/javascript">alert("Su sesion ha expirado por inactividad'; 
                   echo', vuelva a logearse para continuar");window.location.href="index.php";</script>';  
           // también puedes utilizar header(“Location:index.php”);
              
         // destruir informacion de session en el server
      
                unset($_SESSION["expire"]); 
                  unset($_SESSION["nombres"]); 
                session_unset();
                session_destroy(); 
     
                // destruir informacion de session en el cliente 
                $session_cookie_params = session_get_cookie_params(); 
                setcookie(nombres(), '', time() - 24 * 3600, $session_cookie_params['path'], $session_cookie_params['domain'], $session_cookie_params['secure'], $session_cookie_params['httponly']); 
                 
                // Limpiar el array $_SESSION
                $_SESSION = array();  
     
          } 
          // creamos o redefinimos el valor de la variable de session 
          else
          { 
               $_SESSION["expire"] = time(); 
           } 
    }


