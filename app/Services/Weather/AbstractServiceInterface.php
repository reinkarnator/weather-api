<?php

namespace App\Services\Weather;

use Illuminate\Support\Collection;

interface AbstractServiceInterface
{
    public function getName(): string;
    public function getAvailAbleMethods(): Collection;
    public function isValidInstance(string $serviceName): bool;
    public function isIgnored(): bool;
}