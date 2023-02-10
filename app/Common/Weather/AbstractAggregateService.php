<?php

namespace App\Common\Weather;

use App\Infrastructure\InfrastructureInterface;
use App\Services\Weather\AbstractServiceInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

abstract class AbstractAggregateService
{
    public function __construct(
        protected InfrastructureInterface $serviceDetector
    ) {
    }

    /**
     * Getting names of available weather API's
     *
     * @return Collection
     */
    public function listServiceNames(): Collection
    {
        $services = $this->getServicesList();

        $collection = collect();
        foreach ($services as $service) {
            $collection->push($service->getName());
        }

        return $collection;
    }

    /**
     * Getting list of service instances of available weather API's
     *
     * @return Collection
     */
    public function getServicesList(): Collection
    {
        try {
            $serviceCollection = $this->serviceDetector->getReflectionInstancesList(AbstractServiceInterface::class)->filter(
                function ($instance) {
                    return (!$instance->isIgnored());
                }
            );

            if ($serviceCollection->isEmpty()) {
                throw new \Exception("Services not found");
            }

            return  $serviceCollection;

        } catch (\Throwable $error) {
            Log::debug($error);
            throw new NotFoundHttpException("Incorrect request");
        }
    }
}