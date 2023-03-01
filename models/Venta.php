<?php

namespace Model;

class Venta extends ActiveRecord{
    public static $tabla = 'venta';
    public static $columnasDB = ['id','fecha','hora', 'total','mes','anio',
    'num_mesa','id_restaurante','forma_pagoId','estado','direccion','mensaje','telefono','ciudad','cliente'];

    public $id;
    public $fecha;
    public $hora;
    public $total;
    public $mes;
    public $anio;
    public $num_mesa;
    public $id_restaurante;
    public $forma_pagoId;
    public $estado;
    public $direccion;
    public $mensaje;
    public $telefono;
    public $ciudad;
    public $cliente;

    public function __construct($args = []){
        $this->id = $args['id'] ?? null;
        $this->fecha = $args['fecha'] ?? '';
        $this->hora = $args['hora'] ?? '';
        $this->total = $args['total'] ?? '';
        $this->mes = $args['mes'] ?? '';
        $this->anio = $args['anio'] ?? '';
        $this->num_mesa = $args['num_mesa'] ?? '';
        $this->id_restaurante = $args['id_restaurante'] ?? '';
        $this->forma_pagoId = $args['forma_pagoId'] ?? '';
        $this->estado = $args['estado'] ?? '';
        $this->direccion = $args['direccion'] ?? '';
        $this->mensaje = $args['mensaje'] ?? '';
        $this->telefono = $args['telefono'] ?? '';
        $this->ciudad = $args['ciudad'] ?? '';
        $this->cliente = $args['cliente'] ?? '';
    }
    public static function deleteSales($id){
        $query = "DELETE FROM detalle_venta WHERE venta_id = ".$id." ;";
        $resultado = self::$db->query($query);
        return $resultado;
    }
}
?>