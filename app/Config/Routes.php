<?php

use App\Controllers\HomePage;
use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'LandingPage::login');
$routes->get('/register', 'LandingPage::register');

$routes->get('/home', 'HomePage::home');
$routes->get('/topup', 'HomePage::topup');
$routes->get('/transaksi/(:any)', [[HomePage::class, "transaksiID"], "$1"]);
$routes->get('/transaksi', 'HomePage::history');

$routes->get('/akun', 'AkunPage::akun');
