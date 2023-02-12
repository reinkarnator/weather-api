<?php

namespace App\Providers;

use App\Common\Weather\AbstractAggregateService;
use App\Common\Weather\AggregateService;
use App\Infrastructure\RepositoryContainer;
use App\Infrastructure\ServiceDetector;
use App\Repositories\GlobalRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $detector = $this->app->make(ServiceDetector::class);

        $this->app->bind(AbstractAggregateService::class, function () use ($detector) {
            return new AggregateService($detector);
        });

        $this->app->bind(GlobalRepositoryInterface::class, function () use ($detector) {
            return RepositoryContainer::detectRepository();
        });
    }
}
