<?php

namespace Controllers;

use Model\Usuario;
use Model\RestauranteCategoria;

class UsuarioController {
    public static function createUser(){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            
            $respuesta = [];

            if(count($_POST) == 4){

                // Verificar que el email no exista y que exista el restaurante
                $existeUsuarioNombre = Usuario::where('nombre',$_POST['nombre']);
                $existeUsuario = Usuario::where('email',$_POST['email']);
                

                if(($existeUsuario == null) && ($existeUsuarioNombre == null) ){
                    
                    /* 
                        linea 30 debe ser $nuevoUsuario->tipo = 0; 
                    */
                    
                    if(($existeUsuario['email'] != $_POST['email'])){
                        $nuevoUsuario = new Usuario($_POST);
                        // $nuevoUsuario->tipo = 1;
                        $nuevoUsuario->hashearPassword();
                        $nuevoUsuario->tipo = 0;
                        // Crear token
                        $nuevoUsuario->token = bin2hex(openssl_random_pseudo_bytes(16));

                        $resultado = $nuevoUsuario->guardar();

                        if(!$resultado['resultado']){
                            // Ocurrio un error al guardar (datos no validos)
                            $respuesta = [
                                'valido'=> false,
                                'resultado'=>'Error de sintaxis, comuniquese con soporte',
                            ];
                        }else{
                            // Usuarios guardado correctamente
                            $respuesta = [
                                'valido'=> true,
                                'resultado'=>'Creado correctamente'
                            ];
                        }
                        
                    }else{
                        // Usuario ya existe
                        $respuesta = [
                            'valido'=> false,
                            'resultado'=>'Usuario ya existe',
                             
                        ];
                    }
                
                }else{
                    $respuesta = [
                        'valido'=> false,
                        'resultado'=>'El usuario ya existe',
                    ];
                }
            }else{
                $respuesta = [
                    'valido'=> false,
                    'resultado'=>'No se estan enviando bien los parametros',
                ];
            }
            echo json_encode($respuesta);
            return;
        }
    }
    public static function getUsuarios(){
        if($_SERVER['REQUEST_METHOD'] === 'GET'){
            
            if(count($_GET) == 3){
                // Parametros de url
                $idUsuario = $_GET['id'];
                $tipoUsuario = $_GET['user_tipo'];
                $nombreUsaurio = $_GET['nombre'];

                $owner =Usuario::find($idUsuario);

                if(isset($owner)){
                    
                    if($owner->id == $idUsuario && $owner->tipo == $tipoUsuario && $owner->nombre == $nombreUsaurio){
                        
                        $usuarios = Usuario::all();
                        $categorias = RestauranteCategoria::all();

                        $respuesta = [
                            'valido' => true,
                            'usuarios' => $usuarios ?? [],
                            'categorias'=>$categorias ?? []
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