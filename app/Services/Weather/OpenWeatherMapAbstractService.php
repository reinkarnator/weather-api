<?php

namespace App\Services\Weather;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

abstract class OpenWeatherMapAbstractService extends AbstractService
{
    /**
     * @var string
     */
    private string $name = 'openweathermap';
    /**
     * @var string
     */
    private string $apiPath;
    /**
     * @var string
     */
    private string $appId;

    const GEO_API = 'geo/1.0/direct',
        WEATHER_API = 'data/2.5/weather',
        METRIC_UNITS = 'metric';

    public function __construct()
    {
        $this->apiPath = config('weather.openweathermap_api_url');
        $this->appId = config('weather.openweathermap_app_key');
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
        $request .= '&appid=' . $this->appId;
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
     * @param string $city
     * @return array
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function getLongLatByCityName(string $city): ?array
    {
        $request = $this->apiPath . self::GEO_API . '?q=' . $city;
        $response = $this->sendRequest($request);
        $cityData = $response->json();

        return (isset($cityData[0]['lat']) && isset($cityData[0]['lon'])) ? [
            'lat' => $cityData[0]['lat'],
            'lon' => $cityData[0]['lon']
        ] : null;
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
        $longLat = $this->getLongLatByCityName($city);
        if (!$longLat) return null;

        $request = $this->apiPath . self::WEATHER_API . '?lat=' . $longLat['lat'] . '&lon=' . $longLat['lon'] . '&units=' . self::METRIC_UNITS;
        $response = $this->sendRequest($request);
        $totalWeatherInfo = $response->json();

        return $totalWeatherInfo['main']['temp'] ?? null;
    }

}