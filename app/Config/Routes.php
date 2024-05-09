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
$routes->post('escritorio/dashboard', 'Panel::escritorio');

// Rutas para no usar parametros por URL
$routes->get('app', 'Panel::index');

$routes->group('conf-general/admin-usuarios', function($routes) {
    // Definir las rutas específicas para el grupo 'admin'
    $routes->post('index', 'configuracionGeneral\AdministracionUsuarios::index');
    $routes->post('form/empleados/usuarios', 'configuracionGeneral\AdministracionUsuarios::modalAdministracionUsuarios');
    $routes->post('operacion/usuarios', 'configuracionGeneral\AdministracionUsuarios::insertarNuevoUsuario');
    //$routes->get('vista/usuario/sucursal/(:any)', 'configuracionGeneral\AdministracionUsuarios::usuarioSucursal/$1'); 
    $routes->post('vista/usuario/sucursal', 'configuracionGeneral\AdministracionUsuarios::usuarioSucursal'); 
    $routes->post('form/Usuario/sucursal', 'configuracionGeneral\AdministracionUsuarios::modalUsuariosSucursales');
    $routes->post('operacion/agregar/usuario/sucursal', 'configuracionGeneral\AdministracionUsuarios::insertUsuariosSucursal');
    $routes->post('operacion/eliminar/usuario/sucursal', 'configuracionGeneral\AdministracionUsuarios::eliminarUsuarioSucursal');
    $routes->post('operacion/estado/usuario', 'configuracionGeneral\AdministracionUsuarios::ActivarDesactivar');
    //Llamada en HTML: conf-general/admin-usuarios/vista,sucursales,modal/usuario,tabla/usuarios
});

$routes->group('conf-general/admin-roles', function($routes) {
    $routes->post('index', 'configuracionGeneral\AdministracionRoles::index');
    $routes->post('tabla/roles',  'configuracionGeneral\AdministracionRoles::tablaRoles');
    $routes->post('form/nuevo/rol', 'configuracionGeneral\AdministracionRoles::modalRol');
    $routes->post('operacion/guardar/rol', 'configuracionGeneral\AdministracionRoles::modalRolOperacion');
    $routes->post('operacion/eliminar/rol', 'configuracionGeneral\AdministracionRoles::eliminarRol');
    $routes->post('vista/permisos/rol', 'configuracionGeneral\AdministracionRoles::menusModulos');
    $routes->post('tabla/permisos/rol',  'configuracionGeneral\AdministracionRoles::tablaPermisosRol');
    $routes->post('obtener/permisos/select',  'configuracionGeneral\AdministracionRoles::obtenerPermisos');
    $routes->post('form/nuevo/permiso/rol', 'configuracionGeneral\AdministracionRoles::modalNuevoPermiso');
    $routes->post('operacion/insert/permisos/menus', 'configuracionGeneral\AdministracionRoles::permisosMenusOperacion');
    $routes->post('form/permisos/rol/menu', 'configuracionGeneral\AdministracionRoles::modalPermisosRolMenu');

});

$routes->group('conf-general/admin-modulos', function($routes) {
    // Definir las rutas específicas para el grupo 'admin'
    $routes->post('index', 'configuracionGeneral\AdministracionPermisos::configuracionModulos');
    $routes->post('operacion/eliminar/modulo', 'configuracionGeneral\AdministracionPermisos::eliminarModulo');
    $routes->post('form/nuevo/modulo', 'configuracionGeneral\AdministracionPermisos::modalModulo');
    $routes->post('operacion/guardar/modulo', 'configuracionGeneral\AdministracionPermisos::modalModuloOperacion');
    $routes->post('vista/modulos/menus', 'configuracionGeneral\AdministracionPermisos::menusModulos');
    $routes->post('form/modulo/nuevo/menu', 'configuracionGeneral\AdministracionPermisos::modalMenu');
    $routes->post('operacion/guardar/menu', 'configuracionGeneral\AdministracionPermisos::modalMenuOperacion');
    $routes->post('operacion/eliminar/menu', 'configuracionGeneral\AdministracionPermisos::eliminarMenu');
    $routes->post('tabla/modulos',  'configuracionGeneral\AdministracionPermisos::tablaModulos');
    $routes->post('tabla/modulos/menus',  'configuracionGeneral\AdministracionPermisos::tablaModulosMenus');
});

$routes->group('conf-general/admin-permisos', function($routes) {
    // Definir las rutas específicas para el grupo 'admin'
    $routes->post('index', 'configuracionGeneral\ConfiguracionPermisos::indexPermisos');
    $routes->post('tabla/permisos',  'configuracionGeneral\ConfiguracionPermisos::tablaPermisos');
    $routes->post('form/nuevo/permiso', 'configuracionGeneral\ConfiguracionPermisos::modalPermiso');
    $routes->post('operacion/guardar/permisos', 'configuracionGeneral\ConfiguracionPermisos::modalPermisosOperacion');
    $routes->post('operacion/eliminar/permiso', 'configuracionGeneral\ConfiguracionPermisos::eliminarPermiso');
    $routes->post('tabla/usuarios/permiso', 'configuracionGeneral\ConfiguracionPermisos::modalUsuariosPermisos');
    $routes->post('tabla/modulos/permiso/usuarios',  'configuracionGeneral\ConfiguracionPermisos::tablaPermisosUsuarios');
});
$routes->group('inventario/admin-unidades', function($routes) {
    // Definir las rutas específicas para el grupo 'admin-unidades'
    $routes->post('index', 'inventario\AdministracionUnidades::index');
    $routes->post('form/unidades', 'inventario\AdministracionUnidades::modalAdministracionUnidades');
    $routes->post('tabla/unidades',  'inventario\AdministracionUnidades::tablaUnidades');
    $routes->post('operacion/eliminar/unidades', 'inventario\AdministracionUnidades::eliminarUnidades');
    $routes->post('operacion/guardar/unidades', 'inventario\AdministracionUnidades::modalUnidadesOperacion');
    //
});

$routes->group('inventario/admin-tipo', function($routes) {
    // Definir las rutas específicas para el grupo 'admin-tipo'
    $routes->post('index', 'inventario\AdministracionTipo::index');
    $routes->post('form/tipo', 'inventario\AdministracionTipo::modalAdministracionTipo');
    $routes->post('tabla/tipo',  'inventario\AdministracionTipo::tablaTipo');
    $routes->post('operacion/eliminar/tipo', 'inventario\AdministracionTipo::eliminarTipo');
    $routes->post('operacion/guardar/tipo', 'inventario\AdministracionTipo::modalTipoOperacion');
    //
});

$routes->group('inventario/admin-plataforma', function($routes) {
    // Definir las rutas específicas para el grupo 'admin-plataforma'
    $routes->post('index', 'inventario\AdministracionPlataforma::index');
    $routes->post('form/plataforma', 'inventario\AdministracionPlataforma::modalAdministracionPlataforma');
    $routes->post('tabla/plataforma',  'inventario\AdministracionPlataforma::tablaPlataforma');
    $routes->post('operacion/eliminar/plataforma', 'inventario\AdministracionPlataforma::eliminarPlataforma');
    $routes->post('operacion/guardar/plataforma', 'inventario\AdministracionPlataforma::modalPlataformaOperacion');
    //
});

$routes->group('inventario/admin-producto', function($routes) {
    // Definir las rutas específicas para el grupo 'admin-producto'
    $routes->post('index', 'inventario\AdministracionProducto::index');
    $routes->post('form/producto', 'inventario\AdministracionProducto::modalAdministracionProducto');
    $routes->post('form2/precio', 'inventario\AdministracionProducto::modalAdministracionPrecio');
    $routes->post('form3/existencia', 'inventario\AdministracionProducto::modalAdministracionExistencia');
    $routes->post('form4/existenciaProducto', 'inventario\AdministracionProducto::modalAdministracionExistenciaProducto');
    $routes->post('tabla/producto',  'inventario\AdministracionProducto::tablaProducto');
    $routes->post('tabla/existenciaProducto',  'inventario\AdministracionProducto::tablaExistenciaProducto');
    $routes->post('tabla/precio',  'inventario\AdministracionProducto::tablaPrecio');
    $routes->post('operacion/eliminar/producto', 'inventario\AdministracionProducto::eliminarProducto');
    $routes->post('operacion/guardar/producto', 'inventario\AdministracionProducto::modalProductoOperacion');
    $routes->post('operacion/guardar/existencia', 'inventario\AdministracionProducto::modalExistenciaOperacion');
    $routes->post('operacion/estado/usuario', 'inventario\AdministracionProducto::ActivarDesactivar');
    //
});

$routes->group('compras/admin-proveedores', function($routes) {
    $routes->post('index', 'compras\administracionProveedores::index');
    $routes->post('tabla/proveedores',  'compras\administracionProveedores::tablaProveedores');
    $routes->post('form/nuevo/proveedor', 'compras\administracionProveedores::modalProveedores');
});

// Rutas de errores
$routes->get('404', 'Errores::error404');

return $routes;