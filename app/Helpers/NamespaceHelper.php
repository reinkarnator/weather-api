<?php

namespace App\Helpers;

final class NamespaceHelper
{
    const VERSION_PREFIX = 'Version';

    public static function getNamespaceVersionAndService(string $serviceName)
    {
       return ucfirst($serviceName) . '\\' .self::getNamespaceVersion();
    }

    public static function getNamespaceVersion()
    {
        return self::VERSION_PREFIX . config('app.default_api_version');
    }
}