<?php

namespace App\Http\Middleware;

use Laravel\Lumen\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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