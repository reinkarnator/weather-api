<?php

namespace App\Common\Weather;

use App\Services\Weather\AbstractServiceInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class AggregateService extends AbstractAggregateService
{
    /**
     *  Detecting instance by request's service_name field
     *
     * @param string $serviceName
     * @return AbstractServiceInterface
     */
    public function detectService(string $serviceName): AbstractServiceInterface
    {
        try {
            $instance = $this->serviceDetector->detectInstance($serviceName, AbstractServiceInterface::class);

            if ($instance && !$instance->isIgnored()) {
                return $instance;
            }

            throw new \Exception("Current not available");
        } catch (\Throwable $error) {
           Log::debug($error);
           throw new NotFoundHttpException("Incorrect service name");
        }
    }

    /**
     * Get methods for API in service instance
     *
     * @param string $serviceName
     * @return Collection
     */
    public function getAvailAbleMethods(string $serviceName): Collection
    {
        $service = $this->detectService($serviceName);
        return $service->getAvailAbleMethods();
    }
}