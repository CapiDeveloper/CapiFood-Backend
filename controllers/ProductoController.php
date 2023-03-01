<?php
namespace Controllers;

use Model\Categoria;
use Model\Producto;
use Model\Restaurante;
use Model\Usuario;
use Intervention\Image\ImageManagerStatic as Image;

class ProductoController{

    // Cambiar estado de disponibilidad
    public static function toogleAvailableProducto(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $respuesta = [];
            
            if(count($_POST) == 4){
                $existeUsuario = Usuario::find($_POST['id']);
                if (isset($existeUsuario) && $existeUsuario != null && $existeUsuario->token == $_POST['token'] ) {
                    $existeProducto = Producto::find($_POST['idProducto']);
                    if (isset($existeProducto) || $existeProducto != null ) {
                        if($existeProducto->disponible == 1){
                            $existeProducto->disponible = 0;
                        }else{
                            $existeProducto->disponible = 1;
                        }
                        $existeProducto->guardar();
                        $respuesta = [
                            'valido'=> true
                        ];
                    }else{
                        $respuesta = [
                            'valido'=> false
                        ];
                    }
                }else{
                    $respuesta = [
                        'valido'=> false
                    ];
                }
            }else{
                $respuesta = [
                    'valido'=> false
                ];
            }   
            
            echo json_encode($respuesta);
            return;
        }
    }
    // Crear producto
    public static function createProduct(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $respuesta = [];
            
            if (count($_POST) == 7 && isset($_POST['token'])) {
                
                // Verificar que el usuario especifico tengo la categoria ya creada por el
                $queryCategoria = "SELECT *FROM categoria WHERE nombre ='".$_POST['id_categoria']."' AND id_restaurante = ".$_POST['id_restaurante'].";";

                $categoriaReferencia = Categoria::SQL($queryCategoria);
                $existeCategoria = array_shift($categoriaReferencia);
                
                $existeUsuario = Usuario::find($_POST['id_usuario']);                

                if ($existeCategoria != null && isset($existeCategoria) && $existeUsuario != null && $existeUsuario->token == $_POST['token']) {

                    $nuevoProducto = new Producto($_POST);

                    // Guardar imagen en el servidor
                    if(!is_dir('../public/imagenes')){
                        mkdir('../public/imagenes');
                    }
                    // Generar un nombre único
                    $nombrePila= explode(".",$_FILES['imagen']['name'])[0];
                    $nombreImagen = md5( uniqid( rand(), true ) ).$nombrePila.".webp";
            
                    // Setear la imagen
                    // Realiza un resize a la imagen con intervention
                    $imagen = Image::make($_FILES['imagen']['tmp_name'])->fit(200,200);
            
                    // Guardar la imagen en el servidor
                    $imagen->save('./imagenes/'.$nombreImagen, 80, 'webp');

                    $nuevoProducto->imagen = $nombreImagen;
                    $nuevoProducto->id_categoria = $existeCategoria->nombre;
                    $nuevoProducto->disponible = 0;
                    // Fin guardar imagen en servidor

                    $nuevoProducto->id_categoria = $existeCategoria->id;
                    $resultado = $nuevoProducto->guardar();

                    if(!$resultado['resultado']){
                        // Ocurrio un error al guardar (datos no validos)
                        $respuesta = [
                            'valido'=> false,
                            'resultado'=>'Error de sintaxis, comuniquese con soporte',
                        ];
                    }else{
                        // Producto guardado correctamente
                        $productoCreadoQuery = "SELECT *FROM producto WHERE nombre ='".$_POST['nombre']."' AND id_usuario= ".$_POST['id_usuario'].";";
                        $productosReferencia = Producto::SQL($productoCreadoQuery);
                        $productoCreado =  array_shift($productosReferencia);
                        
                        $productoCreado->id_categoria = $existeCategoria->nombre;
                        unset($productoCreado->id_usuario);
                        $respuesta = [
                            'valido'=> true,
                            'resultado'=>$productoCreado
                        ];
                    }

                }else{
                    $respuesta = [
                        'valido'=> false,
                        'resultado'=>'Categoria o usuario no existe'
                    ];
                }
            }else{
                $respuesta = [
                    'valido'=> false,
                    'resultado'=>'Datos no validos'
                ];
            }
            echo json_encode($respuesta);
            return;
        }
    }
    public static function obtenerProductos(){
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $respuesta = [];
            
            $restauranteNombre =  json_encode(strtoupper($_GET['nombre']));
            if($restauranteNombre && strlen($restauranteNombre) > 0){
                $consultaExisteRestaurante = "SELECT id,nombre,id_usuario,telefono from restaurante where nombre = ".$restauranteNombre." LIMIT 1; ";
                $restaurante =  array_shift(Restaurante::SQL($consultaExisteRestaurante));
                if($restaurante && isset($restaurante) && $restaurante != null){

                    $consultaProductos = "SELECT id,nombre,precio,imagen,id_categoria,disponible,descripcion FROM producto WHERE id_usuario = ".$restaurante->id_usuario." AND disponible = 1 ;";
                    $consultaCategorias = "SELECT *FROM categoria WHERE id_restaurante = ".$restaurante->id.";";
                    
                    $productos = Producto::SQL($consultaProductos);
                    $categorias = Categoria::SQL($consultaCategorias);

                    $respuesta = [
                        'valido' => true,
                        'telefono'=>$restaurante->telefono,
                        'productos' => $productos,
                        'categorias' => $categorias,
                    ];

                }else{
                    $respuesta = [
                        'valido' => false,
                        'resultado' => 'No existe'
                    ];
                }
            }else{
                $respuesta = [
                    'valido' => false,
                    'resultado' => 'Restaurante no identificado existe'
                ];
            }
        
            echo json_encode($respuesta);
            return;
            
        }
    }
    // Funcion para extraer inventario
    public static function obtener(){

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {

            $respuesta = [];
            
            $id =  $_GET['id'];
            $token = $_GET['token'];
            $isUsuario = intval($id);

            if (isset($isUsuario) && is_int($isUsuario) && isset($token) && $token != null) {

                $usuarioId = Usuario::find($isUsuario);

                if (isset($usuarioId) && $usuarioId->token == $token) {
                   
                    $existeRestaurante = Restaurante::where('id_usuario',$usuarioId->id);
                    
                    if($existeRestaurante != null){

                        $consultaProductos = "SELECT id,nombre,precio,imagen,id_categoria,disponible,descripcion FROM producto WHERE id_usuario = ".$usuarioId->id." ;";
                        $consultaCategorias = "SELECT *FROM categoria WHERE id_restaurante = ".$existeRestaurante->id.";";
    
                        $productos = Producto::SQL($consultaProductos);
                        $categorias = Categoria::SQL($consultaCategorias);

                        $respuesta = [
                            'valido' => true,
                            'productos' => $productos,
                            'categorias' => $categorias,
                        ];
                    } else{
                        $respuesta = [
                            'valido' => false,
                            'resultado' => 'Restaurante no identificado existe'
                        ];
                    }
                }else{
                    $respuesta = [
                        'valido' => false,
                        'resultado'=>'El usuario no existe o no tiene permisos'
                    ];
                }
            }else{
                $respuesta = [
                    'valido' => false,
                    'resultado'=>'Falla producido, comunicar a soporte'
                ];
            }
            echo json_encode($respuesta);
            return;
        }
    }

    public static function updateProduct(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            $respuesta = [];
            
            if (count($_POST) == 7 && isset($_POST['token'])) {
                $existeUsuario = Usuario::find($_POST['id_usuario']);
                $existeProducto = Producto::find($_POST['id']);
                $queryExisteCategoria = "SELECT *FROM categoria WHERE nombre = '".$_POST['id_categoria']."'  AND  id_restaurante = ".$_POST['restaurante_id'].";";
                $existeCategoria = array_shift(Categoria::SQL($queryExisteCategoria));

                if (isset($existeCategoria) && isset($existeProducto) && isset($existeUsuario) && $existeUsuario->token == $_POST['token']) {

                    $productoActualizado = new Producto($_POST);
                    $productoActualizado->imagen = $existeProducto->imagen;
                    $productoActualizado->id_categoria = $existeCategoria->id;

                    $productoActualizado->guardar();

                    // Importante
                    unset($productoActualizado->id_usuario);
                    $productoActualizado->id_categoria = $existeCategoria->nombre;

                    $respuesta = [
                        'valido'=> true,
                        'resultado'=>$productoActualizado
                    ];

                }else{
                    $respuesta = [
                        'valido'=> false,
                        'resultado'=>'La categoria, producto o usuario no existen'
                    ];
                }
            }else{
                $respuesta = [
                    'valido'=> false,
                    'resultado'=>'Datos no validos'
                ];
            }
            echo json_encode($respuesta);
            return;
        
        }    
    }

    public static function deleteProduct(){
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $respuesta = [];
            
            if(count($_GET) == 3){
                $existeUsuario = Usuario::find($_GET['id_usuario']);
                
                if (isset($existeUsuario) && $existeUsuario != null && $existeUsuario->token == $_GET['token'] ) {
                    $existeProducto = Producto::find($_GET['id_producto']);

                    if (isset($existeProducto) || $existeProducto != null ) {

                        // Eliminar producto de la bd
                        $existeProducto->eliminar();
                        //Eliminar archivo de imagen del servidor
                        unlink('../public/imagenes/'.$existeProducto->imagen);
                        $respuesta = [
                            'valido'=> true,
                        ];
                    } else {
                        // No existe el producto, no se puede eliminar
                        $respuesta = [
                            'valido'=> false,
                            'resultado'=>'No existe el producto, no se puede eliminar'
                        ];
                    }
                    
                }else{
                    // No existe usuario
                    $respuesta = [
                        'valido'=> false,
                        'resultado'=>'No existe usuario'
                    ];
                }

            }else{
                // No cuenta con informacion necesaria
                $respuesta = [
                    'valido'=> false,
                    'resultado'=>'No cuenta con informacion necesaria'
                ];
            }
            echo json_encode($respuesta);
            return;
        }
    }
}