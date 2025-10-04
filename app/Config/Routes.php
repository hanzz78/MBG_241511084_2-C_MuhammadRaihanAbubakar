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
$routes->get('dashboard', 'Dashboard::index', ['filter' => 'auth']); 

// Group routes for Gudang (Admin) with 'auth:gudang' filter
$routes->group('gudang', ['filter' => 'auth:gudang', 'namespace' => 'App\Controllers\Gudang'], static function ($routes) {
    
    $routes->resource('bahanbaku', ['except' => 'show']);
    $routes->get('bahanbaku/edit/(:num)', 'BahanBaku::edit/$1');

    $routes->get('permintaan', 'Permintaan::index');
    $routes->get('permintaan/(:num)', 'Permintaan::show/$1'); 
    $routes->post('permintaan/update/(:num)', 'Permintaan::updateStatus/$1');

    
    
});

// Group routes for Dapur (Client) with 'auth:dapur' filter
$routes->group('dapur', ['filter' => 'auth:dapur', 'namespace' => 'App\Controllers\Dapur'], static function ($routes) {
      $routes->get('permintaan', 'Permintaan::index');          
    $routes->get('permintaan/new', 'Permintaan::new');        
    $routes->post('permintaan/create', 'Permintaan::create');
});