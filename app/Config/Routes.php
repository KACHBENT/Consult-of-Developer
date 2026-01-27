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


// Registro/Login normales
$routes->get('acceso/register-person', 'Home::registerPerson');
$routes->post('acceso/register-person', 'AccesoController::registerPerson');
$routes->get('acceso/login', 'AccesoController::loginShowForm');
$routes->post('acceso/login', 'AccesoController::login');
$routes->get('acceso/logout', 'AccesoController::logout');
