<?php

namespace LetoChat\Includes;

class BaseApi
{
    public static function getApiNamespace()
    {
        return 'letochat/v1';
    }

    public static function getApiOrderRoute()
    {
        return 'order';
    }

    public static function getApiUserCartRoute()
    {
        return 'user-cart';
    }
}