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
$routes->get('permintaan/list', 'Permintaan::list'); // List untuk Dapur

// Tambahkan 2 baris ini ke dalam rute BahanBaku Anda
$routes->get('bahanbaku/edit/(:num)', 'BahanBaku::edit/$1');
$routes->put('bahanbaku/update/(:num)', 'BahanBaku::update/$1');
$routes->delete('bahanbaku/delete/(:num)', 'BahanBaku::delete/$1');

$routes->get('permintaan/gudang', 'Permintaan::approvalList'); // List untuk Gudang
$routes->get('permintaan/detail/(:num)', 'Permintaan::detail/$1'); // Detail permintaan
$routes->post('permintaan/approve/(:num)', 'Permintaan::approve/$1'); // Aksi ACC
$routes->post('permintaan/reject/(:num)', 'Permintaan::reject/$1'); // Aksi Tolak

// Route Persetujuan Gudang
$routes->get('permintaan/gudang', 'Permintaan::approvalList', ['filter' => 'auth']);
$routes->get('permintaan/detail/(:num)', 'Permintaan::detail/$1', ['filter' => 'auth']);
$routes->post('permintaan/approve/(:num)', 'Permintaan::approve/$1', ['filter' => 'auth']);
$routes->post('permintaan/reject/(:num)', 'Permintaan::reject/$1', ['filter' => 'auth']);
