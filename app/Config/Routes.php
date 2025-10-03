<?php

use CodeIgniter\Router\RouteCollection;

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
    
    $routes->resource('bahanbaku', ['except' => 'show']);
    $routes->get('bahanbaku/edit/(:num)', 'BahanBaku::edit/$1');
    
    // Nanti jika ada fitur lain untuk gudang, tambahkan di sini.
});

// Group routes for Dapur (Client) with 'auth:dapur' filter
$routes->group('dapur', ['filter' => 'auth:dapur', 'namespace' => 'App\Controllers\Dapur'], static function ($routes) {
    // Nanti jika ada fitur untuk dapur, tambahkan di sini.
});