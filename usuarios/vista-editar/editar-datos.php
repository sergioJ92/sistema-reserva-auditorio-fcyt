<?php
const RAIZ = '../..';

require_once RAIZ . '/interfazbd/ConexionBD.php';
require_once RAIZ. '/interfazbd/ValidacionExcepcion.php';
require_once RAIZ . '/interfazbd/Validador.php';


function ValidadorDatosUsuario($nombre, $apellido, $correo, $telefono, $nombreUsuario){
		Validador::revisarCampoVacio($nombre, 'Nombre');
		Validador::revisarCampoVacio($apellido, 'Apellidos');
		Validador::revisarCampoVacio($correo, 'Correo');
		Validador::revisarCampoVacio($telefono, 'Telefono');
		Validador::revisarCampoVacio($nombreUsuario, 'Nombre Usuario');
		/////////continuar 
		Validador::revisarCampoEsAlfaNumerico($nombre, 'Nombres');
        Validador::revisarCampoEsAlfaNumerico($apellido, 'Apellidos');
        Validador::revisarCampoEsNumeroEnteroPositivo($telefono, 'Teléfono');
    	Validador::revisarCampoEsCorreo($correo, 'Correo');
    	Validador::revisarCampoEsAlfaNumerico($nombreUsuario, 'Nombre de usuario');

	}
function guardarDatos($nombre,$apellido,$correo,$telefono,$nombreUsuario,$estado,$usuarioActual){
	$res = array('exito' => false);
	$conn = ConexionBD::getConexion();
    pg_query($conn, "BEGIN");
    $sqlDatoUsuario="UPDATE usuario SET nombres='".$nombre."', apellidos='".$apellido."', activo=".$estado.", nombre_usuario='".$nombreUsuario."' where nombre_usuario='".$usuarioActual."'";
    if(pg_query($conn, $sqlDatoUsuario)){
    	$sqlActualizarTelefono="UPDATE telefono_usuario  SET telefono=".$telefono." where nombre_usuario='".$nombreUsuario."'";
    	if(pg_query($conn, $sqlActualizarTelefono)){
    		$sqlActualizarCorreo="UPDATE correo_usuario  SET correo='".$correo."' where nombre_usuario='".$nombreUsuario."'";
    		if(pg_query($conn, $sqlActualizarCorreo)){
    			pg_query($conn, "COMMIT");	
    			$id = "vista-editar/?next=".$nombreUsuario;
    			$res = array('exito' => true, 'id_get' => $id);

			}
    	}else{
    		throw new ValidacionExcepcion('No se a podido editar los datos, telefono');	
    	}

    }else{
        throw new ValidacionExcepcion('No se a podido editar los datos, Nombre de usuario ya existe');
    }
    return $res;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$datos = $_POST;

	$usuarioActual = Validador::desinfectarEntrada($datos['usuario_actual']);
	$nombre = Validador::desinfectarEntrada($datos['nombre']);
	$apellido = Validador::desinfectarEntrada($datos['apellido']);
	$correo = Validador::desinfectarEntrada($datos['correos']);
	$telefono = Validador::desinfectarEntrada($datos['telefonos']);	
	$nombreUsuario = Validador::desinfectarEntrada($datos['nombre_usuario']);
	$estado = $datos['estado'];

	ValidadorDatosUsuario($nombre, $apellido, $correo, $telefono, $nombreUsuario);
	$res = guardarDatos($nombre,$apellido,$correo,$telefono,$nombreUsuario,$estado,$usuarioActual);
	echo json_encode($res);
}