<?php

const RAIZ = '..';

require_once RAIZ. '/interfazbd/ConexionBD.php';
require_once RAIZ. '/interfazbd/Validador.php';
require_once RAIZ. '/interfazbd/ValidacionExcepcion.php';

function obtenerRoles() {
    
    $consulta = 'SELECT * FROM rol r, privilegio p WHERE r.nombre_rol = p.nombre_rol';
    $resultado = ConexionBD::getConexion()->query($consulta);
    $resultadoLista = [];
    while ($fila = $resultado->fetch_assoc()) {
        array_push($resultadoLista, $fila);
    }
    return $resultadoLista;
}

function guardarRol($nombreRol, $puedeTenerMaterias, $privilegios) {
    
    Validador::revisarCampoVacio($nombreRol, 'Nombre de Rol');
    Validador::revisarCampoVacio($puedeTenerMaterias, 'Puede tener materias');
    Validador::revisarCampoEsAlfaNumerico($nombreRol, 'Nombre de Rol');
    Validador::revisarCampoEsBooleno($puedeTenerMaterias, 'Puede tener materias');
    foreach ($privilegios as $privilegio) {
        Validador::revisarCampoVacio($privilegio, 'Privilegios');
        Validador::revisarCampoEsAlfaNumerico($privilegio, 'Privilegios');
    }
    if (count($privilegios) == 0) {
        throw new ValidacionExcepcion('Seleccione los privilegios del Rol');
    }
    $conn = ConexionBD::getConexion();
    $conn->autocommit(false);
    
    $insertarRol = 'INSERT INTO rol (nombre_rol, puede_tener_materias)';
    $insertarRol .= " VALUES ('$nombreRol', '$puedeTenerMaterias')";
    if ($conn->query($insertarRol)) {
        $baseInsertarPriv = 'INSERT INTO privilegio (nombre_rol, nombre_privilegio) VALUES';
        foreach ($privilegios as $privilegio) {
            $insertarPrivilegio = $baseInsertarPriv . " ('$nombreRol', '$privilegio')";
            
            if (!$conn->query($insertarPrivilegio)) {
                $conn->rollback();
                throw new GuardarExcepcion('privilegio');
            }
            $insertarPrivilegio = '';
        }
        $conn->commit();
    } else {
        $conn->rollback();
        throw new ValidacionExcepcion('Ya existe un rol con ese nombre');
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    
    header('Content-Type: application/json');
    echo json_encode(obtenerRoles());
}
else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    header('Content-Type: application/json');
    $entrada = $_POST;
    $nombreRol = Validador::desinfectarEntrada($entrada['nombre_rol']);
    $puedeTenerMaterias = Validador::desinfectarEntrada($entrada['puede_tener_materias']);
    $privilegios = [];
    foreach ($entrada['privilegios'] as $privilegio) {
        array_push($privilegios, Validador::desinfectarEntrada($privilegio));
    }
    
    try {
        guardarRol($nombreRol, $puedeTenerMaterias, $privilegios);
        echo json_encode(array('exito' => true));
    }
    catch (ValidacionExcepcion $ex) {
        echo json_encode(array('exito' => false, 'mensaje' => $ex->getMessage()));
    }
}
else {
    
    header('Location: index.php');
}