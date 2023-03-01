<?php

namespace Model;

class DetalleFactura extends ActiveRecord {
    public static $tabla = 'detalle_factura';
    public static $columnasDB = ['id', 'cantidad', 'precio', 'factura_id',
    'producto_id'];

    public function __construct(){
        $this->id = $args['id'] ?? null;
        $this->precio = $args['precio'] ?? '';
        $this->cantidad = $args['cantidad'] ?? '';
        $this->factura_id = $args['factura_id'] ?? '';
        $this->producto_id = $args['producto_id'] ?? '';
    }
}