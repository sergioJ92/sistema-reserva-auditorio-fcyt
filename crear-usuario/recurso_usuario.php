<?php

const RAIZ = '..';

require_once RAIZ . '/interfazbd/ConexionBD.php';
require_once RAIZ . '/interfazbd/Validador.php';
require_once RAIZ . '/interfazbd/ValidacionExcepcion.php';

function rolPuedeTenerMaterias($nombreRol) {
    
    $conn = ConexionBD::getConexion();
    $rol = $conn->query("SELECT * FROM rol WHERE nombre_rol='$nombreRol'");
    return $rol->fetch_assoc()['puede_tener_materias'] == 1;
}

function validarDatosUsuario(
                $nombres, $apellidos, $telefono, $correo, 
                $nombreUsuario, $contrasenia, $confirmarContrasenia, 
                $nombreRol, $materias) {
    
    Validador::revisarCampoVacio($nombres, 'Nombres');
    Validador::revisarCampoVacio($apellidos, 'Apellidos');
    Validador::revisarCampoVacio($telefono, 'Teléfono');
    Validador::revisarCampoVacio($correo, 'Correo');
    Validador::revisarCampoVacio($nombreUsuario, 'Nombre de usuario');
    Validador::revisarCampoVacio($contrasenia, 'Contraseña');
    Validador::revisarCampoVacio($nombreRol, 'Nombre de Rol');
    
    Validador::revisarCampoEsAlfaNumerico($nombres, 'Nombres');
    Validador::revisarCampoEsAlfaNumerico($apellidos, 'Apellidos');
    Validador::revisarCampoEsNumeroEnteroPositivo($telefono, 'Teléfono');
    Validador::revisarCampoEsCorreo($correo, 'Correo');
    Validador::revisarCampoEsAlfaNumerico($nombreUsuario, 'Nombre de usuario');
    if (strcmp($contrasenia, $confirmarContrasenia) != 0) {
        throw new ValidacionExcepcion('El campo de confirmación de contraseña '
                . 'no empareja con la contraseña');
    }
    if (!rolPuedeTenerMaterias($nombreRol) && count($materias) > 0) {
        throw new ValidacionExcepcion('Según su rol el usuario no puede tener materias');
    }
    else {
        foreach ($materias as $materia) {
            Validador::revisarCampoVacio($materia, 'Materias');
            Validador::revisarCampoEsNumeroEnteroPositivo($materia, 'Materias');
        }
    }
}

function guardarUsuario(
                $nombres, $apellidos, $telefono, $correo, 
                $nombreUsuario, $contrasenia, $confirmarContrasenia, 
                $nombreRol, $materias) {
    
    validarDatosUsuario($nombres, $apellidos, $telefono, $correo, 
            $nombreUsuario, $contrasenia, $confirmarContrasenia, 
            $nombreRol, $materias);
    
    $conn = ConexionBD::getConexion();
    $conn->autocommit(false);
    $hashContrasenia = password_hash($contrasenia, PASSWORD_DEFAULT);
    if (!password_verify($contrasenia, $hashContrasenia)) {
        throw new ValidacionExcepcion('No pudo encriptar correctamente la contraseña. '
                . 'Intente más tarde');
    }
    $insertarUsuario = 'INSERT INTO usuario (nombres, apellidos,';
    $insertarUsuario .= ' nombre_usuario, contrasenia, nombre_rol)';
    $insertarUsuario .= " VALUES ('$nombres', '$apellidos',";
    $insertarUsuario .= " '$nombreUsuario', '$hashContrasenia', '$nombreRol')";
    
    if ($conn->query($insertarUsuario)) {
        $insertarTelefono = 'INSERT INTO telefono_usuario (telefono, nombre_usuario)';
        $insertarTelefono .= " VALUES ('$telefono', '$nombreUsuario')";
        
        if ($conn->query($insertarTelefono)) {
            $insertarCorreo = 'INSERT INTO correo_usuario (correo, nombre_usuario)';
            $insertarCorreo .= " VALUES ('$correo', '$nombreUsuario')";
            
            if ($conn->query($insertarCorreo)) {
                if (count($materias) > 0) {
                    $baseAnadirMateria = 'INSERT INTO tiene_materia (codigo_materia, nombre_usuario)';

                    foreach ($materias as $materia) {
                        $anadirMateria = $baseAnadirMateria . " VALUES ('$materia', '$nombreUsuario')";
                        if (!$conn->query($anadirMateria)) {
                            throw new GuardarExcepcion('Tiene materia');
                        }
                    }
                } else {
                    $conn->query("INSERT INTO tiene_materia"
                            . " (codigo_materia, nombre_usuario)"
                            . " VALUES ('0', '$nombreUsuario')");
                }
                $conn->commit();
            } else {
                $conn->rollback();
                throw new GuardarExcepcion('Correo usuario');
            }
        } else {
            $conn->rollback();
            throw new GuardarExcepcion('Teléfono usuario');
        }
    }
    else {
        $conn->rollback();
        throw new ValidacionExcepcion('Ya existe una cuenta con ese Nombre de usuario');
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $entrada = $_POST;
    $nombres = Validador::desinfectarEntrada($entrada['nombres']);
    $apellidos = Validador::desinfectarEntrada($entrada['apellidos']);
    $telefono = Validador::desinfectarEntrada($entrada['telefono']);
    $correo = Validador::desinfectarEntrada($entrada['correo']);
    $nombreUsuario = Validador::desinfectarEntrada($entrada['nombre_usuario']);
    $contrasenia = Validador::desinfectarEntrada($entrada['contrasenia']);
    $confirmarContrasenia = Validador::desinfectarEntrada($entrada['confirmar_contrasenia']);
    $nombreRol = Validador::desinfectarEntrada($entrada['nombre_rol']);
    $materias = [];
    foreach ($entrada['materias'] as $materia) {
        array_push($materias, Validador::desinfectarEntrada($materia));
    }
    
    try {
        guardarUsuario(
                $nombres, $apellidos, $telefono, $correo, 
                $nombreUsuario, $contrasenia, $confirmarContrasenia, 
                $nombreRol, $materias);
        echo json_encode(array('exito' => true));
    }
    catch (ValidacionExcepcion $ex) {
        echo json_encode(array('exito' => false, 'mensaje' => $ex->getMessage()));
    }
}
else {
    header('Location: index.php');
}