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
    return [
        'services' => [
            'weather',
            'statistic'
        ]
    ];
});

$router->group(
    [
        'prefix' => 'weather',
    ],
    function () use ($router) {
        $router->get('/', function () use ($router) {
            return [
                'versions' => config('weather.available_api_version')
            ];
        });
        $router->group(
            [
                'prefix' => '{version:[0-9]+}',
                'middleware' => ['weather_api_version'],
                'namespace' => \App\Helpers\NamespaceHelper::getNamespaceVersionAndService('weather')
            ],
            function () use ($router) {
                $router->get('/',  ['uses' => 'WeatherAPIController@list']);
                $router->group(
                    [
                        'prefix' => 'average',
                    ],
                    function () use ($router) {
                        $router->get('/',  ['uses' => 'WeatherAPIController@averageIntro']);
                        $router->get('{city}',  ['uses' => 'WeatherAPIController@averageByCity']);
                    }
                );
                $router->group(
                    [
                        'prefix' => '{apiName:[a-z]+}',
                        'middleware' => ['weather_service_validate']
                    ],
                    function () use ($router) {
                        $router->get('/',  ['uses' => 'WeatherAPIController@intro']);
                        $router->get('{city}',  ['uses' => 'WeatherAPIController@getByCity']);
                    }
                );
        });
    }
);

$router->group(
    [
        'prefix' => 'statistic',
    ],
    function () use ($router) {
        $router->get('/', function () use ($router) {
            return [
                'versions' => config('statistic.available_api_version')
            ];
        });
        $router->group(
            [
                'prefix' => '{version:[0-9]+}',
                'middleware' => ['weather_api_version'],
                'namespace' => \App\Helpers\NamespaceHelper::getNamespaceVersionAndService('statistic')
            ],
            function () use ($router) {
                $router->get('/',  function () use ($router) {
                    return [
                        'versions' => config('statistic.available_methods')
                    ];
                });
                $router->group(
                    [
                        'prefix' => 'popular',
                    ],
                    function () use ($router) {
                        $router->get('/',  ['uses' => 'StatisticAPIController@getPopular']);
                    }
                );
                $router->group(
                    [
                        'prefix' => 'monthly',
                    ],
                    function () use ($router) {
                        $router->get('/',  ['uses' => 'StatisticAPIController@getMontly']);
                    }
                );
                $router->group(
                    [
                        'prefix' => 'daily',
                    ],
                    function () use ($router) {
                        $router->get('/',  ['uses' => 'StatisticAPIController@getDaily']);
                    }
                );
            });
    }
);


