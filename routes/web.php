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



$router->group(['prefix' => '/api/v1'], function () use ($router) {

    $router->post('/sign_up', 'UserController@register');

    $router->post('/sign_in', 'UserController@login');


    $router->group(['middleware' => 'auth'], function() use ($router){

        $router->group(['prefix' => '/patient'], function () use ($router) {
            $router->get('/', function (){
                return Auth()->user();
            });
        });

        $router->group(['prefix' => '/doctor'], function () use ($router) {

        });

    });


});

