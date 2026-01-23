<?php

namespace Yugo\FilamentServicePinger\Support;

final class ModelResolver
{
    use UseResolver;

    public static function service(): string
    {
        return self::resolve('service', 'models');
    }

    public static function check(): string
    {
        return self::resolve('check', 'models');
    }
}
