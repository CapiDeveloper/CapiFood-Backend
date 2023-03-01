<?php

namespace Model;

class FormaPago extends ActiveRecord {
    public static $tabla = 'forma_pago';
    public static $columnasDB = ['id', 'nombre'];

    public $id;
    public $nombre;

    public function __construct(){
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
    }
}
?>