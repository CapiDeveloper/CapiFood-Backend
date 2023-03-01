<?php

namespace Controllers;

use Model\Restaurante;
use Model\Usuario;
use Model\RestauranteCategoria;

class RestauranteController {
    public static function createRestaurant(){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            
            $respuesta = [];

            if(isset($_POST) && count($_POST) > 0){
                
                $existeRestaurante = Restaurante::where('ruc',$_POST['ruc']);
                $existeUsuario =  Usuario::find($_POST['id_usuario']);

                // Saber si el el restaurante ya tiene un id

                if($existeRestaurante == null && $existeUsuario != null ){
                     
                    // Existe Restaurante con un id
                    $existeRestauranteConUsuario = Restaurante::where('id_usuario',$existeUsuario->id);

                    if (isset($existeRestauranteConUsuario)) {
                        // No es valido crear un negocio ya que ya tiene usuario existente
                        $respuesta = [
                            'valido' => false,
                            'resultado' => 'El negocio ya tiene un usuario existente'
                        ];
                    }else{
                        // Se puede crear sin problema
                        $restaurant = new Restaurante($_POST);

                        $valido =  $restaurant->guardar();

                        if(!$valido['resultado']){
                            // Algo fallo
                            $respuesta = [
                                'valido' => false,
                                'resultado' => 'Hubo un error al guardar'
                            ];
                        }else{
                            $respuesta = [
                                'valido' => true,
                                'resultado' => 'Guardado correctamente',
                            ];
                        } 
                    }
                    
                }else{
                    $respuesta = [
                        'valido' => false,
                        'resultado' => 'Ya existe un negocio con ese ruc o no existe usuario'
                    ]; 
                }
            }else{
                $respuesta = [
                    'valido' => false,
                    'resultado' => 'Hubo un error, comunicar a soporte'
                ]; 
            }
            echo json_encode($respuesta);
            return;
        }
    }
    public static function getRestaurantes(){
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $respuesta =[];

            if(count($_GET) == 3){
                // Parametros de url
                $idUsuario = $_GET['id'];
                $tipoUsuario = $_GET['user_tipo'];
                $nombreUsaurio = $_GET['nombre'];

                $owner =Usuario::find($idUsuario);

                if(isset($owner)){
                    
                    if($owner->id == $idUsuario && $owner->tipo == $tipoUsuario && $owner->nombre == $nombreUsaurio){
                        
                        $restaurantes = Restaurante::all();

                        $respuesta = [
                            'valido' => true,
                            'resultado' => $restaurantes ?? [],
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
            }
            echo json_encode($respuesta);
            return;
        }
    }
}