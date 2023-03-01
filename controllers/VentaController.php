<?php

namespace Controllers;

use Model\Cliente;
use Model\DetalleVenta;
use Model\InfoVenta;
use Model\Restaurante;
use Model\Usuario;
use Model\Venta;

class VentaController{

    public static function deleteSale(){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $respuesta = [];
            $query = 'SELECT *FROM venta WHERE id ='.$_POST['id'].' AND id_restaurante = '.$_POST['restaurante_id'].';';
            $existeVenta = array_shift(Venta::consultarSQL($query));
            if(isset($existeVenta) == true && !empty($existeVenta)){
                $existeUsuario = Usuario::where('token',$_POST['token']);
                if(!empty($existeUsuario) && isset($existeUsuario) == true){
                    // eliminar detalles de ventas
                    $resultado1 = Venta::deleteSales($_POST['id']);
                    // Eliminar venta
                    $resultado2 = $existeVenta->eliminar();
                    if($resultado1 && $resultado2){
                        $respuesta = [
                            'valido'=>true
                        ];
                    }else{
                        $respuesta = [
                            'valido'=>false
                        ];
                    }
                }else{
                    $respuesta = [
                        'valido'=>false
                    ];
                }
            }else{
                $respuesta = [
                    'valido'=>false
                ];
            }
            
            echo json_encode($respuesta);
            return; 
        }
    }
    public static function changeStateSale(){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $respuesta = [];
            $query = 'SELECT *FROM venta WHERE id ='.$_POST['id'].' AND id_restaurante = '.$_POST['restaurante_id'].';';
            $existeVenta = array_shift(Venta::consultarSQL($query));
            if(isset($existeVenta) == true && !empty($existeVenta)){
                $existeUsuario = Usuario::where('token',$_POST['token']);
                if(!empty($existeUsuario) && isset($existeUsuario) == true){
                    // actualizar
                    $actualizarEstado = new Venta([
                        'id'=>$existeVenta->id,
                        'fecha'=>$existeVenta->fecha,
                        'hora'=>$existeVenta->hora,
                        'total'=>$existeVenta->total,
                        'mes'=>$existeVenta->mes,
                        'anio'=>$existeVenta->anio,
                        'num_mesa'=>$existeVenta->num_mesa,
                        'id_cliente'=>$existeVenta->id_cliente,
                        'id_restaurante'=>$existeVenta->id_restaurante,
                        'forma_pagoId'=>$existeVenta->forma_pagoId,
                        'estado'=>'1',
                    ]);
                    $respuesta = $actualizarEstado->guardar();

                    if($respuesta){
                        $respuesta=[
                            'valido'=>true,
                            'id'=>$existeVenta->id
                        ];
                    }else{
                        $respuesta=['valido'=>false];
                    }
                    
                }else{
                    // No existe usuario
                    $respuesta=['valido'=>false];
                }

            }else{
                // Datos incorrectos
                $respuesta=['valido'=>false];
            }
            echo json_encode($respuesta);
            return;
        }
    }

    public static function crearVentaCliente(){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){

            $respuesta = [];

            $existeRestaurante = Restaurante::find($_POST['restaurante_id']);
            $existeUsuario = Usuario::find($_POST['id_user']);

            if(isset($existeRestaurante) && isset($existeUsuario) && $existeUsuario->token == $_POST['token']){

                // Verificar si el cliente ya existe
                $queryClienteCedula = "SELECT *FROM cliente WHERE cedula =".$_POST['cedula']." AND restaurante_id = ".$_POST['restaurante_id'].";";
                $existeClienteCedula = array_shift(Cliente::SQL($queryClienteCedula));

                $idCliente = '';

                if(isset($existeClienteCedula)){
                    // Existe, no toca crear
                    $idCliente = $existeClienteCedula->id;
                }else{
                    // Crear cliente nuevo
                    $clienteCrear = [
                        'nombre'=>$_POST['nombre'],
                        'email'=>$_POST['email'],
                        'cedula'=>$_POST['cedula'],
                        'direccion'=>$_POST['direccion'],
                        'ciudad'=>$_POST['ciudad'],
                        'telefono'=>$_POST['telefono'],
                        'ruc'=>$_POST['ruc'],
                        'restaurante_id'=>$_POST['restaurante_id']
                    ];
                    $cliente = new Cliente($clienteCrear);
                    $resultado = $cliente->guardar();
                    $idCliente = $resultado['id'];
                }

                // Verificar si se ha guardado
                if(isset($idCliente)){
                    // crear la tabla venta
                    $ventaCrear = [
                        'fecha'=>$_POST['fecha'],
                        'hora'=>$_POST['hora'],
                        'total'=>$_POST['total'],
                        'mes'=>$_POST['mes'],
                        'anio'=>$_POST['anio'],
                        'num_mesa'=>$_POST['num_mesa'],
                        'id_cliente'=>$idCliente,
                        'id_restaurante'=>$_POST['id_restaurante'],
                        'forma_pagoId'=>$_POST['forma_pago'],
                        'estado'=>0
                    ];
                    $venta = new Venta($ventaCrear);
                    $resultadoVentaGuardar = $venta->guardar();
                    $idVentaGuardada = $resultadoVentaGuardar['id'];
                    
                    // Verificar si se ha guardado
                    if(isset($idVentaGuardada)){
                        // Crear la tabla detalle venta
                        $productos = json_decode($_POST['productos'],true);
                        $regresoInfoFront = [];

                        foreach ($productos as $producto) {
                            // Logica para ingreso correcto de datos
                            $producto['venta_id'] = $idVentaGuardada;
                            $producto['producto_id'] = intval($producto['producto_id']);
                            $producto['precio'] = floatval($producto['precio']); 
                            $detalleVenta = new DetalleVenta($producto);
                            
                            $idGuardado = $detalleVenta->guardar();

                            // Logica para enviar al frontend
                            unset($producto['producto_id']);
                            unset($producto['cliente']);

                            $producto['id'] = $idGuardado['id'];
                            $producto['fecha'] = $_POST['fecha'];
                            $producto['hora'] = $_POST['hora'];
                            $producto['cliente'] = $_POST['nombre'];
                            $producto['total'] = $_POST['total'];

                            // buscar cliente
                            $existeCliente = Cliente::find($idCliente);
                            $producto['id_cliente'] = $existeCliente->nombre;
                            array_push($regresoInfoFront,$producto);
                        }

                        // Regresamos tambien el cliente creado si no existe en la BD
                        if(!isset($existeClienteCedula)){
                            $clienteCreado = Cliente::find($idCliente);
                            unset($clienteCreado->email);
                            unset($clienteCreado->ciudad);
                            unset($clienteCreado->direccion);
                            unset($clienteCreado->restaurante_id);
                            unset($clienteCreado->ruc);

                            $respuesta = [
                                'valido'=> true,
                                'vendidos'=> $regresoInfoFront,
                                'clienteCreado'=> $clienteCreado
                            ];
                        }else{
                            $respuesta = [
                                'valido'=> true,
                                'vendidos'=> $regresoInfoFront
                            ];
                        }

                    }else{
                        // No se ha guardadi la venta
                        $respuesta = [
                            'valido'=> false,
                            'resultado'=>'No se puede almacenar la venta',
                        ];
                    }
                }else{
                // No se logro guardar el cliente
                $respuesta = [
                    'valido'=> false,
                    'resultado'=>'No se puede almacenar el cliente',
                ];
                }
            }else{
                // No existe restaurante
                $respuesta = [
                    'valido'=> false,
                    'resultado'=>'El restaurante no existe',
                ];
            }

            echo json_encode($respuesta);
            return;
        }
    }

    public static function crearVentaCondicionalPublico(){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $respuesta = [];
            if (strlen($_POST['restaurante']) > 0) {
                $consultaExisteRestaurante = "SELECT id,nombre,id_usuario from restaurante where nombre = '".$_POST["restaurante"]."' LIMIT 1; ";
                $restaurante =  array_shift(Restaurante::SQL($consultaExisteRestaurante));
                if($restaurante && isset($restaurante) && $restaurante != null){
                    
                    $ventaCrear = [
                        'fecha'=>$_POST['fecha'],
                        'hora'=>$_POST['hora'],
                        'total'=>$_POST['total'],
                        'mes'=>$_POST['mes'],
                        'anio'=>$_POST['anio'],
                        'num_mesa'=>$_POST['num_mesa'],
                        'id_restaurante'=>$restaurante->id,
                        'forma_pagoId'=>$_POST['forma_pago'],
                        'estado'=>0,
                        'direccion'=>$_POST['direccion'],
                        'mensaje'=>$_POST['mensaje'],
                        'telefono'=>$_POST['telefono'],
                        'ciudad'=>$_POST['ciudad'],
                        'cliente'=>$_POST['cliente']
                    ];
                    $venta = new Venta($ventaCrear);
                    
                    $resultadoVentaGuardar = $venta->guardar();
                    $idVentaGuardada = $resultadoVentaGuardar['id'];
                    
                    // Verificar si se ha guardado
                    if(isset($idVentaGuardada)){
                        // Crear la tabla detalle venta
                        $productos = json_decode($_POST['productos'],true);
                        $regresoInfoFront = [];

                        foreach ($productos as $producto) {
                            // Logica para ingreso correcto de datos
                            $producto['venta_id'] = $idVentaGuardada;
                            $producto['producto_id'] = intval($producto['producto_id']);
                            $producto['precio'] = floatval($producto['precio']); 
                            $detalleVenta = new DetalleVenta($producto);
                            
                            $idGuardado = $detalleVenta->guardar();
                        }
                        $respuesta = [
                            'valido'=> true,
                        ];
                    }else{
                        // No se guardo la venta
                        $respuesta = [
                            'valido'=> false,
                            'vendidos'=> 'No se logra guadar la venta'
                        ];
                    }

                }else{
                    $respuesta = [
                        'valido'=> false,
                        'vendidos'=> 'No se logra guadar la venta'
                    ];
                }
            }else{
                $respuesta = [
                    'valido'=> false,
                    'vendidos'=> 'No se logra guadar la venta'
                ];
            }
            echo json_encode($respuesta);
            return;
        }
    }

    public static function crearVentaCondicional(){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){

            $respuesta = [];
            // Venta con cliente existente
            $existeUsuario = Usuario::find($_POST['id_user']);
            
            if ($existeUsuario->token == $_POST['token']) {
                     
                    $ventaCrear = [
                        'fecha'=>$_POST['fecha'],
                        'hora'=>$_POST['hora'],
                        'total'=>$_POST['total'],
                        'mes'=>$_POST['mes'],
                        'anio'=>$_POST['anio'],
                        'num_mesa'=>$_POST['num_mesa'],
                        'id_restaurante'=>$_POST['id_restaurante'],
                        'forma_pagoId'=>$_POST['forma_pago'],
                        'estado'=>0,
                        'cliente'=>$_POST['cliente']
                    ];
                    $venta = new Venta($ventaCrear);
                    
                    $resultadoVentaGuardar = $venta->guardar();
                    $idVentaGuardada = $resultadoVentaGuardar['id'];
                
                    // Verificar si se ha guardado
                    if(isset($idVentaGuardada)){
                        // Crear la tabla detalle venta
                        $productos = json_decode($_POST['productos'],true);
                        $regresoInfoFront = [];

                        foreach ($productos as $producto) {
                            // Logica para ingreso correcto de datos
                            $producto['venta_id'] = $idVentaGuardada;
                            $producto['producto_id'] = intval($producto['producto_id']);
                            $producto['precio'] = floatval($producto['precio']); 
                            $detalleVenta = new DetalleVenta($producto);
                            
                            $idGuardado = $detalleVenta->guardar();

                            // Logica para enviar al frontend
                            unset($producto['producto_id']);
                            unset($producto['cliente']);

                            $producto['id'] = $idGuardado['id'];
                            $producto['num_mesa'] = $_POST['num_mesa'];
                            $producto['fecha'] = $_POST['fecha'];
                            $producto['hora'] = $_POST['hora'];
                            $producto['cliente'] = $_POST['nombre'];
                            $producto['total'] = $_POST['total'];

                            // buscar cliente
                            $producto['cliente'] = $_POST['cliente'];
                            array_push($regresoInfoFront,$producto);
                        }
                        $respuesta = [
                            'valido'=> true,
                            'vendidos'=> $regresoInfoFront
                        ];
                    }else{
                        // No se guardo la venta
                        $respuesta = [
                            'valido'=> false,
                            'vendidos'=> 'No se logra guadar la venta'
                        ];
                    }
            }else{
                $respuesta = [
                    'valido'=> false,
                    'vendidos'=> 'No se logra guadar la venta'
                ];
            }
            echo json_encode($respuesta);
            return;
        }
    }
    public static function getVentas(){
        if($_SERVER['REQUEST_METHOD'] === 'GET'){
            $respuesta = [];
            $id_restaurante = $_GET['id_restaurante'];
            $existeRestaurante = Restaurante::find($id_restaurante);
            $existeUsuario = Usuario::find($_GET['id']);
            if(isset($existeRestaurante) && isset($existeUsuario) && $existeUsuario->token == $_GET['token']){

                // Consulta todas las ventas

                $query = "SELECT detalle_venta.id,venta.num_mesa,venta.fecha,venta.hora,venta.total,venta.estado,venta.cliente,detalle_venta.precio,detalle_venta.cantidad,detalle_venta.monto_total, venta.id as venta_id, producto.nombre as nombre,venta.mensaje,venta.telefono,venta.ciudad,venta.direccion 
                FROM venta  LEFT JOIN detalle_venta ON  venta.id = detalle_venta.venta_id LEFT JOIN producto ON detalle_venta.producto_id = producto.id WHERE venta.id_restaurante = ".$id_restaurante." ORDER BY venta.hora ASC;";

                $ventas = InfoVenta::SQL($query);

                $respuesta = [
                    'valido'=> true,
                    'resultado'=>$ventas ?? null
                ];

            }else{
                // No existe resturante
                $respuesta = [
                    'valido'=> false,
                    'resultado'=>'No tiene ventas realizadas',
                ];
            }
            echo json_encode($respuesta);
            return;
        }
    }

    public static function getVentasByCategory(){
        if($_SERVER['REQUEST_METHOD'] === 'GET'){
            $respuesta = [];
            $id_restaurante = $_GET['id_restaurante'];
            $existeRestaurante = Restaurante::find($id_restaurante);
            $existeUsuario = Usuario::find($_GET['id']);
            if(isset($existeRestaurante) && isset($existeUsuario) && $existeUsuario->token == $_GET['token']){

                // Query para calcular la ventas por categoria
                $queryCategoriaVenta = "SELECT categoria.id as id,categoria.nombre, SUM(detalle_venta.cantidad) as cantidad FROM venta LEFT JOIN detalle_venta ON ";
                $queryCategoriaVenta.= "detalle_venta.venta_id = venta.id LEFT JOIN producto ON ";
                $queryCategoriaVenta.= "producto.id = detalle_venta.producto_id LEFT JOIN categoria ON ";
                $queryCategoriaVenta.= "categoria.id = producto.id_categoria ";
                $queryCategoriaVenta.= "WHERE venta.id_restaurante =".$id_restaurante;
                $queryCategoriaVenta.= " GROUP BY categoria.nombre ORDER BY cantidad DESC;";

                $ventasCategoria = InfoVenta::SQL($queryCategoriaVenta);

                $respuesta = [
                    'valido'=> true,
                    'ventas_Categoria'=>$ventasCategoria ?? []
                ];

            }else{
                // No existe resturante
                $respuesta = [
                    'valido'=> false,
                    'resultado'=>'No tiene ventas realizadas',
                ];
            }
            echo json_encode($respuesta);
            return;
        }
    }

    public static function getVentasProductosIndividuales(){
        if($_SERVER['REQUEST_METHOD'] === 'GET'){
            $respuesta = [];
            $id_restaurante = $_GET['id_restaurante'];
            $existeRestaurante = Restaurante::find($id_restaurante);
            $existeUsuario = Usuario::find($_GET['id']);
            if(isset($existeRestaurante) && isset($existeUsuario) && $existeUsuario->token == $_GET['token']){

                // Query para calcular la ventas por producto
                $queryProductoVenta = "SELECT producto.id as id,producto.nombre, SUM(detalle_venta.cantidad) as cantidad,venta.mes,venta.anio FROM venta LEFT JOIN detalle_venta ON ";
                $queryProductoVenta .= " detalle_venta.venta_id = venta.id LEFT JOIN producto ON ";
                $queryProductoVenta .= " producto.id = detalle_venta.producto_id ";
                $queryProductoVenta .= " WHERE venta.id_restaurante = ".$id_restaurante;
                $queryProductoVenta .= " GROUP BY producto.nombre,venta.mes,venta.anio ORDER BY cantidad DESC;";

                $ventaUnitaria = InfoVenta::SQL($queryProductoVenta);

                $respuesta = [
                    'valido'=> true,
                    'ventas_unitarias'=>$ventaUnitaria ?? [],
                ];

            }else{
                // No existe resturante
                $respuesta = [
                    'valido'=> false,
                    'resultado'=>'No tiene ventas realizadas',
                ];
            }
            echo json_encode($respuesta);
            return;
        }
    }
}