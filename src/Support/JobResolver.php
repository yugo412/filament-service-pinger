<?php

namespace Yugo\FilamentServicePinger\Support;

final class JobResolver
{
    public static function ping(): string
    {
        return self::resolve('ping', 'jobs');
    }
}
