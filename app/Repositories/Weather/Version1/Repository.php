<?php

namespace App\Repositories\Weather\Version1;

use App\Models\Version1\{Cities, Services, Statistic};
use App\Models\Versions;
use App\Repositories\Weather\AbstractRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 *  Repository instance
 */
final class Repository extends AbstractRepository
{
    /**
     * @var Cities
     */
    private Cities $citiesModel;
    /**
     * @var Versions
     */
    private Versions $versionsModel;
    /**
     * @var Services
     */
    private Services $servicesModel;
    /**
     * @var Statistic
     */
    private Statistic $statisticModel;

    public function __construct()
    {
        $this->citiesModel = new Cities();
        $this->versionsModel = new Versions();
        $this->servicesModel = new Services();
        $this->statisticModel = new Statistic();
    }

    /**
     * Saving weather
     *
     * @param string $city
     * @param string $version
     * @param string $service
     * @param float $temperature
     * @return bool
     */
    public function saveItem(string $city, string $version, string $service, float $temperature): bool
    {
        try {
            DB::beginTransaction();

            $cityItem = $this->citiesModel->firstOrCreate([
                'city' => $city
            ]);

            $versionItem = $this->versionsModel->firstOrCreate([
                'version' => $version
            ]);

            $serviceItem = $this->servicesModel->firstOrCreate([
                'service' => $service
            ]);

            $this->statisticModel->create([
                'service_id' => $serviceItem->id,
                'version_id' => $versionItem->id,
                'city_id' => $cityItem->id,
                'temperature' => $temperature
            ]);

            DB::commit();
            return true;
        } catch (\Throwable $error) {
            Log::debug($error);
            DB::rollBack();
            return false;
        }
    }
}