<?php
namespace Controllers;

use Model\Restaurante;
use Model\Usuario;

class AutenticacionController{
    public static function login(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            $email = $_POST['email'];
            $password = $_POST['password'];
            
            $existeUsuario = Usuario::where('email',$email);
            
            $resultado = [];

            if (isset($existeUsuario) && $existeUsuario != null) {
                if (password_verify($password,$existeUsuario->password)) {

                    if($existeUsuario->tipo == 0){

                        $restaurante = Restaurante::where('id_usuario',$existeUsuario->id);
                    
                        $existeUsuario->restaurante_id = $restaurante->id;
                        $existeUsuario->nombre_negocio = $restaurante->nombre;
                        $existeUsuario->ciudad_negocio = $restaurante->ciudad;
                        $existeUsuario->direccion_negocio = $restaurante->direccion;
                        $existeUsuario->telefono_negocio = $restaurante->telefono;
                        $existeUsuario->logo_negocio = $restaurante->logo;
                        $existeUsuario->ruc_negocio = $restaurante->ruc;
                    }

                    unset($existeUsuario->cedula);
                    unset($existeUsuario->email);
                    unset($existeUsuario->password);

                    $resultado = [
                        'valido'=>true,
                        'respuesta'=>$existeUsuario
                    ];
                }else{
                    $resultado = [
                        'valido'=>false,
                        'respuesta'=>'Password incorrecto'
                    ];
                }
            }else{
                $resultado = [
                    'valido'=>false,
                    'respuesta'=>'Usuario no existe'
                ];
            }
            echo json_encode($resultado);
            return;
        }
    }
    public static function loginWithToken(){
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            
            $resultado = [];
            
            $token = $_GET['token'];

            if (isset($token)) {
                $existeUsuario = Usuario::where('token',$token);

                if ($existeUsuario) {

                    if($existeUsuario->tipo == 0){

                        $restaurante = Restaurante::where('id_usuario',$existeUsuario->id);
                        
                        $existeUsuario->restaurante_id = $restaurante->id;
                        $existeUsuario->nombre_negocio = $restaurante->nombre;
                        $existeUsuario->ciudad_negocio = $restaurante->ciudad;
                        $existeUsuario->direccion_negocio = $restaurante->direccion;
                        $existeUsuario->telefono_negocio = $restaurante->telefono;
                        $existeUsuario->logo_negocio = $restaurante->logo;
                        $existeUsuario->ruc_negocio = $restaurante->ruc;
                    }

                    unset($existeUsuario->cedula);
                    unset($existeUsuario->email);
                    unset($existeUsuario->password);

                    $resultado = [
                        'valido'=>true,
                        'respuesta'=>$existeUsuario
                    ];
                }else{
                    $resultado = [
                        'valido'=>false,
                    ];
                }
            }else{
                $resultado = [
                    'valido'=>false,
                ];
            }
            echo json_encode($resultado);
            return;
        }
    }
}