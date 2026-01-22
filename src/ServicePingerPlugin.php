<?php

namespace Yugo\FilamentServicePinger;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Yugo\FilamentServicePinger\Resources\ServiceResource;
use Yugo\FilamentServicePinger\Widgets\ServiceStatusOverview;

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
            ])
            ->widgets([
                ServiceStatusOverview::class,
            ]);
    }

    public function boot(Panel $panel): void {}
}
