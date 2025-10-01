<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
 
//log in log out
$routes->get('login', 'Auth::login');
$routes->post('login/process', 'Auth::loginProcess');
$routes->get('logout', 'Auth::logout');

$routes->get('dashboard/gudang', 'Dashboard::gudang');

