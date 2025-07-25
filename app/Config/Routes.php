<?php

namespace Config;

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Home::index', ['filter' => 'auth']);
$routes->get('/faq', 'Home::faq');

$routes->get('login', 'AuthController::login');
$routes->post('login', 'AuthController::login');
$routes->get('logout', 'AuthController::logout');

$routes->group('produk', ['filter' => 'auth'], function ($routes) {
    $routes->get('', 'ProdukController::index');
    $routes->post('', 'ProdukController::create');
    $routes->post('edit/(:any)', 'ProdukController::edit/$1');
    $routes->get('delete/(:any)', 'ProdukController::delete/$1');
    $routes->get('download', 'ProdukController::download');
});

$routes->group('keranjang', ['filter' => 'auth'], function ($routes) {
    $routes->get('', 'TransaksiController::index');
    $routes->post('', 'TransaksiController::cart_add');
    $routes->post('edit', 'TransaksiController::cart_edit');
    $routes->get('delete/(:any)', 'TransaksiController::cart_delete/$1');
    $routes->get('clear', 'TransaksiController::cart_clear');
});

$routes->get('checkout', 'TransaksiController::checkout', ['filter' => 'auth']);
$routes->post('buy', 'TransaksiController::buy', ['filter' => 'auth']);

$routes->group('kategori', ['filter' => 'auth'], function ($routes) {
    $routes->get('', 'KategoriController::index');
    $routes->post('', 'KategoriController::store');
    $routes->post('edit/(:num)', 'KategoriController::update/$1');
    $routes->get('delete/(:num)', 'KategoriController::delete/$1');
});

$routes->get('get-location', 'TransaksiController::getLocation', ['filter' => 'auth']);
$routes->get('get-cost', 'TransaksiController::getCost', ['filter' => 'auth']);
$routes->get('profile', 'Home::profile', ['filter' => 'auth']);


$routes->get('keranjang', 'TransaksiController::index', ['filter' => 'auth']);
$routes->resource('api', ['controller' => 'apiController']);

// Rute untuk Manajemen Diskon (hanya admin)
$routes->group('diskon', ['filter' => 'auth'], static function ($routes) {
    $routes->get('/', 'DiskonController::index');
    $routes->post('store', 'DiskonController::store'); // POST untuk menyimpan data dari modal Add
    $routes->post('update/(:num)', 'DiskonController::update/$1'); // Menggunakan POST, controller akan cek _method=PUT
    $routes->put('update/(:num)', 'DiskonController::update/$1'); // PERBAIKAN: Menambahkan rute PUT eksplisit
    $routes->post('delete/(:num)', 'DiskonController::delete/$1'); // Menggunakan POST, controller akan cek _method=DELETE
    $routes->delete('delete/(:num)', 'DiskonController::delete/$1'); // Menambahkan rute DELETE eksplisit
});
