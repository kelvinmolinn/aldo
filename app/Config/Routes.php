<?php
/*
namespace Config;

$routes = Services::routes();

$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('login');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();

$routes->setAutoRoute(true);

$routes->get('/', 'login::index');
$routes->get('escritorio', 'Panel::index');
*/
//$routes->get('/productos', 'Productos::index');
//$routes->get('/productos/(:num)','Productos::show/$1');
//$routes->get('/productos/(:alpha)/(:num)','Productos::cat/$1/$2');

//$routes->view('productosList/(:alpha)','lista_productos');

//$routes->group('admin', static function($routes){
//$routes->get('/productos','Admin\Productos::index');
//});
namespace Config;

$routes = Services::routes();

// Ruta por defecto
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Login'); // Cambiado a mayúscula 'L'
$routes->setDefaultMethod('index');

// Rutas para Login
$routes->get('/', 'Login::index');
$routes->get('login', 'Login::index');
$routes->post('login/validarIngreso', 'Login::validarIngreso');
$routes->get('cerrarSession', 'Login::cerrarSession'); // 'cerrarSesion' corregido a 'cerrarSession'

// Rutas para Panel
$routes->get('escritorio/dashboard', 'Panel::index');

// Rutas para Conf general
$routes->get('conf-general/administracion-usuarios', 'configuracionGeneral\AdministracionUsuarios::index');

// Rutas para modals
$routes->get('administracion-usuarios/nuevo-usuario', 'configuracionGeneral\AdministracionUsuarios::modalAdministracionUsuarios');

//insert Nuevo usuario
$routes->post('nuevo-usuario/guardar-usuario', 'configuracionGeneral\AdministracionUsuarios::insertarNuevoUsuario');

// Rutas para configuracion de modulos
$routes->get('conf-general/administracion-modulos', 'configuracionGeneral\AdministracionPermisos::configuracionModulos');

// Ruta para usuarios sucursales
$routes->get('conf-general/usuario-sucursal/(:any)', 'configuracionGeneral\AdministracionUsuarios::usuarioSucursal/$1');

// Rutas para configuracion de menus
$routes->get('conf-general/administracion-menus', 'configuracionGeneral\AdministracionPermisos::administracionMenus');

// Rutas para configuracion de permisos
$routes->get('conf-general/administracion-permisos', 'configuracionGeneral\AdministracionPermisos::administracionPermisos');

// Rutas para configuracion de permisos
$routes->get('conf-general/page-menus-modulos/(:any)', 'configuracionGeneral\AdministracionPermisos::menusModulos/$1');

// Ruta para modals de nuevo menu
$routes->post('administracion-modulos/nuevo-menu', 'configuracionGeneral\AdministracionPermisos::modalnuevoMenu');
$routes->post('administracion-modulos/guardar-menu', 'configuracionGeneral\AdministracionPermisos::insertModuloMenu');


// ruta para modals de Usuario sucursales
$routes->post('usuarios-sucursales/agregar-UsuarioSucursal', 'configuracionGeneral\AdministracionUsuarios::modalUsuariosSucursales');

//insert usuario sucursal 
$routes->post('usuarios-sucursales/guardar-usuario-sucursal', 'configuracionGeneral\AdministracionUsuarios::insertUsuariosSucursal');

//ELIMINAR sucursal del usuario
$routes->post('usuarios-sucursales/eliminar-usuario-sucursal', 'configuracionGeneral\AdministracionUsuarios::eliminarUsuarioSucursal');

// Ruta para editar modulos: accion/operacion
//$routes->post('conf-general/operacion/editar-modulo', 'configuracionGeneral\AdministracionPermisos::editarModulo');

// ruta para modals de editar modulos: vista
//$routes->post('conf-general/editar-modulo', 'configuracionGeneral\AdministracionPermisos::modalEditarModulo');

//ELIMINAR MODULO
$routes->post('administracion-modulos/eliminar-modulo', 'configuracionGeneral\AdministracionPermisos::eliminarModulo');

//insertar nuevo menu
$routes->post('administracion-modulos/nuevo-menu', 'configuracionGeneral\AdministracionPermisos::insertarNuevoMenu');

//desactivar o activar usuario
$routes->post('administracion-modulos/activar-desactivar-usuario', 'configuracionGeneral\AdministracionUsuarios::ActivarDesactivar');

// Ruta general para abrir modal para insertar o editar, se llama con ajax que lleva el parametro operacion que indica que va a realizar
$routes->post('conf-general/administracion-modulos/modulo', 'configuracionGeneral\AdministracionPermisos::modalModulo');

// ruta general para realizar la operacion de la modal, en este caso, podemos llamarla asi
$routes->post('conf-general-administracion-modulos/modulo/operacion', 'configuracionGeneral\AdministracionPermisos::modalModuloOperacion');


// Rutas de errores
$routes->get('404', 'Errores::error404');

return $routes;