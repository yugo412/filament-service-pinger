<?php

namespace Yugo\FilamentServicePinger\Support;

trait UseServiceModel
{
    public static function serviceModel(): string
    {
        return ModelResolver::service();
    }

    public static function checkModel(): string
    {
        return ModelResolver::check();
    }
}
