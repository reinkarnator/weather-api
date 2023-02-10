<?php

namespace App\Services\Weather;

use Illuminate\Support\Collection;

abstract class AbstractService implements AbstractServiceInterface
{
    protected $ignored = false;

    abstract public function getName(): string;
    abstract public function getAvailAbleMethods(): Collection;

    public function isValidInstance(string $serviceName): bool
    {
        return ($this->getName() === $serviceName);
    }

    public function isIgnored(): bool
    {
        return ($this->ignored);
    }

}