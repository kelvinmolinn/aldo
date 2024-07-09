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
$routes->post('escritorio/operacion/cambiarClave', 'Panel::cambiarClave');

// Rutas para no usar parametros por URL
$routes->get('app', 'Panel::index');

$routes->group('conf-general/admin-usuarios', function($routes) {
    // Definir las rutas específicas para el grupo 'admin'
    $routes->post('index', 'configuracionGeneral\AdministracionUsuarios::index');
    $routes->post('tabla/usuarios',  'configuracionGeneral\AdministracionUsuarios::tablaUsuarios');
    $routes->post('tabla/usuarios-sucursales',  'configuracionGeneral\AdministracionUsuarios::tablaUsuariosSucursales');
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
    $routes->post('tabla/permisos-rol-menu', 'configuracionGeneral\AdministracionRoles::tablaPermisosRolMenu');
    $routes->post('operacion/eliminar/permisos-rol-menu', 'configuracionGeneral\AdministracionRoles::eliminarPermisoRolMenu');
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
    $routes->post('form5/AlertaExistencia', 'inventario\AdministracionProducto::modalAdministracionAlertaExistencia');
    $routes->post('tabla/producto',  'inventario\AdministracionProducto::tablaProducto');
    $routes->post('tabla/existenciaProducto',  'inventario\AdministracionProducto::tablaExistenciaProducto');
    $routes->post('tabla/precio',  'inventario\AdministracionProducto::tablaPrecio');
    $routes->post('tabla/AlertaExistenciaMinima',  'inventario\AdministracionProducto::tablaExistenciaMinima');
    $routes->post('operacion/eliminar/producto', 'inventario\AdministracionProducto::eliminarProducto');
    $routes->post('operacion/guardar/producto', 'inventario\AdministracionProducto::modalProductoOperacion');
    $routes->post('operacion/guardar/existencia', 'inventario\AdministracionProducto::modalExistenciaOperacion');
    $routes->post('operacion/guardar/precio', 'inventario\AdministracionProducto::modalPrecioOperacion');
    $routes->post('operacion/estado/usuario', 'inventario\AdministracionProducto::ActivarDesactivar');
    //
});

$routes->group('inventario/admin-salida', function($routes) {
    // Definir las rutas específicas para el grupo 'admin-salidas'
    $routes->post('index', 'inventario\AdministracionSalida::index');
    $routes->post('form/salida', 'inventario\AdministracionSalida::modalAdministracionSalida');
    $routes->post('tabla/salida',  'inventario\AdministracionSalida::tablaSalida');
    $routes->post('operacion/guardar/salida', 'inventario\AdministracionSalida::modalSalidaOperacion');
    $routes->post('vista/actualizar/descargo', 'inventario\AdministracionSalida::vistaContinuarDescargo');
    $routes->post('tabla/ContinuarSalida',  'inventario\AdministracionSalida::tablaContinuarSalida');
    $routes->post('form2/Nuevasalida', 'inventario\AdministracionSalida::modalAdministracionNuevaSalida');
    $routes->post('operacion/guardar/NuevaSalida', 'inventario\AdministracionSalida::modalNuevaSalidaOperacion');
    $routes->post('operacion/eliminar/salida', 'inventario\AdministracionSalida::eliminarSalida');
     $routes->post('operacion/finalizar/finalizar', 'inventario\AdministracionSalida::finalizarSalida');
     $routes->post('tabla/verDescargo',  'inventario\AdministracionSalida::tablaVerDescargo');
     $routes->post('form4/verDescargo', 'inventario\AdministracionSalida::modalAdministracionVerDescargo');
    //
});

$routes->group('inventario/admin-traslados', function($routes) {
    // Definir las rutas específicas para el grupo 'admin-salidas'
    $routes->post('index', 'inventario\AdministracionTraslados::index');
    $routes->post('form/traslados', 'inventario\AdministracionTraslados::modalAdministracionTraslados');
    $routes->post('tabla/traslados',  'inventario\AdministracionTraslados::tablaTraslados');
    $routes->post('operacion/guardar/traslados', 'inventario\AdministracionTraslados::modalTrasladoOperacion');
    $routes->post('vista/actualizar/traslados', 'inventario\AdministracionTraslados::vistaContinuarTraslados');
    $routes->post('form2/Nuevasalida', 'inventario\AdministracionTraslados::modalAdministracionNuevoTraslado');
    $routes->post('operacion/guardar/NuevoTraslado', 'inventario\AdministracionTraslados::modalNuevoTrasladoOperacion');

    //
});

$routes->group('compras/admin-proveedores', function($routes) {
    $routes->post('index', 'compras\administracionProveedores::index');
    $routes->post('tabla/proveedores',  'compras\administracionProveedores::tablaProveedores');
    $routes->post('form/nuevo/proveedor', 'compras\administracionProveedores::modalProveedores');
    $routes->post('operacion/guardar/proveedores', 'compras\administracionProveedores::modalProveedorOperacion');
    $routes->post('form/nuevo/contacto/proveedor', 'compras\administracionProveedores::modalContactoProveedores');
    $routes->post('tabla/contacto/proveedor',  'compras\administracionProveedores::tablaContactoProveedores');
    $routes->post('obtener/contacto/proveedor', 'compras\administracionProveedores::obtenerContactoProveedor');
    $routes->post('operacion/guardar/contacto', 'compras\administracionProveedores::agregarContacto');
    $routes->post('eliminar/contacto/proveedor', 'compras\administracionProveedores::eliminarContacto');
});

$routes->group('compras/admin-compras', function($routes) {
    $routes->post('index', 'compras\administracionCompras::index');
    $routes->post('tabla/compras', 'compras\administracionCompras::tablaCompras');
    $routes->post('form/nueva/compra', 'compras\administracionCompras::modalNuevaCompra');
    $routes->post('operacion/guardar/compra', 'compras\administracionCompras::modalCompraOperacion');
    $routes->post('vista/actualizar/compra', 'compras\administracionCompras::vistaContinuarCompra');
    $routes->post('operacion/actualizar/compra', 'compras\administracionCompras::vistaActualizarCompraOperacion');
    $routes->post('tabla/continuar/compra', 'compras\administracionCompras::tablaContinuarCompras');
    $routes->post('form/producto/compra', 'compras\administracionCompras::modalAgregarProducto');
    $routes->post('operacion/guardar/productos', 'compras\administracionCompras::modalProductosOperacion');
    $routes->post('eliminar/producto/compra', 'compras\administracionCompras::eliminarProductoCompra');
    $routes->post('finalizar/compra', 'compras\administracionCompras::finalizarCompra');
    $routes->post('vista/ver/compra', 'compras\administracionCompras::vistaVerCompra');
    $routes->post('tabla/ver/compra', 'compras\administracionCompras::tablaVerCompra');
    
});

$routes->group('compras/admin-retaceo', function($routes) {
    $routes->post('index', 'compras\administracionRetaceo::index');
    $routes->post('tabla/retaceo', 'compras\administracionRetaceo::tablaRetaceo');
    $routes->post('form/nuevo/retaceo', 'compras\administracionRetaceo::modalNuevoRetaceo');
    $routes->post('vista/continuar/retaceo', 'compras\administracionRetaceo::vistaContinuarRetaceo');
    $routes->post('tabla/continuar/retaceo', 'compras\administracionRetaceo::tablaContinuarRetaceo');
    $routes->post('form/nueva/compra/retaceo', 'compras\administracionRetaceo::modalnuevaCompraRetaceo');
    
});

$routes->group('ventas/admin-clientes', function($routes) {
    $routes->post('index', 'ventas\administracionClientes::index');
    $routes->post('tabla/clientes', 'ventas\administracionClientes::tablaClientes');
    $routes->post('form/nuevo/cliente', 'ventas\administracionClientes::modalNuevoCliente');
    $routes->post('form/nuevo/contacto/cliente', 'ventas\administracionClientes::modalContactoCliente');
    $routes->post('tabla/contacto/cliente', 'ventas\administracionClientes::tablaContactoClientes');
    $routes->post('form/historial/ventas', 'ventas\administracionClientes::modalHistorialVentas');
    $routes->post('tabla/historial/ventas', 'ventas\administracionClientes::tablaHistorialVentas');
    $routes->post('operacion/guardar/clientes', 'ventas\administracionClientes::modalClienteOperacion');
    $routes->post('operacion/guardar/contacto', 'ventas\administracionClientes::agregarContacto');
    $routes->post('obtener/contacto/cliente', 'ventas\administracionClientes::obtenerContactoCliente');
    $routes->post('eliminar/contacto/cliente', 'ventas\administracionClientes::eliminarContacto');
});

$routes->group('ventas/admin-reservas', function($routes) {
    $routes->post('index', 'ventas\administracionReservas::index');
    $routes->post('tabla/reserva', 'ventas\administracionReservas::tablaReservas');
    $routes->post('form/nueva/reserva', 'ventas\administracionReservas::modalNuevaReserva');
    $routes->post('form/anular/reserva', 'ventas\administracionReservas::modalAnularReserva');
    $routes->post('vista/continuar/reserva', 'ventas\administracionReservas::vistaContinuarReserva');
    $routes->post('tabla/continuar/reserva', 'ventas\administracionReservas::tablaContinuarReserva');
    $routes->post('form/pago/reserva', 'ventas\administracionReservas::modalPagoReserva');
    $routes->post('tabla/pago/ventas', 'ventas\administracionReservas::tablePagoReserva');
    $routes->post('modal/nuevo/reserva', 'ventas\administracionReservas::modalNuevoProductoReserva');
    
});

$routes->group('ventas/admin-facturacion', function($routes) {
    $routes->post('index', 'ventas\administracionFacturacion::index');
    $routes->post('tabla/facturacion', 'ventas\administracionFacturacion::tablaFacturacion');
    $routes->post('form/emitir/dte', 'ventas\administracionFacturacion::modalEmitirDTE');
    $routes->post('form/anular/dte', 'ventas\administracionFacturacion::modalAnularDTE');
    $routes->post('vista/continuar/dte', 'ventas\administracionFacturacion::vistaContinuarDTE');
    $routes->post('tabla/continuar/dte', 'ventas\administracionFacturacion::tablaContinuarDTE');
    $routes->post('form/pago/dte', 'ventas\administracionFacturacion::modalPagoDTE');
    $routes->post('tabla/pago/dte', 'ventas\administracionFacturacion::tablaPagoDTE');
    $routes->post('form/complemento/dte', 'ventas\administracionFacturacion::modalComplementoDTE');
    $routes->post('tabla/complemento/dte', 'ventas\administracionFacturacion::tablaComplementoDTE');
    $routes->post('form/error/dte', 'ventas\administracionFacturacion::tablaErrorDTE');
    $routes->post('form/imprimir/dte', 'ventas\administracionFacturacion::imprimirDTE');
});

$routes->group("select", function($routes) {
    $routes->post('catalogos-hacienda/actividad-economica', 'select\selectCatalogosMH::selectActividadEconomica');
    $routes->post('catalogos-hacienda/paises-departamentos', 'select\selectCatalogosMH::selectPaisDepartamento');
    $routes->post('catalogos-hacienda/paises-municipios', 'select\selectCatalogosMH::selectPaisMunicipio');
});
// Rutas de errores
$routes->get('404', 'Errores::error404');

return $routes;