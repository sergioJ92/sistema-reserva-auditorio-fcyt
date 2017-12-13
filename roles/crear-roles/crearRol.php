 <?php
error_reporting(0);
const RAIZ = '../..';

require_once RAIZ. '/interfazbd/ConexionBD.php';
require_once RAIZ. '/interfazbd/Validador.php';
require_once RAIZ. '/interfazbd/ValidacionExcepcion.php';

function obtenerRoles() {
    
    $consulta = 'SELECT r.nombre_rol, r.puede_tener_materias, tp.nombre_privilegio  FROM rol r, tiene_privilegio tp WHERE r.nombre_rol = tp.nombre_rol';
    $resultado = pg_query(ConexionBD::getConexion(), $consulta);
    $resultadoLista = [];
    while ($fila = pg_fetch_assoc($resultado)) {
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
    pg_query($conn, "BEGIN");
    
    $insertarRol = 'INSERT INTO rol (nombre_rol, puede_tener_materias)';
    $insertarRol .= " VALUES ('$nombreRol', '$puedeTenerMaterias')";
    if (pg_query($conn, $insertarRol)) {
        $baseInsertarPriv = 'INSERT INTO tiene_privilegio VALUES';
        foreach ($privilegios as $privilegio) {
            $insertarPrivilegio = $baseInsertarPriv . " ('$privilegio','$nombreRol')";
            
            if (!pg_query($conn,$insertarPrivilegio)) {
                pg_query($conn, "ROLLBACK");
                throw new GuardarExcepcion('privilegio');
            }
            $insertarPrivilegio = '';
        }
        pg_query($conn, "COMMIT");
    } else {
        pg_query($conn, "ROLLBACK");
        throw new ValidacionExcepcion('Ya existe un rol con ese nombre');
    }
}

function guardarRolVacio($nombreRol, $puedeTenerMaterias, $privilegios){
    $conn = ConexionBD::getConexion();
    pg_query($conn, "BEGIN");
    $consulta = 'INSERT INTO rol (nombre_rol, puede_tener_materias)';
    $consulta = $consulta." VALUES ('$nombreRol', '$puedeTenerMaterias')";
    pg_query($conn,$consulta);
    pg_query($conn, "COMMIT");
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    
    header('Content-Type: application/json');
    echo json_encode(obtenerRoles());
}   
else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $entrada = $_POST;
    $nombreRol = '';
    $puedeTenerMaterias = '';
    $privilegios = [];
    if($entrada['nombre_rol'] == 'Ninguno'){
        //agregar como vacio
        $nombreRol = $entrada['nombre_rol'];
        $puedeTenerMaterias = 0;
        try{
            guardarRolVacio($nombreRol, $puedeTenerMaterias, $privilegios);
            echo json_encode(array('exito' => true));
        }catch (ValidacionExcepcion $ex) {
            echo json_encode(array('exito' => false, 'mensaje' => 'El rol ya existe'));
        }
    }else{
        $nombreRol = Validador::desinfectarEntrada($entrada['nombre_rol']);
        $puedeTenerMaterias = Validador::desinfectarEntrada($entrada['puede_tener_materias']);
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
    /**
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
    }**/
}
else {
    
    header('Location: index.php');
}