<?php

namespace Model;

class InfoVenta extends ActiveRecord{
    public static $tabla = 'venta';
    public static $columnasDB = ['id','num_mesa','fecha','hora', 'total', 'cliente','precio','cantidad','monto_total','venta_id','nombre','mes','anio','estado','mensaje','telefono','ciudad','direccion'];

    public $id;
    public $num_mesa;
    public $fecha;
    public $hora;
    public $total;
    public $cliente;
    public $precio;
    public $cantidad;
    public $monto_total;
    public $venta_id;
    public $nombre;
    public $mes;
    public $anio;
    public $estado;
    public $mensaje;
    public $telefono;
    public $ciudad;
    public $direccion;

    public function __construct($args=[])
    {
        $this->id=$args['id']??null;
        $this->num_mesa=$args['num_mesa']??'';
        $this->fecha=$args['fecha']??'';
        $this->hora=$args['hora']??'';
        $this->total=$args['total']??'';
        $this->cliente=$args['cliente']??'';
        $this->precio=$args['precio']??'';
        $this->cantidad=$args['cantidad']??'';
        $this->monto_total=$args['monto_total']??'';
        $this->venta_id=$args['venta_id']??'';
        $this->nombre=$args['nombre']??'';
        $this->mes=$args['mes']??'';
        $this->anio=$args['anio']??'';
        $this->estado=$args['estado']??'';
        $this->mensaje=$args['mensaje']??'';
        $this->telefono=$args['telefono']??'';
        $this->ciudad=$args['ciudad']??'';
        $this->direccion=$args['direccion']??'';
    }
}