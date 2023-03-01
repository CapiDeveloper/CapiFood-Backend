<?php

namespace Model;

class RestauranteCategoria extends ActiveRecord {
    public static $tabla = 'restaurante_categoria';
    public static $columnasDB = ['id', 'nombre'];

    public $id;
    public $nombre;

    public function __construct($args = []){
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
    }
}
