<?php

namespace Config;

$routes = Services::routes();

$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();

//$routes->setAutoRoute(true);
/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Home::index');
$routes->get('/productos', 'Productos::index');
$routes->get('/productos/([0-9]{2})','Productos::show/$1');
$routes->get('/productos/(:alpha)/(:num)','Productos::cat/$1/$2');

$routes->view('productosList/(:alpha)','lista_productos');

$routes->group('admin', static function($routes){
    $routes->get('/productos','Admin\Productos::index');
});