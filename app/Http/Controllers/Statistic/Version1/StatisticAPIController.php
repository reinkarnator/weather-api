<?php

namespace App\Http\Controllers\Statistic\Version1;

use App\Http\Controllers\Controller;
use App\Repositories\GlobalRepositoryInterface;

class StatisticAPIController extends Controller
{
    public function __construct(
        private GlobalRepositoryInterface $repository
    ) {}

    /**
     * @return string[]
     */
    public function getMethodsList()
    {
        return ['popular', 'monthly', 'daily'];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getPopular()
    {
        return $this->repository->getPopular();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getMontly()
    {
        return $this->repository->getMonthly();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getDaily()
    {
        return $this->repository->getDaily();
    }
}