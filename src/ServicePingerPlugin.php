<?php

namespace Yugo\FilamentServicePinger;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Yugo\FilamentServicePinger\Resources\ServiceResource;

class ServicePingerPlugin implements Plugin
{
    public function getId(): string
    {
        return 'service-pinger';
    }

    public function register(Panel $panel): void
    {
        $panel
            ->resources([
                ServiceResource::class,
            ]);
    }

    public function boot(Panel $panel): void {}
}
