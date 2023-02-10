<?php

namespace App\Http\Middleware;

use App\Common\Weather\AbstractAggregateService;
use Symfony\Component\HttpFoundation\Response;
use Laravel\Lumen\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class WeatherServiceNameValidate
{

    /**
     * @param Request $request
     * @param callable $next
     * @return Response
     */
    public function handle(Request $request, callable $next): Response
    {
        $serviceNameCollection = app(AbstractAggregateService::class)->listServiceNames();
        if ($serviceNameCollection->contains($request->route('apiName'))) {
            return $next($request);
        }

        throw new NotFoundHttpException('API method not found');
    }
}