<?php

namespace App\Models\Version1;

use App\Models\Statistic as AbstractStatistic;
use App\Models\Versions;

class Statistic extends AbstractStatistic
{
    public function city()
    {
        return $this->hasOne(Cities::class, 'id', 'city_id');
    }

    public function version()
    {
        return $this->hasOne(Versions::class, 'id', 'version_id');
    }

    public function service()
    {
        return $this->hasOne(Services::class, 'id', 'service_id');
    }
}