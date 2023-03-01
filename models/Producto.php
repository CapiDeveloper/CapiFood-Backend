<?php

namespace Model;

class Producto extends ActiveRecord {
    public static $tabla = 'producto';
    public static $columnasDB = ['id', 'nombre', 'precio', 'imagen','id_categoria','id_usuario','disponible','descripcion'];

    public $id;
    public $nombre;
    public $precio;
    public $imagen;
    public $id_categoria;
    public $id_usuario;
    public $disponible;
    public $descripcion;

    public function __construct($args = []){
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->precio = $args['precio'] ?? '';
        $this->imagen = $args['imagen'] ?? '';
        $this->id_categoria = $args['id_categoria'] ?? '';
        $this->id_usuario = $args['id_usuario'] ?? '';
        $this->disponible = $args['disponible'] ?? '';
        $this->descripcion = $args['descripcion'] ?? '';
    }

}
?>