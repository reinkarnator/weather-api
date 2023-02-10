<?php

namespace Tests;

class WeatherApiTest extends TestCase
{

    public function test_that_base_endpoint_returns_a_successful_response()
    {
        $this->get('/api');

        $this->assertEquals(
            json_encode([        'services' => [
                'weather',
                'statistic'
            ]]), $this->response->getContent()
        );
    }

    public function test_that_weather_endpoint_returns_a_correct_services()
    {
        $this->get('/api/weather/1')
            ->seeStatusCode(200)
            ->seeJson([
                "services" => [
                    "openweathermap",
                    "weatherapi"
                ],
                "methods" => [
                    "average"
                ]
            ]);
    }

    public function test_that_weather_service_endpoint_returns_a_methods_list()
    {
        $this->get('/api/weather/1/weatherapi')
            ->seeStatusCode(200)
            ->seeJson([
                "methods" => [
                    "{city}"
                ]
            ]);
    }

    public function test_that_weather_service_city_endpoint_returns_a_correct_value()
    {
        $this->get('/api/weather/1/weatherapi/Moscow')
            ->seeStatusCode(200)
            ->seeJsonStructure([
                'result'
            ]);
    }

    public function test_that_weather_average_city_endpoint_returns_a_correct_value()
    {
        $this->get('/api/weather/1/average/Moscow')
            ->seeStatusCode(200)
            ->seeJsonStructure([
                'result'
            ]);
    }



}