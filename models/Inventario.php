<?php

// **UTILIZADO**
// No existe una tabla en la BD, sino que es un modelo para extraer una vista
// hecha con inner joins

namespace Model;

class Inventario extends ActiveRecord {
    public static $tabla = 'producto';
    public static $columnasDB = ['id', 'nombre', 'categoria', 'precio'];

    public $id;
    public $nombre;
    public $categoria;
    public $precio;

    public function __construct(){
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->categoria = $args['categoria'] ?? '';
        $this->precio = $args['precio'] ?? '';
    }
}
?>