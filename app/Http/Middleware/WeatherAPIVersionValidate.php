<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;
use Laravel\Lumen\Http\Request;

class WeatherAPIVersionValidate
{
    /**
     * @param Request $request
     * @param callable $next
     * @return Response
     */
    public function handle(Request $request, callable $next): Response
    {
        if (in_array($request->route('version'), config('weather.available_api_version'))) {
            config(['app.default_api_version' => $request->route('version')]);
            return $next($request);
        }

        throw new NotFoundHttpException(
            sprintf(
                "Current version not available. Available versions: %s",
                implode(',', config('weather.available_api_version'))
            )
        );
    }
}