<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'LandingPage::login');
$routes->get('/register', 'LandingPage::register');

$routes->get('/home', 'HomePage::home');
$routes->get('/topup', 'HomePage::topup');
$routes->get('/transaksi', 'HomePage::transaksi');
$routes->get('/transaksi/history', 'HomePage::history');

$routes->get('/akun', 'AkunPage::akun');
