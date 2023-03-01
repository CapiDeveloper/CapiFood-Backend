<?php

namespace Model;

class Restaurante extends ActiveRecord{

    public static $tabla = 'restaurante';
    public static $columnasDB = ['id','nombre','descripcion','ciudad', 'direccion', 
    'telefono', 'logo', 'ruc','capacidad','internet','delivery','llevar','id_usuario','id_categoria'];

    public $id;
    public $nombre;
    public $descripcion;
    public $ciudad;
    public $direccion;
    public $telefono;
    public $logo;
    public $ruc;
    public $capacidad;
    public $internet;
    public $delivery;
    public $llevar;
    public $id_usuario;
    public $id_categoria;

    public function __construct($args=[]){
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->descripcion = $args['descripcion'] ?? '';
        $this->ciudad = $args['ciudad'] ?? '';
        $this->direccion = $args['direccion'] ?? '';
        $this->telefono = $args['telefono'] ?? '';
        $this->logo = $args['logo'] ?? '';
        $this->ruc = $args['ruc'] ?? '';
        $this->capacidad = $args['capacidad'] ?? '';
        $this->internet = $args['internet'] ?? '';
        $this->delivery = $args['delivery'] ?? '';
        $this->llevar = $args['llevar'] ?? '';
        $this->id_usuario = $args['id_usuario'] ?? '';
        $this->id_categoria = $args['id_categoria'] ?? '';
    }
}
?>