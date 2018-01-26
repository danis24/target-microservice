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

$router->get('/targets', 'TargetController@browse');
$router->get('/verified/{id}', 'TargetController@verified');
$router->get('/targets/{id}', 'TargetController@read');
$router->post('/targets', 'TargetController@add');
$router->post('/targets/by_email', 'TargetController@show');
