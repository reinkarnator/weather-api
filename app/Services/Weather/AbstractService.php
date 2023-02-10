<?php

namespace App\Services\Weather;

use Illuminate\Support\Collection;

abstract class AbstractService implements AbstractServiceInterface
{
    /**
     * is service ignored
     *
     * @var bool
     */
    protected $ignored = false;

    /**
     * Getting service name
     *
     * @return string
     */
    abstract public function getName(): string;

    /**
     * Service's available methods
     *
     * @return Collection
     */
    abstract public function getAvailAbleMethods(): Collection;

    /**
     * Service validation by name
     *
     * @param string $serviceName
     * @return bool
     */
    public function isValidInstance(string $serviceName): bool
    {
        return ($this->getName() === $serviceName);
    }

    /**
     * @return bool
     */
    public function isIgnored(): bool
    {
        return ($this->ignored);
    }

}