<?php 

require_once __DIR__ . '/../includes/app.php';

use Controllers\FacturaController;
use Controllers\ProductoController;
use Controllers\RestauranteController;
use Controllers\AutenticacionController;
use Controllers\CategoriaController;
use Controllers\UsuarioController;
use Controllers\VentaController;
use MVC\Router;
$router = new Router();

// ** Admin negocio
// Autenticacion
$router->post('/login',[AutenticacionController::class,'login']);
$router->get('/autenticacion-token',[AutenticacionController::class,'loginWithToken']);

// Ruta obtener informacion de productos y categorias (con token)
$router->get('/inventario',[ProductoController::class,'obtener']);
// Ruta publica de inventario
$router->get('/inventario-business',[ProductoController::class,'obtenerProductos']);

// Crear categoria (con token)
$router->post('/crear-categoria',[CategoriaController::class,'createCategory']);

// Crear Producto (con token)
$router->post('/crear-producto',[ProductoController::class,'createProduct']);

// Actualizar Producto (con token)
$router->post('/actualizar-producto',[ProductoController::class,'updateProduct']);

// Eliminar producto (con token)
$router->get('/eliminar-producto',[ProductoController::class,'deleteProduct']);

// Ruta obtener informacion de cliente y forma de pagos (con token)
$router->get('/formulario-factura',[FacturaController::class,'getInfoClientesAndFormaPago']);

// Ruta guardar venta con nuevo cliente (con token)
$router->post('/orden-venta',[VentaController::class,'crearVentaCliente']);

// Ruta Guardar venta con cliente existente o no existente (con token)
$router->post('/orden-venta-condicional',[VentaController::class,'crearVentaCondicional']);

// Ruta Guardar venta con cliente existente o no existente, publico
$router->post('/orden-venta-condicional-publico',[VentaController::class,'crearVentaCondicionalPublico']);

// Ruta obtener ventas (con token)
$router->get('/informacion-ventas',[VentaController::class,'getVentas']);

// Ruta para obtener ventas de productos individuales (con token)
$router->get('/informacion-ventas-producto',[VentaController::class,'getVentasProductosIndividuales']);

// Ruta para obtener ventas por categorias (con token)
$router->get('/informacion-ventas-categoria',[VentaController::class,'getVentasByCategory']);

// Ruta para cambiar estado de la venta
$router->post('/cambiar-estado-venta',[VentaController::class,'changeStateSale']);

// Ruta para eliminar una venta
$router->post('/eliminar-venta',[VentaController::class,'deleteSale']);

// Ruta para cambiar estado de disponible producto
$router->post('/cambiar-disponible',[ProductoController::class,'toogleAvailableProducto']);

// ** Owner
// Ruta para tener info de restaurantes
$router->get('/informacion-restaurantes',[RestauranteController::class,'getRestaurantes']);

// Ruta para tener info de usuarios
$router->get('/informacion-usuarios',[UsuarioController::class,'getUsuarios']);

// Ruta para crear restaurante
$router->post('/crear-restaurante',[RestauranteController::class, 'createRestaurant']);

//Ruta para crear usuario
$router->post('/crear-usuario',[UsuarioController::class,'createUser']);

// Comprueba y valida las rutas, que existan y les asigna las funciones del Controlador
$router->comprobarRutas();