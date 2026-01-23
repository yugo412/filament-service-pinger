<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\TestCase as Orchestra;
use Yugo\FilamentServicePinger\Support\JobResolver;
use Yugo\FilamentServicePinger\Support\ModelResolver;

class TestCase extends Orchestra
{
    use RefreshDatabase;

    protected string $serviceModelResolver;

    protected string $serviceCheckModelResolver;

    protected string $pingJobResolver;

    protected function getPackageProviders($app)
    {
        return [
            \Yugo\FilamentServicePinger\Provider::class,
        ];
    }

    protected function defineEnvironment($app)
    {
        // Model resolver
        $app['config']->set('service-pinger.models.service', \Tests\Fixtures\Models\Service::class);
        $app['config']->set('service-pinger.models.check', \Tests\Fixtures\Models\ServiceCheck::class);

        // Database (sqlite in-memory)
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->serviceModelResolver = ModelResolver::service();
        $this->serviceCheckModelResolver = ModelResolver::check();

        $this->pingJobResolver = JobResolver::ping();
    }

    protected function defineDatabaseMigrations()
    {
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
    }
}
