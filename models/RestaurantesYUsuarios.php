<?php
namespace Model;
class RestaurantesYUsuarios extends ActiveRecord{
    
    public static $tabla = 'restaurante';
    public static $columnasDB = ['id','nombre','ciudad', 'direccion', 
    'telefono','ruc','nombre_usuario','cedula'];

    public $id;
    public $nombre;
    public $ciudad;
    public $direccion;
    public $telefono;
    public $ruc;
    public $nombre_usuario;
    public $cedula;

    public function __construct(){
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->ciudad = $args['ciudad'] ?? '';
        $this->direccion = $args['direccion'] ?? '';
        $this->telefono = $args['telefono'] ?? '';
        $this->ruc = $args['ruc'] ?? '';
        $this->nombre_usuario = $args['nombre_usuario'] ?? '';
        $this->cedula = $args['cedula'] ?? '';
    }

}