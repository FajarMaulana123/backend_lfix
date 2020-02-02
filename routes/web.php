<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

// $router->get('/', function () use ($router) {
//     return $router->app->version();
// });

$router->post('/register', 'adminController@register');
$router->post('/login', 'adminController@login');

$router->post('/addbarang', 'adminController@addbarang');
$router->get('/barang', 'adminController@barang');
$router->post('/updatebarang/{id}', 'adminController@updatebarang');
$router->get('/deletebarang/{id}', 'adminController@deletebarang');

$router->post('/addestimasi', 'adminController@addestimasi');
$router->get('/estimasi', 'adminController@estimasi');
$router->post('/updateestimasi/{id}', 'adminController@updateestimasi');
$router->get('/deleteestimasi/{id}', 'adminController@deleteestimasi');
$router->get('/tipeestimasi/{kode_barang}', 'adminController@tipeestimasi');

$router->post('/daftarteknisi', 'adminController@daftarteknisi');
$router->get('/teknisi', 'adminController@teknisi');