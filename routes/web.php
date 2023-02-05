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

$router->group(
    [
        'prefix' => 'api/{version:[0-9]+}',
        'middleware' => 'weather_api_version'
    ],
    function () use ($router) {
        $router->group(
            [
                'prefix' => 'weather',
                'namespace' => \App\Helpers\RouteHelper::getNamespaceByRequest()
            ],
            function () use ($router) {
                $router->get('list',  ['uses' => 'WeatherAPIController@list']);
                $router->group(
                    [
                        'prefix' => '{api_name:[a-z]+}',
                        'middleware' => ['weather_service_validate']
                    ],
                    function () use ($router) {
                        $router->get('/',  ['uses' => 'WeatherAPIController@list']);
                        $router->get('{city}',  ['uses' => 'WeatherAPIController@getByCity']);
                    }
                );
                $router->get('average',  ['uses' => 'WeatherAPIController@averageByServices']);
            }
        );



    /*
    $router->get('authors',  ['uses' => 'AuthorController@showAllAuthors']);

    $router->get('authors/{id}', ['uses' => 'AuthorController@showOneAuthor']);

    $router->post('authors', ['uses' => 'AuthorController@create']);

    $router->delete('authors/{id}', ['uses' => 'AuthorController@delete']);

    $router->put('authors/{id}', ['uses' => 'AuthorController@update']);
    */
});
