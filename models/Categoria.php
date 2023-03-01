<?php

namespace Model;

class Categoria extends ActiveRecord {
    public static $tabla = 'categoria';
    public static $columnasDB = ['id', 'nombre', 'descripcion', 'id_restaurante'];

    public function __construct($args = []){
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->descripcion = $args['descripcion'] ?? '';
        $this->id_restaurante = $args['id_restaurante'] ?? '';
    }
}
