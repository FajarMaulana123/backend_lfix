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




// Endpoint Permintaan User
$router->post('/register', 'userController@register');
$router->post('/login', 'userController@login');
$router->post('/updateToken', 'userController@updateToken');
$router->get('/kategoriBarang', 'userController@kategoriBarang');
$router->post('/service', 'userController@service');
$router->post('/data_service', 'userController@dataservice');
$router->post('/confirm_teknisi', 'userController@confirm_teknisi');
$router->post('/cancel_teknisi', 'userController@cancel_teknisi');
$router->post('/rating', 'userController@rating');
$router->post('/cancel_service', 'userController@cancel_service');
$router->post('/confirm_damage', 'userController@confirm_damage');
$router->post('/data_guarantee', 'userController@data_guarantee');
$router->post('/claim_guarantee', 'userController@claim_guarantee');

//teknisi
$router->get('/api/teknisi/dataservice', 'teknisiController@dataservice');
$router->post('/api/teknisi/ambilservice', 'teknisiController@ambilservice');
$router->post('/api/teknisi/kerusakan', 'teknisiController@kerusakan');
$router->post('/api/teknisi/servicedone', 'teknisiController@servicedone');
$router->post('/api/teknisi/datakerusakan', 'teknisiController@datakerusakan');
$router->post('/api/teknisi/login', 'teknisiController@login');
// $router->post('/api/teknisi/rating', 'teknisiController@rating');
// $router->post('/api/teknisi/pergi', 'teknisiController@pergi');
// $router->post('/api/teknisi/doingservice', 'teknisiController@doingservice');




// EndPoint Coba-coba

$router->post('/api/admin/addbarang', 'adminController@addbarang');
$router->get('/api/admin/barang', 'adminController@barang');
$router->post('/api/admin/updatebarang/{id}', 'adminController@updatebarang');
$router->get('/api/admin/deletebarang/{id}', 'adminController@deletebarang');

$router->post('/api/admin/addestimasi', 'adminController@addestimasi');
$router->get('/api/admin/estimasi', 'adminController@estimasi');
$router->post('/api/admin/updateestimasi/{id}', 'adminController@updateestimasi');
$router->get('/api/admin/deleteestimasi/{id}', 'adminController@deleteestimasi');
// $router->get('/api/admin/tipeestimasi/{kode_barang}', 'adminController@tipeestimasi');

$router->post('/api/admin/daftarteknisi', 'adminController@daftarteknisi');
$router->get('/api/admin/teknisi', 'adminController@teknisi');
$router->get('/api/admin/service', 'adminController@service');