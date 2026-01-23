<?php

namespace Yugo\FilamentServicePinger\Support;

use InvalidArgumentException;

trait UseResolver
{
    protected static function resolve(string $configKey, string $key = 'models'): string
    {
        $class = config("service-pinger.{$key}.{$configKey}");

        if (! is_string($class) || ! class_exists($class)) {
            throw new InvalidArgumentException(
                "Configured class [service-pinger.{$key}.{$configKey}] \"{$class}\" is invalid or does not exist."
            );
        }

        return $class;
    }
}
