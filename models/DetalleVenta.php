<?php

namespace Model;

class DetalleVenta extends ActiveRecord {
    public static $tabla = 'detalle_venta';
    public static $columnasDB = ['id', 'precio', 'cantidad', 'monto_total',
    'producto_id','venta_id'];

    public function __construct($args){
        $this->id = $args['id'] ?? null;
        $this->precio = $args['precio'] ?? '';
        $this->cantidad = $args['cantidad'] ?? '';
        $this->monto_total = $args['monto_total'] ?? '';
        $this->producto_id = $args['producto_id'] ?? '';
        $this->venta_id = $args['venta_id'] ?? '';
    }
}