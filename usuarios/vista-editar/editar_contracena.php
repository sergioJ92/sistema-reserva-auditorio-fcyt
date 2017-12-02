<?php
const RAIZ = '../..';

require_once RAIZ . '/interfazbd/ConexionBD.php';
require_once RAIZ. '/interfazbd/ValidacionExcepcion.php';
require_once RAIZ . '/interfazbd/Validador.php';

function guardarDatos($actual,$contrasena,$reContrasena){
	validarDatosUsuario($actual,$contrasena,$reContrasena);
}

function validarDatosUsuario($actual,$contrasena,$reContrasena){
	Validador::revisarCampoVacio($actual, 'Contraseña actual');
	Validador::revisarCampoVacio($contrasena, 'Contraseña');
	Validador::revisarCampoVacio($reContrasena, 'Reingresar contraseña');

	//falta comparacion de la contracena actual
	if (strcmp($contrasena, $reContrasena) != 0) {
        throw new ValidacionExcepcion('El campo de Reingrsar contraseña '
                . 'no empareja con la contraseña');
    }


}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
     $entrada = $_POST ;
	 $actual = Validador::desinfectarEntrada($entrada['actual']);
	 $contrasena = Validador::desinfectarEntrada($entrada['contrasena']);
	 $reContrasena = Validador::desinfectarEntrada($entrada['reContrasena']);


	 guardarDatos($actual,$contrasena,$reContrasena);
	 
///////
	 $resultado = ConexionBD::getConexion();
	 $resultado = pg_query($sql);
     $res = [];
     while ($fila = pg_fetch_assoc($resultado)) {
         array_push($res, $fila);
     }
	 echo json_encode($res);
}