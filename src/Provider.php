<?php

namespace Yugo\FilamentServicePinger;

use Illuminate\Support\ServiceProvider;
use Yugo\FilamentServicePinger\Console\Commands\ServicePingerCommand;
use Yugo\FilamentServicePinger\Contracts\Pinger;
use Yugo\FilamentServicePinger\Services\HttpServicePinger;

class Provider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/service-pinger.php',
            'service-pinger',
        );

        $this->app->bind(Pinger::class, HttpServicePinger::class);
    }

    public function boot(): void
    {
        $this->loadTranslationsFrom(
            __DIR__.'/../resources/lang',
            'service-pinger'
        );

        $this->publishes([
            __DIR__.'/../config/service-pinger.php' => config_path('service-pinger.php'),
        ], 'service-pinger-config');

        $this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/service-pinger'),
        ], 'service-pinger-translations');

        $this->publishes([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'service-pinger-migrations');

        $this->loadViewsFrom(
            __DIR__.'/../resources/views',
            'filament-service-pinger'
        );

        $this->commands([
            ServicePingerCommand::class,
        ]);
    }
}
