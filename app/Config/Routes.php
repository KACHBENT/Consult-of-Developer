<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

/*

$routes->group('', ['filter' => 'auth'], static function ($routes) {
    $routes->get('/', 'Home::index');
});
*/
$routes->get('/', 'Home::index');
$routes->get('/enrique', 'Home::enrique');
$routes->get('/brandon', 'Home::brandon');
$routes->get('/julio', 'Home::julio');

// Registro/Login normales
$routes->get('acceso/register-person', 'AccesoController::registerShowForm');
$routes->post('acceso/register', 'AccesoController::register');
$routes->get('acceso/login', 'AccesoController::loginShowForm');
$routes->post('acceso/login', 'AccesoController::login');
$routes->get('acceso/logout', 'AccesoController::logout');


$routes->get('contacto', 'ContactoController::index');
$routes->post('contacto/enviar', 'ContactoController::enviar');
$routes->get('contacto/verificar/(:segment)', 'ContactoController::verificar/$1');
$routes->get('contacto/gracias', 'ContactoController::gracias');

$routes->get('promociones', 'PromocionesController::index');