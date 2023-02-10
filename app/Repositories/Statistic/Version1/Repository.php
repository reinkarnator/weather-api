<?php

namespace App\Repositories\Statistic\Version1;

use App\Repositories\Statistic\RepositoryInterface;
use App\Models\Version1\Statistic;
use Illuminate\Support\Carbon;

/**
 *  Repository instance
 */
final class Repository implements RepositoryInterface
{
    private Statistic $statisticModel;

    const RELATIONS = ['city', 'version', 'service'];
    const POPULAR_COUNT = 5;

    public function __construct()
    {
        $this->statisticModel = new Statistic();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getMonthly()
    {
        return $this->statisticModel
            ->with(self::RELATIONS)
            ->where('created_at', '>=', Carbon::now()->startOfMonth()->subMonth()->toDateString())
            ->get();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getDaily()
    {
        return $this->statisticModel
            ->with(self::RELATIONS)
            ->whereDate('created_at', Carbon::today())
            ->get();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getPopular()
    {
        return $this->statisticModel
            ->with(self::RELATIONS)
            ->selectRaw('COUNT(*) AS count, service_id, version_id, city_id')
            ->groupBy(['city_id','version_id', 'service_id'])
            ->orderByDesc('count')
            ->limit(self::POPULAR_COUNT)
            ->get();
    }
}