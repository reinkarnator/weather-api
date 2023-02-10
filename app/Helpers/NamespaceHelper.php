<?php

namespace App\Helpers;

final class NamespaceHelper
{
    const VERSION_PREFIX = 'Version';

    /**
     * Generate namespace for service with version
     *
     * @param string $serviceName
     * @return string
     */
    public static function getNamespaceVersionAndService(string $serviceName)
    {
       return ucfirst($serviceName) . '\\' .self::getNamespaceVersion();
    }

    /**
     * Generate version namespace
     *
     * @return string
     */
    public static function getNamespaceVersion()
    {
        return self::VERSION_PREFIX . config('app.default_api_version');
    }
}