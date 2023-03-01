<?php

namespace Controllers;

use Model\Categoria;
use Model\Restaurante;
use Model\Usuario;

class CategoriaController {
    public static function createCategory(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $respuesta = [];

            $existeRestaurante = Restaurante::find($_POST['id_restaurante']);
            $existeUsuario = Usuario::find($_POST['id_user']);
            $token = $_POST['token'];

            if(isset($existeRestaurante) && isset($token) && $existeUsuario->token == $token ){

                $query = "SELECT *FROM categoria WHERE id_restaurante = ";
                $query .= $_POST['id_restaurante'] ." AND nombre = '".strtoupper($_POST['nombre'])."';";
                $categoriasSQL = Categoria::SQL($query);
                $existeCategoria = array_shift($categoriasSQL);

                if($existeCategoria == null){
                    $nuevaCategoria = new Categoria($_POST);
                     
                    $resultado = $nuevaCategoria->guardar();

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
                    $respuesta = [
                        'valido'=> false,
                        'resultado'=>'La categoria ya existe',
                    ];
                }

            }else{
                $respuesta = [
                    'valido'=> false,
                    'resultado'=>'El restaurante no existe',
                ];
            }
            // $nombre = $_POST['nombre'];

            echo json_encode($respuesta);
            return;
        }
    }
}