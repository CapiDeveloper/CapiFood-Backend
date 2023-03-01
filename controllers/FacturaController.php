<?php
namespace Controllers;

use Model\Cliente;
use Model\FormaPago;
use Model\Restaurante;
use Model\Usuario;

class FacturaController {

    public static function getInfoClientesAndFormaPago(){
        if($_SERVER['REQUEST_METHOD'] === 'GET'){
            
            $respuesta = [];
            $id_restaurante = $_GET['id_restaurante'];

            // verificar si existe el restaurante
            $existe_restaurante = Restaurante::find($id_restaurante);
            $existeUsuario = Usuario::find($_GET['id']);
            
            if($existe_restaurante && isset($existeUsuario) && $existeUsuario->token == $_GET['token']){
                if(isset($id_restaurante)){
                
                    // No me acuerdo para que era
                    $queryClientes = "SELECT *FROM cliente WHERE restaurante_id = ".$id_restaurante.";";
                    $clientesConsulta = Cliente::SQL($queryClientes);

                    $dataClientesSeguros = [];
                    foreach ($clientesConsulta as $cliente) {
                        unset($cliente->email);
                        unset($cliente->ciudad);
                        unset($cliente->direccion);
                        unset($cliente->restaurante_id);
                        unset($cliente->ruc);

                        array_push($dataClientesSeguros,$cliente);
                    }
                    // Traer tipo de de formas de pago que existen
                    $forma_pago = FormaPago::all();
                        
                    // Si hay clientes
                    $respuesta = [
                        'valido'=>true,
                        'clientes'=>$dataClientesSeguros ?? [],
                        'forma_pago'=>$forma_pago ?? []
                    ];
    
                }else{
                    $respuesta = [
                        'valido'=>false,
                        'resultado'=>[]
                    ];
                }
            }else{
                $respuesta = [
                    'valido'=>false,
                    'resultado'=>[]
                ];
            }
            echo json_encode($respuesta);
            return;
        }
    }

}