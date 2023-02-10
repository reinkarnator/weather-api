<?php

namespace App\Infrastructure;

use App\Helpers\NamespaceHelper;
use App\Repositories\GlobalRepositoryInterface;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class RepositoryContainer implements InfrastructureInterface
{
    const REPOSITORY = 'Repository';
    const REPOSITORY_BASE = 'App\Repositories';

    /**
     * Generating repository instance by namespace & request's version
     *
     * @return GlobalRepositoryInterface
     */
    public function detectRepository(string $serviceName): GlobalRepositoryInterface
    {
        try {
            $versionedRepository = self::REPOSITORY_BASE . '\\' . NamespaceHelper::getNamespaceVersionAndService(
                    $serviceName
                ) . '\\' . self::REPOSITORY;
            return new $versionedRepository();
        } catch (\Throwable $error) {
            Log::debug($error);
            throw new NotFoundHttpException("Incorrect API version");
        }
    }
}