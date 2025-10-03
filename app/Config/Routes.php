<?php

use CodeIgniter\Router\RouteCollection;

/**
// ... (lines 1-32)
/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// Auth routes
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::attemptLogin');
$routes->get('logout', 'Auth::logout');
$routes->get('dashboard', 'Dashboard::index', ['filter' => 'auth']); // Protected route

// Group routes for Gudang (Admin) with 'auth:gudang' filter
$routes->group('gudang', ['filter' => 'auth:gudang', 'namespace' => 'App\Controllers\Gudang'], static function ($routes) {
    // Routes for Gudang features will be added here
});

// Group routes for Dapur (Client) with 'auth:dapur' filter
$routes->group('dapur', ['filter' => 'auth:dapur', 'namespace' => 'App\Controllers\Dapur'], static function ($routes) {
    // Routes for Dapur features will be added here
});