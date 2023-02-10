<?php

namespace App\Services\Weather;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

abstract class WeatherApiAbstractService extends AbstractService
{
    /**
     * @var string
     */
    private string $name = 'weatherapi';
    /**
     * @var string
     */
    private string $apiPath;
    /**
     * @var string
     */
    private string $appId;

    const WEATHER_API = 'v1/current.json';

    public function __construct()
    {
        $this->apiPath = config('weather.weather_api_url');
        $this->appId = config('weather.weather_app_key');
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return Collection
     */
    public function getAvailAbleMethods(): Collection
    {
        return collect(['{city}']);
    }

    /**
     * @param string $request
     * @return void
     */
    private function setAppId(string &$request): void
    {
        $request .= '&key=' . $this->appId;
    }

    /**
     * @param string $request
     * @return Response
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    private function sendRequest(string $request): Response
    {
        $this->setAppId($request);
        $response = Http::get($request);
        $this->validateResponse($response);
        return $response;
    }

    /**
     * @param Response $response
     * @return void
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    private function validateResponse(Response $response): void
    {
        if ($response->failed() || (empty($response->json()) || !is_array($response->json()))) {
            throw new NotFoundHttpException("Nothing found");
        }
    }

    /**
     * @param string $city
     * @return float|null
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function getByCity(string $city): ?float
    {
        $request = $this->apiPath . self::WEATHER_API . '?q=' . $city;
        $response = $this->sendRequest($request);
        $totalWeatherInfo = $response->json();

        return $totalWeatherInfo['current']['temp_c'] ?? null;
    }

}