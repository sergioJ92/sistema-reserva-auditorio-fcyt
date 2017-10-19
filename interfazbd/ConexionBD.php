<?php

date_default_timezone_set('America/La_Paz');

/**
 * Abstraccion de la conexion con la base de datos,
 * es una clase que todas las clases con la intencion de representar
 * una interfaz de comunicacion con una entidad del modelo de la base de dato
 * deberia extender
 */
class ConexionBD {
    
    const ID_NULO = -1;
    /**
     * Unica instancia de la conexion con la Base de datos
     *
     * @var mysqli Conexion con la base de datos
     */
    private static $conexion;

    /**
     * Conectar con la Base de Datos MySql
     * Si no es posible crear la conexion, termina la ejecucion de la aplicacion
     * 
     * @return bool True si conecta con exito, False si ya existe conexion
     */
    public static function conectar() {

        if (ConexionBD::$conexion === null) {
            require_once 'propiedades.php';
            ConexionBD::$conexion = new mysqli(HOST, USER, PASSWORD, DATABASE);
            if (ConexionBD::$conexion->connect_error) {
                die("Conexion fallida: " . ConexionBD::$conexion->connect_error);
            }
            self::$conexion->query("SET NAMES 'utf8'");
            return true;
        }
        return false;
    }
    
    /**
     * Obtener la conexion con mysql
     * 
     * @return mysqli
     */
    public static function getConexion() {
        
        if (self::$conexion == null) {
            self::conectar();
        }
        return self::$conexion;
    }

    /**
     * Desconectar la Base de datos MySql
     * 
     * @return bool True si tiene exito, False en otro caso
     */
    public static function desconectar() {
        
        if (self::getConexion() !== null) {
            if (self::getConexion()->close()) {
                self::$conexion = null;
                return true;
            }
        }
        return false;
    }
    
    /**
     * Construye una consulta SQL para actualizar una fila de la $tabla, con
     * $valores. Actualiza aquella columna que empareje los $valorClaves 
     * 
     * @param string $tabla Nombre de la tabla objetivo
     * @param array $atributos Atributos a atualizar
     * @param array $valores Valores de los artibutos a actualizar
     * @param array $nombreLlaves Claves de la $tabla
     * @param array $valorLlaves Valores de las claves de la tabla
     * @return array Consulta SQL para actualizar una fila
     */
    public static function construirConsultaUpdate(
            $tabla, $atributos, $valores, $nombreLlaves, $valorLlaves) {
        
        $consulta = "UPDATE $tabla";
        $consulta .= " SET ";
        $consulta .= self::concatenarLlaveValor($atributos, $valores, "'");
        $consulta .= " WHERE ";
        $consulta .= self::concatenarLlaveValor(
                $nombreLlaves, $valorLlaves, "'", "=", " AND ");
        return $consulta;
    }
    
    /**
     * Construye una consulta SQL para borrar una fila de la $tabla, la fila
     * es aquella que empareje los $valorClaves
     * 
     * @param string $tabla Nombre de la tabla objetivo
     * @param array $nombreLlaves Claves de la $tabla
     * @param array $valorLlaves Valores de $nombreClaves
     * @return string Consulta SQL para eliminar una fila
     * @throws InvalidArgumentException
     */
    public static function construirConsultaDelete($tabla, $nombreLlaves, $valorLlaves) {
        
        $consulta = "DELETE FROM $tabla";
        $consulta .= " WHERE ";
        $consulta .= self::concatenarLlaveValor(
                $nombreLlaves, $valorLlaves, "'", "=", " AND ");
        return $consulta;
    }
    
    /**
     * Construye una consulta SQL para insertar, con los valores 
     * de $tabla, $atributos y $valores. El numero de elementos de 
     * $atributos y $valores debe coincidir
     * 
     * @param string $tabla Nombre dela tabla
     * @param array $atributos Atributos de la tabla
     * @param array $valores Valores coincidentes a los atributos en las posiciones
     * @return string Consulta SQL para insertar los $valores en la $tabla
     * @throws InvalidArgumentException
     */
    public static function construirConsultaInsert($tabla, $atributos, $valores) {
        
        if (count($atributos) !== count($valores)) {
            throw new InvalidArgumentException();
        }
        $consulta = "INSERT INTO $tabla ";
        $consulta .= self::descargarArreglo($atributos, "(", ")");
        $consulta .= " VALUES ";
        $consulta .= self::descargarArreglo($valores, "(", ")", "'");
        return $consulta;
    }
    
    /**
     * Construye una consulta SQL para seleccionar todos en una tabla que
     * emparejen con los $atributos => $attrValores dados como argumento.
     * $atributos y $attrValores deben ser del mismo tamano
     * 
     * @param string $nombreTabla Nombre de la tabla objetivo
     * @param array $atributos Arreglo de atributos para la consulta select
     * @param array $attrValores Valores de los atributos a emparejar
     * @return string Consulta SQL construida
     * @throws InvalidArgumentException
     */
    public static function construirConsultaSelect(
            $nombreTabla, $atributos, $attrValores) {
        
        $consulta = "SELECT * FROM $nombreTabla";
        $consulta .= " WHERE ";
        $consulta .= self::concatenarLlaveValor(
                $atributos, $attrValores, "'", "=", " AND ");
        return $consulta;
    }
    
    /**
     * Reemplaza una lista de Objetos de tipo ConexionBD por sus 
     * respectivos areglos de asociaion
     * 
     * @param array $lista Lista de elementos de tipo ConexionBD
     * @return array
     */
    public static function reemplazarBdPorArrayAssoc($lista) {
        
        $resultado = [];
        foreach ($lista as $elem) {
            array_push($resultado, $elem->getArrayAssoc());
        }
        return $resultado;
    }
    
    /**
     * Devuelve el separador para que pueda ser anexado si es posible
     * hacerlo basado en la logitud del array. No es posible cuando 
     * se intenta anexar el separador para el ultimo elemento del array
     * entonces devuelve un string vacio
     * @param int $i indice del elemento despues del cual se anexara el separador
     * @param array $arreglo Arreglo original
     * @param string $separador Separador
     * @return string el separador o vacio si no es posible
     */
    private static function anexarSeparador($i, $arreglo, $separador) {
        
        if ($i < count($arreglo) - 1) {
            return $separador;
        }
        return "";
    }
    
    /**
     * Concatena clave y valor en la forma "clave=valor, ..." dependiendo del 
     * separador y relacionador proporcionados como parametros. Si $claves y 
     * $valores tiene un numero de elementos diferente lanza una excepcion
     * 
     * @param array $claves
     * @param array $valores
     * @param string $limitador Cadena que encierra los valores
     * @param string $relacionador Por defecto "="
     * @param string $separador Por defecto ", "
     * @return string Concatenacion
     * @throws InvalidArgumentException
     */
    public static function concatenarLlaveValor(
            $claves, $valores, $limitador = "", 
            $relacionador="=", $separador=", ") {
        
        $resultado = "";
        if (!assert(count($claves) === count($valores))) {
            throw new InvalidArgumentException();
        }
        for ($i = 0; $i < count($claves); $i++) {
            $clave = $claves[$i];
            $valor = $valores[$i];
            $resultado .= "$clave$relacionador$limitador$valor$limitador" . 
                    self::anexarSeparador($i, $claves, $separador);
        }
        return $resultado;
    }
    
    /**
     * Crea una cadena con todos los elementos de $arreglo encerrados dentro
     * $abertura y $cerradura, separados por $separador 
     * 
     * @param array $arreglo
     * @param string $abertura Por defecto "["
     * @param string $cerradura Por defecto "]"
     * @param string $separador Por defecto ", "
     * @return string
     */
    public static function descargarArreglo(
            $arreglo, $abertura="[", $cerradura="]", 
            $limitador="", $separador=", ") {
        
        $resultado = $abertura;
        for ($i = 0; $i < count($arreglo); $i++) {
            $valor = $arreglo[$i];
            $resultado .= "$limitador$valor$limitador" . 
                    self::anexarSeparador($i, $arreglo, $separador);
        }
        $resultado .= $cerradura;
        return $resultado;
    }
    
    public static function mesclarArreglos($primero, $segundo) {
        
        $resultado = [];
        $mesclando = max(count($primero), count($segundo)) > 0;
        $indicePrimero = 0;
        $indiceSegundo = 0;
        while ($mesclando) {
            if ($indicePrimero < count($primero)) {
                array_push($resultado, $primero[$indicePrimero]);
                $indicePrimero++;
            }
            if ($indiceSegundo < count($segundo)) {
                array_push($resultado, $segundo[$indiceSegundo]);
                $indiceSegundo++;
            }
            $mesclando = $indiceSegundo < count($segundo) ||
                    $indicePrimero < count($primero);
        }
        return $resultado;
    }
    
    private function __construct() {
    }
}
