<?php

namespace App\Infrastructure;

use App\Helpers\NamespaceHelper;
use App\Services\Weather\AbstractServiceInterface;
use Illuminate\Support\Collection;

final class ServiceDetector implements InfrastructureInterface
{

    /**
     * @param string $serviceName
     * @param string $instance
     * @return AbstractServiceInterface|null
     * @throws \ReflectionException
     */
    public function detectInstance(string $serviceName, string $instance): ?AbstractServiceInterface
    {
        $instances = $this->getReflectionInstancesList($instance);

        foreach ($instances as $instanceItem) {
            if ($instanceItem->isValidInstance($serviceName)) {
                return $instanceItem;
            }
        }
        return null;
    }


    /**
     * @param string $class
     * @return Collection
     * @throws \ReflectionException
     */
    public function getReflectionInstancesList(string $class): Collection
    {
        $reflector = (new \ReflectionClass($class));
        $classPath = $reflector->getFileName();

        if (!$classPath) {
            throw new \Exception("Wrong filepath given");
        }

        $namespace_with_version = $reflector->getNamespaceName() . '\\' . NamespaceHelper::getNamespaceVersion();

        return $this->getInstancesList($classPath, $namespace_with_version, $reflector);
    }

    /**
     * @param string $interfacePath
     * @param string $namespace
     * @param \ReflectionClass $reflector
     * @return Collection
     * @throws \ReflectionException
     */
    private function getInstancesList(string $interfacePath, string $namespace, \ReflectionClass $reflector): Collection
    {
        $interface_directory = pathinfo($interfacePath, PATHINFO_DIRNAME);
        $services_directory = $interface_directory . DIRECTORY_SEPARATOR . NamespaceHelper::getNamespaceVersion();

        $servicesFilesList = new \RecursiveDirectoryIterator($services_directory);

        $instances = collect();
        foreach ($servicesFilesList as $serviceItem) {
            $class = str_replace('.php', '', basename($serviceItem));
            if (class_exists($namespace . '\\' . $class)) {
                $class_with_namespace = $namespace . '\\' . $class;
                if ((new \ReflectionClass($class_with_namespace))->implementsInterface(
                        $reflector
                    )) {
                    $instances->push(new $class_with_namespace);
                }
            }
        }

        if ($instances->isEmpty()) {
            throw new \Exception("No valid instance found");
        }

        return $instances;
    }
}