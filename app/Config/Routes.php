<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// Rute untuk Auth (Login/Logout)
$routes->get('login', 'Auth::login');
$routes->post('login/process', 'Auth::loginProcess');
$routes->get('logout', 'Auth::logout');

// --- Rute Dashboard ---
$routes->get('dashboard/gudang', 'Dashboard::gudang');
$routes->get('dashboard/dapur', 'Dashboard::dapur');

// --- Rute Fitur Bahan Baku (Petugas Gudang) ---
$routes->get('bahanbaku', 'BahanBaku::index'); 
$routes->get('bahanbaku/tambah', 'BahanBaku::tambah');
$routes->post('bahanbaku/simpan', 'BahanBaku::simpan');
