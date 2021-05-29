<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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

$router->group(['prefix' => 'api'], function () use ($router) {
    $router->post('auth/login', ['as' => 'authenticate', 'uses' => 'AuthController@login']);
    $router->get('price-conversion/{amount}', ['as' => 'price-conversion', 'uses' => 'PriceController@conversion']);
    $router->get('product/{productId}/prices', ['as' => 'product-prices', 'uses' => 'ProductController@showPrices']);
    $router->get('product/{id}', ['as' => 'product-get', 'uses' => 'ProductController@show']);
});

$router->group(['middleware' => 'auth:api', 'prefix' => 'api/auth'], function ($router) {
    $router->post('logout', ['as' => 'logout', 'uses' => 'AuthController@logout']);
    $router->post('refresh', ['as' => 'auth-refresh', 'uses' => 'AuthController@refresh']);
    $router->post('me', ['as' => 'auth-me', 'uses' => 'AuthController@me']);
});

$router->group(['middleware' => 'auth:api', 'prefix' => 'api'], function ($router) {
    $router->post('product', ['as' => 'product-post', 'uses' => 'ProductController@store']);
});
