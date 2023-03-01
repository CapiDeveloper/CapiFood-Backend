<?php

namespace Model;

class Cliente extends ActiveRecord {
    public static $tabla = 'cliente';
    public static $columnasDB = ['id', 'nombre', 'email', 'cedula',
    'direccion','ciudad','ruc','telefono','restaurante_id'];

    public function __construct($args = []){
        $this->id = $args['id'] ?? null;
        $this->email = $args['email'] ?? '';
        $this->nombre = $args['nombre'] ?? '';
        $this->cedula = $args['cedula'] ?? '';
        $this->direccion = $args['direccion'] ?? '';
        $this->ciudad = $args['ciudad'] ?? '';
        $this->ruc = $args['ruc'] ?? '';
        $this->telefono = $args['telefono'] ?? '';
        $this->restaurante_id = $args['restaurante_id'] ?? '';
    }
}