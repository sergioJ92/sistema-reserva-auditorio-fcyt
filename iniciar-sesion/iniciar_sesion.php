<?php
const RAIZ = './..';

include_once RAIZ.'/interfazbd/ConexionBD.php';
include_once RAIZ.'/interfazbd/Validador.php';

function obtenerUsuario($user) {
    
    $consulta = "select u.nombre_usuario, u.nombres, u.apellidos, u.contrasenia, tr.nombre_rol, u.activo from usuario as u, tiene_rol as tr where u.nombre_usuario='".$user."' and u.nombre_usuario=tr.nombre_usuario";
    $resultado = pg_query(ConexionBD::getConexion(), $consulta);
    if (pg_num_rows($resultado) > 0) {
        return pg_fetch_assoc($resultado);
    } else {
        return false;
    }
}

function obtenerPrivilegio($rol) {
    
    $consulta = "select tp.nombre_privilegio from rol as r, tiene_privilegio as tp where r.nombre_rol='".$rol."' and r.nombre_rol=tp.nombre_rol";
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
    //if($resultado['activo'] == true){
        
        if ($resultado == false) {
            echo json_encode($resultado);
        }else{
            if(password_verify($passwordUsuario, $resultado['contrasenia'])){
            //if($passwordUsuario == $resultado['contrasenia']){
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
    //}else{
    //    echo json_encode(['exito' => false,'mensaje' => 'El usuario esta inactivo no puede ingresar al sistema']);
    //}
}else{
    header('Location: index.php');
}
