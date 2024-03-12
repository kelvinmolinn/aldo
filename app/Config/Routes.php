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

// Ruta para modals de nuevo modulo
$routes->get('administracion-modulos/nuevo-modulo', 'configuracionGeneral\AdministracionPermisos::modalnuevoModulo');

// Ruta para modals de nuevo modulo
$routes->get('administracion-modulos/editar-modulo', 'configuracionGeneral\AdministracionPermisos::modalEditarModulo');

$routes->get('administracion-permisos/obtener-modulo', 'configuracionGeneral\AdministracionPermisos::obtenerModulo');


//modl modulo
$routes->post('xd/nuevo-xd', 'configuracionGeneral\AdministracionPermisos::insertarNuevoModulo');

// Ruta para usuarios sucursales
$routes->get('conf-general/usuario-sucursal', 'configuracionGeneral\AdministracionUsuarios::usuarioSucursal');

// Rutas para configuracion de menus
$routes->get('conf-general/administracion-menus', 'configuracionGeneral\AdministracionPermisos::administracionMenus');

// Rutas para configuracion de permisos
$routes->get('conf-general/administracion-permisos', 'configuracionGeneral\AdministracionPermisos::administracionPermisos');

// Rutas para configuracion de permisos
$routes->get('conf-general/page-menus-modulos', 'configuracionGeneral\AdministracionPermisos::menusModulos');

// Ruta para modals de nuevo menu
$routes->get('administracion-modulos/nuevo-menu', 'configuracionGeneral\AdministracionPermisos::modalnuevoMenu');

// ruta para modals de Usuario sucursales
$routes->get('usuarios-sucursales/agregar-UsuarioSucursal', 'configuracionGeneral\AdministracionUsuarios::modalUsuariosSucursales');


// Rutas de errores
$routes->get('404', 'Errores::error404');

return $routes;