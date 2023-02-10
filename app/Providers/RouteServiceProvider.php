<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->router->get('/', function () {
            return [
                '/api'
            ];
        });

        $this->app->router->group([
            'prefix' => 'api',
            'namespace' => 'App\Http\Controllers',
        ], function ($router) {
            require base_path('routes/api.php');
        });
    }
}