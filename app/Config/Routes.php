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

// Registro/Login normales
$routes->get('acceso/register-person', 'AccesoController::registerShowForm');
$routes->post('acceso/register', 'AccesoController::register');
$routes->get('acceso/login', 'AccesoController::loginShowForm');
$routes->post('acceso/login', 'AccesoController::login');
$routes->get('acceso/logout', 'AccesoController::logout');
