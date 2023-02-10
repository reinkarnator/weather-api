<?php

namespace Tests;

class StatisticApiTest extends TestCase
{

    public function test_that_statistic_endpoint_returns_versions_list()
    {
        $this->get('/api/statistic/1')
            ->seeStatusCode(200)
            ->seeJson([
                "versions" => [
                    "popular",
                    "monthly",
                    "daily"
                ]
            ]);
    }

    public function test_that_statistic_popular_endpoint_returns_a_correct_data()
    {
        $this->get('/api/statistic/1/popular')
            ->seeStatusCode(200)
            ->seeJsonStructure([
                "*" => [
                    "count",
                    "service_id",
                    "version_id",
                    "city_id"
                ]
            ]);
    }

    public function test_that_statistic_daily_endpoint_returns_a_correct_data()
    {
        $this->get('/api/statistic/1/daily')
            ->seeStatusCode(200)
            ->seeJsonStructure([
                "*" => [
                    "service_id",
                    "version_id",
                    "city_id"
                ]
            ]);
    }

    public function test_that_statistic_monthly_endpoint_returns_a_correct_data()
    {
        $this->get('/api/statistic/1/monthly')
            ->seeStatusCode(200)
            ->seeJsonStructure([
                "*" => [
                    "service_id",
                    "version_id",
                    "city_id"
                ]
            ]);
    }

}