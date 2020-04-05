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

$router->post('/getserverinfo', 'TargetController@getServerInfo');
$router->get('/targets', 'TargetController@browse');
$router->get('/targets/{id}', 'TargetController@read');
$router->post('/targets', 'TargetController@add');
$router->delete('/targets/{id}', 'TargetController@delete');
$router->post('/targets/by_email', 'TargetController@show');
$router->patch('/launch/{id}', 'TargetController@updateScannerId');
$router->get('/geo', 'TargetController@getGeo');
