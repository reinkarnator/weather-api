<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Request;

final class NamespaceHelper
{
    const VERSION_PREFIX = 'Version';

    /**
     * Generate namespace for service with version
     *
     * @param string $serviceName
     * @return string
     */
    public static function getNamespaceVersionAndService()
    {
       $entity = Request::segment(2);
       return ucfirst($entity) . '\\' .self::getNamespaceVersion();
    }

    /**
     * Generate version namespace
     *
     * @return string
     */
    public static function getNamespaceVersion()
    {
        return self::VERSION_PREFIX . Request::segment(3);
    }
}