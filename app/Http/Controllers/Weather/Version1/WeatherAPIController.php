<?php

namespace App\Http\Controllers\Weather\Version1;

use App\Common\Weather\AbstractAggregateService;
use App\Http\Controllers\Controller;
use App\Repositories\GlobalRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class WeatherAPIController extends Controller
{
    const CURRENT_VERSION = 1,
          AVERAGE_SERVICE = 'average';

    public function __construct(
        private AbstractAggregateService $serviceLocator,
        private GlobalRepositoryInterface $repository
    ) {}

    /**
     * List of available services and methods on root request
     *
     * @return array
     */
    public function list(): array
    {
        return [
            'services' => $this->serviceLocator->listServiceNames(),
            'methods' => [
                'average',
            ]
        ];
    }

    /**
     * List of available methods
     *
     * @param string $apiName
     * @return array
     */
    public function intro(string $apiName): array
    {
        return [
            'methods' => $this->serviceLocator->getAvailAbleMethods($apiName)
        ];
    }

    /**
     * List of methods on average call
     *
     * @return \string[][]
     */
    public function averageIntro(): array
    {
        return [
            'methods' => [
                '{city_name}',
            ]
        ];
    }

    /**
     * Getting weather by city and service
     *
     * @param string $city
     * @param string $apiName
     * @return array
     */
    public function getByCity(string $city, string $apiName): array
    {
        $service = $this->serviceLocator->detectService($apiName);

        $weatherValue = $service->getByCity($city);
        if (!$weatherValue) {
            throw new NotFoundHttpException("Weather info error");
        }

        $isSaved = $this->repository->saveItem($city, self::CURRENT_VERSION, $apiName, $weatherValue);
        if (!$isSaved) {
            throw new HttpException(500, "Save error");
        }

        return ['result' => $weatherValue];
    }

    /**
     * Getting average weather by city from all services
     *
     *
     * @param string $city
     * @return array
     */
    public function averageByCity(string $city): array
    {
        $services = $this->serviceLocator->getServicesList();

        $temperature = 0;
        $i = 0;
        foreach ($services as $service) {
            $tempVal = $service->getByCity($city);
            if (!$tempVal) continue;
            $i++;
            $temperature += $tempVal;
        }

        $averageTemp = $temperature / $i;
        $isSaved = $this->repository->saveItem($city, self::CURRENT_VERSION, self::AVERAGE_SERVICE, $averageTemp);
        if (!$isSaved) {
            throw new HttpException(500, "Save error");
        }

        return ['result' => $averageTemp];
    }

}