<?php

/**
 * Clase principal de exepciones validacion
 */
class ValidacionExcepcion extends Exception {
    
    public function __construct($message) {
        
        parent::__construct($message);
    }
}

class CampoVacioExcepcion extends ValidacionExcepcion {
    
    /**
     * 
     * @param string $nombreCampo
     */
    public function __construct($nombreCampo) {
        
        parent::__construct("El campo $nombreCampo no puede ser vacio");
    }
}

class ValorIncorrectoExcepcion extends ValidacionExcepcion {
    
    /**
     * 
     * @param string $nombreCampo
     * @param string $razon
     */
    public function __construct($nombreCampo, $razon) {
        
        parent::__construct("El campo $nombreCampo es incorrecto: $razon");
    }
}

function descargarNombreCampos($nombreCampos) {
    
    $len = count($nombreCampos);
    if ($len < 1) {
        return null;
    }
    $resultado = $nombreCampos[0];
    if ($len == 1) {
        return $resultado;
    }
    for ($indice = 1; $indice < $len - 1; $indice++) {
        $nombre = $nombreCampos[$indice];
        $resultado .= ", $nombre";
    }
    $nombre = $nombreCampos[$len - 1];
    $resultado .= " y $nombre";
    return $resultado;
}

class ValoresIncorrectosExcepcion extends ValidacionExcepcion {
    
    public function __construct($nombreCampos, $razon) {
        
        parent::__construct("Los campos ".descargarNombreCampos($nombreCampos)." no son correctos: $razon");
    }
}

class GuardarExcepcion extends ValidacionExcepcion {
    
    /**
     * 
     * @param string $nombreTabla
     */
    public function __construct($nombreTabla) {
        
        parent::__construct("No se pudo guardar en $nombreTabla");
    }
}

class ActualizarExcepcion extends ValidacionExcepcion {
    
    /**
     * 
     * @param string $nombreTabla
     * @param string|int $id
     */
    public function __construct($nombreTabla, $id) {
        
        parent::__construct("No se pudo actualizar el registro $id en $nombreTabla");
    }
}

class EliminarExcepcion extends ValidacionExcepcion {
    
    /**
     * 
     * @param string $nombreTabla
     * @param string|int $id
     */
    public function __construct($nombreTabla, $id) {
        
        parent::__construct("No se pudo eliminar el registro $id en $nombreTabla");
    }
}