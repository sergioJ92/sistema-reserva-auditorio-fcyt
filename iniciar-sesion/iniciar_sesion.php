<?php
const RAIZ = './..';

include_once RAIZ.'/interfazbd/ConexionBD.php';
include_once RAIZ.'/interfazbd/Validador.php';

function obtenerUsuario($user) {
    
    $consulta = "SELECT * FROM usuario WHERE nombre_usuario = ";
    $consulta .= " '";
    $consulta .= $user;
    $consulta .= "'";
    $resultado = pg_query(ConexionBD::getConexion(), $consulta);
    if (pg_num_rows($resultado) > 0) {
        return pg_fetch_assoc($resultado);
    } else {
        return false;
    }
}

function obtenerPrivilegio($rol) {
    
    $consulta = "SELECT nombre_privilegio FROM privilegio WHERE nombre_rol = ";
    $consulta .= " '";
    $consulta .= $rol;
    $consulta .= "'";
    $resultado = pg_query(ConexionBD::getConexion(), $consulta);
    $lista = [];
    while ($fila = pg_fetch_assoc($resultado)) {
        array_push($lista, $fila);
    }
    return $lista;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $nombreUsuario = Validador::desinfectarEntrada($_POST['usuario']) ;
    $passwordUsuario = Validador::desinfectarEntrada($_POST['contrasenia']);

    $resultado = obtenerUsuario($nombreUsuario);
    
    if ($resultado == false) {
        echo json_encode($resultado);
    }else{
        if(password_verify($passwordUsuario, $resultado['contrasenia'])){
//         if($passwordUsuario == $resultado['contrasenia']){
            //password_hash('password', PASSWORD_DEFAULT);
            $privilegios = obtenerPrivilegio($resultado['nombre_rol']);
            session_start();
            $_SESSION['nombres'] = $resultado['nombres'];
            $_SESSION['apellidos'] = $resultado['apellidos'];
            $_SESSION['usuario'] = $resultado['nombre_usuario'];
            $_SESSION['nombre_rol'] = $resultado['nombre_rol'];
            $_SESSION['privilegios'] = $privilegios;
            echo json_encode(['exito' => true]);
        }else{
            echo json_encode(['exito' => false]);
        }
        
    }
}else{
    header('Location: index.php');
}
