<?php

namespace App\Services\Weather;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

abstract class AccuWeatherAbstractService extends AbstractService
{
    /**
     * @var string
     */
    private string $name = 'accuweather';
    /**
     * @var string
     */
    private string $apiPath;
    /**
     * @var string
     */
    private string $appId;

    protected $ignored = true;

    const LOCATIONS_API = 'locations/v1/cities/search',
        WEATHER_API = 'forecasts/v1/hourly/1hour/',
        METRIC_UNITS = 'true';

    public function __construct()
    {
        $this->apiPath = config('weather.accuweather_api_url');
        $this->appId = config('weather.accuweather_app_key');
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
        $request .= '&apikey=' . $this->appId;
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
     * @return string
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    private function getLocationKeyByCity(string $city): ?string
    {
        $request = $this->apiPath . self::LOCATIONS_API . '?q=' . $city;
        $response = $this->sendRequest($request);
        $location = $response->json();
        return $location[0]['Key'] ?? null;
    }

    /**
     * @param string $city
     * @return float|null
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function getByCity(string $city): ?float
    {
        $key = $this->getLocationKeyByCity($city);
        if (!$key) return null;

        $request = $this->apiPath . self::WEATHER_API . $key . '?metric=' . self::METRIC_UNITS;
        $response = $this->sendRequest($request);
        $totalWeatherInfo = $response->json();

        return $totalWeatherInfo[0]['Temperature']['Value'] ?? null;
    }

}