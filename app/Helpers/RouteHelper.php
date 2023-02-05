<?php

namespace App\Helpers;

final class RouteHelper
{
    public static function getNamespaceByRequest()
    {
       return 'Version' . config('app.default_api_version');
    }
}