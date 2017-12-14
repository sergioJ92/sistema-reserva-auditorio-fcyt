<?php

class Validador {
    
    /**
     * Desinfecta la cadena, util para procesar datos que llegan desde el cliente
     * 
     * @param string $entrada
     * @return string
     */
    public static function desinfectarEntrada($entrada) {
        
        return trim(htmlspecialchars(stripslashes(trim($entrada))));
    }
    
    /**
     * Valida si la cadena solo contiene letras, nada de caracteres especiales o
     * numeros
     * 
     * @param string $nombre
     * @return boolean
     */
    public static function esNombre($nombre) {
        
        return preg_match("/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+$/", $nombre) === 1;
    }
    
    /**
     * Valida si el argumento representa un numero entero
     * 
     * @param string|int $numero
     * @return boolean
     */
    public static function esNumeroEntero($numero) {
        
        return preg_match("/^[-|+]?[0-9]*$/", $numero) === 1;
    }
    
    /**
     * Acepta cadenas con letras, numeros y espacios
     * 
     * @param string $texto
     * @return boolean
     */
    public static function esAlfaNumerico($texto) {
        
        //return ctype_alnum($texto);
        return preg_match("/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ\s]+$/", $texto) === 1;
    }
    
    /**
     * Acepta una cadena si representa un correo electronico valido
     * 
     * @param string $email
     * @return boolean
     */
    public static function esEmail($email) {
        
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return true;
        }
        return false;
    }


    /**
     * Acepta una cadena si representa una URL valida
     * 
     * @param string $url
     * @return boolean
     */
    public static function esURL($url) {
        
        return preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)"
                . "[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",
                $url) === 1;
    }
    
    
    /**
     * Construye una fecha con el formato correcto para insertar en la Base de datos
     * 
     * @param int $_anio Entre 1 y 32767
     * @param int $_mes Entre 1 y 12
     * @param int $_dia Dia del mes entre 1 y 31 segun el mes, toma en cuenta años bisiestos
     * @param int $_hora Entre 0 y 23
     * @param int $_minuto Entre 0 y 59
     * @param int $_segundo Entre 0 y 59
     * @return string
     * @throws InvalidArgumentException
     */
    public static function construirFecha(
            $_anio, $_mes, $_dia, 
            $_hora=0, $_minuto=0, $_segundo=0) {
        
        if (!self::revisarFecha($_anio, $_mes, $_dia, $_hora, $_minuto, $_segundo)) {
            throw new InvalidArgumentException();
        }
        $anio = self::rellenar($_anio, 4);
        $mes = self::rellenar($_mes, 2);
        $dia = self::rellenar($_dia, 2);
        $hora = self::rellenar($_hora, 2);
        $minuto = self::rellenar($_minuto, 2);
        $segundo = self::rellenar($_segundo, 2);
        return "$anio-$mes-$dia $hora:$minuto:$segundo";
    }
    
    /**
     * Revisa si la fecha tiene los valores correctos para ingresar en la base de datos
     * 
     * @param int $anio Entre 1 y 32767
     * @param int $mes Entre 1 y 12
     * @param int $dia Dia del mes entre 1 y 31 segun el mes, toma en cuenta años bisiestos
     * @param int $hora Entre 0 y 23
     * @param int $minuto Entre 0 y 59
     * @param int $segundo Entre 0 y 59
     * @return boolean
     */
    public static function revisarFecha(
            $anio, $mes, $dia, 
            $hora=0, $minuto=0, $segundo=0) {
        
        return checkdate($mes, $dia, $anio) &&
                self::revisarHora($hora, $minuto, $segundo);
    }
    
    /**
     * Revisa si los argumentos de hora, minuto y segudo estn dentro los
     * paratros aceptables
     * 
     * @param int $hora Entre 0  y 23
     * @param int $minuto Entre 0 y 59
     * @param int $segundo Entre 0 y 59
     * @return boolean
     */
    public static function revisarHora($hora, $minuto, $segundo) {
        
        return $hora >= 0 && $hora <= 23 &&
                $minuto >= 0 && $minuto <= 59 &&
                $segundo >= 0 && $segundo <= 59;
    }
    
    /**
     * Revisa el formato correcto de una fecha y el rango correcto de
     * valores. Debe tener la forma "Y-m-d H:i:s"
     * Y 4 caracteres Desde 0001 a 9999
     * m 2 caracteres Desde 01 a 12
     * d 2 caracteres Desde 01 a 31
     * H 2 caracteres Desde 00 a 23
     * i 2 caracteres Desde 00 a 59
     * s 2 caracteres Desde 00 a 59
     * 
     * @param string $fechaHora
     * @return bool
     */
    public static function esFechaHora($fechaHora)
    {
        $formato = 'Y-m-d H:i:s';
        $d = DateTime::createFromFormat($formato, $fechaHora);
        if ($d && $d->format($formato) == $fechaHora) {
            $fecha_assoc = getdate(strtotime($fechaHora));
            return self::revisarFecha(
                    $fecha_assoc['year'], $fecha_assoc['mon'], $fecha_assoc['mday'], 
                    $fecha_assoc['hours'], $fecha_assoc['minutes'], $fecha_assoc['seconds']);
        }
        return false;
    }
    
    /**
     * Verifica si la entrada es una fecha con el formato correcto
     * Y-m-d
     * Y años de 0001 a 9999
     * m meses de 01 a 12
     * d dias de 01 a 31 dependieno del mes
     * 
     * @param string $fecha
     * @return boolean
     */
    public static function esFecha($fecha) {
        
        $formato = 'Y-m-d';
        $d = DateTime::createFromFormat($formato, $fecha);
        if ($d && $d->format($formato) == $fecha) {
            $fecha_assoc = getdate(strtotime($fecha));
            return self::revisarFecha(
                    $fecha_assoc['year'], $fecha_assoc['mon'], 
                    $fecha_assoc['mday'], 0, 0, 0);
        }
        return false;
    }
    
    
    /**
     * Acepta 2 horas validas concatenadas por ejemplo h1-h2 y las valida
     * 
     * @param string $periodo
     * @return boolean
     */
    public static function esPeriodo($periodo) {
        
        $periodo = explode('-', $periodo);
        $hora1 = explode(" ", $periodo[0]);
        $hora2 = explode(" ", $periodo[1]);
        if (self::esHoraMinuto($hora1) && self::esHoraMinuto($hora2)) {
            return true;
        }
        return false;
    }
    
    /**
     * Valida los valores de hora y minuto
     * 
     * Formato hh:mm
     * 
     * @param string $entrada
     * @return boolean
     */
    public static function esHoraMinuto($entrada){
        $horaMinuto = explode(" ", $entrada);
        $hora = explode(":", $horaMinuto[0])[0];
        $minuto = explode(":", $horaMinuto[0])[1];
        if (self::esNumeroEntero($hora) && self::esNumeroEntero($minuto)) {
            return $hora >= 0 && $minuto >= 0 && $hora <= 23 && $minuto <= 59;
        }
        return false;
    }
    /**
     * Verifica si una fecha es menor que otra, toma en cuenta
     * año, mes, dia, hora, minuto y segundo
     * 
     * @param string $fecha1
     * @param string $fecha2
     * @return boolean
     */
    public static function fechaEsMenor($fecha1, $fecha2) {
        
        $tiempo1 = strtotime($fecha1);
        $tiempo2 = strtotime($fecha2);
        return $tiempo1 < $tiempo2;
    }
    
    /**
     * Verifica si una fecha es menor o igual que otra
     * 
     * @param string $fecha1
     * @param string $fecha2
     * @return boolean
     */
    public static function fechaEsMenorIgual($fecha1, $fecha2) {
        
        $tiempo1 = strtotime($fecha1);
        $tiempo2 = strtotime($fecha2);
        return $tiempo1 <= $tiempo2;
    }
    
    private static function rellenar($valor, $tamano, $relleno='0') {
        
        $str = "$valor";
        while (strlen($str) < $tamano) {
            $str = "$relleno$str";
        }
        return $str;
    }
    
    public static function revisarCampoVacio($valor, $nombre) {
        
        if ($valor == '' || $valor == null) {
            throw new CampoVacioExcepcion($nombre);
        }
    }
    
    public static function revisarCampoEsNumeroEntero($valor, $nombre) {
        
        if (!Validador::esNumeroEntero($valor)) {
            throw new ValorIncorrectoExcepcion($nombre, 'No es número entero');
        }
    }
    
    public static function revisarCampoEsBooleno($valor, $nombre) {
        
        if (!($valor == 1 || $valor == 0)) {
            throw new ValorIncorrectoExcepcion($nombre, 'No es un booleano');
        }
    }
    
    public static function revisarCampoEsNumeroEnteroPositivo($valor, $nombre) {
        
        if (!Validador::esNumeroEntero($valor) || $valor < 0) {
            throw new ValorIncorrectoExcepcion($nombre, 'No es número entero positivo');
        }
    }
    
    public static function revisarCampoEsAlfaNumerico($valor, $nombre) {
        
        if (!Validador::esAlfaNumerico($valor)) {
            throw new ValorIncorrectoExcepcion($nombre, 'No es alfanumérico');
        }
    }
    
    public static function revisarCampoEsFecha($valor, $nombre) {
        
        if (!Validador::esFecha($valor)) {
            throw new ValorIncorrectoExcepcion(
                    $nombre, 'No tiene el formato de fecha');
        }
    }
    
    public static function revisarCampoEsFechaHora($valor, $nombre) {
        
        if (!Validador::esFechaHora($valor)) {
            throw new ValorIncorrectoExcepcion(
                    $nombre, 'No tiene el formato de fecha y hora');
        }
    }
    
    public static function revisarFechaEsMenor($fechaA, $fechaB, $nombreA, $nombreB) {
        
        if (!Validador::fechaEsMenor($fechaA, $fechaB)) {
            throw new ValoresIncorrectosExcepcion(
                    [$nombreA, $nombreB], "$nombreA debe ser menor que $nombreB");
        }
    }
    
    public static function revisarFechaEsMenorIgual($fechaA, $fechaB, $nombreA, $nombreB) {
        
        if (!Validador::fechaEsMenorIgual($fechaA, $fechaB)) {
            throw new ValoresIncorrectosExcepcion(
                [$nombreA, $nombreB], "$nombreA debe ser menor o igual que $nombreB");
        }
    }
    
    public static function revisarHoraEsMenor($horaInicio, $horaFin, $nombreA, $nombreB){
        
        if (Validador::revisarFechaEsMenor(
                date('Y-m-d') . " " . $horaInicio,
                date('Y-m-d') . " " . $horaFin, 'Hora inicio', 'Hora fin')) {
            throw new ValoresIncorrectosExcepcion(
                [$nombreA, $nombreB], "$nombreA debe ser menor que $nombreB");
        }
    }
    
    public static function revisarHoraEsMenorIgual($horaInicio, $horaFin, $nombreA, $nombreB){
        
        if (Validador::revisarFechaEsMenorIgual(
                date('Y-m-d') . " " . $horaInicio,
                date('Y-m-d') . " " . $horaFin, 'Hora inicio', 'Hora fin')) {
            throw new ValoresIncorrectosExcepcion(
                [$nombreA, $nombreB], "$nombreA debe ser menor o igual que $nombreB");
        }
    }
    
    public static function revisarCampoEsCorreo($valor, $nombreCampo) {
        
        if (!Validador::esEmail($valor)) {
            throw new ValorIncorrectoExcepcion($nombreCampo, 
                    "No es un correo electrónico válido");
        }
    }
    
    public static function revisarCampoEsNombre($valor, $nombreCampo){
        
        if (!Validador::esNombre($valor)) {
            throw new ValorIncorrectoExcepcion($nombreCampo, 
                    "Solo se permiten caracteres del alfabeto");
        }
    }
    
    public static function revisarCampoEsHoraMinuto($valor, $nombreCampo){
        
        if (!Validador::esHoraMinuto($valor)) {
            throw new ValorIncorrectoExcepcion($nombreCampo, 
                    "El formato de hora-minuto no es correcto");
        }
    }
    public static function revisarCampoEsHora($valor, $nombreCampo){
        
        if (!Validador::esHoraMinuto($valor)) {
            throw new ValorIncorrectoExcepcion($nombreCampo, 
                    "El formato de hora-minuto no es correcto");
        }
    }
}