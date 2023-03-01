<?php

namespace Model;

class Factura extends ActiveRecord {
    public static $tabla = 'Factura';
    public static $columnasDB = ['id', 'fecha', 'nro_comprobante', 'total',
    'id_cliente','id_usuario','forma_pagoId'];

    public function __construct(){
        $this->id = $args['id'] ?? null;
        $this->fecha = $args['fecha'] ?? '';
        $this->nro_comprobante = $args['nro_comprobante'] ?? '';
        $this->total = $args['total'] ?? '';
        $this->id_cliente = $args['id_cliente'] ?? '';
        $this->id_usuario = $args['id_usuario'] ?? '';
        $this->forma_pagoId = $args['forma_pagoId'] ?? '';
    }
}