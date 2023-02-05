<?php

namespace App\Http\Middleware;

use Laravel\Lumen\Http\Request;

class WeatherAPIVersionValidate
{
    public function handle(Request $request, callable $next)
    {
        if (in_array($request->route('version'), config('app.available_api_version'))) {
            config(['app.default_api_version' => $request->segment(2)]);
            return $next($request);
        }

        throw new \Exception("Wrong API version");
    }
}