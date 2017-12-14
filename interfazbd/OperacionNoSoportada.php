<?php

class OperacionNoSoportada extends Exception {
    
    public function __construct($mensaje) {
        parent::__construct($mensaje);
    }
}