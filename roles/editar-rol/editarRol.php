<?php 
//error_reporting(0);
const RAIZ = '../..';

require_once RAIZ . '/interfazbd/ConexionBD.php';
require_once RAIZ . '/interfazbd/Validador.php';
require_once RAIZ . '/interfazbd/ValidacionExcepcion.php';

function obtenerDatosRoles($dato){

	$consulta = "SELECT r.nombre_rol, r.puede_tener_materias, tp.nombre_privilegio FROM rol as r, tiene_privilegio tp WHERE  r.nombre_rol='".$dato."' AnD r.nombre_rol=tp.nombre_rol ";
	$resultado = ConexionBD::getConexion();
    $resultado = pg_query($consulta);
    $resultadoLista = [];
    while ($fila = pg_fetch_assoc($resultado)) {
        array_push($resultadoLista, $fila);
    }
    return $resultadoLista;
}

function validarDatosUsuario($nombre_rol, $tiene_materias) {
    
    Validador::revisarCampoVacio($nombre_rol, 'Nombre rol');
    Validador::revisarCampoEsAlfaNumerico($nombre_rol, 'Nombre rol');
}

function actualizarDatos($nombre_rol, $tiene_materias, $rol_actual){
    validarDatosUsuario($nombre_rol, $tiene_materias);
    $editarDatoRol = "UPDATE rol SET nombre_rol='".$nombre_rol."', puede_tener_materias='".$tiene_materias."' WHERE nombre_rol='".$rol_actual."'";
    $conn = ConexionBD::getConexion();
    $resultado = pg_query($conn, $editarDatoRol);
    
}
function actualizarPrivilegios($lista_agregar, $nombre_rol){
    $conn = ConexionBD::getConexion();
    $insertarPrivilegio = "INSERT INTO tiene_privilegio (nombre_privilegio,nombre_rol) ";
    foreach ($lista_agregar as $dato) {
        $anadirPrivilegio = $insertarPrivilegio."VALUES ('".$dato."','".$nombre_rol."')";
        if(!pg_query($conn, $anadirPrivilegio)){
            throw new GuardarExcepcion('Tiene materia');
        }
    }
}
function eliminarPrivilegios($lista_eliminar, $nombre_rol){
    $conn = ConexionBD::getConexion();
    $eliminarPrivilegio = "DELETE FROM tiene_privilegio ";
    foreach ($lista_eliminar as $dato) {
        $borrarPrivilegio = $eliminarPrivilegio."WHERE nombre_rol='".$nombre_rol."' AND nombre_privilegio = '".$dato."'";
        if(!pg_query($conn, $borrarPrivilegio)){
            throw new GuardarExcepcion('No puede borrar');
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $dato = $_GET["nombre_usuario"];
    header('Content-Type: application/json');
	$resConsulta = obtenerDatosRoles($dato);

    echo json_encode($resConsulta);
}else{
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $datos = $_POST;

        $nombre_rol = Validador::desinfectarEntrada($datos['nombre_rol']);
        try{
            if($datos['lista_agregar'] == null &&  $datos['lista_eliminar'] == null){
                actualizarDatos($datos['nombre_rol'],$datos['tiene_materias'],$datos['rol_actual']);
            }else{
                if(count($datos['lista_agregar']) > 0 &&  $datos['lista_eliminar'] == null){
                    actualizarDatos($datos['nombre_rol'],$datos['tiene_materias'],$datos['rol_actual']);
                    actualizarPrivilegios($datos['lista_agregar'],$datos['nombre_rol']);
                    
                }else{
                    if($datos['lista_agregar'] == null &&  count($datos['lista_eliminar']) > 0){
                        actualizarDatos($datos['nombre_rol'],$datos['tiene_materias'],$datos['rol_actual']);
                        eliminarPrivilegios($datos['lista_eliminar'],$datos['nombre_rol']);  
                    }else{
                        actualizarDatos($datos['nombre_rol'],$datos['tiene_materias'],$datos['rol_actual']);
                        actualizarPrivilegios($datos['lista_agregar'],$datos['nombre_rol']);
                        eliminarPrivilegios($datos['lista_eliminar'],$datos['nombre_rol']);                        
                    }
                }
            }
            $arreglo = array('exito' => true);

            echo json_encode($arreglo, true);
        
        }catch (ValidacionExcepcion $ex){
            $res = array('exito' => false, 'mensaje' => $ex->getMessage());
            echo json_encode($res);
        }
    }
}   