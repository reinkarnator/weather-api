<?php

namespace App\Traits\Weather\Version1;

trait EndpointsDescribeTrait
{
    /**
     * List of available services and methods on root request
     * @return array
     */
    public function list(): array
    {
        return [
            'services' => $this->common->listServiceNames(),
            'methods' => [
                'average',
            ]
        ];
    }

    /**
     * List of available methods
     * @param string $apiName
     * @return array
     */
    public function intro(string $apiName): array
    {
        return [
            'methods' => $this->common->getAvailAbleMethods($apiName)
        ];
    }

    /**
     * List of methods on average call
     *
     * @return \string[][]
     */
    public function averageIntro(): array
    {
        return [
            'methods' => [
                '{city_name}',
            ]
        ];
    }
}