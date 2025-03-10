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

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->get('hashes', 'PasswordsController@checkHash');
$router->post('login', 'PasswordsController@login');
$router->post('passwords', 'PasswordsController@setPassword');
$router->get('user/passwords', 'PasswordsController@getMyPassword');
$router->post('synchronize/passwords', [ 'uses' => 'PasswordsController@syncSetPassword', 'middleware' => 'sync'] );
