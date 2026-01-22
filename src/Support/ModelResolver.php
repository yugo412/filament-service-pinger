<?php

namespace Yugo\FilamentServicePinger\Support;

class ModelResolver
{
    public static function service(): string
    {
        return config('service-pinger.models.service');
    }

    public static function check(): string
    {
        return config('service-pinger.models.check');
    }
}
