<?php

namespace App\Http\Controllers\Statistic\Version1;

use App\Http\Controllers\Controller;
use App\Infrastructure\RepositoryContainer;
use App\Repositories\GlobalRepositoryInterface;

class StatisticAPIController extends Controller
{
    private GlobalRepositoryInterface $repository;

    public function __construct(
        RepositoryContainer $repositoryContainer
    ) {
        $this->repository = $repositoryContainer->detectRepository('statistic');
    }

    public function getPopular()
    {
        return $this->repository->getPopular();
    }

    public function getMontly()
    {
        return $this->repository->getMonthly();
    }

    public function getDaily()
    {
        return $this->repository->getDaily();
    }
}